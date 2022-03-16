@extends('layouts.app')

@section('header')
    @breadcrumb(['title' => 'Cartórios'])
    <li>
        <a href="{!! route('app.index') !!}">
            <i class="fa fa-th"></i> Dashboard
        </a>
    </li>

    <li>
        <a href="{!! route('app.notaryAddresses.index') !!}">
            <i class="fa fa-warehouse"></i> Cartórios
        </a>
    </li>

    <li class="active">
        <i class="fa fa-edit"></i> Editar
    </li>

    <li class="active">
        <i class="fa fa-hashtag"></i> {{ $notaryAddress->name }}
    </li>
    @endbreadcrumb
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 text-right mb-1">
            <a href="{!! route('app.notaryAddresses.index') !!}" class="btn btn-sm btn-default">
                <i class="fa fa-reply"></i> Voltar
            </a>
        </div>
        <div class="col-md-12">
            <div class="chart-box">
                <form method="post" action="{{ route('app.notaryAddresses.update', $notaryAddress->id) }}">
                    @csrf
                    @method('put')
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="input-name">Nome</label>
                                <input type="text" name="name" id="input-name" class="form-control" value="{{ old('name', $notaryAddress->name) }}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="input-email-1">E-mail 1</label>
                                <input type="email" name="email_1" id="input-email-1" class="form-control" value="{{ old('email_1', $notaryAddress->email_1) }}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="input-email-2">E-mail 2</label>
                                <input type="email" name="email_2" id="input-email-2" class="form-control" value="{{ old('email_2', $notaryAddress->email_2) }}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="input-phone-1">Telefone 1</label>
                                <input type="text" name="phone_1" id="input-phone-1" class="form-control phone" value="{{ old('phone_1', $notaryAddress->phone_1) }}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="input-phone-2">Telefone 2</label>
                                <input type="text" name="phone_2" id="input-phone-2" class="form-control phone" value="{{ old('phone_2', $notaryAddress->phone_2) }}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="input-phone-3">Telefone 3</label>
                                <input type="text" name="phone_3" id="input-phone-3" class="form-control phone" value="{{ old('phone_3', $notaryAddress->phone_3) }}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2">
                            <div class="form-group">
                                <label for="zip">CEP</label>
                                <input type="text" name="zip" id="zip" class="form-control" value="{{ old('zip', $notaryAddress->zip) }}" data-mask="00000-000">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-2 col-lg-1">
                            <div class="form-group">
                                <label for="state">UF</label>
                                <input type="text" name="state" id="state" class="form-control" value="{{ old('state', $notaryAddress->state) }}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="city">Cidade</label>
                                <input type="text" name="city" id="city" class="form-control" value="{{ old('city', $notaryAddress->city) }}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="neighborhood">Bairro</label>
                                <input type="text" name="neighborhood" id="neighborhood" class="form-control" value="{{ old('neighborhood', $notaryAddress->neighborhood) }}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-7">
                            <div class="form-group">
                                <label for="street">Logradouro</label>
                                <input type="text" name="street" id="street" class="form-control" value="{{ old('street', $notaryAddress->street) }}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2">
                            <div class="form-group">
                                <label for="street_number">Número</label>
                                <input type="text" name="street_number" id="street_number" class="form-control" value="{{ old('street_number', $notaryAddress->street_number) }}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label for="complement">Complemento</label>
                                <input type="text" name="complement" id="complement" class="form-control" value="{{ old('complement', $notaryAddress->complement) }}">
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="textarea-obs1">Observação 1</label>
                                <textarea class="form-control summernote" id="textarea-obs1" name="observation_1">{{ old('observation_1', $notaryAddress->observation_1) }}</textarea>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="textarea-obs2">Observação 2</label>
                                <textarea class="form-control summernote" id="textarea-obs2" name="observation_2">{{ old('observation_2', $notaryAddress->observation_2) }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-12 text-right">
                            <button type="submit" class="btn btn-sm btn-success">
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
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.6.12/dist/summernote.min.js"></script>
    <script type="text/javascript">
        $('.summernote').summernote({
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']]
            ]
        });
    </script>
@endsection
