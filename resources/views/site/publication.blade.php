@extends('layouts.publications')
@section('title', $pub->title)
@section('css')
  <link href="{{ asset('libs/splide-4.1.3/dist/css/themes/splide-skyblue.min.css') }}" rel="stylesheet">
  <link href="{{ asset('libs/lightbox2/dist/css/lightbox.min.css') }}" rel="stylesheet">
@endsection

@section('js')
  <script src="{{ asset('libs/splide-4.1.3/dist/js/splide.min.js') }}"></script>
  <script src="{{ asset('libs/lightbox2/dist/js/lightbox.min.js') }}" defer></script>
  <script src="{{ asset('js/publication.js') }}"></script>
@endsection

@section('metas')
  <meta property="og:title" content="{{ $pub->title }}">
  <meta property="og:description" content="{{ $pub->synopsis }}">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:image" content="{{ asset($pub->image) }}">
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="{{ config('app.name', 'SOMOS GO') }}">
  <meta property="og:locale" content="es_ES">
@endsection

@section('returnBtn', route('home'))
@section('content')

<div class="container-fluid go-publications">
  <div class="row">
    <div class="col-12">
      <section id="publication">
        <div class="row">
          <div class="col-12">

            <h2 class="text-left">{{ $pub->title }}</h2>
            <h4 class="text-left">{{ $pub->subtitle }}</h4>
            
            <div class="autor-date">
              <span class="autor">Por: {{ $pub->pseudonym ? $pub->pseudonym : $pub->pseudonym }}</span> -
              <span class="date">{{ date('d/m/Y', strtotime($pub->datetime)) }}</span> -
              <span class="hour">{{ date('H:i', strtotime($pub->datetime)) }}</span>
            </div>
            
            <div class="pub-imageCont">
              <img src="{{ asset($pub->image) }}">
            </div>
            
            <div id="synopsis" class="bd-callout bd-callout-danger">
              {{ $pub->synopsis }}
            </div>
            
            <div id="description">
              {!! $pub->description !!}
            </div>

            @if($pub->lat && $pub->lng)
            <div id="mapCont" class="col-12 section">
              <h2>Mapa</h2>
              <div id="map"></div>
              <div class="">
                {{ $pub->address }}
              </div>
            </div>
            @endif
            
            @if(count($pub->gallery))
            <div id="gallery" class="section">
              <h2>Galería</h2>
              <section class="splide" aria-label="Galería">
                <div class="splide__track">
                  <ul class="splide__list">
                    @foreach($pub->gallery as $gal)
                    <li class="splide__slide text-center">
                      <a href="{{ asset($gal->path.'/'.$gal->image) }}" data-lightbox="gallery">
                        <img src="{{ asset($gal->path.'/'.$gal->image) }}">
                      </a>
                    </li>
                    @endforeach
                  </ul>
                </div>

                <div class="splide__progress">
                  <div class="splide__progress__bar"></div>
                </div>
              </section>
            </div>
            @endif

            @if($pub->facebook || $pub->instagram || $pub->twitter || $pub->youtube || $pub->web)
            <div id="socialmediaCont" class="col-12 section">
              <h2>Redes sociales</h2>
              <div id="socialmedia">
                @if($pub->facebook)
                  <a href="{{ $pub->facebook }}" class="btn btn-purple" target="_blank" title="Facebook">
                    <i class="bi bi-facebook"></i>
                  </a>
                @endif

                @if($pub->instagram)
                  <a href="{{ $pub->instagram }}" class="btn btn-purple" target="_blank" title="Instagram">
                    <i class="bi bi-instagram"></i>
                  </a>
                @endif

                @if($pub->twitter)
                  <a href="{{ $pub->twitter }}" class="btn btn-purple" target="_blank" title="X">
                    <i class="bi bi-twitter-x"></i>
                  </a>
                @endif

                @if($pub->youtube)
                  <a href="{{ $pub->youtube }}" class="btn btn-purple" target="_blank" title="Youtube">
                    <i class="bi bi-youtube"></i>
                  </a>
                @endif

                @if($pub->web)
                  <a href="{{ $pub->web }}" class="btn btn-purple" title="Web">
                    <i class="bi bi-globe2"></i>
                  </a>
                @endif
              </div>
            </div>
            @endif

            <div class="interactions">
              <a href="#" class="btn btn-purple btn-visits" title="Visitas">
                <i class="bi bi-eye"></i> {{ $pub->visits ? $pub->visits : 0 }}
              </a>
              <a href="#" class="btn btn-purple btn-like" title="Me gusta" data-state="off">
                <i id="likesIcon" class="bi bi-heart"></i> 
                <span id="likesNo">{{ $pub->likes ? $pub->likes : 0 }}</span>
              </a>
              <a href="#" class="btn btn-purple btn-share" title="Compartir">
                <i class="bi bi-share"></i>
              </a>
            </div>

          </div>
        </div>
      </section>
    </div>
  </div>
</div>


@push('scripts')
<script type="application/javascript">
  window.addEventListener('load', function() {
    goPublication.init({
      urlSetLike: '{{ route("publication.setLike") }}',
      lng: '{{ $pub->lng }}',
      lat: '{{ $pub->lat }}',
    });
  });
</script>
@endpush

@endsection