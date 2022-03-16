@extends('layouts.app')

@section('header')
    @breadcrumb(['title' => 'Serviços'])
    <li>
        <a href="{!! route('app.index') !!}">
            <i class="fa fa-th"></i> Dashboard
        </a>
    </li>
    <li>
        <a href="{{ route('app.services.index') }}">
            <i class="fa fa-tags"></i> Serviços
        </a>
    </li>
    <li class="active">
        <i class="fa fa-plus"></i> Adicionar
    </li>
    @endbreadcrumb
@endsection
@section('content')
    <div class="row">


        <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-4 col-md-offset-4">
            <div class="chart-box">
                <form method="post" action="{{ route('app.services.store') }}" id="addForm">
                    @csrf
                    <div class="form-group">
                        <label>Nome</label>
                        <input type="text" class="form-control" name="name">
                    </div>

                    <div class="form-group">
                        <label>Descrição</label>
                        <textarea class="form-control" name="description"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Valor</label>
                        <input type="text" class="form-control" name="valor" data-mask="000.000,00" data-mask-reverse="true">
                    </div>

                    <div class="form-group">
                        <label>Valor por extenso</label>
                        <input type="text" class="form-control" name="valorString">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{route('app.services.index')}}" class="btn btn-default btn-sm btn-block">
                                <i class="fa fa-reply"></i> Voltar
                            </a>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-sm btn-block btn-success">
                                <i class="fa fa-save"></i> Salvar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">

        $('#addForm').on('submit', function (e) {
            e.preventDefault();
            var form = $(this);
            var data = new FormData(form[0]);
            var button = form.find('button[type="submit"]');
            var buttonText = button.html();
            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: data,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    button.html('<i class="fas fa-spinner fa-pulse"></i> Salvando...').attr('disabled', 'disabled');
                },
                success: function (response) {
                    button.html('<i class="fas fa-spinner fa-pulse"></i> Redirecionando...');
                    window.location.href = "{{ route('app.services.index') }}";
                },
                error: function (response) {
                    button.html(buttonText).removeAttr('disabled');
                    var err = JSON.parse(response.responseText);
                    Swal('Oops', err.message, 'error');
                }
            })
        });
    </script>
@endsection
