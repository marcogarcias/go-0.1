@extends('layouts.videoChat')
@section('title', 'Inicio')
@section('returnBtn', route('/'))

@section('css')
  <link href="{{ asset('css/videoChat03.css?').microtime() }}" rel="stylesheet">
@endsection

@section('js')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.5.1/socket.io.js"></script>
  <script src="{{ asset('js/videoChat05.js?cachebust=1587347550') }}" defer></script>
@endsection

@section('content')

@php
    $inputRoomClass = $type;
@endphp

<div class="container-fluid">
  <div class="line01"></div>

  <div class="row">
    <div class="col-3">
      <div id="usersNoCont" class="usersNo">
        <i class="fas fa-eye"></i>
        <span id="usersNo">0</span>
      </div>
    </div>

    <div class="col-9">
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
          @if($type == 'kukurygirl' || $type == 'guest')
            <button id="startButton">Iniciar Cámara</button>
          @endif
          @if($type == 'kukurygirl' || $type == 'guest' || $type == 'viewer')
            <button id="joinButton">Unirse a Chat</button>
          @endif
        </div>
      </div>

      <div class="row">
        <div class="col-6 col-md-6">
          <div id="local" class="video-preview">
            <i class="fas fa-user"></i>
          </div>
        </div>
        <div class="col-6 col-md-6">
          <div id="broadcaster" class="video-preview">
            <i class="fas fa-user"></i>
          </div>
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

  <div class="line01"></div>
</div>

@push('scripts')
<script type="application/javascript">
  document.addEventListener('DOMContentLoaded', function() {
    console.log('type', '{{ $type }}');
    initVideoCam({ userType: '{{ $type }}' });
  });
</script>
@endpush

@endsection