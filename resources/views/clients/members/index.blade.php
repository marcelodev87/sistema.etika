@extends('layouts.app')

@section('header')
    @breadcrumb(['title' => 'Membros'])
    <li>
        <a href="{!! route('app.index') !!}">
            <i class="fa fa-th"></i> Dashboard
        </a>
    </li>
    <li>
        <a href="{!! route('app.clients.index') !!}">
            <i class="fa fa-user"></i> Clientes
        </a>
    </li>
    <li>
        <a href="{!! route('app.clients.show', $client->id) !!}">
            @if($client->internal_code)
                {{$client->internal_code}} - {{ $client->name }}
            @else
                {{ $client->name }}
            @endif
        </a>
    </li>
    <li class="active">
        <i class="fa fa-users"></i> Membros
    </li>
    @endbreadcrumb
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 text-right mb-1">
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-form-cadastrar">
                Novo Membro
            </button>
        </div>
        <div class="col-md-12">
            <div class="chart-box">
                <div class="bs-example" data-example-id="hoverable-table">
                    <table class="table table-hover table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Nome Completo</th>
                            <th>Documento</th>
                            <th>Email</th>
                            <th>Telefone</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($client->members as $member)
                            <tr data-tr-member="{{ $member->id }}">
                                <td>{{ $member->id }}</td>
                                <td>{{ $member->name }}</td>
                                <td>{{ $member->document }}</td>
                                <td>{{ $member->emails()->where('main', 1)->first()->email ?? null }}</td>
                                <td>{{ $member->phones()->where('main', 1)->first()->phone ?? null }}</td>
                                <td class="text-right">
                                    <a href="javascript:void(0);" data-modal="#modal-show" data-member="{{ $member->id }}" class="btn btn-xs btn-info" data-toggle="tooltip" data-placement="bottom" title="Informações">
                                        <i class="fa fa-user"></i>
                                    </a>
                                    <a href="javascript:void(0);" data-modal="#modal-edit" data-member="{{ $member->id }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="bottom" title="Editar">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="javascript:void(0);" data-modal="#modal-addresses" data-member="{{ $member->id }}" class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="bottom" title="Endereços">
                                        <i class="fa fa-map-marker-alt"></i>
                                    </a>
                                    <a href="javascript:void(0);" data-modal="#modal-emails" data-member="{{ $member->id }}" class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="bottom" title="E-mails">
                                        <i class="fa fa-envelope"></i>
                                    </a>
                                    <a href="javascript:void(0);" data-modal="#modal-phones" data-member="{{ $member->id }}" class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="bottom" title="Telefones">
                                        <i class="fa fa-phone"></i>
                                    </a>

                                    <form class="form-inline member-delete" method="post" action="{{ route('app.clients.members.delete',[$client->id, $member->id]) }}">
                                        <button type="submit" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="bottom" title="Deletar">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    @include('clients.members.modals.create')
    @include('clients.members.modals.addresses')
    @include('clients.members.modals.emails')
    @include('clients.members.modals.phones')
    @include('clients.members.modals.edit')
    @include('clients.members.modals.show')

@endsection

@section('script')
    <script type="text/javascript">
        var $member = null;

        $("#form-cadastrar").on('submit', function (e) {
            e.preventDefault();
            var $form = $(this);
            var $button = $form.find('button[type="submit"]');
            var $buttonText = $button.html();
            var $data = new FormData($form[0]);
            $.ajax({
                url: $form.attr('action'),
                type: $form.attr('method'),
                data: $data,
                dataType: 'json',
                contentType: false,
                processData: false,
                cache: false,
                beforeSend: () => { // aqui vai o que tem que ser feito antes de chamar o endpoint
                    $button.attr('disabled', 'disabled').html('<i class="fas fa-spinner fa-pulse"></i> Carregando...');
                },
                success: (response) => { // aqui vai o que der certo
                    window.location.href = "{{ route('app.clients.members.index', $client->id) }}";

                },
                error: (response) => { // aqui vai o que acontece quando ocorrer o erro
                    var json = $.parseJSON(response.responseText);
                    setTimeout(() => {
                        alert(json.message);
                    }, 100)
                },
                complete: () => { // aqui vai o que acontece quando tudo acabar
                    $button.removeAttr('disabled').html($buttonText);
                }
            });
        });

        // ADDRESSES
        // OPEN MODAL
        $('[data-modal="#modal-addresses"]').on('click', function (e) {
            e.preventDefault();
            var $target = $(e.currentTarget);
            var $modal = $($target.attr('data-modal'));
            $member = $target.attr('data-member');
            var $linkGet = "{{ route('app.clients.members.addresses.index',[$client->id, ':MEMBER']) }}".replace(":MEMBER", $member);
            var $linkStore = "{{ route('app.clients.members.addresses.store',[$client->id, ':MEMBER']) }}".replace(":MEMBER", $member);
            $('#modal-form-address').attr('action', $linkStore);
            $.get($linkGet, function (response) {
                var $table = $('#modal-addresses').find('table').find('tbody');
                $table.html('');
                var $html = ''
                $.each(response.data, function (i, e) {
                    $html += '<tr>';
                    $html += '<td>' + e.zip + '</td>';
                    $html += '<td>' + e.state + '</td>';
                    $html += '<td>' + e.city + '</td>';
                    $html += '<td>' + e.neighborhood + '</td>';
                    $html += '<td>' + e.street + '</td>';
                    $html += '<td>' + e.number + '</td>';
                    $html += '<td>' + e.complement + '</td>';
                    if (e.main) {
                        $html += '<td class="text-center"><i class="fa fa-check text-success"></i></td>';
                    } else {
                        $html += '<td class="text-center"><i class="fa fa-times text-danger"></i></td>';
                    }
                    $linkDelete = "{{ route('app.clients.members.addresses.delete', [$client->id, ":MEMBER", ":ID"]) }}".replace(':MEMBER', $member).replace(':ID', e.id);
                    $linkPadrao = "{{ route('app.clients.members.addresses.main', [$client->id, ":MEMBER", ":ID"])  }}".replace(':MEMBER', $member).replace(':ID', e.id);
                    $html += '<td class="text-right">';
                    if (!e.main) {

                        $html += '<form method="post" action="' + $linkPadrao + '" style="display:inline-block;">';
                        $html += '<button class="btn btn-xs btn-success address-form-main" type="button">';
                        $html += '<i class="fa fa-check-circle"></i>';
                        $html += '</button>';
                        $html += '</form>';

                        $html += '<form method="post" action="' + $linkDelete + '" style="display:inline-block;">';
                        $html += '<button class="btn btn-xs btn-danger address-form-delete" type="button">';
                        $html += '<i class="fa fa-trash"></i>';
                        $html += '</button>';
                        $html += '</form>';
                    }
                    $html += '</td>';
                    $html += '</tr>';
                });
                $table.append($html);
            });
            $modal.modal('show');
        });
        // SHOW FORM
        $('[href="#address-create"]').on('click', function () {
            var $link = $(this);
            setTimeout(() => {
                var $area = $('#address-create');
                if ($area.hasClass('in')) {
                    $link.removeClass('btn-default').addClass('btn-danger').html('<i class="fa fa-minus"></i> Fechar');
                } else {
                    $link.removeClass('btn-danger').addClass('btn-default').html('<i class="fa fa-plus"></i> Adicionar');
                }
            }, 500)
        });
        // SUBMIT FORM
        $('#modal-form-address').on('submit', function (e) {
            e.preventDefault();
            var $form = $(this);
            var $button = $form.find('button[type="submit"]');
            var $buttonText = $button.html();
            var $data = new FormData($form[0]);
            var $table = $form.closest('.modal-body').find('table').find('tbody');
            $.ajax({
                url: $form.attr('action'),
                type: $form.attr('method'),
                data: $data,
                dataType: 'json',
                contentType: false,
                processData: false,
                cache: false,
                beforeSend: () => { // aqui vai o que tem que ser feito antes de chamar o endpoint
                    $button.attr('disabled', 'disabled').html('<i class="fas fa-spinner fa-pulse"></i> Carregando...');
                },
                success: (response) => { // aqui vai o que der certo
                    var $html = ''
                    $html += '<tr>';
                    $html += '<td>' + response.data.zip + '</td>';
                    $html += '<td>' + response.data.state + '</td>';
                    $html += '<td>' + response.data.city + '</td>';
                    $html += '<td>' + response.data.neighborhood + '</td>';
                    $html += '<td>' + response.data.street + '</td>';
                    $html += '<td>' + response.data.number + '</td>';
                    $html += '<td>' + response.data.complement + '</td>';
                    if (response.data.main) {
                        $html += '<td class="text-center"><i class="fa fa-check text-success"></i></td>';
                    } else {
                        $html += '<td class="text-center"><i class="fa fa-times text-danger"></i></td>';
                    }
                    $linkDelete = "{{ route('app.clients.members.addresses.delete', [$client->id, ":MEMBER", ":ID"]) }}".replace(':MEMBER', $member).replace(':ID', response.data.id);
                    $linkPadrao = "{{ route('app.clients.members.addresses.main', [$client->id, ":MEMBER", ":ID"])  }}".replace(':MEMBER', $member).replace(':ID', response.data.id);

                    $html += '<td class="text-right">';

                    $html += '<form method="post" action="' + $linkPadrao + '" style="display:inline-block;">';
                    $html += '<button class="btn btn-xs btn-success address-form-main" type="button">';
                    $html += '<i class="fa fa-check-circle"></i>';
                    $html += '</button>';
                    $html += '</form>';

                    $html += '<form method="post" action="' + $linkDelete + '">';
                    $html += '<button class="btn btn-xs btn-danger address-form-delete" type="button">';
                    $html += '<i class="fa fa-trash"></i>';
                    $html += '</button>';
                    $html += '</form>';

                    $html += '</td>';
                    $html += '</tr>';
                    $table.append($html);

                },
                error: (response) => { // aqui vai o que acontece quando ocorrer o erro
                    var json = $.parseJSON(response.responseText);
                    setTimeout(() => {
                        alert(json.message);
                    }, 100)
                },
                complete: () => { // aqui vai o que acontece quando tudo acabar
                    $button.removeAttr('disabled').html($buttonText);
                    $form.trigger('reset');
                }
            });
        });
        // DELETE ITEM
        $('body').on('click', '.address-form-delete', function (e) {
            e.preventDefault();
            var $form = $(this).closest('form');
            Swal.fire({
                title: 'Você tem certeza que deseja deletar o endereço?',
                text: "Você não poderá reverter isso!",
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, exclua!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: $form.attr('action'),
                        type: 'DELETE',
                        dataType: 'json',
                        contentType: false,
                        processData: false,
                        cache: false,
                        success: (response) => { // aqui vai o que der certo
                            $form.closest('tr').remove();
                        },
                        error: (response) => { // aqui vai o que acontece quando ocorrer o erro
                            var json = $.parseJSON(response.responseText);
                            setTimeout(() => {
                                alert(json.message);
                            }, 100)
                        }
                    });
                }
            })
        });
        // MAIN ITEM
        $('body').on('click', '.address-form-main', function (e) {
            e.preventDefault();
            var $form = $(this).closest('form');
            Swal.fire({
                title: 'Você tem certeza que deseja escolher esse como padrão?',
                text: "Você poderá mudar a qualquer momento!",
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: $form.attr('action'),
                        type: 'POST',
                        dataType: 'json',
                        contentType: false,
                        processData: false,
                        cache: false,
                        success: (response) => { // aqui vai o que der certo
                            var $table = $('#modal-addresses').find('table').find('tbody');
                            $table.html('');
                            var $html = ''
                            $.each(response.data, function (i, e) {
                                $html += '<tr>';
                                $html += '<td>' + e.zip + '</td>';
                                $html += '<td>' + e.state + '</td>';
                                $html += '<td>' + e.city + '</td>';
                                $html += '<td>' + e.neighborhood + '</td>';
                                $html += '<td>' + e.street + '</td>';
                                $html += '<td>' + e.number + '</td>';
                                $html += '<td>' + e.complement + '</td>';
                                if (e.main) {
                                    $html += '<td class="text-center"><i class="fa fa-check text-success"></i></td>';
                                } else {
                                    $html += '<td class="text-center"><i class="fa fa-times text-danger"></i></td>';
                                }
                                $linkDelete = "{{ route('app.clients.members.addresses.delete', [$client->id, ":MEMBER", ":ID"]) }}".replace(':MEMBER', $member).replace(':ID', e.id);
                                $linkPadrao = "{{ route('app.clients.members.addresses.main', [$client->id, ":MEMBER", ":ID"])  }}".replace(':MEMBER', $member).replace(':ID', e.id);
                                $html += '<td class="text-right">';
                                if (!e.main) {

                                    $html += '<form method="post" action="' + $linkPadrao + '" style="display:inline-block;">';
                                    $html += '<button class="btn btn-xs btn-success address-form-main" type="button">';
                                    $html += '<i class="fa fa-check-circle"></i>';
                                    $html += '</button>';
                                    $html += '</form>';

                                    $html += '<form method="post" action="' + $linkDelete + '" style="display:inline-block;">';
                                    $html += '<button class="btn btn-xs btn-danger address-form-delete" type="button">';
                                    $html += '<i class="fa fa-trash"></i>';
                                    $html += '</button>';
                                    $html += '</form>';
                                }
                                $html += '</td>';
                                $html += '</tr>';
                            });
                            $table.append($html);
                        },
                        error: (response) => { // aqui vai o que acontece quando ocorrer o erro
                            var json = $.parseJSON(response.responseText);
                            setTimeout(() => {
                                alert(json.message);
                            }, 100)
                        }
                    });
                }
            })
        });

        // E-MAILS
        // OPEN MODAL
        $('[data-modal="#modal-emails"]').on('click', function (e) {
            e.preventDefault();
            var $target = $(e.currentTarget);
            var $modal = $($target.attr('data-modal'));
            $member = $target.attr('data-member');
            var $linkGet = "{{ route('app.clients.members.emails.index',[$client->id, ':MEMBER']) }}".replace(":MEMBER", $member);
            var $linkStore = "{{ route('app.clients.members.emails.store',[$client->id, ':MEMBER']) }}".replace(":MEMBER", $member);
            $('#modal-form-emails').attr('action', $linkStore);
            $.get($linkGet, function (response) {
                var $table = $('#modal-emails').find('table').find('tbody');
                $table.html('');
                var $html = ''
                $.each(response.data, function (i, e) {
                    $html += '<tr>';
                    $html += '<td>' + e.email + '</td>';
                    if (e.main) {
                        $html += '<td class="text-center"><i class="fa fa-check text-success"></i></td>';
                    } else {
                        $html += '<td class="text-center"><i class="fa fa-times text-danger"></i></td>';
                    }
                    $linkDelete = "{{ route('app.clients.members.emails.delete', [$client->id,":MEMBER", ":ID"]) }}".replace(':MEMBER', $member).replace(':ID', e.id);
                    $linkPadrao = "{{ route('app.clients.members.emails.main', [$client->id,":MEMBER", ":ID"]) }}".replace(':MEMBER', $member).replace(':ID', e.id);

                    $html += '<td class="text-right">';

                    if (!e.main) {
                        $html += '<form method="post" action="' + $linkPadrao + '" style="display:inline">';
                        $html += '<button class="btn btn-xs btn-success emails-form-main" type="button">';
                        $html += '<i class="fa fa-check-circle"></i>';
                        $html += '</button>';
                        $html += '</form>';

                        $html += '<form method="post" action="' + $linkDelete + '" style="display:inline">';
                        $html += '<button class="btn btn-xs btn-danger emails-form-delete" type="button">';
                        $html += '<i class="fa fa-trash"></i>';
                        $html += '</button>';
                        $html += '</form>';

                    }
                    $html += '</td>';
                    $html += '</tr>';
                });
                $table.append($html);
            });
            $modal.modal('show');
        });
        // SHOW FORM
        $('[href="#emails-create"]').on('click', function () {
            var $link = $(this);
            setTimeout(() => {
                var $area = $('#emails-create');
                if ($area.hasClass('in')) {
                    $link.removeClass('btn-info').addClass('btn-danger').html('<i class="fa fa-minus"></i> Fechar');
                } else {
                    $link.removeClass('btn-danger').addClass('btn-info').html('<i class="fa fa-plus"></i> Adicionar');
                }
            }, 500)
        });
        // SUBMIT FORM
        $('#modal-form-emails').on('submit', function (e) {
            e.preventDefault();
            var $form = $(this);
            var $button = $form.find('button[type="submit"]');
            var $buttonText = $button.html();
            var $data = new FormData($form[0]);
            var $table = $form.closest('.modal-body').find('table').find('tbody');
            $.ajax({
                url: $form.attr('action'),
                type: $form.attr('method'),
                data: $data,
                dataType: 'json',
                contentType: false,
                processData: false,
                cache: false,
                beforeSend: () => { // aqui vai o que tem que ser feito antes de chamar o endpoint
                    $button.attr('disabled', 'disabled').html('<i class="fas fa-spinner fa-pulse"></i> Carregando...');
                },
                success: (response) => { // aqui vai o que der certo
                    var $html = ''
                    $html += '<tr>';
                    $html += '<td>' + response.data.email + '</td>';
                    if (response.data.main) {
                        $html += '<td class="text-center"><i class="fa fa-check text-success"></i></td>';
                    } else {
                        $html += '<td class="text-center"><i class="fa fa-times text-danger"></i></td>';
                    }
                    $linkDelete = "{{ route('app.clients.members.emails.delete', [$client->id, ':MEMBER', ":ID"]) }}".replace(':MEMBER', $member).replace(':ID', response.data.id);
                    $linkPadrao = "{{ route('app.clients.members.emails.main', [$client->id,":MEMBER", ":ID"]) }}".replace(':MEMBER', $member).replace(':ID', response.data.id);

                    $html += '<td class="text-right">';

                    $html += '<form method="post" action="' + $linkPadrao + '" style="display:inline">';
                    $html += '<button class="btn btn-xs btn-success emails-form-main" type="button">';
                    $html += '<i class="fa fa-check-circle"></i>';
                    $html += '</button>';
                    $html += '</form>';

                    $html += '<form method="post" action="' + $linkDelete + '">';
                    $html += '<button class="btn btn-xs btn-danger emails-form-delete" type="button">';
                    $html += '<i class="fa fa-trash"></i>';
                    $html += '</button>';
                    $html += '</form>';


                    $html += '</td>';
                    $html += '</tr>';
                    $table.append($html);

                },
                error: (response) => { // aqui vai o que acontece quando ocorrer o erro
                    var json = $.parseJSON(response.responseText);
                    setTimeout(() => {
                        alert(json.message);
                    }, 100)
                },
                complete: () => { // aqui vai o que acontece quando tudo acabar
                    $button.removeAttr('disabled').html($buttonText);
                }
            });
        });
        // DELETE ITEM
        $('body').on('click', '.emails-form-delete', function (e) {
            e.preventDefault();
            var $form = $(this).closest('form');
            Swal.fire({
                title: 'Você tem certeza que deseja deletar o endereço?',
                text: "Você não poderá reverter isso!",
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, exclua!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: $form.attr('action'),
                        type: 'DELETE',
                        dataType: 'json',
                        contentType: false,
                        processData: false,
                        cache: false,
                        success: (response) => { // aqui vai o que der certo
                            $form.closest('tr').remove();
                        },
                        error: (response) => { // aqui vai o que acontece quando ocorrer o erro
                            var json = $.parseJSON(response.responseText);
                            setTimeout(() => {
                                alert(json.message);
                            }, 100)
                        }
                    });
                }
            })
        });
        // MAIN ITEM
        $('body').on('click', '.emails-form-main', function (e) {
            e.preventDefault();
            var $form = $(this).closest('form');
            Swal.fire({
                title: 'Você tem certeza que deseja escolher esse como padrão?',
                text: "Você poderá mudar a qualquer momento!",
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: $form.attr('action'),
                        type: 'POST',
                        dataType: 'json',
                        contentType: false,
                        processData: false,
                        cache: false,
                        success: (response) => { // aqui vai o que der certo
                            var $table = $('#modal-emails').find('table').find('tbody');
                            $table.html('');
                            var $html = ''
                            $.each(response.data, function (i, e) {
                                $html += '<tr>';
                                $html += '<td>' + e.email + '</td>';
                                if (e.main) {
                                    $html += '<td class="text-center"><i class="fa fa-check text-success"></i></td>';
                                } else {
                                    $html += '<td class="text-center"><i class="fa fa-times text-danger"></i></td>';
                                }
                                $linkDelete = "{{ route('app.clients.members.emails.delete', [$client->id,":MEMBER", ":ID"]) }}".replace(':MEMBER', $member).replace(':ID', e.id);
                                $linkPadrao = "{{ route('app.clients.members.emails.main', [$client->id,":MEMBER", ":ID"]) }}".replace(':MEMBER', $member).replace(':ID', e.id);

                                $html += '<td class="text-right">';

                                if (!e.main) {
                                    $html += '<form method="post" action="' + $linkPadrao + '" style="display:inline">';
                                    $html += '<button class="btn btn-xs btn-success emails-form-main" type="button">';
                                    $html += '<i class="fa fa-check-circle"></i>';
                                    $html += '</button>';
                                    $html += '</form>';

                                    $html += '<form method="post" action="' + $linkDelete + '" style="display:inline">';
                                    $html += '<button class="btn btn-xs btn-danger emails-form-delete" type="button">';
                                    $html += '<i class="fa fa-trash"></i>';
                                    $html += '</button>';
                                    $html += '</form>';

                                }

                                if (e.main) {
                                    var pTd = $('[data-tr-member=' + $member + ']');
                                    $.each(pTd.find('td'), function (x, j) {
                                        if (x == 3) {
                                            $(j).html(e.email);
                                        }
                                    });
                                }
                                $html += '</td>';
                                $html += '</tr>';
                            });
                            $table.append($html);
                        },
                        error: (response) => { // aqui vai o que acontece quando ocorrer o erro
                            var json = $.parseJSON(response.responseText);
                            setTimeout(() => {
                                alert(json.message);
                            }, 100)
                        }
                    });
                }
            })
        });


        // PHONES
        // OPEN MODAL
        $('[data-modal="#modal-phones"]').on('click', function (e) {
            e.preventDefault();
            var $target = $(e.currentTarget);
            var $modal = $($target.attr('data-modal'));
            $member = $target.attr('data-member');
            var $linkGet = "{{ route('app.clients.members.phones.index',[$client->id, ':MEMBER']) }}".replace(":MEMBER", $member);
            var $linkStore = "{{ route('app.clients.members.phones.store',[$client->id, ':MEMBER']) }}".replace(":MEMBER", $member);
            $('#modal-form-phones').attr('action', $linkStore);
            $.get($linkGet, function (response) {
                var $table = $modal.find('table').find('tbody');
                $table.html('');
                var $html = ''
                $.each(response.data, function (i, e) {
                    $html += '<tr>';
                    $html += '<td>' + e.phone + '</td>';
                    if (e.main) {
                        $html += '<td class="text-center"><i class="fa fa-check text-success"></i></td>';
                    } else {
                        $html += '<td class="text-center"><i class="fa fa-times text-danger"></i></td>';
                    }
                    $linkDelete = "{{ route('app.clients.members.phones.delete', [$client->id,":MEMBER", ":ID"]) }}".replace(':MEMBER', $member).replace(':ID', e.id);
                    $linkPadrao = "{{ route('app.clients.members.phones.main', [$client->id,":MEMBER", ":ID"]) }}".replace(':MEMBER', $member).replace(':ID', e.id);

                    $html += '<td class="text-right">';

                    if (!e.main) {
                        $html += '<form method="post" action="' + $linkPadrao + '" style="display:inline">';
                        $html += '<button class="btn btn-xs btn-success phones-form-main" type="button">';
                        $html += '<i class="fa fa-check-circle"></i>';
                        $html += '</button>';
                        $html += '</form>';

                        $html += '<form method="post" action="' + $linkDelete + '" style="display:inline">';
                        $html += '<button class="btn btn-xs btn-danger phones-form-delete" type="button">';
                        $html += '<i class="fa fa-trash"></i>';
                        $html += '</button>';
                        $html += '</form>';

                    }
                    $html += '</td>';
                    $html += '</tr>';
                });
                $table.append($html);
            });
            $modal.modal('show');
        });
        // SHOW FORM
        $('[href="#phones-create"]').on('click', function () {
            var $link = $(this);
            setTimeout(() => {
                var $area = $('#phones-create');
                if ($area.hasClass('in')) {
                    $link.removeClass('btn-info').addClass('btn-danger').html('<i class="fa fa-minus"></i> Fechar');
                } else {
                    $link.removeClass('btn-danger').addClass('btn-info').html('<i class="fa fa-plus"></i> Adicionar');
                }
            }, 500)
        });
        // SUBMIT FORM
        $('#modal-form-phones').on('submit', function (e) {
            e.preventDefault();
            var $form = $(this);
            var $button = $form.find('button[type="submit"]');
            var $buttonText = $button.html();
            var $data = new FormData($form[0]);
            var $table = $form.closest('.modal-body').find('table').find('tbody');
            $.ajax({
                url: $form.attr('action'),
                type: $form.attr('method'),
                data: $data,
                dataType: 'json',
                contentType: false,
                processData: false,
                cache: false,
                beforeSend: () => { // aqui vai o que tem que ser feito antes de chamar o endpoint
                    $button.attr('disabled', 'disabled').html('<i class="fas fa-spinner fa-pulse"></i> Carregando...');
                },
                success: (response) => { // aqui vai o que der certo
                    var $html = ''
                    $html += '<tr>';
                    $html += '<td>' + response.data.phone + '</td>';
                    if (response.data.main) {
                        $html += '<td class="text-center"><i class="fa fa-check text-success"></i></td>';
                    } else {
                        $html += '<td class="text-center"><i class="fa fa-times text-danger"></i></td>';
                    }
                    $linkDelete = "{{ route('app.clients.members.phones.delete', [$client->id,":MEMBER", ":ID"]) }}".replace(':MEMBER', $member).replace(':ID', response.data.id);
                    $linkPadrao = "{{ route('app.clients.members.phones.main', [$client->id,":MEMBER", ":ID"]) }}".replace(':MEMBER', $member).replace(':ID', response.data.id);

                    $html += '<td class="text-right">';

                    if (!e.main) {
                        $html += '<form method="post" action="' + $linkPadrao + '" style="display:inline">';
                        $html += '<button class="btn btn-xs btn-success phones-form-main" type="button">';
                        $html += '<i class="fa fa-check-circle"></i>';
                        $html += '</button>';
                        $html += '</form>';

                        $html += '<form method="post" action="' + $linkDelete + '" style="display:inline">';
                        $html += '<button class="btn btn-xs btn-danger phones-form-delete" type="button">';
                        $html += '<i class="fa fa-trash"></i>';
                        $html += '</button>';
                        $html += '</form>';

                    }
                    $html += '</td>';
                    $html += '</tr>';
                    $table.append($html);

                },
                error: (response) => { // aqui vai o que acontece quando ocorrer o erro
                    var json = $.parseJSON(response.responseText);
                    setTimeout(() => {
                        alert(json.message);
                    }, 100)
                },
                complete: () => { // aqui vai o que acontece quando tudo acabar
                    $button.removeAttr('disabled').html($buttonText);
                }
            });
        });
        // DELETE ITEM
        $('body').on('click', '.phones-form-delete', function (e) {
            e.preventDefault();
            var $form = $(this).closest('form');
            Swal.fire({
                title: 'Você tem certeza que deseja deletar o endereço?',
                text: "Você não poderá reverter isso!",
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, exclua!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: $form.attr('action'),
                        type: 'DELETE',
                        dataType: 'json',
                        contentType: false,
                        processData: false,
                        cache: false,
                        success: (response) => { // aqui vai o que der certo
                            $form.closest('tr').remove();
                        },
                        error: (response) => { // aqui vai o que acontece quando ocorrer o erro
                            var json = $.parseJSON(response.responseText);
                            setTimeout(() => {
                                alert(json.message);
                            }, 100)
                        }
                    });
                }
            })
        });
        // MAIN ITEM
        $('body').on('click', '.phones-form-main', function (e) {
            e.preventDefault();
            var $form = $(this).closest('form');
            Swal.fire({
                title: 'Você tem certeza que deseja escolher esse como padrão?',
                text: "Você poderá mudar a qualquer momento!",
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: $form.attr('action'),
                        type: 'POST',
                        dataType: 'json',
                        contentType: false,
                        processData: false,
                        cache: false,
                        success: (response) => { // aqui vai o que der certo
                            var $table = $('#modal-phones').find('table').find('tbody');
                            $table.html('');
                            var $html = ''
                            $.each(response.data, function (i, e) {
                                $html += '<tr>';
                                $html += '<td>' + e.phone + '</td>';
                                if (e.main) {
                                    $html += '<td class="text-center"><i class="fa fa-check text-success"></i></td>';
                                } else {
                                    $html += '<td class="text-center"><i class="fa fa-times text-danger"></i></td>';
                                }
                                $linkDelete = "{{ route('app.clients.members.phones.delete', [$client->id,":MEMBER", ":ID"]) }}".replace(':MEMBER', $member).replace(':ID', e.id);
                                $linkPadrao = "{{ route('app.clients.members.phones.main', [$client->id,":MEMBER", ":ID"]) }}".replace(':MEMBER', $member).replace(':ID', e.id);

                                $html += '<td class="text-right">';

                                if (!e.main) {
                                    $html += '<form method="post" action="' + $linkPadrao + '" style="display:inline">';
                                    $html += '<button class="btn btn-xs btn-success phones-form-main" type="button">';
                                    $html += '<i class="fa fa-check-circle"></i>';
                                    $html += '</button>';
                                    $html += '</form>';

                                    $html += '<form method="post" action="' + $linkDelete + '" style="display:inline">';
                                    $html += '<button class="btn btn-xs btn-danger phones-form-delete" type="button">';
                                    $html += '<i class="fa fa-trash"></i>';
                                    $html += '</button>';
                                    $html += '</form>';
                                }

                                if (e.main) {
                                    var pTd = $('[data-tr-member=' + $member + ']');
                                    $.each(pTd.find('td'), function (x, j) {
                                        if (x == 4) {
                                            $(j).html(e.phone);
                                        }
                                    });
                                }

                                $html += '</td>';
                                $html += '</tr>';
                            });
                            $table.append($html);
                        },
                        error: (response) => { // aqui vai o que acontece quando ocorrer o erro
                            var json = $.parseJSON(response.responseText);
                            setTimeout(() => {
                                alert(json.message);
                            }, 100)
                        }
                    });
                }
            })
        });


        // EDIT
        // OPEN MODAL
        $('[data-modal="#modal-edit"]').on('click', function (e) {
            e.preventDefault();
            var $target = $(e.currentTarget);
            var $modal = $($target.attr('data-modal'));
            var $member = $target.attr('data-member');
            var $linkGet = "{{ route('app.clients.members.show',[$client->id, ':MEMBER']) }}".replace(":MEMBER", $member);
            var $linkStore = "{{ route('app.clients.members.update',[$client->id, ':MEMBER']) }}".replace(":MEMBER", $member);
            var $form = $('#modal-form-edit');
            $form.attr('action', $linkStore);
            $.get($linkGet, function (response) {
                $form.find('input[name="natural"]').val(response.data.natural);
                $form.find('input[name="name"]').val(response.data.name);
                $form.find('input[name="document"]').val(response.data.document);
                $form.find('select[name="role"]').val(response.data.role);
                $form.find('select[name="marital_status"]').val(response.data.marital_status);
                $form.find('[name="profession"]').val(response.data.profession);
                $form.find('select[name="gender"]').val(response.data.gender);
                $form.find('input[name="dob"]').val(response.data.dob);
                $form.find('input[name="rg"]').val(response.data.rg);
                $form.find('input[name="rg_expedidor"]').val(response.data.rg_expedidor);
            });
            $modal.modal('show');
        });
        // SUBMIT FORM
        $('#modal-form-edit').on('submit', function (e) {
            e.preventDefault();
            var $form = $(this);
            var $button = $form.find('button[type="submit"]');
            var $buttonText = $button.html();
            var $data = new FormData($form[0]);
            var $table = null;
            $.ajax({
                url: $form.attr('action'),
                type: $form.attr('method'),
                data: $data,
                dataType: 'json',
                contentType: false,
                processData: false,
                cache: false,
                beforeSend: () => { // aqui vai o que tem que ser feito antes de chamar o endpoint
                    $button.attr('disabled', 'disabled').html('<i class="fas fa-spinner fa-pulse"></i> Carregando...');
                },
                success: (response) => { // aqui vai o que der certo
                    $table = $('[data-tr-member="' + response.data.id + '"]');
                    $.each($table.find('td'), function (i, e) {
                        switch (i) {
                            case 1:
                                $(e).html(response.data.name);
                                break;
                            case 2:
                                $(e).html(response.data.document)
                                break;
                        }
                    })
                },
                error: (response) => { // aqui vai o que acontece quando ocorrer o erro
                    var json = $.parseJSON(response.responseText);
                    setTimeout(() => {
                        alert(json.message);
                    }, 100)
                },
                complete: () => { // aqui vai o que acontece quando tudo acabar
                    $button.removeAttr('disabled').html($buttonText);
                    $form.trigger('clear');
                    $($form.closest('.modal')).modal('hide');
                }
            });
        });

        // SHOW MEMBER
        $('[data-modal="#modal-show"]').on('click', function (e) {
            e.preventDefault();
            var $target = $(e.currentTarget);
            var $modal = $($target.attr('data-modal'));
            var $member = $target.attr('data-member');

            var $linkGet = "{{ route('app.clients.members.information', [$client->id,':MEMBER']) }}";
            var $area = $('#modal-show-area');

            $linkGet = $linkGet.replace(':MEMBER', $member);
            $.get($linkGet, function (response) {
                $area.find('.persona-name').html(response.data.persona.name);
                $area.find('.persona-document').html(response.data.persona.document);
                $area.find('.persona-role').html(response.data.persona.role);
                $area.find('.persona-natural').html(response.data.persona.natural);
                $area.find('.persona-gender').html(response.data.persona.gender);
                $area.find('.persona-marital_status').html(response.data.persona.marital_status);
                $area.find('.persona-profession').html(response.data.persona.profession);
                $area.find('.persona-dob').html(response.data.persona.dob);
                $area.find('.persona-rg').html(response.data.persona.rg);
                $area.find('.persona-rg-expedidor').html(response.data.persona.rg_expedidor);

                // Emails
                var $tbEmails = $area.find('#tb-emails');
                $tbEmails.html('');
                if (response.data.emails.length) {
                    $.each(response.data.emails, function (i, e) {
                        var $type = (e.main) ? "check text-success" : "times text-danger";
                        var $icon = '<i class="fa fa-' + $type + '"></i>'
                        var $html = '<tr>';
                        $html += '<td>' + e.email + '</td>';
                        $html += '<td class="text-right">' + $icon + '</td>';
                        $html += '</tr>';
                        $tbEmails.append($html);
                    });
                } else {
                    var $html = '<tr>';
                    $html += '<td colspan="2">Não há emails registrados</td>';
                    $html += '</tr>';
                    $tbEmails.append($html);
                }

                // Telefones
                var $tbPhones = $area.find('#tb-phones');
                $tbPhones.html('');
                if (response.data.phones.length) {
                    $.each(response.data.phones, function (i, e) {
                        var $type = (e.main) ? "check text-success" : "times text-danger";
                        var $icon = '<i class="fa fa-' + $type + '"></i>'
                        var $html = '<tr>';
                        $html += '<td>' + e.phone + '</td>';
                        $html += '<td class="text-right">' + $icon + '</td>';
                        $html += '</tr>';
                        $tbPhones.append($html);
                    });
                } else {
                    var $html = '<tr>';
                    $html += '<td colspan="2">Não há telefones registrados</td>';
                    $html += '</tr>';
                    $tbPhones.append($html);
                }

                // Endereços
                var $tbAddresses = $area.find('#tb-addresses');
                $tbAddresses.html('');
                if (response.data.addresses.length) {
                    $.each(response.data.addresses, function (i, e) {
                        var $type = (e.main) ? "check text-success" : "times text-danger";
                        var $icon = '<i class="fa fa-' + $type + '"></i>'
                        var $html = '<tr>';
                        $html += '<td>' + e.street + '</td>';
                        $html += '<td>' + e.number ?? '' + '</td>';
                        $html += '<td>' + e.complement ?? '' + '</td>';
                        $html += '<td>' + e.neighborhood + '</td>';
                        $html += '<td>' + e.city + '</td>';
                        $html += '<td>' + e.state + '</td>';
                        $html += '<td>' + e.zip + '</td>';
                        $html += '<td text-right>' + $icon + '</td>';

                        $html += '</tr>';
                        $tbAddresses.append($html);
                    });
                } else {
                    var $html = '<tr>';
                    $html += '<td colspan="8">Não há endereços registrados</td>';
                    $html += '</tr>';
                    $tbAddresses.append($html);
                }

            });
            $modal.modal('show');
        });

        $('body').on('submit', '.member-delete', function (event) {
            event.preventDefault();
            var $form = $(this);
            Swal.fire({
                title: 'Você tem certeza que deseja deletar um membro?',
                text: "Você não poderá reverter isso!",
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, exclua!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: $form.attr('action'),
                        type: 'DELETE',
                        dataType: 'json',
                        contentType: false,
                        processData: false,
                        cache: false,
                        success: (response) => { // aqui vai o que der certo
                            $form.closest('tr').remove();
                        },
                        error: (response) => { // aqui vai o que acontece quando ocorrer o erro
                            var json = $.parseJSON(response.responseText);
                            setTimeout(() => {
                                alert(json.message);
                            }, 100)
                        }
                    });
                }
            })
        });
    </script>
@endsection
