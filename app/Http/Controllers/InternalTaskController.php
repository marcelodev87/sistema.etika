<?php

namespace App\Http\Controllers;

use App\InternalTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class InternalTaskController extends Controller
{

    public function index()
    {
        $tasks = InternalTask::all();
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $input['slug'] = Str::slug($input['name']);
        $input['price'] = str_replace(['.', ','], ['', '.'], $input['price']);
        $rules = [
            'name' => 'required|string|min:3',
            'slug' => 'required|string|unique:internal_tasks',
            'price' => 'required|numeric|min:0',
            'setor' => 'required'
        ];
        $errors = [];
        $fields = [
            'name' => 'nome',
            'price' => 'price',
        ];
        $validator = Validator::make($input, $rules, $errors, $fields);
        if ($validator->fails()) {
            session()->flash('flash-warning', $validator->errors()->first());
            return redirect()->back()->withInput($request->all());
        }

        try {
            InternalTask::create([
                'name' => $input['name'],
                'slug' => $input['slug'],
                'price' => $input['price'],
                'setor' => $input['setor']
            ]);
            session()->flash('flash-success', 'Tarefa cadastrado com sucesso');
            return redirect()->route('app.tasks.index');
        } catch (\Exception $e) {
            session()->flash('flash-warning', $e->getMessage());
            return redirect()->back();
        }
    }

    public function edit(InternalTask $internalTask)
    {
        return view('tasks.edit', compact('internalTask'));
    }

    public function update(Request $request, InternalTask $internalTask)
    {
        $input = $request->all();
        $input['slug'] = Str::slug($input['name']);
        $input['price'] = str_replace(['.', ','], ['', '.'], $input['price']);
        $rules = [
            'name' => 'required|string|min:3',
            'slug' => 'required|string|unique:internal_tasks,slug,' . $internalTask->id,
            'price' => 'required|numeric|min:0',
        ];
        $errors = [];
        $fields = [
            'name' => 'nome',
            'price' => 'price',
        ];
        $validator = Validator::make($input, $rules, $errors, $fields);
        if ($validator->fails()) {
            session()->flash('flash-warning', $validator->errors()->first());
            return redirect()->back()->withInput($request->all());
        }

        try {
            $internalTask->update([
                'name' => $input['name'],
                'slug' => $input['slug'],
                'price' => $input['price'],
            ]);
            session()->flash('flash-success', 'Tarefa editada com sucesso');
            return redirect()->route('app.tasks.index');
        } catch (\Exception $e) {
            session()->flash('flash-warning', $e->getMessage());
            return redirect()->back();
        }
    }

    public function destroy(InternalTask $internalTask)
    {
        $internalTask->delete();
        session()->flash('flash-success', 'Deletado com sucesso');
        return redirect()->back();
    }

    public function sector()
    {
        $userLogged = auth()->user();
        $setor = $userLogged->setor;
        if ($setor == null) {
            session()->flash('flash-warning', 'Você não tem um setor definido, fale com o Administrador');
            return redirect()->back();
        }
        $type = 'open';
        $tarefas = [];

        foreach (InternalTask::where('setor', $setor)->first()->clientProcess()->where('closed', 0)->get() as $t) {
            $color = $t->isLate() ? 'danger' : 'success';
            $endAt = ($t->end_at) ? '<span class="text-' . $color . ' text-bold">' . $t->end_at->format('d/m/Y H:i:s') . '</span>' : 'Não informado';
            $arr = [
                'id' => $t->id,
                'type' => 'process_task',
                'process_id' => $t->process->id,
                'name' => $t->task->name,
                'entrega' => $endAt,
                'client' => $t->client->document . ' - ' . $t->client->name,
                'client_id' => $t->client->id,
                'criacao' => $t->created_at->format('d/m/Y H:i:s'),
                'responsavel' => ($t->responsible_person) ? $t->responsible->name : 'Não vinculado',
                'responsible_id' => $t->user_id
            ];
            array_push($tarefas, $arr);
        }

        foreach (InternalTask::where('setor', $setor)->first()->clientTask()->where('closed', 0)->get() as $t) {
            $color = $t->isLate() ? 'danger' : 'success';
            $endAt = ($t->end_at) ? '<span class="text-' . $color . ' text-bold">' . $t->end_at->format('d/m/Y H:i:s') . '</span>' : 'Não informado';
            $arr = [
                'id' => $t->id,
                'type' => 'single_task',
                'name' => $t->task->name,
                'entrega' => $endAt,
                'client' => $t->client->document . ' - ' . $t->client->name,
                'client_id' => $t->client->id,
                'criacao' => $t->created_at->format('d/m/Y H:i:s'),
                'responsavel' => ($t->user_id) ? $t->responsible->name : 'Não vinculado',
                'responsible_id' => $t->user_id
            ];
            array_push($tarefas, $arr);
        }

        foreach (InternalTask::where('setor', $setor)->first()->clientSubscription()->where('closed', 0)->get() as $t) {
            $color = $t->isLate() ? 'danger' : 'success';
            $endAt = ($t->end_at) ? '<span class="text-' . $color . ' text-bold">' . $t->end_at->format('d/m/Y H:i:s') . '</span>' : 'Não informado';
            $arr = [
                'id' => $t->id,
                'type' => 'subscription_task',
                'subscription_id' => $t->client_subscription_id,
                'name' => $t->task->name,
                'entrega' => $endAt,
                'client' => $t->client->document . ' - ' . $t->client->name,
                'client_id' => $t->client->id,
                'criacao' => $t->created_at->format('d/m/Y H:i:s'),
                'responsavel' => ($t->user_id) ? $t->user->name : 'Não vinculado',
                'responsible_id' => $t->user_id
            ];
            array_push($tarefas, $arr);

        }

        return view('sectorTasks', compact('tarefas', 'type'));
    }
}
