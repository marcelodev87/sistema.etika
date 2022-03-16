@extends('layouts.app')

@section('content')
    <div class="form-body" id="middleAuthBox">
        <form method="post" action="{{ route('login') }}" class="col-form">
            @csrf
            <div class="col-logo">
                <a href="#">
                    <img alt="imagem de logo" src="{!! asset('img/logo-lg.jpeg') !!}" class="img-responsive center-block"/>
                </a>
            </div>
            <fieldset>
                <section>
                    <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                        <label class="control-label">E-mail</label>
                        <input class="form-control" placeholder="E-mail" type="text" name="email" value="{{ old('email') }}"/>
                        @if ($errors->has('email'))
                            <span class="help-block" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </section>
                <section>
                    <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                        <label class="control-label">Senha</label>
                        <input class="form-control" placeholder="Senha" type="password" name="password"/>
                        @if ($errors->has('password'))
                            <span class="help-block" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                </section>
                <section>
                    <div class="row">
                        <div class="col-md-6 checkbox">
                            <a href="{!! route('password.request') !!}" class="modal-opener">Esqueceu a senha?</a>
                        </div>
                        <div class="col-md-6 text-right">
                            <label class="checkbox">
                                <input name="remember" type="checkbox"/>
                                <i></i>Manter conectado
                            </label>
                        </div>
                    </div>
                </section>
            </fieldset>
            <footer class="text-right">
                <button type="submit" class="btn btn-success btn-sm pull-right">
                    <i class="fa fa-sign-in-alt"></i> Entrar
                </button>
            </footer>
        </form>
    </div>
@endsection

@section('script')
    <script type="text/javascript"></script>
@endsection
