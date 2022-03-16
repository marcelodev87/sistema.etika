@php
    $type = 'open';
    $tarefas = [];

    foreach (\App\ClientTask::where('closed', 0)->where('user_id', auth()->user()->id)->get() as $t) {
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
    foreach (\App\ClientSubscriptionTask::where('closed', 0)->where('user_id', auth()->user()->id)->get() as $t) {
        $color = $t->isLate() ? 'danger' : 'success';
        $endAt = ($t->end_at) ? '<span class="text-' . $color . ' text-bold">' . $t->end_at->format('d/m/Y H:i:s') . '</span>' : 'Não informado';
        $arr = [
            'id' => $t->id,
            'type' => 'subscription_task',
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
@endphp
@if(count($tarefas))
    <div class="chart-box">
        <h3 style="margin: 0">Notificações</h3>
        <table class="table table-hover table-striped" id="datatable">
            <thead>
            <tr>
                <th>Tipo</th>
                <th>Cliente</th>
                <th>Responsável</th>
                <th>Data Entrega</th>
                <th>Data Criação</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($tarefas as $tarefa)
                @if(auth()->user()->hasRole('adm') || auth()->user()->id == $tarefa['responsible_id'])
                    @php

                        switch ($tarefa['type']){
                            case 'single_task':
                                $tipo = "Tarefa";
                                break;
                            case 'process_task':
                                $tipo = "Processo";
                                break;
                            case 'subscription_task':
                                $tipo = "Assinatura";
                                break;
                        }
                    @endphp
                    <tr>
                        <td>
                            {{ $tarefa['name'] }}
                            <small>({{ $tipo }})</small>
                        </td>
                        <td>
                            <a href="{{ route('app.clients.show', $tarefa['client_id']) }}">
                                {{ $tarefa['client'] }}
                            </a>
                        </td>
                        <td>{{ $tarefa['responsavel'] }}</td>
                        <td>{!! $tarefa['entrega'] !!}</td>
                        <td>{{ $tarefa['criacao'] }}</td>
                        <td class="text-right">


                        </td>
                    </tr>
                @endif
            @endforeach
            </tbody>
        </table>
    </div>
@endif
