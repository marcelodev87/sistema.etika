@extends('layouts.app')

@section('header')
    @breadcrumb(['title' => 'Assinaturas'])
    <li>
        <a href="{!! route('app.index') !!}">
            <i class="fa fa-th"></i> Dashboard
        </a>
    </li>
    <li>
        <a href="{{ route('app.subscriptions.index') }}">
            <i class="fa fa-file-signature"></i> Assinaturas
        </a>
    </li>
    <li class="active">
        <i class="fa fa-plus"></i> Adicionar
    </li>
    @endbreadcrumb
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 text-right mb-1">
            <a href="{{ route('app.subscriptions.index') }}" class="btn btn-default btn-sm">
                <i class="fa fa-reply"></i> Voltar
            </a>
        </div>
    </div>

    <form method="post" action="{{ route('app.subscriptions.store') }}" id="create-form">
        <div class="chart-box">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Nome</label>
                        <input type="text" name="name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Valor</label>
                        <div class="input-group">
                            <div class="input-group-addon">R$</div>
                            <input type="text" name="price" class="form-control" data-mask="000.000,00" data-mask-reverse="true">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Hora para delay</label>
                        <div class="input-group">
                            <div class="input-group-addon"><i class="fa fa-clock"></i></div>
                            <input type="text" name="delay_hour" required class="form-control" data-mask="00:00" required value="{{ old('delay_hour', "18:00") }}">
                        </div>
                    </div>
                </div>

                <div class="col-md-9">
                    @for($i=0; $i<= 20; $i++)
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <select class="form-control" name="task[]">
                                        <option value="">Selecione a Tarefa</option>
                                        @foreach(\App\InternalTask::orderBy('name', 'asc')->get() as $task)
                                            <option value="{{ $task->id }}">{{ $task->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-group-sm">
                                    <select class="form-control" name="responsible[]">
                                        <option value="">Selecione o responsável</option>
                                        @foreach(\App\User::orderBy('name', 'asc')->get() as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-group-sm">
                                    <select class="form-control" name="delay[]">
                                        <option value="">Delay</option>
                                        @for($z=1; $z<=30; $z++)
                                            <option value="{{ $z }}">{{ $z }} dia(s)</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>

                <div class="col-md-12 text-right">
                    <button type="submit" class="btn btn-sm btn-success">
                        <i class="fa fa-save"></i> Salvar
                    </button>
                </div>
            </div>
        </div>
    </form>

@endsection

@section('script')
    <script type="text/javascript">

        $('#create-form').on('submit', function (e) {
            e.preventDefault();
            var $confirm = confirm('Todos os campos estão preenchidos corretamente?');
            if ($confirm) {
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
                        setTimeout(function () {
                            window.location.href = "{!! route('app.subscriptions.index') !!}";
                        }, 500)
                    },
                    error: (response) => { // aqui vai o que acontece quando ocorrer o erro
                        console.log(response)
                        var json = $.parseJSON(response.responseText);
                        alert(json.message);
                        $button.removeAttr('disabled').html($buttonText);
                    }
                });
            }
        });
    </script>
@endsection
