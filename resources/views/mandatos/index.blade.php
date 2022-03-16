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
    <li class="active">
        <i class="fa fa-ribbon"></i> Mandatos
    </li>
    @endbreadcrumb
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 text-right">
            <a href="{{ route('app.clients.mandatos.create', $client) }}" class="btn btn-sm btn-success">
                <i class="fa fa-plus"></i> Adicionar
            </a>
        </div>
    </div>

    <div class="chart-box mt-2">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Início</th>
                <th>Término</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($client->mandatos as $mandato)
                <tr>
                    <td>{{ $mandato->start_at->format('d/m/Y') }}</td>
                    <td>{{ $mandato->end_at->format('d/m/Y') }}</td>
                    <td class="text-right">
                        <form method="post" action="{{ route('app.clients.mandatos.delete', [$client->id, $mandato->id]) }}" onsubmit="return confirm('Deseja mesmo deletar?')">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-xs btn-danger">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection


