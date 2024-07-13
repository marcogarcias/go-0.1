@extends('layouts.stablishments')
@section('title', 'Inicio')
@section('returnBtn', route('/'))

@section('content')

<div class="container-fluid go-stablishments">
  <div class="row">
    <div class="col-12">
      <section id="stablishments" class="sections">
        <div class="row">
          <div class="col-12">
            <div class="text-center">
              <a href="#" class="btn btn-black">Elige una sección</a>
            </div>
          </div>
          
          <div class="col-12 text-center my-3">
            @forelse($sections as $sec)
              <div class="section-item">
                @if($sec->name == "Vacantes")
                  <a href="{{ route('stablishmentsJobs') }}"><img src="{{ asset('img/site/btn/'.$sec->image) }}" title="{{ __($sec->name) }}" alt="{{ __($sec->name) }}"></a>
                @else
                  <a href="{{ route('stablishments', $sec->idsection) }}"><img src="{{ asset('img/site/btn/'.$sec->image) }}" title="{{ __($sec->name) }}" alt="{{ __($sec->name) }}"></a>
                @endif
              </div>
            @empty
                      
            @endforelse
          </div>
          
          <div class="col-12">
            <div class="text-center cercaDeTi">
              <a class="" href="{{ route('cercaDeTi') }}">
                <img src="{{ asset('img/site/btn/btn-cercaDeTi.png') }}" title="{{ __('Cerca de tí') }}">
              </a>
            </div>
          </div>

          <div class="col-12">
            <div>
              @if(count($ads))
                <div class="align-center mt-3" >
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
      </section>
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
  window.addEventListener('load', function() {
    go.redimentions(true);
    go.initAds();
    go.initAdHome();
  });
</script>
@endpush

@endsection