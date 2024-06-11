<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'Go 0.1') }} - @yield('title')</title>

  <!-- Fonts -->
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link rel="icon" type="image/png" href="{{ asset('img/varios/favicon-1.png') }}">
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link href="{{ asset('libs/toastr/toastr.min.css') }}" rel="stylesheet">
  <link href="{{ asset('css/admin.css?').microtime() }}" rel="stylesheet">
  @yield('css')
</head>
<body>
  <div id="app">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
      <div class="container-fluid">
        <a class="navbar-brand" href="{{ url('/') }}">
          {{ config('app.name', 'Laravel') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <!-- Left Side Of Navbar -->
          <ul class="navbar-nav mr-auto">
            <li class="nav-item">
              <a class="nav-link" href="{{ route('admin.publications') }}">{{ __('Publicaciones').' | ' }}</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('admin.stablishments') }}">{{ __('Establecimientos').' | ' }}</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('admin.stablishments') }}">{{ __('Zonas').' | ' }}</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('admin.stablishments') }}">{{ __('Secciones').' | ' }}</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('admin.stablishments') }}">{{ __('Subsecciones').' | ' }}</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('admin.advertisements') }}">{{ __('Anuncios').' | ' }}</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('home') }}">{{ __('somosgo.com.mx').' | ' }}</a>
            </li>
          </ul>

          <!-- Right Side Of Navbar -->
          <ul class="navbar-nav ml-auto">
            <!-- Authentication Links -->
            <li class="nav-item dropdown">
              <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                {{ Auth::user()->name }}
              </a>

              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ route('logout') }}" 
                  onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                  {{ __('Logout') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                  @csrf
                </form>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <main class="py-4">
      @yield('content')
    </main>
  </div>

  @stack('scripts')

  <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}"></script>
  <script src="{{ asset('js/utils.js') }}"></script>
  <script src="{{ asset('libs/jquery.ui/js/jquery-ui.min.js') }}"></script>
  <script src="{{ asset('libs/toastr/toastr.min.js') }}" defer></script>
  @yield('js')
  <link href="{{ asset('libs/jquery.ui/css/jquery-ui.min.css') }}" rel="stylesheet">
@if(env('APP_ENV')==='production')
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-HFTHY5JBSH"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-HFTHY5JBSH');
  </script>
@endif

  @yield('scripts')
</body>
</html>
