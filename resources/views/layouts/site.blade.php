<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="theme-color" content="#353367">
  <title>{{ config('app.name', 'Go 0.1') }} - @yield('title')</title>

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

  @if(session('isStablishment'))
    <link href="{{ asset('css/chatbox_v1.css?').microtime() }}" rel="stylesheet">
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
    <header>
      <div class="row header-cont">
        <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 col-xl-2 text-left">
          <div class="btn-back"><a href="javascript:history.back()"><img src="{{ asset('img/site/btn/btn-back-02.png') }}" title="{{ __('Regresar') }}" alt="{{ __('Regresar') }}"></a></div>
          <div class="text-left btn-help"><a href="#btn-help"><img src="{{ asset('img/site/btn/btn-help-01.png') }}" title="{{ __('Ayuda') }}" alt="{{ __('Ayuda') }}"></a></div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-7 col-xl-7">
          <h1 class="text-center header-txt">Todo lo que buscas a un click!! </h1>
        </div>
        @guest
          <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 btn-cont">
            <div class="text-left btn-help-movil" data-toggle="modal" data-target="#help-modal"><a href="#"><img src="{{ asset('img/site/btn/btn-help-01.png') }}" title="{{ __('Ayuda') }}" alt="{{ __('Ayuda') }}"></a></div>
            <div class="text-left btn-back-movil"><a href="javascript:history.back()"><img src="{{ asset('img/site/btn/btn-back-02.png') }}" title="{{ __('Regresar') }}" alt="{{ __('Regresar') }}"></a></div>

            @if (Route::has('login'))
              <a href="{{ route('login') }}" class="btn btn-black">Ingresar</a>
            @endif
            
            @if (Route::has('register'))
              <a href="{{ route('register') }}" class="btn btn-black">Registrarme</a>
            @endif
          </div>
        @else
          @php 
            $admin = isset(Auth::user()->profile_id) && Auth::user()->profile_id==1?'style="margin-right: 5%;"':'';
          @endphp
          <!-- <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
            {{ Auth::user()->name }}
          </a> -->
          <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 btn-cont">
            <div class="text-left btn-help-movil" {!! $admin !!} data-toggle="modal" data-target="#help-modal"><a href="#"><img src="{{ asset('img/site/btn/btn-help-01.png') }}" title="{{ __('Ayuda') }}" alt="{{ __('Ayuda') }}"></a></div>
            <div class="text-left btn-back-movil" {!! $admin !!}><a href="javascript:history.back()"><img src="{{ asset('img/site/btn/btn-back-02.png') }}" title="{{ __('Regresar') }}" alt="{{ __('Regresar') }}"></a></div>
            @if(isset(Auth::user()->profile_id) && Auth::user()->profile_id==1)
              <a href="{{ route('admin.stablishments') }}" class="btn btn-black">Admin</a>
            @endif

            @if (Route::has('myspace'))
              <a href="{{ route('myspace') }}" class="btn btn-black">Mi espacio</a>
            @endif

            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-black">{{ __('Salir') }}</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
              @csrf
            </form>
          </div>
        @endguest
      </div>
      <div class="row header-line-1">
        &nbsp;
      </div>
      <div class="row header-line-2">
        &nbsp;
      </div>
    </header>

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

<!-- CHAT -->
@auth
@if(session('isStablishment') && session('chatStablishment'))
  <div id="btn-openChat" class="newMsgGeneral" data-userStablishment="{{ Crypt::encryptString(session('userStablishment')) }}" data-userClient="">
    <div class="chat-icon">
      <i class="far fa-comment-dots"></i>    
    </div>
    <div class="newMsgGeneralIcon">
      <i class="fa fa-envelope" aria-hidden="true" title="Nuevo mensaje"></i>
    </div>
  </div>

  <!-- INICIA VENTANA DE CHAT -->
  <div id="chatbox-stablish">
    <div id="chatbox">
      <div id="friendslist">
        <div id="topmenu">
          <div id="titleChat">
            <h3 class="text-center">USUARIOS</h3>
          </div>
          <div id="closeCont">
            <i class="fa fa-times-circle"></i>
          </div>
          <!-- <span class="friends"></span>
          <span class="chats"></span>
          <span class="history"></span> -->
        </div>
            
        <div id="friends">
        
        </div>
      </div>
        
      <div id="chatview" class="p1">
        <div id="profile">

          <div id="close">
            <div class="cy"></div>
            <div class="cx"></div>
          </div>

          <p>Name</p>
          <span>name@email.com</span>
        </div>

        <div id="chat-messages">
          <label>Thursday 02</label>
        </div>

        <div id="sendmessage">
          <input type="text" value="Escribir mensage">
          <div class="d-inline" id="send">
            <i class="fas fa-paper-plane"></i>
          </div>
        </div>

      </div>        
    </div>
  </div>
  <!-- TERMINA VENTANA DE CHAT -->
@endif
@endauth

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
