@extends('layouts.app')

@section('content')
    @if ($errors->any())
        <div class="alert alert-warning alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>Oops...</strong> {!! $errors->first() !!}
        </div>
    @endif
    <div class="form-body" id="middleAuthBox">
        <form method="post" action="{!! route('register') !!}" class="col-form" novalidate="">
            @csrf
            <div class="col-logo">
                <a href="#">
                    <img alt="" src="{!! asset('img/logo-lg.png') !!}" class="img-responsive center-block"/>
                </a>
            </div>
            <fieldset>
                <section>
                    <div class="form-group has-feedback">
                        <label class="control-label">Nome</label>
                        <input class="form-control" placeholder="Nome" type="text" name="name" value="{{ old('name') }}">
                        <span class="fa fa-user form-control-feedback" aria-hidden="true"></span>
                    </div>
                </section>

                <section>
                    <div class="form-group has-feedback">
                        <label class="control-label">E-mail</label>
                        <input class="form-control" placeholder="E-mail" type="text" name="email"
                               value="{{ old('email') }}">
                        <span class="fa fa-envelope form-control-feedback" aria-hidden="true"></span>
                    </div>
                </section>

                <section>
                    <div class="form-group has-feedback">
                        <label class="control-label">Senha</label>
                        <input class="form-control" placeholder="Senha" type="password" name="password"
                               value="{{ old('password') }}">
                        <span class="fa fa-lock form-control-feedback" aria-hidden="true"></span>
                    </div>
                </section>

                <section>
                    <div class="form-group has-feedback">
                        <label class="control-label">Confirmação de Senha</label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                               required>
                        <span class="fa fa-lock form-control-feedback" aria-hidden="true"></span>
                    </div>
                </section>
                <section>
                    <div class="row">
                        <div class="col-md-12 text-center mb-1 text-justify" style="font-size: 10px; text-transform: uppercase">
                            ao se registrar nesta página você concodar com a politica de privacidade e termos de uso
                        </div>
                    </div>
                </section>
            </fieldset>
            <footer class="text-right">
                <button type="submit" class="btn btn-success btn-sm pull-right ml-1">
                    <i class="fa fa-save"></i> Registrar
                </button>
                <a href="{!! route('login') !!}" class="btn btn-default btn-sm pull-right">
                    <i class="fa fa-reply"></i> Login
                </a>
            </footer>
        </form>
    </div>
@endsection
