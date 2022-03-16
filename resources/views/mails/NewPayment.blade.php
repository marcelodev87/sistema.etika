@extends('mails.layout')

@section('content')
    <h2>Novo Pagamento</h2>
    <p>Cliente: <b>{{ $cliente ?? "" }}</b></p>
    <p>Valor: <b>R$ {{ number_format($valor ?? 0, 2, ',', '.') }}</b></p>
@endsection
