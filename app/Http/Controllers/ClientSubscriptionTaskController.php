<?php

namespace App\Http\Controllers;

use App\Client;
use App\ClientSubscription;
use App\ClientSubscriptionTask;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ClientSubscriptionTaskController extends Controller
{
    public function done(ClientSubscriptionTask $clientSubscriptionTask)
    {
        if ($clientSubscriptionTask->closed) {
            session()->flash('flash-warning', 'Tarefa já está fechada');
            return redirect()->back();
        }
        $clientSubscriptionTask->update([
            'closed' => 1,
            'closed_by' => auth()->user()->id,
            'closed_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        session()->flash('flash-success', 'Tarefa finalizada com sucesso');
        return redirect()->back();
    }

    public function delay(Request $request)
    {
        $rules = [
            'task_id' => 'required|integer|exists:client_subscription_tasks,id',
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

        $task = ClientSubscriptionTask::find($request->task_id);

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

    public function comments(ClientSubscriptionTask $clientSubscriptionTask)
    {
        $arr = [];
        foreach ($clientSubscriptionTask->comments as $c) {
            $files = [];
            foreach (json_decode($c->files, true) as $file){
                array_push($files, Storage::disk('public')->url($file));
            }
            array_push($arr, [
                'user' => $c->user->name,
                'date' => $c->created_at->format('d/m/Y H:i:s'),
                'comment' => $c->comment,
                'files' => $files
            ]);
        }
        return response()->json(['data' => $arr], 200);
    }

    public function newComment(ClientSubscriptionTask $clientSubscriptionTask, Request $request)
    {
        $files = [];
        if ($request->has('files')) {
            foreach ($request->file('files') as $file) {
                $name = 'comments/' . md5(uniqid(rand(), true)) . '.' . $file->extension();
                $file->move(storage_path() . '/app/public/comments', $name);
                array_push($files, $name);
            }
        }

        $clientSubscriptionTask->comments()->create([
            'comment' => $request->comment,
            'user_id' => auth()->user()->id,
            'files' => json_encode($files),
        ]);

        return response()->json([], 201);
    }
}
