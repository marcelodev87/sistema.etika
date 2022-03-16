<html lang="pt-br">
<head>
    <meta charset="utf8">
</head>
<body>

<div style="max-width:600px; border:1px solid #f0f0f0; background-color: #f7f7f7">
    <div style="text-align: center; width:70%; margin: 0 auto; border-bottom: 1px solid #ccc; padding: 21px;">
        <img src="{{ $message->embed(public_path() . '/img/logo-lg.jpeg') }}" alt="" style="display: block; width: 100%;">
        {{--        <img src="{{ asset('/img/logo-lg.jpeg') }}" style="display: block; width: 100%;">--}}
    </div>

    <div style="padding:21px 21px 42px 21px; text-align: center">
        <p>Olá <b>{{ $name ?? "NAME" }}</b>,<br>abaixo está a senha para acessar o painel.</p>
        <div style="display: inline-block; margin-top:21px; border: 1px solid #272727; background-color: #272727; color: #fff; padding: 21px 35px; letter-spacing: 5px">
            {{ $password ?? "SENHA" }}
        </div>
    </div>
</div>

</body>
</html>
