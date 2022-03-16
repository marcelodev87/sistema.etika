@extends('layouts.app')

@section('header')
    @breadcrumb(['title' => 'Processos'])
    <li>
        <a href="{!! route('app.index') !!}">
            <i class="fa fa-th"></i> Dashboard
        </a>
    </li>
    <li class="active">
        <i class="fa fa-warehouse"></i> Cartórios
    </li>
    @endbreadcrumb
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 text-right mb-1">
            <a href="{!! route('app.processes.create') !!}" class="btn btn-sm btn-success">
                <i class="fa fa-plus"></i> Adicionar
            </a>
        </div>
        <div class="col-md-12">
            <div class="chart-box">
                <div class="bs-example" data-example-id="hoverable-table">
                    <table class="table table-hover table-striped" id="datatable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Nome</th>
                            <th>Preço</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($processes as $process)
                            <tr>
                                <td>{{ $process->id }}</td>
                                <td>{{ $process->name }}</td>
                                <td>{{ brl($process->price) }}</td>
                                <td class="text-right">
                                    <a href="{!! route('app.processes.edit', $process->id) !!}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="left" title="Editar"><i class="fa fa-edit"></i></a>
                                    <form class="form-inline" action="{!! route('app.processes.delete', $process->id) !!}" method="post">
                                        @csrf
                                        @method('delete')
                                        <button type="button" class="btn btn-xs btn-danger formConfirmDelete" data-nome="{{ $process->name }}" data-toggle="tooltip" data-placement="left" title="Deletar">
                                            <i class="fa fa-trash"></i></button>
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
        $('body').on('click', '.formConfirmDelete', function (event) {
            event.preventDefault();
            var form = $(this).closest('form');
            var nome = $(this).attr('data-nome');
            Swal.fire({
                title: 'Você tem certeza que deseja deletar o processo \'' + nome + '\'?',
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

        $("#datatable").dataTable();
    </script>
@endsection
