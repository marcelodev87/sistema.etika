<?php

namespace App\Http\Controllers;

use App\InternalProcess;
use App\InternalTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class InternalProcessController extends Controller
{

    public function index()
    {
        $processes = InternalProcess::all();
        return view('processes.index', compact('processes'));
    }

    public function create()
    {
        return view('processes.create');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $input['slug'] = Str::slug($input['name']);
        $input['price'] = str_replace(['.',','],['', '.'], $input['price']);
        $rules = [
            'name' => 'required|string|min:3',
            'slug' => 'required|string|unique:internal_processes',
            'price' => 'required|numeric|min:0',
        ];
        $errors = [];
        $fields = [
            'name' => 'nome',
            'price' => 'price',
        ];
        $validator = Validator::make($input, $rules, $errors, $fields);
        if($validator->fails()){
            session()->flash('flash-warning', $validator->errors()->first());
            return redirect()->back()->withInput($request->all());
        }

        try {
            InternalProcess::create([
               'name' => $input['name'],
               'slug' => $input['slug'],
               'price' => $input['price'],
            ]);
            session()->flash('flash-success', 'Processo cadastrado com sucesso');
            return redirect()->route('app.processes.index');
        }catch (\Exception $e){
            session()->flash('flash-warning', $e->getMessage());
            return redirect()->back();
        }
    }

    public function edit(InternalProcess $internalProcess)
    {
        $attached = $internalProcess->tasks()->pluck('id');
        $tasks = InternalTask::whereNotIn('id', $attached)->orderBy('name')->get();
        return view('processes.edit', compact('internalProcess', 'tasks'));
    }

    public function update(Request $request, InternalProcess $internalProcess)
    {
        $input = $request->all();
        $input['slug'] = Str::slug($input['name']);
        $input['price'] = str_replace(['.',','],['', '.'], $input['price']);
        $rules = [
            'name' => 'required|string|min:3',
            'slug' => 'required|string|unique:internal_processes,slug,'.$internalProcess->id,
            'price' => 'required|numeric|min:0',
        ];
        $errors = [];
        $fields = [
            'name' => 'nome',
            'price' => 'price',
        ];
        $validator = Validator::make($input, $rules, $errors, $fields);
        if($validator->fails()){
            session()->flash('flash-warning', $validator->errors()->first());
            return redirect()->back()->withInput($request->all());
        }

        try {
            $internalProcess->update([
                'name' => $input['name'],
                'slug' => $input['slug'],
                'price' => $input['price'],
            ]);
            session()->flash('flash-success', 'Processo editado com sucesso');
            return redirect()->route('app.processes.index');
        }catch (\Exception $e){
            session()->flash('flash-warning', $e->getMessage());
            return redirect()->back();
        }
    }

    public function attachTask(Request $request, InternalProcess $internalProcess)
    {
        $next = $internalProcess->tasks()->count();
        $internalProcess->tasks()->attach($request->internal_task_id, ['position' => $next]);

        return redirect()->route('app.processes.edit', $internalProcess->id);
    }

    public function detachTask(Request $request, InternalProcess $internalProcess)
    {
        $internalProcess->tasks()->wherePivot('position', $request->key)->delete();

            $x = $internalProcess->tasks()->wherePivot('position', '>', $request->key)->get();
            foreach ($x as $y){
                $internalProcess->tasks()->updateExistingPivot($y->id, ['position' => $y->pivot->position - 1]);
            }

        return redirect()->back();
    }

    public function putUp(Request $request, InternalProcess $internalProcess)
    {
        $key = $request->key;
        $newKey = $key - 1;
        $newOldKey = $key;
        $taskOld = $internalProcess->tasks()->wherePivot('position', $key)->get();
        $taskNew = $internalProcess->tasks()->wherePivot('position', $newKey)->get();
        $internalProcess->tasks()->updateExistingPivot($taskOld, ['position' => $newKey]);
        $internalProcess->tasks()->updateExistingPivot($taskNew, ['position' => $newOldKey]);
        return redirect()->back();
    }

    public function putDown(Request $request, InternalProcess $internalProcess)
    {
        $key = $request->key;
        $newKey = $key + 1;
        $newOldKey = $key;
        $taskOld = $internalProcess->tasks()->wherePivot('position', $key)->get();
        $taskNew = $internalProcess->tasks()->wherePivot('position', $newKey)->get();
        $internalProcess->tasks()->updateExistingPivot($taskOld, ['position' => $newKey]);
        $internalProcess->tasks()->updateExistingPivot($taskNew, ['position' => $newOldKey]);
        return redirect()->back();
    }


}
