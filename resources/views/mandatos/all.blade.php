@extends('layouts.app')
@section('header')
    @breadcrumb(['title' => 'Mandatos'])
    <li>
        <a href="{!! route('app.index') !!}">
            <i class="fa fa-th"></i> Dashboard
        </a>
    </li>
    <li class="active">
        <i class="fa fa-ribbon"></i> Mandatos
    </li>
    @endbreadcrumb
@endsection


@section('content')

    <div class="chart-box mt-2">
        <table class="table table-striped" id="datatable">
            <thead>
            <tr>
                <th>Cod. Interno</th>
                <th>Cliente</th>
                <th>Início</th>
                <th>Término</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($mandatos as $mandato)
                <tr>
                    <td>{{ $mandato->client->internal_code }}</td>
                    <td>{{ $mandato->client->name }}</td>
                    <td>{{ $mandato->start_at->format('d/m/Y') }}</td>
                    <td>{{ $mandato->end_at->format('d/m/Y') }}</td>
                    <td class="text-right">
                        <form method="post" action="{{ route('app.clients.mandatos.delete', [$mandato->client->id, $mandato->id]) }}" onsubmit="return confirm('Deseja mesmo deletar?')">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-xs btn-danger">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        //$("#datatable").dataTable();

        $("#datatable").dataTable({
            columnDefs: [
                {orderable: false, targets: 4}
            ],
            language: $datatableBR,
        });

    </script>
@endsection
