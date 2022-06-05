<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>C230 Consultores</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/img/fav/site/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/fav/site/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/fav/site/favicon-16x16.png">
    <link rel="manifest" href="/img/fav/site/site.webmanifest">
    <link rel="mask-icon" href="/img/fav/site/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg fixed-top navbar-light bg-light p-2 shadow">
			<div class="container-fluid">
				<a class="navbar-brand" href="/">
					<img class="" src="/img/site/logo_dark.png" style="width:50px">
				</a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
          <span class="fas fa-bars"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarTogglerDemo02">
					<ul class="navbar-nav ml-auto mt-2 mt-lg-0">
{{-- 						<li class="nav-item">
							<router-link class="nav-link text-uppercase pb-1" to="/">
								<i class="fas fa-home"></i>
							</router-link>
						</li> --}}
						<li class="nav-item">
							<router-link class="nav-link text-uppercase pb-1" to="/quienes-somos">
								Quiénes somos
							</router-link>
						</li>
						<li class="nav-item">
							<router-link class="nav-link text-uppercase pb-1" to="/que-hacemos">
								Qué hacemos
							</router-link>
						</li>
						<li class="nav-item">
							<router-link class="nav-link text-uppercase" to="/vacantes">
								Vacantes
							</router-link>
						</li>
					</ul>
				</div>
			</div>
        </nav>
        <div class="container-fluid">
            <div class="row site-content-padding">
    {{--             <div class="col-md-2 d-none d-md-block bg-light">
                    <nav class="site-sidenav">
                        <div class="sidebar-sticky">
                          <ul class="nav flex-column">
                            <li class="nav-item">
                              <a class="nav-link" href="#">
                                <img class="logo-app" src="/img/site/logo_dark.png" v-if="!managementRoute">
                              </a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link lead text-uppercase text-dark" href="#" style="border-bottom:solid 2px">
                                Quiénes somos <span class="sr-only">(current)</span>
                              </a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link lead text-uppercase text-dark" href="#">
                                Qué hacemos
                              </a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link lead text-uppercase text-dark" href="#">
                                Vacantes
                              </a>
                            </li>
                          </ul>
                        </div>
                      </nav>
                </div> --}}
                <div class="col-md-12">
                    <main role="main">
                        @yield('content')
                    </main>
                </div>
            </div>
            <site-footer></site-footer>
          </div>

    </div>
</body>
</html>
