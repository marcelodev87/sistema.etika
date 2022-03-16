@extends('layouts.app')

@section('header')
    @breadcrumb(['title' => 'Serviços'])
    <li>
        <a href="{!! route('app.index') !!}">
            <i class="fa fa-th"></i> Dashboard
        </a>
    </li>
    <li class="active">
        <i class="fa fa-tags"></i> Serviços
    </li>
    @endbreadcrumb
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 text-right mb-1">
            <a href="{{ route('app.services.create') }}" class="btn btn-primary btn-sm">
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
                            <th>Descrição</th>
                            <th>Valor</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($services as $service)
                            <tr>

                                <td>{{ $service->name }}</td>
                                <td>{{ $service->descricao }}</td>
                                <td>{{ brl($service->valor) }} <small>({{ $service->valor_string }})</small></td>
                                <td class="text-right">
                                    <a href="{{ route('app.services.edit',$service->id) }}" class="btn btn-xs btn-info">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form class="form-inline" method="post" action="{{ route('app.services.delete', $service->id) }}" onsubmit="return confirm('Deseja mesmo deletar?')">
                                        @csrf
                                        @method('delete')
                                        <button class="btn btn-danger btn-xs">
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
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        $("#datatable").dataTable();
    </script>
@endsection
