<html lang="pt-br">
<head>
    <meta charset="utf8">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,700;1,300;1,500&display=swap" rel="stylesheet">
</head>
<body>
<div style="width:500px; margin: 0 auto; background-color: #f7f7f7; font-family: 'Roboto', sans-serif; ">
    <div style="width: 80%; margin: 0px auto; padding: 10px 0">
        {{--        <img src="{{ asset('img/logo-lg.jpeg') }}" style="width: 100%;">--}}
        <img src="{{ $message->embed(public_path() . '/img/logo-lg.jpeg') }}" style="width: 100%">
    </div>
    <div style=" width: 95%; border-bottom: 1px solid #000; margin:0 auto;"></div>
    <div style="width: 95%; margin: 21px auto; padding-bottom: 21px; text-align: center">
        @yield('content')
    </div>
</div>

</body>
</html>
