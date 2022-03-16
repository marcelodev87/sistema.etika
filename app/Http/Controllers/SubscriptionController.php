<?php

namespace App\Http\Controllers;

use App\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::all();
        return view('subscriptions.index', compact('subscriptions'));
    }

    public function create()
    {
        return view('subscriptions.create');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $input['price'] = str_replace(['.', ','], ['', '.'], $input['price']);
        $rules = [
            'name' => 'required|string|min:4',
            'price' => 'required|numeric|min:0',
        ];
        $errors = [];
        $fields = [
            'name' => 'nome',
            'price' => 'valor',
            'delay_hour' =>  'hora para delay',
            'task' => 'required|array|min:1',
        ];
        $validator = Validator::make($input, $rules, $errors, $fields);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        $tasks = [];
        for ($i = 0; $i <= 20; $i++) {
            if ($request->task[$i] != "" && $request->responsible[$i] != "" && $request->delay[$i] != null) {
                $arr = [
                    'task' => $request->task[$i],
                    'responsible' => $request->responsible[$i],
                    'delay' => $request->delay[$i],
                ];
                array_push($tasks, $arr);
            }
        }
        $json = [
            'tasks' => $tasks,
            'delay' => $request->delay_hour,
        ];

        try {
            $create = Subscription::create([
                'name' => $request->name,
                'price' => str_replace(['.', ','], ['', '.'], $request->price),
                'tasks' => json_encode($json),
            ]);
            return response()->json(['message' => ""], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }


    }

    public function edit(Subscription $subscription)
    {
        $info = json_decode($subscription->tasks, true);
        return view('subscriptions.edit', compact('subscription', 'info'));
    }

    public function update(Subscription $subscription, Request $request)
    {
        $input = $request->all();
        $input['price'] = str_replace(['.', ','], ['', '.'], $input['price']);
        $rules = [
            'name' => 'required|string|min:4|unique:subscriptions,name,'.$subscription->id,
            'price' => 'required|numeric|min:0',
        ];
        $errors = [];
        $fields = [
            'name' => 'nome',
            'price' => 'valor',
            'delay_hour' =>  'hora para delay',
            'task' => 'required|array|min:1',
        ];
        $validator = Validator::make($input, $rules, $errors, $fields);
        if ($validator->fails()) {
            session()->flash('flash-warning', $validator->errors()->first());
            return redirect()->back()->withInput($request->all());
        }

        $tasks = [];
        for ($i = 0; $i <= 20; $i++) {
            if ($request->task[$i] != "" && $request->responsible[$i] != "" && $request->delay[$i] != null) {
                $arr = [
                    'task' => $request->task[$i],
                    'responsible' => $request->responsible[$i],
                    'delay' => $request->delay[$i],
                ];
                array_push($tasks, $arr);
            }
        }
        $json = [
            'tasks' => $tasks,
            'delay' => $request->delay_hour,
        ];

        try {
            $subscription->update([
                'name' => $request->name,
                'price' => str_replace(['.', ','], ['', '.'], $request->price),
                'tasks' => json_encode($json),
            ]);
            session()->flash('flash-success', 'Assinatura editada com sucesso');
            return redirect()->route('app.subscriptions.index');
        } catch (\Exception $e) {
            session()->flash('flash-warning', $e->getMessage());
            return redirect()->back()->withInput($request->all());
        }
    }
}
