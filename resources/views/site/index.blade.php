@extends('layouts.site')
@section('title', 'Inicio')
@section('returnBtn', route('home'))

@section('content')

<div class="container-fluid go-wallpaper">
  <div class="row">
    <div class="col-md-5 text-left logo-lateral">
      <img class="logo-lateral-img" src="{{ asset('img/site/logo-md.gif') }}" title="{{ __('Go') }}" alt="{{ __('Go') }}">
      <img class="logo-banner-img" src="{{ asset('img/site/logo-sm.gif') }}" title="{{ __('Go') }}" alt="{{ __('Go') }}">
    </div>
    <div class="col-md-7 sections text-center">
      <div class="text-center">
        <a href="#" class="btn btn-line">Elige una opción</a>
      </div>
      <div id="carousel-sections" class="carousel slide" data-interval="false" data-ride="false" data-ride="carousel">
        <div class="carousel-inner">
          @php($first = true)
          @forelse($sections as $sec)
            <div class="carousel-item {{ $first?'active':'' }}">
              @if($sec->name == "Vacantes")
                <a href="{{ route('stablishmentsJobs') }}"><img class="d-block w-100" src="{{ asset('img/site/btn/'.$sec->image) }}" title="{{ __($sec->name) }}" alt="{{ __($sec->name) }}"></a>
              @else
                <a href="{{ route('stablishments', $sec->idsection) }}"><img class="d-block w-100" src="{{ asset('img/site/btn/'.$sec->image) }}" title="{{ __($sec->name) }}" alt="{{ __($sec->name) }}"></a>
              @endif
            </div>
            @php($first = false)
          @empty
            
          @endforelse
        </div>
        <a class="carousel-control-prev" href="#carousel-sections" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">{{ __('Anterior') }}</span>
        </a>
        <a class="carousel-control-next" href="#carousel-sections" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">{{ __('Siguiente') }}</span>
        </a>
      </div>

      <div>
        @if(count($ads))
          <br>
          <div class="align-center" >
            <ul class="ads-list">
              @forelse($ads as $ad)
                <li>
                  <a class="ads-list-a" href="#" data-img="{{ asset('img/site/advertisements/'.$ad->image).'?'.microtime() }}" data-name="{{ __($ad->name) }}" data-toggle="modal" data-target="#ads-modal">
                    <img src="{{ asset('img/site/advertisements/'.$ad->image) }}" title="{{ __($ad->name) }}" alt="{{ __($ad->name) }}">
                  </a>
                </li>
              @empty

              @endforelse
            </ul>
          </div>
          <br>
        @endif
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
      <img src="{{ asset('img/site/adHome-iztapalapa.jpeg') }}">
    </div>
    <div class="btn btn-black close-modal">CERRAR</div>
  </div>
</div>

@push('scripts')
<script type="application/javascript">
  window.addEventListener('load', function() {
    go.redimentions(true);
    go.initAds();
    go.initAdHome();
  });
</script>
@endpush

@endsection