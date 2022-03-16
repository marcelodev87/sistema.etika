<?php

namespace App\Http\Controllers;

use App\Client;
use App\ClientTask;
use App\ClientTaskComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ClientTaskCommentController extends Controller
{
    public function index($task)
    {
        $clientTask = ClientTask::find($task);
        $data = [];
        foreach ($clientTask->comments()->orderBy('id', 'desc')->get() as $r) {
            $arr = [
                'user' => $r->clientTask->responsible->name,
                'date' => $r->created_at->format('d/m/Y H:i:s'),
                'comment' => $r->comment,
                'files' => []
            ];
            if ($r->files != null) {
                foreach (json_decode($r->files, true) as $f) {
                    array_push($arr['files'], Storage::disk('public')->url($f));
                }
            }
            array_push($data, $arr);
        }
        return response()->json(['data' => $data], 200);
    }

    public function store(Request $request, Client $client, ClientTask $clientTask)
    {
        $rules = [
            'comment' => 'required|string|min:10',
        ];
        $errors = [];
        $fields = [];
        $validator = Validator::make($request->all(), $rules, $errors, $fields);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        try {
            $files = [];
            if ($request->has('files')) {
                foreach ($request->file('files') as $file) {
                    $name = 'comments/' . md5(uniqid(rand(), true)) . '.' . $file->extension();
                    $file->move(storage_path() . '/app/public/comments', $name);
                    array_push($files, $name);
                }
            }

            $comment = $clientTask->comments()->create([
                'client_task_id' => $clientTask->id,
                'comment' => $request->comment,
                'user_id' => auth()->user()->id,
                'files' => json_encode($files),
            ]);
            return response()->json([], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }

    }
}
