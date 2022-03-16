<?php

namespace App\Http\Controllers;

use App\Client;
use App\ClientTask;
use App\Mail\NewTaskNotification;
use Carbon\Carbon;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ClientTaskController extends Controller
{
    public function store(Request $request, Client $client)
    {
        $input = $request->all();
        $input['price'] = brlToNumeric($input['price']);
        $rules = [
            'task_id' => 'required|integer|exists:internal_tasks,id',
            'price' => 'required|numeric|min:0',
            'user_id' => 'required|integer|exists:users,id',
            'end_at' => 'required|date_format:d/m/Y H:i',
        ];
        $errors = [];
        $fields = [
            'task_id' => 'tarefa',
            'price' => 'preço',
            'user_id' => 'responsável',
            'end_at' => 'dt entrega',
        ];
        $validator = Validator::make($input, $rules, $errors, $fields);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        try {
            $task = $client->tasks()->create([
                'task_id' => $input['task_id'],
                'user_id' => $input['user_id'],
                'price' => $input['price'],
                'end_at' => Carbon::createFromFormat('d/m/Y H:i', $input['end_at'])->format('Y-m-d H:i:s'),
            ]);
            $task->comments()->create([
                'user_id' => auth()->user()->id,
                'comment' => '<b>criou essa tarefa</b>'
            ]);
            $resp = User::find($input['user_id']);
            Mail::to($resp->email)->send(new NewTaskNotification(["name" => $resp->name]));
            return response()->json([], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }

    }

    public function done(Client $client, ClientTask $clientTask)
    {
        if (!$clientTask->closed) {
            $clientTask->update([
                'closed' => 1,
                'closed_by' => auth()->user()->id,
                'closed_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
            session()->flash('flash-success', 'Tarefa entregue!');
        } else {
            session()->flash('flash-warning', 'Essa tarefa já foi entregue.');
        }

        return redirect()->back();
    }

    public function delay(Request $request)
    {
        $rules = [
            'task_id' => 'required|integer|exists:client_tasks,id',
            'tipo' => 'required|string|in:h,d',
            'qt' => 'required|integer|min:1|max:24',
        ];
        $errors = [];
        $fields = [
            'task_id' => 'tarefa',
            'tipo' => 'tipo',
            'qt' => 'quantidade'
        ];
        $validator = Validator::make($request->all(), $rules, $errors, $fields);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }
        $hours = $request->qt;
        if ($request->tipo == 'd') {
            $hours = $request->qt * 24;
        }

        $task = ClientTask::find($request->task_id);

        // atualiza
        $oldHour = $task->end_at ?? Carbon::now()->second(0);
        $newHour = Carbon::parse($oldHour)->addHours($hours);
        $task->update([
            'end_at' => $newHour->format('Y-m-d H:i:s')
        ]);
        $task->comments()->create([
            'user_id' => auth()->user()->id,
            'comment' => '<b>fez o adiamento</b> antigo: ' . $oldHour->format('d/m/Y H:i:s') . ' ~ novo ' . $newHour->format('d/m/Y H:i:s') . '; total de ' . $hours . ' hora(s) adicionada.'
        ]);

        return response()->json(['message' => 'atualizado com sucesso'], 200);
    }
}
