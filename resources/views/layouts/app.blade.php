<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    {{-- Font Awesome --}}
    <link href="{{ asset('css/fontawesome.min.css') }}" rel="stylesheet"/>
{{--    <link href="{{ asset('css/summernote-bs3.css') }}" rel="stylesheet">--}}
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-select.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/summernote.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sweetalert.min.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.21/b-1.6.2/b-html5-1.6.2/datatables.min.css"/>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker-standalone.min.css">
    @yield('style')
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet"/>

</head>
<body class="{{ (in_array(Request::route()->getName(), ['password.email', 'password.reset', 'password.request', 'login', 'register'])) ? 'body-bg-color' : 'sidebar-mini'}}">

<div class="wrapper">
    @if (\Auth::check())
        @include('layouts.header')
        @include('layouts.aside')
    @endif

    {{-- verificação se a página é em branco ou faz parte do dashboard --}}
    @if (in_array(Request::route()->getName(), ['password.email', 'password.reset', 'password.request', 'login', 'register']))
        @yield('content')
    @else
        <div class="content-wrapper">
            @yield('header')
            <section class="content container-fluid">
                @yield('content')
            </section>
        </div>
    @endif
    @yield('modal')
</div>

<!-- Scripts -->
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/script.js') }}"></script>
<script src="{{ asset('js/jquery.mask.min.js') }}"></script>
<script src="{{ asset('js/sweetalert.min.js') }}"></script>
<script src="{{ asset('js/application.js') }}"></script>
<script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('js/summernote.min.js') }}"></script>

<script type="text/javascript">
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
    $.fn.modal.prototype.constructor.Constructor.DEFAULTS.backdrop = 'static';
    $.fn.modal.prototype.constructor.Constructor.DEFAULTS.keyboard =  false;
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/locale/pt-br.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.21/b-1.6.2/b-html5-1.6.2/datatables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
<script src="{{ asset('js/datatable_pt-BR.js') }}"></script>
<script src="//cdn.datatables.net/plug-ins/1.10.22/sorting/natural.js"></script>
@yield('script')
</body>
</html>
