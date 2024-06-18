@extends('layouts.site')
@section('title', 'Inicio')
@section('returnBtn', route('/'))

@section('css')
  <link href="{{ asset('html5game/amloGame.css?').microtime() }}" rel="stylesheet">
@endsection

@section('js')
  <script src="{{ asset('html5game/Amlogame.js?cachebust=1587347550') }}"></script>
@endsection

@section('content')

<div class="container-fluid go-wallpaper">
  <div class="row">
    <div class="col-md-12 sections text-center">
      <div class="gm4html5_div_class" id="gm4html5_div_id">  
        <!-- Builtin injector for splash screen -->
        <!-- Create the canvas element the game draws to -->
        <canvas id="canvas" width="1280" height="720" >
          <p>Your browser doesn't support HTML5 canvas.</p>
        </canvas>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="ads-modal" tabindex="-1" role="dialog" aria-labelledby="ads-modal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content ads">
      <img id="ads-img" src="" title="" alt="">
    </div>
    <div class="btn btn-black close-modal">CERRAR</div>
  </div>
</div>

<!-- Modal anuncio -->
<div class="modal fade" id="adHome-modal" tabindex="-1" role="dialog" aria-labelledby="adHome-modal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <img src="{{ asset('img/site/anuncio01.png') }}">
    </div>
    <div class="btn btn-black close-modal">CERRAR</div>
  </div>
</div>

@push('scripts')
<script type="application/javascript">

</script>
<script>window.onload = GameMaker_Init;</script>
@endpush

@endsection