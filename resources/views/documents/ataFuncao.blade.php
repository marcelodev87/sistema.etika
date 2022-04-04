@extends('layouts.app')

@section('header')
    @breadcrumb(['title' => 'Geração de Documentos'])
    <li>
        <a href="{!! route('app.index') !!}">
            <i class="fa fa-th"></i> Painel
        </a>
    </li>
    <li class="active">
        <i class="fa fa-copy"></i> Geração de Documentos
    </li>
    <li class="active">
        Ata de Fundação
    </li>
    @endbreadcrumb
@endsection

@section('style')

    <style>
        .chart-box {
            margin-bottom: 14px;
        }

        .bootstrap-select + .bootstrap-select {
            margin-top: 7px;
        }

        .bootstrap-select .btn.dropdown-toggle {
            padding: 5px 20px !important;
        }
    </style>
@endsection
@section('content')


    <div class="row">
        <div class="col-md-4">
            <form action="{{ route('app.documents.ataFundacao') }}" method="post">
                @csrf

                <div class="chart-box">
                    <fieldset class="form-group form-group-sm">
                        <select class="form-control selectpicker" name="client_id" required>
                            <option value="">Selecione a Igreja</option>
                            @foreach(\App\Client::where('type', 'igreja')->get() as $user)
                                <option value="{{$user->id}}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </fieldset>

                    <fieldset class="form-group form-group-sm">
                        <select class="form-control selectpicker" name="load_file">
                            <option value="">Tipo de geração</option>
                            <option value="gera_ata_funcao">Ata de Fundação</option>
                        </select>
                    </fieldset>

                    <fieldset class="form-group form-group-sm">
                        <input type="text" name="dt_fundacao" class="form-control" data-mask="00/00/0000" required placeholder="Data da Fundação">
                    </fieldset>
                </div>

                <div class="chart-box selects">
                    <fieldset class="form-group form-group-sm">
                        <select class="form-control selectpicker" name="presidente" required>
                            <option value="">Selecione o Presidente</option>
                        </select>
                    </fieldset>

                    <fieldset class="form-group form-group-sm">
                        <select class="form-control selectpicker" name="vice_presidente">
                            <option value="">Selecione o Vice-Presidente</option>
                        </select>
                    </fieldset>

                    <fieldset class="form-group form-group-sm">
                        <div class="tes">
                            <select class="form-control selectpicker" name="tesouraria[]">
                                <option value="">Selecione Tesoureiro</option>
                            </select>
                            <select class="form-control selectpicker" name="tesouraria[]">
                                <option value="">Selecione Tesoureiro</option>
                            </select>
                            <select class="form-control selectpicker" name="tesouraria[]">
                                <option value="">Selecione Tesoureiro</option>
                            </select>
                        </div>
                    </fieldset>

                    <fieldset class="form-group form-group-sm">
                        <div class="sec">
                            <select class="form-control selectpicker" name="secretaria[]">
                                <option value="">Selecione o Secretário</option>
                            </select>
                            <select class="form-control selectpicker" name="secretaria[]">
                                <option value="">Selecione o Secretário</option>
                            </select>
                            <select class="form-control selectpicker" name="secretaria[]">
                                <option value="">Selecione o Secretário</option>
                            </select>
                        </div>
                    </fieldset>
                </div>

                <button type="submit" class="btn btn-success btn-block">
                    <i class="fa fa-print"></i> Gerar
                </button>

            </form>
        </div>

        <div class="col-md-8">
            <div class="chart-box">
                <div id="output"></div>
            </div>
        </div>
    </div>
@endsection


@section('script')

    <script type="text/javascript">

        $('select[name="client_id"]').on('change', function () {
            var $igreja = $(this).val();
            console.log($igreja)
            // reset selects
            $('.selects').find('select').each((i, e) => {
                var $select = $(e);
                $select.find('option').each((x, z) => {
                    if (x > 0) {
                        z.remove();
                    }
                    $select.selectpicker('refresh');
                });
            });
            if ($igreja != "") {
                var $data = new FormData();
                $data.append('igreja', $igreja);
                $.ajax({
                    url: "{{ route('app.documents.ataFundacao.personas') }}",
                    type: "POST",
                    dataType: 'json',
                    data: $data,
                    contentType: false,
                    processData: false,
                    cache: false,
                    success: (response) => {
                        $('.selects').find('select').each((i, e) => {
                            var $select = $(e);
                            $.each(response.personas, (t, y) => {
                                $option = '<option value="' + y.id + '">' + y.name + '(' + y.role + ')</option>';
                                $select.append($option);
                            });
                            $select.selectpicker('refresh');
                        });
                    },
                    error: (response) => {
                        var json = $.parseJSON(response.responseText);
                        alert(json.message);
                    }
                })


            }
        });

        $('form').on('submit', function (e) {
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
                    $('#output').html(response.html);

                },
                error: (response) => { // aqui vai o que acontece quando ocorrer o erro
                    var json = $.parseJSON(response.responseText);
                    alert(json.message);
                },
                complete: () => { // aqui vai o que acontece quando tudo acabar
                    $button.removeAttr('disabled').html($buttonText);
                }
            });
        })


    </script>
@endsection
