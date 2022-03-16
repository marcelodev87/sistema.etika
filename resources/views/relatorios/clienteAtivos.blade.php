@extends('layouts.app')

@section('header')
    @breadcrumb(['title' => 'Clientes'])
    <li>
        <a href="{!! route('app.index') !!}">
            <i class="fa fa-th"></i> Dashboard
        </a>
    </li>
    <li class="active">
        <i class="fa fa-users"></i> Clientes
    </li>
    @endbreadcrumb
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 text-right mb-1">
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-form-cadastrar">
                Criar
            </button>
        </div>
        <div class="col-md-12">
            <div class="chart-box">
                <div class="bs-example" data-example-id="hoverable-table">
                    <table class="table table-hover table-striped" id="datatable">
                        <thead>
                        <tr>
                            <th>Codigo interno</th>
                            <th>Nome Completo</th>
                            <th>Documento</th>
                            <th>Tipo</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($clients as $client)
                            <tr>
                                <td>{{ $client->internal_code ?? "Não vinculado" }}</td>
                                <td>{{ $client->name }}</td>
                                <td>{{ $client->document }}</td>
                                <td>{{ $client->type }}</td>
                                <td class="text-right">
                                    <a href="{!! route('app.clients.show', $client->id) !!}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="left" title="Ver"><i class="fa fa-eye"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script type="text/javascript">
        $("#datatable").dataTable();
    </script>
@endsection
