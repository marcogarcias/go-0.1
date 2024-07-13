<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="theme-color" content="#353367">
  <title>{{ config('app.name', 'SOMOS GO') }} - @yield('title')</title>

  <!-- Fonts -->
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
  <link rel="icon" type="image/png" href="{{ asset('img/varios/favicon-1.png') }}">
  <!-- Estilos de LeafLet -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
   integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
   crossorigin=""/>

  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="{{ asset('libs/toastr/toastr.min.css') }}" rel="stylesheet">
  <link href="{{ asset('css/site_v2.css?').microtime() }}" rel="stylesheet">
  <link href="{{ asset('css/stablishments.css?').microtime() }}" rel="stylesheet">

  @if(session('isStablishment'))
    <!-- <link href="{{ asset('css/chatbox_v1.css?').microtime() }}" rel="stylesheet"> -->
  @endif
  @yield('css')

  <!-- Scripts -->
  <!-- script para LeafLet -->
  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
   integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
   crossorigin=""></script>

  <script src="{{ asset('js/app.js') }}" defer></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" integrity="sha512-RXf+QSDCUQs5uwRKaDoXt55jygZZm2V++WUZduaU/Ui/9EGp3f/2KZVahFZBKGH0s774sd3HmrhUy+SgOFQLVQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="{{ asset('libs/toastr/toastr.min.js') }}" defer></script>
  <script src="{{ asset('js/utils.js') }}"></script>
  <script src="{{ asset('js/site.js?').microtime() }}" defer></script>
    @if(session('isStablishment'))
    <script src="{{ asset('js/chatbox_v1.js?').microtime() }}" defer></script>
  @endif
  @yield('js')

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
</head>

<body>
  <div id="app">

    <header id="stablishments-layout-header">
      <div class="stablishments-layout-header-left">
        <img id="stablishments-header-landscape" src="{{ asset('/img/site/logo-md-03.png') }}">
        <img id="stablishments-header-portrait" src="{{ asset('/img/site/logo-md-02.png') }}">
      </div>
      <!-- MENÚ DESKTOP -->
      <div class="buttons-box">
        @guest
          @if (Route::has('login'))
            <a href="{{ route('login') }}" class="btn btn-transparent">Ingresar</a>
          @endif
            
          @if (Route::has('register'))
            <a href="{{ route('register') }}" class="btn btn-transparent">Registrarme</a>
          @endif
        @else
          @if(isset(Auth::user()->profile_id) && Auth::user()->profile_id==1)
            <a href="{{ route('admin.stablishments') }}" class="btn btn-transparent">Admin</a>
          @endif

          @if (Route::has('myspace'))
            <a href="{{ route('myspace') }}" class="btn btn-transparent">Mi espacio</a>
          @endif

          <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-transparent">{{ __('Cerrar sesión') }}</a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
          </form>
        @endguest
      </div>

      <!-- MENÚ MOVIL -->
      <a id="btn-menu-movil" href="#" class="btn btn-transparent">
        <i class="bi bi-justify"></i>
      </a>
      <nav id="menu-movil" class="menu-movil">
        <ul>
          <li><a href="{{ route('/') }}">Inicio</a></li>
          <li><a href="{{ route('quienesSomos') }}">¿Quiénes somos?</a></li>
          <li><a href="{{ route('publications') }}">Publicaciones</a></li>
          <li><a href="{{ route('stablishments.home') }}">Negocios</a></li>
          <li><a href="{{ route('/') }}">Juegos</a></li>
          <li><a href="{{ route('/') }}">Contáctanos</a></li>
        </ul>
      </nav>
    </header>

    <div class="row header-line-1">
      <nav id="menu-desktop" class="menu-desktop">
        <ul>
          <li><a href="{{ route('/') }}" class="btn btn-transparent">Inicio</a></li>
          <li><a href="{{ route('quienesSomos') }}" class="btn btn-transparent">¿Quiénes somos?</a></li>
          <li><a href="{{ route('publications') }}" class="btn btn-transparent">Publicaciones</a></li>
          <li><a href="{{ route('stablishments.home') }}" class="btn btn-transparent">Negocios</a></li>
          <li><a href="{{ route('/') }}" class="btn btn-transparent">Juegos</a></li>
          <li><a href="{{ route('contactanos') }}" class="btn btn-transparent">Contáctanos</a></li>
        </ul>
      </nav>
    </div>

    <main>
      @yield('content')
    </main>

    <footer>
      <div class="row header-line-1">
        &nbsp;
      </div>
      <div class="row footer-txt">
        <div class="col-12 col-md-4">
          <p class="footer-warning">
            Todos los servicios son responsabilidad de los anunciantes.
          </p>
        </div>
        <div class="col-12 col-md-4 footer-br">
          <a href="{{ route("termsAndConditions") }}" class="text-white">Términos y condiciones</a>
        </div>
        <div class="col-12 col-md-4 text-center">
          <p class="footer-contac">Se parte de nuestra comunidad y has crecer tu negocio</p>
          <p>Tel: 56-24-14-09-29</p>
        </div>
      </div>
    </footer>
    <div id="newElement" class="" style="display: none;"></div>
  </div>



<!-- Modal -->
<div class="modal fade" id="help-modal" tabindex="-1" role="dialog" aria-labelledby="help-modal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <img src="{{ asset('img/site/help/help-tags-1.png') }}">
    </div>
    <div class="btn btn-black close-modal">CERRAR</div>
  </div>
</div>

@stack('scripts')
<script type="application/javascript">
window.addEventListener('load', function() {
  $(document).ready(function() {
    let cfg = { 
      auth: {{ Auth::check()?1:0 }},
      asset: '{{ asset('/') }}'
    };
    go.init(cfg);

    @if(session('isStablishment'))
      chatClient.init({
        type: 'stablishmentToClient',
        urlLoadAllMessages: '{{ route('chat.loadAllMessages') }}',
        urlLoadMessages: '{{ route('chat.loadMessages') }}',
        urlSaveMessage: '{{ route('chat.messageSave') }}',
        urlLoadAllUsers: '{{ route('chat.loadAllUsers') }}',
        urlLoadNewMsgGeneral: '{{ route('chat.loadNewMsgGeneral') }}',
        userStablishment: '{{ Crypt::encryptString(session('userStablishment')) }}',
        img: '{{ asset('img') }}',
        logo: '{{ session('logoStablishment') }}',
      });
    @endif
  });
});
</script>
</body>
</html>
