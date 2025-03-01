@extends('layouts.site')
@section('title', 'Inicio')
@section('returnBtn', route('/'))

@section('css')
  <link href="{{ asset('css/videoChat.css?').microtime() }}" rel="stylesheet">
@endsection

@section('js')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.5.1/socket.io.js"></script>
  <script src="{{ asset('js/videoChat.js?cachebust=1587347550') }}"></script>
@endsection

@section('content')

<div class="container">
  <h1>Video Chat</h1>
  <div class="video-container">
    <div class="video-box">
      <video id="localVideo" autoplay playsinline muted></video>
    </div>
    <div class="video-box">
      <video id="remoteVideo" autoplay playsinline></video>
    </div>
  </div>
  <div class="controls">
    <input type="text" id="roomId" placeholder="Ingresa ID de sala">
    <button id="startButton">Iniciar Cámara</button>
    <button id="joinButton">Unirse a Chat</button>
  </div>
</div>

@push('scripts')
<script type="application/javascript">
  console.log('okok...');
</script>
@endpush

@endsection