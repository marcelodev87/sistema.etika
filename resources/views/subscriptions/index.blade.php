@extends('layouts.app')

@section('header')
    @breadcrumb(['title' => 'Assinaturas'])
    <li>
        <a href="{!! route('app.index') !!}">
            <i class="fa fa-th"></i> Dashboard
        </a>
    </li>
    <li class="active">
        <i class="fa fa-file-signature"></i> Assinaturas
    </li>
    @endbreadcrumb
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 text-right mb-1">
            <a href="{{ route('app.subscriptions.create') }}" class="btn btn-success btn-sm">
                <i class="fa fa-plus"></i> Adicionar
            </a>
        </div>

        <div class="col-md-12">
            <div class="chart-box">
                <div class="bs-example" data-example-id="hoverable-table">
                    <table class="table table-hover table-striped" id="datatable">
                        <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Valor</th>
                            <th class="text-center">Ações</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($subscriptions as $sub)
                            <tr>

                                <td>{{ $sub->name }}</td>
                                <td>{{ brl($sub->price) }}</td>
                                <td class="text-center">{{ count(json_decode($sub->tasks, true)['tasks']) }}</td>
                                <td class="text-right">
                                    <a href="{{ route('app.subscriptions.edit', $sub->id) }}" class="btn btn-xs btn-primary">
                                        <i class="fa fa-edit"></i> Editar
                                    </a>
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
