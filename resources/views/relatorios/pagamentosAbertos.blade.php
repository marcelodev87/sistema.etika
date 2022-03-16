@extends('layouts.app')

@section('header')
    @breadcrumb(['title' => 'Relatórios'])
    <li>
        <a href="{!! route('app.index') !!}">
            <i class="fa fa-th"></i> Dashboard
        </a>
    </li>
    <li class="active">
        <i class="fa fa-copy"></i> Pagamentos Abertos
    </li>
    @endbreadcrumb
@endsection
@section('content')
    <div class="chart-box">
        <table class="table table-hover table-striped" id="datatable">
            <thead>
            <tr>
                <th>Cliente</th>
                <th>Processo</th>
                <th>Valor</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($processos as $processo)
                @php
                    $total = $processo->totalPrice() - $processo->totalPayed();
                @endphp
                @if($total > 0)
                    <tr>

                        <td>
                            <a href="{{ route('app.clients.show', $processo->client->id) }}">{{ $processo->client->name }}</a>
                        </td>
                        <td>
                            <a href="{{ route('app.clients.processes.index', [$processo->client->id, $processo]) }}">{{ $processo->process->name }}
                        </td>
                        <td>{{ brl($total) }}</td>

                    </tr>
                @endif
            @endforeach
            </tbody>
        </table>
    </div>
@endsection


@section('script')
    <script type="text/javascript">
        $("#datatable").dataTable();
    </script>
@endsection
