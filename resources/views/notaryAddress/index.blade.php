@extends('layouts.app')

@section('header')
    @breadcrumb(['title' => 'Cartórios'])
    <li>
        <a href="{!! route('app.index') !!}">
            <i class="fa fa-th"></i> Dashboard
        </a>
    </li>
    <li class="active">
        <i class="fa fa-clipboard-check"></i> Cartório
    </li>
    @endbreadcrumb
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 text-right mb-1">
            <a href="{!! route('app.notaryAddresses.create') !!}" class="btn btn-sm btn-success">
                <i class="fa fa-plus"></i> Adicionar
            </a>
        </div>
        <div class="col-md-12">
            <div class="chart-box">
                <table class="table table-hover table-striped" id="datatable">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Telefone</th>
                        <th>Endereço</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($notaryAddresses as $notaryAddress)
                        <tr>
                            <td>{{ $notaryAddress->id }}</td>
                            <td>{{ $notaryAddress->name }}</td>
                            <td>{{ $notaryAddress->email_1 ?? '' }} {{ $notaryAddress->email_2 ?? '' }}</td>
                            <td>{{ $notaryAddress->phone_1 ?? '' }} {{ $notaryAddress->phone_2 ?? '' }} {{ $notaryAddress->phone_3 ?? '' }}</td>
                            <td>{{ $notaryAddress->getAddress() ?? '' }}</td>
                            <td class="text-right">

                                <a href="{{ route('app.notaryAddresses.edit', $notaryAddress->id) }}" class="btn btn-xs btn-info">
                                    <i class="fa fa-edit"></i>
                                </a>

                                <form class="form-inline" action="{!! route('app.notaryAddresses.delete', $notaryAddress->id) !!}" method="post">
                                    @csrf
                                    @method('delete')
                                    <button type="button" class="btn btn-xs btn-danger formConfirmDelete" data-nome="{{ $notaryAddress->name }}" data-toggle="tooltip" data-placement="left" title="Deletar">
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
@endsection

@section('script')
    <script type="text/javascript">
        $("#datatable").dataTable();
        $('body').on('click', '.formConfirmDelete', function (event) {
            event.preventDefault();
            var form = $(this).closest('form');
            var nome = $(this).attr('data-nome');
            Swal.fire({
                title: 'Você tem certeza que deseja deletar o cartóriod \'' + nome + '\'?',
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
