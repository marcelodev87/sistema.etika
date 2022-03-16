<?php

namespace App\Http\Controllers;

use App\Client;
use App\ClientProcess;
use App\ClientProcessLog;
use App\ClientProcessTask;
use App\ClientProcessTaskComment;
use App\InternalProcess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ClientProcessController extends Controller
{

    public function index(Client $client, ClientProcess $clientProcess)
    {
        return view('clients.process.index', compact('client', 'clientProcess'));
    }

    public function store(Request $request, Client $client)
    {
        $input = $request->all();
        $input['price'] = str_replace(['.', ','], ['', '.'], $input['price']);
        $rules = [
            'process_id' => 'required|integer|exists:internal_processes,id',
            'price' => 'required|numeric|min:0',
        ];
        $errors = [];
        $fields = [
            'process_id' => 'processo',
            'price' => 'preço',
        ];
        $validator = Validator::make($input, $rules, $errors, $fields);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        try {
            $process = $client->processes()->create([
                'process_id' => $input['process_id'],
                'price' => $input['price']
            ]);
            $tasks = InternalProcess::find($input['process_id'])->tasks;
            foreach ($tasks as $task) {
                try {
                    $process->tasks()->create([
                        'client_id' => $client->id,
                        'task_id' => $task->id,
                        'price' => 0,
                    ]);
                } catch (\Exception $e) {
                    $process->delete();
                    return response()->json(['message' => $e->getMessage()], 400);
                }
            }
            $log = ClientProcessLog::create([
                'user_id' => auth()->user()->id,
                'client_process_id' => $process->id,
                'action' => '<b>criou este processo</b>',
                'type' => 'process',
                'refer_id' => $process->id
            ]);
            return response()->json(['message' => 'Processo cadastrado com sucesso'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }

    }

    public function history(Client $client, ClientProcess $clientProcess)
    {
        $data = [];
        foreach ($clientProcess->logs()->orderBy('id', 'desc')->get() as $log) {
            $arr = [];
            $arr['user'] = $log->user->name;
            $arr['date'] = $log->created_at->format('d/m/Y H:i:s');
            if($log->type == "task"){
                $task = ClientProcessTask::find($log->refer_id);
                $arr['comment'] = $log->action .': '. $task->task->name;
            }

            if($log->type == "comment"){
                $comment = ClientProcessTaskComment::find($log->refer_id);
                $arr['comment'] = $log->action .': '. $comment->comment;
                $arr['files'] = [];
                foreach (json_decode($comment->files, true) as $f) {
                    array_push($arr['files'], Storage::disk('public')->url($f));
                }
            }

            if($log->type == "process"){
                $process = ClientProcess::find($log->refer_id);
                $arr['comment'] = $log->action .': ' .$process->process->name;
            }


            array_push($data, $arr);
        }
        return response()->json(['data' => $data], 200);
    }
}
