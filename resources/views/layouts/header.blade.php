<header class="main-header dark-bg">

    {{-- Logo --}}
    <a href="{!! route('app.index') !!}" class="logo dark-bg">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">
            <img src="{!! asset('img/logo.png') !!}">
        </span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">
            <img src="{!! asset('img/logo-lg.jpeg') !!}" class="img-responsive">
        </span>
    </a>

    {{-- Header Navbar --}}
    <nav class="navbar navbar-static-top" role="navigation">

        {{-- Sidebar toggle button --}}
        <a href="javascript:void(0)" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <i class="fa fa-bars fa-lg"></i>
            <span class="sr-only">Toggle navigation</span>
        </a>

        {{-- Navbar Right Menu --}}
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                {{-- User Account Menu --}}
                <li class="dropdown user user-menu">
                    <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                        <img
                            src="{!! getAvatar(auth()->user()->id) !!}"
                            class="user-image appUserAvatar" alt="User Image" >
                        <span class="hidden-xs appUserName">{{ auth()->user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-header">
                            <div class="pull-left user-img">
                                <img
                                    src="{!! getAvatar(auth()->user()->id)  !!}"
                                    class="img-responsive appUserAvatar" alt="User Image" >
                            </div>
                            <p class="text-left">
                                <span class="appUserName">{{ auth()->user()->name }}</span>
                                <small class="appUserEmail">{{ auth()->user()->email }}</small>
                            </p>
                        </li>

                        <li role="separator" class="divider"></li>
                        <li>
                            <a href="{!! route('app.profile.index') !!}" onclick="">
                                <i class="fa fa-user"></i> Perfil
                            </a>
                        </li>


                        <li>
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fa fa-power-off"></i> Sair
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </nav>
</header>
