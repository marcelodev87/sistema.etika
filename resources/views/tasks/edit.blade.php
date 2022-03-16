@extends('layouts.app')

@section('header')
    @breadcrumb(['title' => 'Tarefas'])
    <li>
        <a href="{!! route('app.index') !!}">
            <i class="fa fa-th"></i> Dashboard
        </a>
    </li>
    <li>
        <a href="{!! route('app.tasks.index') !!}">
            <i class="fa fa-check-circle"></i> Tarefas
        </a>
    </li>
    <li class="active">
        <i class="fa fa-edit"></i> Editar
    </li>
    <li class="active">
        <i class="fa fa-hashtag"></i> {{ $internalTask->name }}
    </li>
    @endbreadcrumb
@endsection
@section('content')

    <div class="row">
        <div class="col-xs-12 col-sm-6 col-sm-offset-6 col-md-4 col-md-offset-4">
            <div class="chart-box">
                <form class="row" method="post" action="{{ route('app.tasks.update', $internalTask->id) }}">
                    @csrf
                    @method('put')
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label>Nome</label>
                            <input name="name" type="text" class="form-control" value="{{ old('name', $internalTask->name) }}">
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="form-group">
                            <label>Preço</label>
                            <input name="price" type="text" class="form-control" value="{{ old('price',  $internalTask->price) }}" data-mask="##0.000,00" data-mask-reverse="true">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <a href="{!! route('app.tasks.index') !!}" class="btn btn-sm btn-block btn-default">
                            <i class="fa fa-reply"></i> Voltar
                        </a>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <button class="btn btn-sm btn-block btn-success">
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

    </script>
@endsection
