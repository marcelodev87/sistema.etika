<?php

namespace App\Http\Controllers;

use App\ClientProcess;
use App\ClientProcessTask;
use App\ClientSubscriptionTask;
use App\ClientTask;
use Illuminate\Http\Request;

class RelatorioController extends Controller
{
    public function processoAberto()
    {
        $processos = ClientProcess::where('closed', 0)->get();
        return view('relatorios.processosAbertos', compact('processos'));
    }

    public function processoFechado()
    {
        $processos = ClientProcess::where('closed', 1)->get();
        return view('relatorios.processosFechados', compact('processos'));
    }

    public function pagamentoAberto()
    {
        $processos = ClientProcess::where('closed', 0)->get();
        return view('relatorios.pagamentosAbertos', compact('processos'));
    }

    public function tarefaAberta()
    {
        $type = 'open';
        $tarefas = [];
        foreach (ClientProcessTask::where('closed', 0)->get() as $t) {
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

        foreach (ClientTask::where('closed', 0)->get() as $t) {
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

        foreach (ClientSubscriptionTask::where('closed', 0)->get() as $t) {
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

        return view('relatorios.tarefa', compact('tarefas','type'));
    }

    public function tarefaFechada()
    {
        $type = 'closed';
        $tarefas = [];
        foreach (ClientProcessTask::where('closed', 1)->get() as $t) {
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

        foreach (ClientTask::where('closed', 1)->get() as $t) {
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

        foreach (ClientSubscriptionTask::where('closed', 1)->get() as $t) {
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

        return view('relatorios.tarefa', compact('tarefas', 'type'));
    }
}
