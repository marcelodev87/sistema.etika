@extends('layouts.app')

@section('header')
    @breadcrumb(['title' => 'Assinaturas'])
    <li>
        <a href="{!! route('app.index') !!}">
            <i class="fa fa-th"></i> Dashboard
        </a>
    </li>
    <li>
        <a href="{{ route('app.subscriptions.index') }}">
            <i class="fa fa-file-signature"></i> Assinaturas
        </a>
    </li>
    <li class="active">
        <i class="fa fa-hashtag"></i> {{ $subscription->id }}
    </li>
    <li class="active">
        <i class="fa fa-edit"></i> Editar
    </li>
    @endbreadcrumb
@endsection
@section('content')

    <form method="post" action="{{ route('app.subscriptions.update', $subscription->id) }}" id="create-form">
        @csrf
        @method('put')
        <div class="chart-box">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Nome</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $subscription->name) }}">
                    </div>
                    <div class="form-group">
                        <label>Valor</label>
                        <div class="input-group">
                            <div class="input-group-addon">R$</div>
                            <input type="text" name="price" class="form-control" data-mask="000.000,00" data-mask-reverse="true" value="{{ old('price', $subscription->price) }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Hora para delay</label>
                        <div class="input-group">
                            <div class="input-group-addon"><i class="fa fa-clock"></i></div>
                            <input type="text" name="delay_hour" required class="form-control" data-mask="00:00" required value="{{ old('delay_hour', $info['delay']) }}">
                        </div>
                    </div>
                </div>

                <div class="col-md-9">
                    @for($i=0; $i<= 20; $i++)
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <select class="form-control" name="task[]">
                                        <option value="">Selecione a Tarefa</option>
                                        @foreach(\App\InternalTask::orderBy('name', 'asc')->get() as $task)
                                            <option value="{{ $task->id }}" {{ (isset($info['tasks'][$i]) && $info['tasks'][$i]['task'] == $task->id) ? 'selected' : 'null' }}>{{ $task->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <select class="form-control" name="responsible[]">
                                        <option value="">Selecione o responsável</option>
                                        @foreach(\App\User::orderBy('name', 'asc')->get() as $user)
                                            <option value="{{ $user->id }}" {{ (isset($info['tasks'][$i]) && $info['tasks'][$i]['responsible'] == $user->id) ? 'selected' : 'null' }}>{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-group-sm">
                                    <select class="form-control" name="delay[]">
                                        <option value="">Delay</option>
                                        @for($z=1; $z<=30; $z++)
                                            <option value="{{ $z }}" {{ (isset($info['tasks'][$i]) && $info['tasks'][$i]['delay'] == $z) ? 'selected' : 'null' }}>{{ $z }} dia(s)</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>

                <div class="col-md-12 text-right">
                    <button type="submit" class="btn btn-sm btn-success">
                        <i class="fa fa-save"></i> Salvar
                    </button>
                </div>
            </div>
        </div>
    </form>

@endsection
