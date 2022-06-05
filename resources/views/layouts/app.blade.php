<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Cache-control" content="public">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PGC') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <!-- <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> -->

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/img/fav/pgc/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/fav/pgc/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/fav/pgc/favicon-16x16.png">
    <link rel="manifest" href="/img/fav/pgc/site.webmanifest">
    <link rel="mask-icon" href="/img/fav/pgc/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">


    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        @auth
        <div class="grid-nav">
          <div class="uno-n">
            <label>&nbsp;</label>
          </div>
          <nav class="navbar navbar-expand-md nav-new-style">
              <div class="container">
                  <a
                      class="navbar-brand font-weight-bold"
                      href="{{ url('/home') }}"
                  >
                      <img class="logo-app" src="/img/PGC- Logo nuevo.png" style="width : 4.5rem;">
                      <!-- <span v-if="!managementRoute">
                          {{ config('app.name', 'PGC 2.0') }}
                      </span> -->
                  </a>
                  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                      <i class="fas fa-bars" style="color : #FFFFFF;"></i>
                  </button>


                  <div class="collapse navbar-collapse" id="navbarSupportedContent">
                      <!-- Left Side Of Navbar -->
                      <ul class="navbar-nav mr-auto">

                      </ul>

                      <!-- Right Side Of Navbar -->
                      <ul class="navbar-nav ml-auto">
                          <!-- Authentication Links -->
                          @guest
                              <li class="nav-item active">
                                  <a class="nav-link style-new-a" href="{{ route('login') }}">{{ __('Iniciar sesión') }}</a>
                              </li>
                              @if (Route::has('register'))
                                  <li class="nav-item active">
                                      <a class="nav-link style-new-a" href="{{ route('register') }}">{{ __('Registrase') }}</a>
                                  </li>
                              @endif
                          @else
                              <li class="nav-item active">
                                  <router-link to="/projects" class="nav-link style-new-a pb-1">Proyectos</router-link>
                                  <!-- <a class="nav-link style-new-a pb-1" href="/projects">Proyectos</a> -->

                              </li>
                              <li class="nav-item active">
                                  <router-link to="/staff" class="nav-link style-new-a pb-1">Equipo de trabajo</router-link>
                              </li>
                              @if (auth()->user()->role_id === 1 || auth()->user()->role_id === 2)
                              <li class="nav-item active">
                                  <router-link to="/vacancies" class="nav-link style-new-a pb-1">Reclutamiento</router-link>
                              </li>
                              @endif
                              <li class="nav-item active">
                                  <router-link to="/resources" class="nav-link style-new-a pb-1">Recursos</router-link>
                              </li>
                              <li class="nav-item active mr-4">
                                  <router-link to="/manage/home" class="nav-link style-new-a pb-1">Administración</router-link>
                              </li>
                              <li class="nav-item dropdown">
                                  <a id="navbarDropdown" class="nav-link style-new-a dropdown-toggle active" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                      <img class="avatar" src="{{ Auth::user()->avatar }}" alt="avatar">
                                      {{ Auth::user()->name }}
                                      <span class="caret"></span>
                                  </a>

                                  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                      <a class="dropdown-item" href="{{ url('/staff/'.Auth::user()->id) }}">Perfil</a>
                                      <div class="dropdown-divider"></div>
                                      <a class="dropdown-item" href="{{ route('logout') }}"
                                         onclick="event.preventDefault();
                                                       document.getElementById('logout-form').submit();">
                                          {{ __('Cerrar sesión') }}
                                      </a>

                                      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                          @csrf
                                      </form>
                                  </div>
                              </li>
                          @endguest
                      </ul>
                  </div>
              </div>
          </nav>
          <div class="tres-n">
            <label>&nbsp;</label>
          </div>
        </div>

        @endauth
        <main>
            @yield('content')
        </main>

    </div>
    @auth
    <footer>
      <nav class="navbar navbar-expand-md nav-new-style-footer">
          <div class="container">
            <a href="https://c-230.com/">
              <img class="logo-app" src="/img/C230 blanco.png" style="width : 4.5rem;">
            </a>
            <a href="https://fundacionidea.org.mx/">
              <img class="logo-app" src="/img/IDEA- blanco.png" style="width : 4.5rem;">
            </a>


          </div>
      </nav>
    </footer>

    @endauth
    @auth
        <script>
            window.user = @json(auth()->user());
            let algolia_appid = '{{ env('ALGOLIA_APP_ID') }}';
            let algolia_apikey = '{{ env('ALGOLIA_API_KEY') }}';
        </script>
        <style media="screen">
          .grid-nav {
            display: grid;
            grid-template-columns: 2% 96% 2%;
          }

          .nab-sd {
            right: 20px;
    left: 26px;

          }

          .nav-new-style {
            z-index: 1;
          }
          @media only screen and (max-width: 768px) {
            .nab-sd {
              right: 20px;
              left: 15px;
            }
          }

          @media only screen and (max-width: 480px) {
            .nab-sd {
              right: 20px;
              left: 8px;
            }
          }
        </style>
    @endauth
    <!-- <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
</body>
</html>
