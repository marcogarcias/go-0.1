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
            
            @if(count($pub->gallery))
            <div id="gallery">
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
      hashPublication: '{{ $pub->hashPublication }}',
    });
  });
</script>
@endpush

@endsection