@extends('layouts.app')

@section('content')
    <div class="form-body" id="middleAuthBox">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="col-form">
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
            </fieldset>
            <footer class="text-right">
                <a href="{{ route('app.index') }}" class="btn btn-sm btn-default" id="backBtn">
                    <i class="fa fa-reply"></i> Voltar
                </a>
                <button type="button" class="btn btn-success btn-sm" id="submitBtn">
                    <i class="fa fa-magic"></i> Recuperar senha
                </button>
            </footer>
        </form>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $('#submitBtn').on('click', function(){
            $('#backBtn').hide();
            $(this).closest('form').submit();
            $(this).attr('disabled', 'disabled').attr('type', 'button').html(' <i class="fas fa-spinner fa-pulse"></i> Aguarde');
        });
    </script>
@endsection
