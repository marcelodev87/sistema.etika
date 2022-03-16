@extends('layouts.app')

@section('header')
    @breadcrumb(['title' => 'Clientes'])
    <li><a href="{!! route('app.index') !!}"><i class="fa fa-th"></i> Dashboard</a></li>
    <li class="active"><i class="fa fa-users"></i> Clientes</li>
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

@section('modal')
    {{-- Modal de criação --}}
    <div class="modal fade" tabindex="-1" role="dialog" id="modal-form-cadastrar">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Cadastro</h4>
                </div>
                <form action="{{ route('app.clients.store') }}" method="post" id="form-cadastrar">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-xs-12 col-md-sm-6 col-md-4 col-lg-3">
                                <fieldset class="form-group">
                                    <label>Codigo Interno</label>
                                    <input class="form-control" name="internal_code" type="text">
                                </fieldset>
                            </div>

                            <div class="col-md-xs-12 col-md-sm-6 col-md-6 col-lg-6">
                                <fieldset class="form-group">
                                    <label>Nome Completo</label>
                                    <input class="form-control" name="name" type="text">
                                </fieldset>
                            </div>

                            <div class="col-md-xs-12 col-md-sm-6 col-md-4 col-lg-3">
                                <fieldset class="form-group">
                                    <label>Documento</label>
                                    <input class="form-control document-mask" name="document" type="text">
                                </fieldset>
                            </div>

                            <div class="col-md-xs-12 col-md-sm-6 col-md-4 col-lg-6">
                                <fieldset class="form-group">
                                    <label>E-mail</label>
                                    <input class="form-control" name="email" type="text">
                                </fieldset>
                            </div>

                            <div class="col-md-xs-12 col-md-sm-6 col-md-4 col-lg-3">
                                <fieldset class="form-group">
                                    <label>Telefone</label>
                                    <input class="form-control phone-mask" name="phone" type="text">
                                </fieldset>
                            </div>

                            <div class="col-md-xs-12 col-md-sm-6 col-md-4 col-lg-3">
                                <fieldset class="form-group">
                                    <label>Tipo</label>
                                    <select class="form-control" name="type">
                                        <option value="">Selecione</option>
                                        <option value="Igreja">Igreja</option>
                                        <option value="Empresa">Empresa</option>
                                        <option value="Pessoa Física">Pessoa Física</option>
                                    </select>
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <div class="form-group">
                                    <label for="zip">CEP</label>
                                    <input type="text" name="zip" class="form-control" data-mask="00000-000" id="zip">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-1">
                                <div class="form-group">
                                    <label for="state">UF</label>
                                    <input type="text" name="state" class="form-control" data-mask="AA" id="state">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-4">
                                <div class="form-group">
                                    <label for="city">Cidade</label>
                                    <input type="text" name="city" class="form-control" id="city">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-4">
                                <div class="form-group">
                                    <label for="neighborhood">Bairro</label>
                                    <input type="text" name="neighborhood" class="form-control" id="neighborhood">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-7">
                                <div class="form-group">
                                    <label for="street">Logradouro</label>
                                    <input type="text" name="street" class="form-control" id="street">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2">
                                <div class="form-group">
                                    <label for="street_number">Número</label>
                                    <input type="text" name="street_number" class="form-control" data-mask="000000" id="street_number">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <div class="form-group">
                                    <label for="complement">Complemento</label>
                                    <input type="text" name="complement" class="form-control" id="complement">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Cadastrar</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    {{-- Modal de criação --}}
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>

    <script type="text/javascript">

        $("#form-cadastrar").on('submit', function (e) {
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
                    window.location.href = "{{ route('app.clients.index') }}";

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

        $("#datatable").dataTable({
            order: [
                [0, "asc"]
            ],
            columnDefs: [
                {type: 'natural', targets: 0}
            ]
        });


    </script>
@endsection
