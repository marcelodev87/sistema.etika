@extends('layouts.app')

@section('style')
    <style type="text/css">
        .overlay {
            background-color: #fff;
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: 9999999;
            top: 0;
            left: 0;
        }

        .overlay > .loader {
            width: 200px;
            height: 40px;
            position: absolute;
            top: 50%;
            left: 50%;
            margin-top: -20px;
            margin-left: -100px;
        }
    </style>
@endsection

@section('header')
    @breadcrumb(['title' => 'Pagamentos'])
    <li>
        <a href="{!! route('app.index') !!}">
            <i class="fa fa-th"></i> Dashboard
        </a>
    </li>

    <li class="active">
        <i class="fa fa-hand-holding-usd"></i> Pagamentos
    </li>
    @endbreadcrumb
@endsection


@section('content')
    <div class="overlay">
        <div class="loader text-center">
            Aguarde ...
        </div>
    </div>
    <div class="chart-box">
        <form class="row" id="load-form" method="post" action="{{ route('app.payments.load') }}">
            <div class="col-md-5 col-lg-4 text-right col-md-offset-4 col-lg-offset-6">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="sr-only" for="exampleInputAmount">Período</label>
                    <div class="input-group">
                        <input type="text" name="start_at" class="form-control" id="start-at" data-mask="00/00/0000" value="{{ \Carbon\Carbon::now()->subWeek()->format('d/m/Y') }}">
                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                        <input type="text" name="end_at" class="form-control" id="end-at" data-mask="00/00/0000" value="{{ \Carbon\Carbon::now()->format('d/m/Y') }}">
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-lg-2">
                <button type="submit" class="btn btn-success btn-block">
                    <i class="fa fa-search"></i> Buscar
                </button>
            </div>
        </form>
    </div>
    <div class="chart-box hidden" id="load-area">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-responsive hidden" id='load-table'>
                    <thead>
                    <tr>
                        <th>Data</th>
                        <th>Cliente</th>
                        <th>Valor</th>
                        <th>Arquivo</th>
                    </tr>
                    </thead>
                    <tbody id="load-data">

                    </tbody>
                </table>

                <div id="load-message" class="text-center">
                    <div class="spinner hidden" style="padding:50px 0">
                        <p><i class="fas fa-spinner fa-pulse fa-5x"></i></p>
                        <h1>Carregando dados</h1>
                    </div>
                    <div class="message hidden"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $(window).on('load', function () {
            setTimeout(function () {
                $('.overlay').fadeToggle();
            }, 1000);
        });

        const $area = $('#load-area')
        const $spinner = $area.find('#load-message').find('.spinner');
        const $message = $area.find('#load-message').find('.message');
        const $table = $area.find('#load-table');
        const $data = $table.find('#load-data');

        $('#load-form').on('submit', function (e) {
            e.preventDefault()
            const $form = $(this);
            const $formData = new FormData($form[0]);
            $.ajax({
                url: $form.attr('action'),
                type: $form.attr('method'),
                data: $formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                cache: false,
                beforeSend: () => {
                    $area.removeClass('hidden');
                    $spinner.removeClass('hidden');
                    $message.addClass('hidden').html('');
                    $table.addClass('hidden');
                    $data.html('');
                },
                success: (response) => {
                    setTimeout(function () {
                        $spinner.addClass('hidden');
                    }, 500);

                    if (response.data.registers === 0) {
                        setTimeout(function () {
                            $message.removeClass('hidden').html('<h3>Não há registros nesse período</h3>');
                        }, 500);
                    } else {
                        $.each(response.data.payments, function (index, element) {
                            let link = "";
                            if (element.file) {
                                link = '<a href="' + element.file + '" class="btn btn-xs btn-info" target="_blank">' +
                                    '<i class="fa fa-eye"></i> Visualizar' +
                                    '</a>';
                            }
                            const html = "<tr>" +
                                "<td>" + element.pay_at + "</td>" +
                                "<td>" + element.client + "</td>" +
                                "<td>" + element.value + "</td>" +
                                "<td> " + link + " </td>" +
                                "</tr>";
                            $data.append(html);
                        });
                        setTimeout(function () {
                            $table.removeClass('hidden');
                        }, 500)
                    }
                }
            });

        })
    </script>
@endsection
