@extends('layouts.app')

@section('header')
    @breadcrumb(['title' => 'Usuários'])
    <li>
        <a href="{!! route('app.index') !!}">
            <i class="fa fa-th"></i> Dashboard
        </a>
    </li>
    <li class="active">
        <i class="fa fa-users"></i> Usuários
    </li>
    @endbreadcrumb
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 text-right mb-1">
            <a href="{!! route('app.users.create') !!}" class="btn btn-xs btn-success">
                <i class="fa fa-plus"></i> Adicionar
            </a>
        </div>
        <div class="col-md-12">
            <div class="chart-box">
                <div class="bs-example" data-example-id="hoverable-table">
                    <table class="table table-hover table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Nome Completo</th>
                            <th>Aniversário</th>
                            <th>Genero</th>
                            <th>E-mail</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ ($user->dob) ? $user->dob->format('d/m/Y') : '' }}</td>
                                <td>{{ $user->gender }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ ($user->status) ? 'Liberado' : 'Bloqueado' }}</td>
                                <td class="text-right">

                                    <form class="form-inline" action="{!! route('app.users.updateStatus', $user->id) !!}" method="post">
                                        @csrf
                                        @method('patch')
                                        <button type="submit" class="btn btn-xs btn-{{ ($user->status) ? 'warning' : 'success' }}" data-toggle="tooltip" data-placement="left" title="Mudar o status">
                                            <i class="fa fa-retweet"></i>
                                        </button>
                                    </form>

                                    <a href="{!! route('app.users.edit', $user->id) !!}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="left" title="Editar"><i class="fa fa-edit"></i></a>
                                    <form class="form-inline" action="{!! route('app.users.delete', $user->id) !!}" method="post">
                                        @csrf
                                        @method('delete')
                                        <button type="button" class="btn btn-xs btn-danger formConfirmDelete" data-nome="{{ $user->name }}" data-toggle="tooltip" data-placement="left" title="Deletar">
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
                title: 'Você tem certeza que deseja deletar o usuário \'' + nome + '\'?',
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
