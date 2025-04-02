@extends('layouts.videoChat')
@section('title', 'Inicio')
@section('returnBtn', route('/'))

@section('css')
  <link href="{{ asset('css/videoChat03.css?').microtime() }}" rel="stylesheet">
@endsection

@section('js')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.5.1/socket.io.js"></script>
  <script src="{{ asset('js/videoChat06.js?cachebust=1587347550') }}" defer></script>
@endsection

@section('content')

@php
    $inputRoomClass = $type;
@endphp

<div class="container-fluid">
  <div class="line01">
    <h3 id="topicHeader"></h3>
  </div>

  <div class="row">
    <div class="col-3">
      <div id="usersNoCont" class="usersNo">
        <i class="fas fa-eye"></i>
        <span id="usersNo">0</span>
      </div>
    </div>

    <div id="gamesCont" class="col-6">
      <div id="buttonsStartGamesCont">
        <div id="startGameBtn" class="btn-kukury">
          Jugar
        </div>
      </div>

      <div id="buttonsGamesCont" class="">
        <div id="verdadRetoBtn" class="btn-kukury" data-game="verdad">
          Verdad/Reto
        </div>
        <div id="ruletaBtn" class="btn-kukury" data-game="ruleta">
          Ruleta
        </div>
      </div>

      <div id="verdadRetoCont">
        <div id="verdadRuleta" class="girar" data-start="0">
          <img id="verdadRuletaImg" src="{{ asset('img/site/video/verdad-ruleta.png') }}" title="Ruleta">
        </div>
        <div id="verdadFlecha">
          <img src="{{ asset('img/site/video/verdad-flecha.png') }}" title="Flecha">
        </div>
        <div class="gameClose"><i class="fas fa-times-circle" title="Cerrar"></i></div>
      </div>

      <div id="ruletaCont" data-start="0">
        <ul>
          <li><img src="{{ asset('img/site/video/ruleta-bra.png') }}" title="??"></li>
          <li><img src="{{ asset('img/site/video/ruleta-cuerpo.png') }}" title="??"></li>
          <li><img src="{{ asset('img/site/video/ruleta-gemido.png') }}" title="??"></li>
          <li><img src="{{ asset('img/site/video/ruleta-gluteos.png') }}" title="??"></li>
          <li><img src="{{ asset('img/site/video/ruleta-lengua.png') }}" title="??"></li>
          <li><img src="{{ asset('img/site/video/ruleta-mano.png') }}" title="??"></li>
          <li><img src="{{ asset('img/site/video/ruleta-pie.png') }}" title="??"></li>
          <li><img src="{{ asset('img/site/video/ruleta-piernas.png') }}" title="??"></li>
          <li><img src="{{ asset('img/site/video/ruleta-tanga.png') }}" title="??"></li>
        </ul>
        <div class="gameClose"><i class="fas fa-times-circle" title="Cerrar"></i></div>
      </div>
    </div>

    <div class="col-3">
      <div id="logoCont">
        <img src="{{ asset('img/site/video/logo-01.png') }}" alt="Kukury cam">
      </div>
    </div>
  </div>



  <div id="status" class="status d-none"></div>

  <div class="row">
    <div class="col-12 col-md-9 video-grid" id="videoGrid">

      <div class="row">
        <div id="buttonsCont" class="col-12">
          <input type="text" id="roomId" class="{{ $inputRoomClass }}" placeholder="Nombre sala">
          <input type="text" id="nick" placeholder="Elige un nick">
          <input type="text" id="topic" placeholder="Tema del día">
          <div class="loading"><i class="fas fa-spinner fa-spin"></i></div>
          @if($type == 'kukurygirl' || $type == 'guest')
            <button id="topicButton">Elegir tema</button>
          @endif
          @if($type == 'kukurygirl' || $type == 'guest')
            <button id="startButton">Iniciar Cámara</button>
          @endif
          @if($type == 'kukurygirl' || $type == 'guest' || $type == 'viewer')
            <button id="nickButton">Elegir nick</button>
            <button id="joinButton">Unirse a Chat</button>
          @endif
        </div>
      </div>

      <div class="row">
        <div class="col-6 col-md-6">
          <div id="kukurygirl" class="video-preview">
            <i class="fas fa-user"></i>
          </div>
          <div class="nickLabel">Cámara 1</div>
        </div>
        <div class="col-6 col-md-6">
          <div id="broadcaster" class="video-preview">
            <i class="fas fa-user"></i>
          </div>
          <div class="nickLabel">Cámara 2</div>
        </div>
      </div>

    </div>

    <div class="col-12 col-md-3">
      <div class="main-content">
        <div class="chat-container">
          <div class="chat-messages" id="chatMessages"></div>
          <div class="chat-input">
            <input type="text" id="messageInput" placeholder="Escribe un mensaje...">
            <button id="sendButton">Enviar</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="line01">
  <ul id="buttonsRedesCont">
      <li>
        <a href="https://www.facebook.com/people/Kukury-Peach/pfbid037ARbR2kPZwwziNAp5rExHPZWedYkA2eEKwqmS7HjbjAqgAEkPbyyLHYg1PmUUbHBl/" target="_blanck">
          <img src="{{ asset('img/site/video/logo-face-01.png') }}" alt="Facebook">
        </a>
      </li>
      <li>
        <a href="https://www.instagram.com/kukury_peach" target="_blanck">
          <img src="{{ asset('img/site/video/logo-insta-01.png') }}" alt="Intagram">
        </a>
      </li>
      <li>
        <a href="https://t.me/+zLdSNwHHu3gwYzkx?fbclid=IwZXh0bgNhZW0CMTAAAR2ZYgWY-NGl1Csji0ld88DIjsd4heyRxCMzr1wpXz3DREXjpKQj7eqMSJ4_aem_WwD74mVVHjzwkKLRg9YLlw" target="_blanck">
          <img src="{{ asset('img/site/video/logo-telegram-01.png') }}" alt="Telegram">
        </a>
      </li>
    </ul>
  </div>
</div>

@push('scripts')
<script type="application/javascript">
  document.addEventListener('DOMContentLoaded', function() {
    console.log('type', '{{ $type }}');
    initVideoCam({ 
      userType: '{{ $type }}', 
      ip: '{{ $ip }}', 
      assets: '{{ asset("/") }}',
      from: '{{ $from }}',
      fromFull: '{{ $refererDomain }}',
    });
  });
</script>
@endpush

@endsection