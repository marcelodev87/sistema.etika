@extends('layouts.app')
@section('header')
    @breadcrumb(['title' => 'Perfil'])
    <li>
        <a href="{!! route('app.index') !!}">
            <i class="fa fa-th"></i> Painel
        </a>
    </li>
    <li class="active">
        <i class="fa fa-user"></i> Perfil
    </li>
    @endbreadcrumb
@endsection

@section('content')

    <div class="row profile">

        <div class="col-md-12">

            <div class="chart-box">

                <div class="row">
                    <div class="col-md-3 col-lg-2">
                        {{-- Nav tabs --}}
                        <ul class="nav" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#changeAvatar" aria-controls="changeAvatar" role="tab" data-toggle="tab">
                                    <i class="fa fa-photo"></i> Foto
                                </a>
                            </li>
                            <li role="presentation" class="#changeInformation">
                                <a href="#changeInformation" aria-controls="changeInformation" role="tab" data-toggle="tab">
                                    <i class="fa fa-info"></i> Informações
                                </a>
                            </li>
                            <li role="presentation">
                                <a href="#changeEmail" aria-controls="changeEmail" role="tab" data-toggle="tab">
                                    <i class="fa fa-envelope"></i> E-mail
                                </a>
                            </li>
                            <li role="presentation">
                                <a href="#changePassword" aria-controls="changePassword" role="tab" data-toggle="tab">
                                    <i class="fa fa-key"></i> Senha
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-9">
                        {{--  Tab panes --}}
                        <div class="tab-content m-top-2">
                            @include('profile.tabs.changeAvatar')
                            @include('profile.tabs.changeInformation')
                            @include('profile.tabs.changeEmail')
                            @include('profile.tabs.changePassword')
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $('#linkUploadAvatar').on('click', function () {
            $('#inputAvatarFile').click();
        });

        $('#inputAvatarFile').on('change', function () {
            var form = $('#profile-update-avatar');
            var data = new FormData(form[0]);
            var imgDefault = "{{ asset('img/avatar-default.png') }}";
            var output = $('#previewAvatar');
            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: data,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    output.attr('src', "{{ asset('img/carregando.gif') }}")
                },
                success: function (response) {
                    form.trigger('reset');
                    Swal('Tudo certo', response.message, 'success');
                    $('.appUserAvatar').each(function (i, e) {
                        $(this).attr('src', response.avatar);
                    })
                },
                error: function (response) {
                    var err = JSON.parse(response.responseText);
                    Swal('Oops', err.error, 'error');
                    output.attr('src', imgDefault);
                }
            })
        });

        $('#profile-update-information').on('submit', function (e) {
            e.preventDefault();
            var form = $(this);
            var data = new FormData(form[0]);
            var button = form.find('button[type="submit"]');
            var buttonText = button.html();
            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: data,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    button.html('<i class="fas fa-spinner fa-pulse"></i> Salvando...');
                },
                success: function (response) {
                    button.html(buttonText);
                    $('.appUserName').each(function (i, e) {
                        $(this).html(response.name);
                    })
                    Swal('Tudo certo', response.message, 'success');
                    form.find('[name="password"]').val('');
                },
                error: function (response) {
                    button.html(buttonText);
                    var err = JSON.parse(response.responseText);
                    Swal('Oops', err.error, 'error');
                }
            })
        });

        $('#profile-update-email').on('submit', function (e) {
            e.preventDefault();
            var form = $(this);
            var data = new FormData(form[0]);
            var button = form.find('button[type="submit"]');
            var buttonText = button.html();
            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: data,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    button.html('<i class="fas fa-spinner fa-pulse"></i> Salvando...');
                },
                success: function (response) {
                    button.html(buttonText);
                    $('.appUserEmail').each(function (i, e) {
                        $(this).html(response.email);
                    })
                    Swal('Tudo certo', response.message, 'success');
                    form.find('[name="password"]').val('');
                },
                error: function (response) {
                    button.html(buttonText);
                    var err = JSON.parse(response.responseText);
                    Swal('Oops', err.error, 'error');
                }
            })
        });

        $('#profile-update-password').on('submit', function (e) {
            e.preventDefault();
            var form = $(this);
            var data = new FormData(form[0]);
            var button = form.find('button[type="submit"]');
            var buttonText = button.html();
            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: data,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    button.html('<i class="fas fa-spinner fa-pulse"></i> Salvando...');
                },
                success: function (response) {
                    button.html(buttonText);
                    Swal('Tudo certo', response.message, 'success');
                    form.trigger('reset');
                },
                error: function (response) {
                    button.html(buttonText);
                    var err = JSON.parse(response.responseText);
                    Swal('Oops', err.error, 'error');
                }
            })
        });
    </script>

@endsection
