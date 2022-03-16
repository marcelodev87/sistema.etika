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
        <i class="fa fa-plus"></i> Adicionar
    </li>
    @endbreadcrumb
@endsection
@section('content')

    <div class="row">
        <div class="col-xs-12 col-sm-6 col-sm-offset-6 col-md-4 col-md-offset-4">
            <div class="chart-box">
                <form class="row" method="post" action="{{ route('app.tasks.store') }}">
                    @csrf
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label>Nome</label>
                            <input name="name" type="text" class="form-control" value="{{ old('name') }}">
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="form-group">
                            <label>Preço</label>
                            <input name="price" type="text" class="form-control" value="{{ old('price') }}" data-mask="##0.000,00" data-mask-reverse="true">
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <fieldset class="form-group">
                            <label>Setor</label>
                            <select class="form-control" name="setor">
                                <option value="">Selecione</option>
                                @foreach(loadSectors() as $key => $value)
                                    <option value="{{$value}}">{{$value}}</option>
                                @endforeach
                            </select>
                        </fieldset>
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
