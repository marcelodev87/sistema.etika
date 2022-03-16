@extends('layouts.app')

@section('header')
    @breadcrumb(['title' => 'Clientes'])
    <li>
        <a href="{!! route('app.index') !!}">
            <i class="fa fa-th"></i> Dashboard
        </a>
    </li>
    <li>
        <a href="{!! route('app.clients.index') !!}">
            <i class="fa fa-user"></i> Clientes
        </a>
    </li>

    <li>
        <a href="{{ route('app.clients.show', $client->id) }}">
            @if($client->internal_code)
                {{$client->internal_code}} - {{ $client->name }}
            @else
                {{ $client->name }}
            @endif
        </a>
    </li>

    <li class="active">
        Assinaturas
    </li>

    <li class="active">
        <i class="fa fa-hashtag"></i> {{ $clientSubscription->id }}
    </li>
    @endbreadcrumb
@endsection

@section('style')
    <style>
        .panel-heading > h3 {
            margin: 0;
            padding: 0;
        }

        .btn-h-35 {
            height: 35px;
        }
    </style>
@endsection

@section('content')

    <div class="col-md-12 text-right mb-1">
        <a href="#modal-payment" data-toggle="modal" class="btn btn-success btn-sm">
            <i class="fa fa-plus"></i> Pagamento
        </a>
    </div>
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>Pagamentos</h3>
            </div>
            <div class="panel-body">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Data</th>
                        <th>Valor</th>
                        <th>Observação</th>
                        <th>Comprovante</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($clientSubscription->payments as $pay)
                        <tr>
                            <td>{{ $pay->pay_at->format('d/m/Y') }}</td>
                            <td>{{ brl($pay->price) }}</td>
                            <td>{!! $pay->description !!}</td>
                            <td>
                                @if($pay->file)
                                    <a href="{{ \Illuminate\Support\Facades\Storage::url($pay->file) }}" target="_blank" class="btn btn-xs btn-default">
                                        <i class="fa fa-paperclip"></i> Anexo
                                    </a>
                                @else
                                    --
                                @endif
                            </td>
                            <td class="text-right">
                                <form class="form-inline" action="{!! route('app.clients.subscriptions.payments.delete', [$client->id, $clientSubscription->id, $pay->id]) !!}" method="post">
                                    @csrf
                                    @method('delete')
                                    <button type="button" class="btn btn-xs btn-danger formConfirmDelete" data-toggle="tooltip" data-placement="left" title="Deletar">
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


@section('modal')
    @include('clients.subscriptions.modals.addPayment')
@endsection


@section('script')
    <script type="text/javascript">

        $('body').on('click', '.formConfirmDelete', function (event) {
            event.preventDefault();
            var form = $(this).closest('form');
            Swal.fire({
                title: 'Você tem certeza que deseja deletar ?',
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

        $('#form-payment').on('submit', function (e) {
            e.preventDefault();
            var $form = $(this);
            var $button = $form.find('button[type="submit"]');
            var $buttonText = $button.html();
            var $data = new FormData($form[0]);
            $.ajax({
                url: $form.attr('action'),
                type: $form.attr('method'),
                data: $data,
                dataType: 'json',
                contentType: false,
                processData: false,
                cache: false,
                beforeSend: () => { // aqui vai o que tem que ser feito antes de chamar o endpoint
                    $button.attr('disabled', 'disabled').html('<i class="fas fa-spinner fa-pulse"></i> Carregando...');
                },
                success: (response) => { // aqui vai o que der certo
                    window.location.reload();
                    return;
                },
                error: (response) => { // aqui vai o que acontece quando ocorrer o erro
                    console.log(response)
                    var json = $.parseJSON(response.responseText);
                    alert(json.message);
                },
                complete: () => { // aqui vai o que acontece quando tudo acabar
                    $button.removeAttr('disabled').html($buttonText);
                }
            });

        });

        $('.summernote').summernote({
            height: 180,
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['misc', ['codeview']]
            ]
        });
    </script>
@endsection
