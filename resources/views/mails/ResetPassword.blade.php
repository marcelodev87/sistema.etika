@extends('mails.layout')

@section('content')
    <p style="line-height: 1.5rem">
        Olá <b>{{ $name ?? "NAME" }}</b>, <br/>
        click no link abaixo para trocar sua senha.
    </p>
    <a href="{{ $link ?? "LINK" }}" style="line-height:1.5rem;background-color: #03a678; text-decoration: none; color:#fff; padding: 10px 15px; border-radius: 4px">
        Trocar senha
    </a>
    <p style="font-size: 13px; line-height: 1.5rem">
        <i>caso não tenho solicitado desconsidere este e-mail</i>
    </p>
@endsection
