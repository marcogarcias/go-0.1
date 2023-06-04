@extends('layouts.site')
@section('title', 'Cerca de tí')

@section('css')
  <link href="{{ asset('libs/mapbox/mapbox-gl.css?').microtime() }}" rel="stylesheet">
  <link href="{{ asset('libs/mapbox/mapbox-gl-directions.css?').microtime() }}" rel="stylesheet">
@endsection

@section('js')
  <script src="{{ asset('libs/turf/turf.min.js') }}"></script>
  <script src="{{ asset('libs/mapbox/mapbox-gl.js') }}"></script>
  <script src="{{ asset('libs/mapbox/mapbox-gl-directions.js') }}"></script>
  <script src="{{ asset('js/map.js') }}"></script>
@endsection

@section('returnBtn', route('home'))

@section('content')

<div class="container-fluid go-wallpaper">
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-5 logo-lateral">
      <img class="logo-lateral-img" src="{{ asset('img/site/logo-md.gif') }}" title="{{ __('Go') }}" alt="{{ __('Go') }}">
      <img class="logo-banner-img" src="{{ asset('img/site/logo-sm-slim.gif') }}" title="{{ __('Go') }}" alt="{{ __('Go') }}">
    </div>
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 col-xl-7 sections text-center">
      <div>
        <div class="">
          <div class="btn btn-line">Metros a la redonda</div>
        </div>
        <div class="map-around-cont">
          <span>1 Km.</span>
          <input type="range" class="custom-rang map-around" id="map-around" min="10" max="30" step="1" value="20">
          <span>3 Km.</span>
        </div>
      </div>
      <div class="btn btn-black btn-return-extra" onclick="javascript:history.back()">REGRESAR</a></div>
      <div class="">
        <div id="go-mapa"></div>
      </div>
    </div>
  </div>
</div>
@push('scripts')
<script type="application/javascript">
  window.addEventListener('load', function() {
    $(document).ready(function() {
      let zoom = 14;
      if($(document).width() < 576){
        zoom = 15;
      }
      let cfg = {
        showBtnHelp: false,
        'url': '{{ route("test2") }}',
        'asset': '{{ asset('/') }}',
        'zoom': zoom,
        'toGo': '{{ route('stablishment', '#ID#') }}'
      };

      @if(is_object($stab))
        cfg.stablish = { 
          'idstablishment': '{{ $stab->idstablishment ? $stab->idstablishment : 0 }}',
          'lat': '{{ $stab->lat ? $stab->lat : 0 }}',
          'lng': '{{ $stab->lng ? $stab->lng : 0 }}' ,
          'image': '{{ $stab->image ? $stab->image : '' }}' ,
          'secImage': '{{ $stab->secImage ? $stab->secImage : null }}',
          'description2': '{{ $stab->description2 ? $stab->description2 : '' }}'
        }
      @endif
      map.init(cfg);
    });
  });
</script>
@endpush

@endsection