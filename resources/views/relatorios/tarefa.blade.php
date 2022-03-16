@extends('layouts.app')

@section('header')
    @breadcrumb(['title' => 'Relatórios'])
    <li>
        <a href="{!! route('app.index') !!}">
            <i class="fa fa-th"></i> Dashboard
        </a>
    </li>
    <li class="active">
        <i class="fa fa-copy"></i> Tarefas Abertas
    </li>
    @endbreadcrumb
@endsection

@section('content')
    <div class="chart-box">
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
                                $url = route('app.clients.show', $tarefa['client_id']);
                                $tipo = "Tarefa";
                                break;
                            case 'process_task':
                                $url = route('app.clients.processes.index', [$tarefa['client_id'], $tarefa['process_id']]);
                                $tipo = "Processo";
                                break;
                            case 'subscription_task':
                                $url = route('app.clients.subscriptions.tasks.show', [$tarefa['client_id'], $tarefa['subscription_id']]);
                                $tipo = "Assinatura";
                                break;
                        }
                    @endphp
                    <tr>
                        <td>
                            <a href="{{ $url }}">
                                {{ $tarefa['name'] }}
                                <small>({{ $tipo }})</small>
                            </a>
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

                            @if($type != 'closed')
                                @if($tarefa['type'] == 'single_task')
                                    <a href="#modal-adiar" data-toggle="modal" data-task-type="{{ $tarefa['type'] }}" data-task="{{ $tarefa['id'] }}" class="btn btn-xs btn-danger singleTaskAdiar">
                                        <i class="fa fa-times"></i> Adiar
                                    </a>

                                    <a href="{{ route('app.clients.tasks.done',[$tarefa['client_id'], $tarefa['id']]) }}" class="btn btn-xs btn-success" onclick="return confirm('Deseja mesmo finalizar?')">
                                        <i class="fa fa-check"></i> Finalizar
                                    </a>
                                @elseif($tarefa['type'] == 'process_task')
                                    <a href="#modal-adiar" data-toggle="modal" data-task-type="{{ $tarefa['type'] }}" data-task="{{ $tarefa['id'] }}" class="btn btn-xs btn-danger processTaskAdiar">
                                        <i class="fa fa-times"></i> Adiar
                                    </a>
                                    <a href="{{ route('app.clients.processes.tasks.done', [$tarefa['client_id'],$tarefa['process_id'], $tarefa['id']]) }}" data-task="{{ $tarefa['id'] }}" class="btn btn-xs btn-success processTaskFinalizar" onclick="return confirm('Deseja mesmo finalizar?')">
                                        <i class="fa fa-check"></i> Finalizar
                                    </a>
                                @else
                                    <a href="#modal-adiar" data-toggle="modal" data-task-type="{{ $tarefa['type'] }}" data-task="{{ $tarefa['id'] }}" class="btn btn-xs btn-danger processTaskAdiar">
                                        <i class="fa fa-times"></i> Adiar
                                    </a>
                                    <a href="{{ route('app.assinaturaTaskClose',  $tarefa['id']) }}" data-task="{{ $tarefa['id'] }}" class="btn btn-xs btn-success processTaskFinalizar" onclick="return confirm('Deseja mesmo finalizar?')">
                                        <i class="fa fa-check"></i> Finalizar
                                    </a>
                                @endif
                            @endif
                            <a href="javascript:void(0)" class="btn btn-xs btn-info showComments" data-task-type="{{ $tarefa['type'] }}" data-task="{{ $tarefa['id'] }}">
                                <i class="fa fa-comments"></i>
                            </a>
                        </td>
                    </tr>
                @endif
            @endforeach
            </tbody>
        </table>
    </div>

    @include('widgets.comments')


@endsection

@section('modal')
    <div class="modal fade" tabindex="-1" role="dialog" id="modal-adiar">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Adiamento</h4>
                </div>

                <form method="post" action="" id="form-adiamento">
                    <input type="hidden" name="task_id" value="">
                    <input type="hidden" name="task_type" value="">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Quantidade</label>
                            <select class="form-control" name="qt">
                                @for($i= 1; $i <= 24; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tipo</label>
                            <select class="form-control" name="tipo">
                                <option value="h">Hora(s)</option>
                                <option value="d">Dia(s)</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">
                            <i class="fa fa-times"></i> Fechar
                        </button>
                        <button type="submit" class="btn btn-sm btn-success ">
                            <i class="fa fa-save"></i> Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        $("#datatable").dataTable();

        $('#modal-adiar').on('show.bs.modal', function (event) {
            var $target = $(event.relatedTarget);
            var $type = $target.attr('data-task-type');
            var $task = $target.attr('data-task');
            var $modal = $('#modal-adiar');
            $modal.find('input[name="task_id"]').val($task);
            $modal.find('input[name="task_type"]').val($type);
        });

        $('#form-adiamento').on('submit', function (e) {
            e.preventDefault()
            var $confirm = confirm('Desja mesmo fazer esta ação?');
            if($confirm){

                var $form = $(this);
                var $button = $form.find('button[type="submit"]');
                var $buttonText = $button.html();
                var $data = new FormData($form[0]);

                var $type = $form.find('input[name="task_type"]').val();
                var $url = '';
                if ($type == 'single_task') {
                    $url = "{{ route('app.singleTaskDelay') }}";
                } else if ($type == 'process_task') {
                    $url = "{{ route('app.processTaskDelay') }}";
                } else {
                    $url = "{!! route('app.subscriptionTaskDelay') !!}";
                }

                $.ajax({
                    url: $url,
                    type: $form.attr('method'),
                    data: $data,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    cache: false,
                    beforeSend: () => { // aqui vai o que tem que ser feito antes de chamar o endpoint
                        $button.attr('disabled', 'disabled').html('<i class="fas fa-spinner fa-pulse"></i> Carregando...');
                    },
                    success: (response) => { // aqui vai o que der certo
                        window.location.reload();
                    },
                    error: (response) => { // aqui vai o que acontece quando ocorrer o erro
                        var json = $.parseJSON(response.responseText);
                        alert(json.message);
                    },
                    complete: () => { // aqui vai o que acontece quando tudo acabar
                        $button.removeAttr('disabled').html($buttonText);
                    }
                });
            }
        });

        {{-- Mostrar Comentários --}}
        $('body').on('click', '.showComments', function () {
            var $type = $(this).attr('data-task-type');
            var $task = $(this).attr('data-task');
            var $endpoint = "";
            switch ($type) {
                case 'single_task':
                    $endpoint = "{{ route('app.task.comments.single', ':TASK') }}";
                    break;
                case 'process_task':
                    $endpoint = "{{ route('app.task.comments.process', ':TASK') }}";
                    break;
                case 'subscription_task':
                    $endpoint = "{{ route('app.assinaturaTaskComments', ':TASK')  }}";
                    break;
            }
            $endpoint = $endpoint.replace(':TASK', $task);
            if ($endpoint != "") {
                $.get($endpoint, function (response) {
                    var $html = '';
                    if(response.data.length){
                        var $html = '';
                        $.each(response.data, function (i, e) {
                            $html += '<div class="panel panel-default">';
                            $html += '<div class="panel-heading">';
                            $html += '<h4><b>' + e.user + ' - ' + e.date + '</b></h4>';
                            $html += '</div>';
                            $html += '<div class="panel-body">' + e.comment;
                            $html += '<div class="files">';

                            $.each(e.files, function (x, z) {
                                $html += '<a href="' + z + '" class="btn btn-xs btn-default" target="_blank">';
                                $html += '<i class="fa fa-paperclip"></i> Anexo';
                                $html += '</a>';
                            })

                            $html += '</div>';
                            $html += '</div>';
                            $html += '</div>';
                        });
                        console.log($html)
                    }else{
                         $html += '<h4 class="text-center">Sem comentários</h4>';
                    }
                    $('.sidebar-right-blank').find('.body').append($html);
                });
                $('.sidebar-right-blank').removeClass('hide');
            }
        });

        function showComments(type, task) {
            $.get($endpoint, function (response) {
                $.each(response.data, function (i, e) {
                    var $html = '';
                    $html += '<div class="panel panel-default">';
                    $html += '<div class="panel-heading">';
                    $html += '<h4><b>' + e.user + ' - ' + e.date + '</b></h4>';
                    $html += '</div>';
                    $html += '<div class="panel-body">' + e.comment;
                    $html += '<div class="files">';

                    $.each(e.files, function (x, z) {
                        $html += '<a href="' + z + '" class="btn btn-xs btn-default" target="_blank">';
                        $html += '<i class="fa fa-paperclip"></i> Anexo';
                        $html += '</a>';
                    })

                    $html += '</div>';
                    $html += '</div>';
                    $html += '</div>';
                    $('.sidebar-right-blank').find('.body').append($html);
                });
            });
        }
    </script>
@endsection
