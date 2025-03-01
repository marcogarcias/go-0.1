@extends('layouts.site')
@section('title', 'Inicio')
@section('returnBtn', route('/'))

@section('css')
  <link href="{{ asset('css/videoChat02.css?').microtime() }}" rel="stylesheet">
@endsection

@section('js')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.5.1/socket.io.js"></script>
  <script src="{{ asset('js/videoChat02.js?cachebust=1587347550') }}"></script>
@endsection

@section('content')

<div class="container">
        <h1>Video Chat con Espectadores</h1>
        <div id="status" class="status"></div>
        <div class="video-grid" id="videoGrid"></div>
        <div class="controls">
            <input type="text" id="roomId" placeholder="ID de sala">
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