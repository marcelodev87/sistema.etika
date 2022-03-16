@extends('layouts.app')

@section('header')
    @breadcrumb(['title' => 'Relatórios'])
    <li>
        <a href="{!! route('app.index') !!}">
            <i class="fa fa-th"></i> Dashboard
        </a>
    </li>
    <li class="active">
        <i class="fa fa-copy"></i> Processos Fechados
    </li>
    @endbreadcrumb
@endsection
@section('content')
    <div class="chart-box">
        <table class="table table-hover table-striped" id="datatable">
            <thead>
            <tr>
                <th>CPF/CNPJ</th>
                <th>Cliente</th>
                <th>UF</th>
                <th>Processo</th>
                <th>Criado em</th>
                <th>Finalizado em</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($processos as $processo)
                <tr>
                    <td>{{ $processo->client->document }}</td>
                    <td>
                        <a href="{{ route('app.clients.show', $processo->client->id) }}">{{ $processo->client->name }}</a>
                    </td>
                    <td>{{ $processo->client->state }}</td>
                    <td>
                        <a href="{{ route('app.clients.processes.index',[$processo->client_id, $processo->id]) }}">
                            {{ $processo->process->name }}
                        </a>
                    </td>
                    <td>
                        <span class="hidden">{{ $processo->created_at->timestamp }}</span>{{ $processo->created_at->format('d/m/Y') }}
                    </td>
                    <td>
                        <span class="hidden">{{ $processo->updated_at->timestamp }}</span>{{ $processo->updated_at->format('d/m/Y') }}
                    </td>
                    <td class="text-right">
                        <button type="button" class="btn btn-xs btn-success" onclick="showHistory({{$processo->client_id}}, {{$processo->id}})">
                            <i class="fa fa-history"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="sidebar-right-blank hide">
        <div class="header">
            <h3>Histórico</h3>
            <a href="javascript:void(0)" onclick="hideComments()" class="text-danger">
                <i class="fa fa-times"></i>
            </a>
        </div>
        <div class="body">

        </div>
    </div>
@endsection


@section('script')
    <script type="text/javascript">
        $("#datatable").dataTable({
            columnDefs: [
                {orderable: false, targets: 6}
            ],
            language: $datatableBR,
        });

        function showHistory(client, process) {
            const $block = $('.sidebar-right-blank');
            var $endpoint = "{{ route('app.clients.processes.history', [':CLIENT', ':PROCESS']) }}";
            $endpoint = $endpoint.replace(':CLIENT', client).replace(':PROCESS', process);
            $.get($endpoint, function (response) {
                $.each(response.data, function (i, e) {
                    console.log(e);
                    var $html = '';
                    $html += '<div class="panel panel-default">';
                    $html += '<div class="panel-heading">';
                    $html += '<h4><b>' + e.user + ' - ' + e.date + '</b></h4>';
                    $html += '</div>';
                    $html += '<div class="panel-body">' + e.comment;
                    $html += '<div class="files">';

                    $.each(e.files, function (x, z) {
                        $html += '<a href="' + z + '" class="btn btn-xs btn-default" target="_blank">';
                        $html += '<i class="fa fa-paperclip"></i> Anexo';
                        $html += '</a>';
                    })

                    $html += '</div>';
                    $html += '</div>';
                    $html += '</div>';
                    $block.find('.body').append($html);
                });
            });
            $block.find('h3').html('Histórico');
            $block.removeClass('hide');
        }
    </script>
@endsection
