<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ENSAT Community') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link rel="icon" href="{{ asset('img/favicon.png') }}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style-nav.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style-footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style-welcome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style-login.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style-class.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style-profil.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style-global.css') }}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css" integrity="sha384-vp86vTRFVJgpjF9jiIGPEEqYqlDwgyBgEF109VFjmqGmIY/Y4HV4d3Gp2irVfcrp" crossorigin="anonymous">
    <script src="{{ asset('js/jquery-3.4.1.slim.min.js') }}" type="text/javascript"></script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light fixed-top py-0 px-5">
            <a href="{{ route('welcome') }}" class="navbar-brand d-flex">
                @guest
                    <img src="{{ asset('img/logo.png') }}" class="img-fluid" width="100px" />
                @else
                    <img src="{{ asset('img/logo-icon.png') }}" class="img-fluid" width="35px" />
                @endguest
            </a>
            
            @auth
                <div class="text-white ml-3 d-flex align-items-center h-100">
                    <h4 class="m-0">
                        @isset($class)
                            @if (Auth::user()->isProfessor())
                                <a href="{{ route('classes') }}" class="link_white">{{ $class->label }}</a>
                            @else
                                <a href="{{ route('welcome') }}" class="link_white">{{ $class->label }}</a>
                            @endif
                        @else
                            @if (Auth::user()->isProfessor())
                                <a href="{{ route('welcome') }}" class="link_white">My classes</a>
                            @else
                                <a href="{{ route('welcome') }}" class="link_white">My class</a>
                            @endif
                        @endisset
                    </h4>
                </div>
            @endauth

            <button class="navbar-toggler btn-free text-white" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse flex-fill" id="navbarSupportedContent">
                @isset($class)
                <ul class="nav-top-list flex-fill d-flex justify-content-center mt-2 pt-1">
                    <li class="nav-item 
                        @empty ($tab_index)
                            active
                        @endempty">
                        <a class="nav-link px-md-4" href="{{ route('classes.discussions', $class->id) }}">Community</a>
                        <div class="indicator"></div>
                    </li>
                    <li class="nav-item 
                        @isset ($tab_index)
                        @if ($tab_index == 1)
                            active
                        @endif
                        @endisset">
                        <a class="nav-link px-md-4" href="{{ route('classes.members', $class->id) }}">Members</a>
                        <div class="indicator"></div>
                    </li>
                    <li class="nav-item 
                        @isset ($tab_index)
                        @if ($tab_index == 2)
                            active
                        @endif
                        @endisset">
                        <a class="nav-link px-md-4" href="{{ route('classes.courses', $class->id) }}">Courses</a>
                        <div class="indicator"></div>
                    </li>
                    <li class="nav-item 
                        @isset ($tab_index)
                        @if ($tab_index == 3)
                            active
                        @endif
                        @endisset">
                        <a class="nav-link px-md-4" href="{{ route('classes.groups', $class->id) }}">Groups</a>
                        <div class="indicator"></div>
                    </li>
                    @can('update', $class)
                        <li class="nav-item 
                            @isset ($tab_index)
                            @if ($tab_index == 4)
                                active
                            @endif
                            @endisset">
                            <a class="nav-link px-md-4" href="{{ route('classes.edit', $class->id) }}">Details</a>
                            <div class="indicator"></div>
                        </li>
                    @endcan
                </ul>
                @else
                    @isset($user_tab_index)
                        <ul class="nav-top-list flex-fill d-flex justify-content-center mt-2 pt-1">
                            <li class="nav-item 
                                @if ($user_tab_index == 0)
                                    active
                                @endempty">
                                <a class="nav-link px-md-4" href="{{ route('admin.students') }}">Students</a>
                                <div class="indicator"></div>
                            </li>
                            <li class="nav-item 
                                @if ($user_tab_index == 1)
                                    active
                                @endif">
                                <a class="nav-link px-md-4" href="{{ route('admin.professors') }}">Professors</a>
                                <div class="indicator"></div>
                            </li>
                        </ul>
                    @endisset
                @endisset
                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @guest
                        <li class="nav-item">
                            <a class="navbar-btn mr-2" href="{{ route('login') }}">SIGN IN</a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="btn-p navbar-btn" href="{{ route('register') }}">SIGN UP</a>
                            </li>
                        @endif
                    @else
                        @if (Auth::user()->isAdmin())
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white text-bold" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Administration</a>
                            <div class="dropdown-menu dropdown-menu-right rkm-dropdown-menu mt-2">
                                <a class="dropdown-item rkm-dropdown-item" href="{{ route('admin.students') }}">Members</a>
                                <a class="dropdown-item rkm-dropdown-item" href="{{ route('classes') }}">Classes</a>
                            </div>
                        </li>
                        @endif
                    @endguest
                </ul>
            </div>
            
            @auth
                <div class="dropdown">
                    <img src="{{ Auth::user()->image }}" id="dml_profil" class="img-fluid rounded-circle dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" />
                    <div class="dropdown-menu dropdown-menu-right rkm-dropdown-menu mt-2" aria-labelledby="dml_profil">
                        {{--<a class="dropdown-item rkm-dropdown-item" href="#">Bookmarks</a>--}}
                        <a class="dropdown-item rkm-dropdown-item" href="{{ route('profile.edit', Auth::user()->cin) }}">Account Settings</a>
                        <a class="dropdown-item rkm-dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                            Sign out
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            @endauth
        </nav>

        <main class="bg-white">
            @yield('content')
        </main>

        <footer class="pt-5">
            <div class="container mb-5 mt-2">
                <div class="row">
                    <div class="col-lg-4">
                        <img src="{{ asset("img/logo.png")}}" class="img-fluid mr-3 mb-4" width="170px" />
                        <p>
                            <strong>ENSA Tanger Community</strong> is a laravel application made with heart dedicated for the community of national school of applied science of Tangier, to serve the basic services of e-learning in light of the global state of Covid-19.
                        </p>
                    </div>
                    <div class="col-lg-6 offset-lg-2 pt-3">
                        <h5 class="text-bold mb-3">EXTRAS</h5>
                        <ul class="footer-list">
                            <li>
                                <a href="https://ensat.ac.ma/">ENSA Tanger</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                © ENSA Tanger Community 2020-2021. All rights reserved.
            </div>
        </footer>
    </div>
    <script type="text/javascript" src="{{ asset('js/global-scripts.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/sidenav-scripte.js') }}"></script>
    @stack('scripts')
</body>
</html>
