@extends('layouts.app')

@section('header')
    @breadcrumb(['title' => 'Processos'])
    <li>
        <a href="{!! route('app.index') !!}">
            <i class="fa fa-th"></i> Dashboard
        </a>
    </li>
    <li>
        <a href="{!! route('app.processes.index') !!}">
            <i class="fa fa-clipboard-check"></i> Processos
        </a>
    </li>
    <li class="active">
        <i class="fa fa-edit"></i> Eitar
    </li>
    <li class="active">
        <i class="fa fa-hashtag"></i> {{ $internalProcess->name }}
    </li>
    @endbreadcrumb
@endsection
@section('content')

    <div class="row">
        <div class="col-xs-12 col-sm-4 col-md-2">
            <div class="chart-box">
                <form class="row" method="post" action="{{ route('app.processes.update', $internalProcess->id) }}">
                    @csrf
                    @method('put')
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label>Nome</label>
                            <input name="name" type="text" class="form-control" value="{{ old('name', $internalProcess->name) }}">
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="form-group">
                            <label>Preço</label>
                            <input name="price" type="text" class="form-control" value="{{ old('price', $internalProcess->price) }}" data-mask="##0.000,00" data-mask-reverse="true">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12">
                        <a href="{!! route('app.processes.index') !!}" class="btn btn-sm btn-block btn-default mb-1">
                            <i class="fa fa-reply"></i> Voltar
                        </a>
                    </div>
                    <div class="col-xs-12 col-sm-12">
                        <button class="btn btn-sm btn-block btn-success">
                            <i class="fa fa-save"></i> Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="chart-box">
                <form class="row" method="post" action="{{ route('app.processes.attach.task', $internalProcess->id) }}">
                    @csrf
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="task-id">Tarefas</label>
                            <select name="internal_task_id" class="form-control" id="task-id">
                                <option value="">Selecione</option>
                                @foreach($tasks as $task)
                                    <option value="{{ $task->id }}">{{ $task->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="col-xs-12 col-sm-6">
                        <button class="btn btn-sm btn-block btn-success">
                            <i class="fa fa-save"></i> Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-6">
            <div class="chart-box">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $maxPos = $internalProcess->tasks()->count();
                    @endphp
                    @foreach($internalProcess->tasks()->orderBy('position', 'asc')->get() as $task)

                        <tr>
                            <td>{{ $task->pivot->position }}</td>
                            <td>{{ $task->name }}</td>
                            <td class="text-right">
                                @if($task->pivot->position > 0)
                                    <form class="form-inline" method="post" action="{{ route('app.processes.task.up', $internalProcess->id) }}">
                                        @csrf
                                        @method('put')
                                        <input type="hidden" name="key" value="{{ $task->pivot->position }}">
                                        <button class="btn btn-xs btn-info" type="submit">
                                            <i class="fa fa-arrow-up"></i>
                                        </button>
                                    </form>
                                @endif

                                @if($task->pivot->position < ($maxPos -1))
                                    <form class="form-inline" method="post" action="{{ route('app.processes.task.down', $internalProcess->id) }}">
                                        @csrf
                                        @method('put')
                                        <input type="hidden" name="key" value="{{ $task->pivot->position }}">
                                        <button class="btn btn-xs btn-info" type="submit">
                                            <i class="fa fa-arrow-down"></i>
                                        </button>
                                    </form>
                                @endif

                                <form class="form-inline" method="post" action="{{ route('app.processes.detach.task', $internalProcess->id) }}">
                                    @csrf
                                    @method('delete')
                                    <input type="hidden" name="key" value="{{ $task->pivot->position }}">
                                    <button type="button" class="btn btn-xs btn-danger formConfirmDelete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $('body').on('click', '.formConfirmDelete', function (event) {
            event.preventDefault();
            var form = $(this).closest('form');
            var nome = $(this).attr('data-nome');
            Swal.fire({
                title: 'Você tem certeza que deseja deletar a tarefa \'' + nome + '\'?',
                text: "Você não poderá reverter isso!",
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, exclua!'
            }).then((result) => {
                if (result.value) {
                    form.submit()
                }
            })
        });
    </script>
@endsection
