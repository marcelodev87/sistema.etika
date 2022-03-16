<?php

namespace App\Http\Controllers;

use App\ClientSubscriptionTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClientSubscriptionTaskCommentController extends Controller
{
    public function index($task)
    {
        $clientSubscriptionTask = ClientSubscriptionTask::find($task);
        $data = [];
        foreach ($clientSubscriptionTask->comments()->orderBy('id', 'desc')->get() as $r) {
            $arr = [
                'user' => $r->clientTask->user->name,
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

}
