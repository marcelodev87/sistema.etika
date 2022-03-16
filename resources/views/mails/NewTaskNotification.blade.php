@extends('mails.layout')

@section('content')
    <p style="line-height: 1.5rem">
        Olá <b>{{ $name ?? "NAME" }}</b>, <br/>
        foi vinculado a você uma nova tarefa.
    </p>
@endsection
