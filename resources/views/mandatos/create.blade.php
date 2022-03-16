@extends('layouts.app')
@section('header')
    @breadcrumb(['title' => 'Mandatos'])
    <li>
        <a href="{!! route('app.index') !!}">
            <i class="fa fa-th"></i> Dashboard
        </a>
    </li>
    <li>
        <a href="{!! route('app.clients.index') !!}">
            <i class="fa fa-users"></i> Clientes
        </a>
    </li>
    <li>
        <a href="{!! route('app.clients.show', $client->id) !!}">
            {{ $client->name }}
        </a>
    </li>
    <li>
       <a href="{{ route('app.clients.mandatos.index', $client->id) }}">
           <i class="fa fa-ribbon"></i> Mandatos
       </a>
    </li>
    <li class="active">
        <i class="fa fa-plus"></i> Adicionar
    </li>
    @endbreadcrumb
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-4 col-md-offset-4">
           <div class="chart-box">
               <h2 class="text-center" style="margin-top: 0">Mandatos</h2>
               <hr>
               <form method="post" action="{{ route('app.clients.mandatos.store', $client->id) }}">
                   @csrf
                   <div class="form-group">
                       <label>Início</label>
                       <input type="text" name="start_at" class="form-control" data-mask="00/00/0000" required value="{{ old('start_at') }}">
                   </div>
                   <div class="form-group">
                       <label>Termino</label>
                       <input type="text" name="end_at" class="form-control" data-mask="00/00/0000" required value="{{ old('end_at') }}">
                   </div>

                   <button class="btn btn-sm btn-success btn-block" type="submit">
                       <i class="fa fa-save"></i> Salvar
                   </button>
               </form>
           </div>
        </div>
    </div>


@endsection
