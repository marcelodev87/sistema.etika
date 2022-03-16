@extends('layouts.app')

@section('content')
    <div class="form-body" id="middleAuthBox">

        <form method="POST" action="{{ route('password.update') }}" class="col-form" >
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">
            <div class="col-logo">
                <a href="#">
                    <img alt="imagem de logo" src="{!! asset('img/logo-lg.jpeg') !!}" class="img-responsive center-block"/>
                </a>
            </div>
            <fieldset>

                <section>
                    <div class="form-group row">
                        <label for="password">Senha</label>

                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>
                </section>
                <section>
                    <div class="form-group row">
                        <label for="password-confirm">Confirme a senha</label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                    </div>
                </section>
            </fieldset>
            <footer class="text-center">
                <button type="submit" class="btn btn-sm btn-success">
                    <i class="fa fa-key"></i> Trocar senha
                </button>

            </footer>

        </form>
    </div>

@endsection


@section('script')

@endsection
