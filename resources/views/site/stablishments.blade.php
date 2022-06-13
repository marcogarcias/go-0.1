@extends('layouts.site')
@section('title', 'Lugares')
@section('returnBtn', route('home'))

@section('content')

<div class="container-fluid go-wallpaper">
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-5 logo-lateral">
      <img class="logo-lateral-img" src="{{ asset('img/site/logo-md.gif') }}" title="{{ __('Go') }}" alt="{{ __('Go') }}">
    </div>
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 col-xl-7 sections text-center">
      <div class="">
        <div class="mt-4 mb-4 filters-btns-cont text-center">
            <div class="mr-4"><a class="btn btn-dark" id="filtersOpen">{{ __('Filtros de búsqueda') }}</a></div>
            <div><a class="btn btn-dark" href="{{ route('cercaDeTi') }}">{{ __('Cerca de tí') }}</a></div>
        </div>
        <form id="filters">
          <h4 class="title-sec"></h4>
          <div class="row form-group stabs-filters">
            <div class="col-md-4 col-xs-12 col-sm-4">
              <input type="text" class="form-control filter" id="nameFil" name="nameFil" value="{{ old('nameFil') }}" placeholder="Nombre">
              {!! $errors->first('nameFil', '<div class="invalid-feedback" style="display: block;">:message</div>') !!}
            </div>

            <div class="col-md-3 col-xs-12 col-sm-3">
              <select id="zoneFil" class="form-control filter" name="zoneFil">
                <option value="">{{ __('Todas las zonas') }}</option>
                @foreach($zones as $zone)
                  <option value="{{ $zone->idzone }}">{{ $zone->name }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-3 col-xs-12 col-sm-3">
              <select id="tagFil" class="form-control filter" name="tagFil">
                <option value="">{{ __('Todos los tipos') }}</option>
                @foreach($tags as $tag)
                  <option value="{{ $tag->idtag }}">{{ $tag->name }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-2 col-xs-12 col-sm-2">
              <a class="btn btn-purple around" href="{{ route('stablishments', $sec) }}" role="button">{{ __('Todo') }}</a>
            </div>
          </div>
        </form>
      </div>
      <div class="overflow-auto stablish-cont">
        <table class="stablish-table">
          @forelse($stablish as $stab)
            <tr class="">
              <td class="stablish-logo">
                <a href="{{ route('stablishment', $stab->idstablishment) }}">
                  <img src="{{ asset($stab->image) }}" title="{{ __($stab->description) }}" alt="{{ __($stab->name) }}">
                </a>
              </td>
              <td class="stablish-visit">
                <a class="btn btn-purple" href="{{ route('stablishment', $stab->idstablishment) }}">Click</a>
              </td>
              <td class="stablish-desc">
                <div class="row">
                  <div class="text-center stablish-logo-2">
                    <a href="{{ route('stablishment', $stab->idstablishment) }}">
                      <img src="{{ asset($stab->image) }}" title="{{ __($stab->description) }}" alt="{{ __($stab->name) }}">
                    </a>
                  </div>
                  <div class="text-center stablish-visit-2">
                    <a class="btn btn-purple" href="{{ route('stablishment', $stab->idstablishment) }}">Click</a>
                  </div>
                </div>
                <div class="row">
                  {{ $stab->description }}
                </div>
              </td>
              <td class="stablish-offer">
                @if($stab->offer)
                  <img src="{{ asset('img/site/btn/offer-01.png?').microtime() }}" title="{{ __('Oferta disponible') }}" alt="{{ __('Oferta disponible') }}">
                @endif
              </td>
              <td class="stablish-space"></td>
              @auth
                <td class="stablish-add-space">
                  <div class="stablish-add" data-stab="{{ Crypt::encryptString($stab->idstablishment) }}" data-name="{{ Crypt::encryptString($stab->name) }}">
                    <a href="#">
                      <img src="{{ asset('img/site/btn/btn-add-01.png') }}" title="{{ __('Agregar') }}" alt="{{ __('Agregar') }}">
                    </a>
                  </div>
                </td>
              @endauth
            </tr>
            <tr class="text-center stablish-footer">
              <td colspan="5">
                @if($stab->offer)
                  <img src="{{ asset('img/site/btn/offer-01.png?').microtime() }}" title="{{ __('Oferta disponible') }}" alt="{{ __('Oferta disponible') }}">
                @endif
                &nbsp;&nbsp;
                @auth
                  <a href="#">
                    <img class="stablish-add" data-stab="{{ Crypt::encryptString($stab->idstablishment) }}" data-name="{{ Crypt::encryptString($stab->name) }}" src="{{ asset('img/site/btn/btn-add-01.png') }}" title="{{ __('Agregar') }}" alt="{{ __('Agregar') }}">
                  </a>
                @endauth
              </td>
            </tr>
            <tr><td colspan="5" class="stablish-row"></td></tr>
          @empty
            
          @endforelse
        </table>
      </div>
    </div>
  </div>
</div>
@push('scripts')
<script type="application/javascript">
window.addEventListener('load', function() {
  $(document).ready(function() {
    let cfg = {
      showBtnHelp: false,
      urlAjax: '{{ route("addStablishment") }}', 
      urlSetFilter: '{{ route("setFilter") }}',
      section: {{ $sec}}
    };
    let auth = {{ Auth::check()?1:0 }};
    let html, stab, gotToStab;
    let gotToStab_='{{ route("stablishment", '#STAB#') }}';

    go.initStablisments(cfg, function(stablish){
      stab = stablish.stablish ? stablish.stablish : {};
      html='';
      for(let x in stab){
        gotToStab = gotToStab_.replace('#STAB#', stab[x].idstablishment)
        html+=''+
          '<tr class="">'+
              '<td class="stablish-logo">'+
                '<a href="'+gotToStab+'">'+
                  '<img src="/'+stab[x].image+'" title="'+{{ __("stab[x].description") }}+'" alt="'+{{ __("stab[x].name") }}+'">'+
                '</a>'+
              '</td>'+
              '<td class="stablish-visit">'+
                '<a class="btn btn-purple" href="'+gotToStab+'">Click</a>'+
              '</td>'+
              '<td class="stablish-desc">'+
                '<div class="row">'+
                  '<div class="text-center stablish-logo-2">'+
                    '<a href="'+gotToStab+'">'+
                      '<img src="/'+stab[x].image+'" title="'+{{ __("stab[x].description") }}+'" alt="'+{{ __("stab[x].name") }}+'">'+
                    '</a>'+
                  '</div>'+
                  '<div class="text-center stablish-visit-2">'+
                    '<a class="btn btn-purple" href="'+gotToStab+'">Click</a>'+
                  '</div>'+
                '</div>'+
                '<div class="row">'+
                  stab[x].description+
                '</div>'+
              '</td>'+
              '<td class="stablish-offer">';
              if(stab[x].offer){
                html+='<img src="{{ asset("img/site/btn/offer-01.png") }}" title="{{ __("Oferta disponible") }}" alt="{{ __("Oferta disponible") }}">';
              }
              html+='</td>'+
              '<td class="stablish-space"></td>';
              if(auth){
                html+=''+
                '<td class="stablish-add-space">'+
                  '<div class="stablish-add" data-stab="{{ Crypt::encryptString('+stab[x].idstablishment+') }}" data-name="{{ Crypt::encryptString('+stab[x].name+') }}">'+
                    '<a href="#">'+
                      '<img src="{{ asset("img/site/btn/btn-add-01.png") }}" title="{{ __("Agregar") }}" alt="{{ __("Agregar") }}">'+
                    '</a>'+
                  '</div>'+
                '</td>';
              }
            html+=''+
            '</tr>'+
            '<tr class="text-center stablish-footer">'+
              '<td colspan="5">';
                if(stab[x].offer){
                  html+='<img src="{{ asset("img/site/btn/offer-01.png") }}" title="{{ __("Oferta disponible") }}" alt="{{ __("Oferta disponible") }}">';
                }
                html+='&nbsp;&nbsp;';
                if(auth){
                  html+=''+
                  '<a href="#">'+
                    '<img src="{{ asset("img/site/btn/btn-add-01.png") }}" class="stablish-add" data-stab="{{ Crypt::encryptString('+stab[x].idstablishment+') }}" data-name="{{ Crypt::encryptString('+stab[x].name+') }}" title="{{ __("Agregar") }}" alt="{{ __("Agregar") }}">'+
                  '</a>';
                }
              html+=''+
              '</td>'+
            '</tr>'+
            '<tr><td colspan="5" class="stablish-row"></td></tr>';
      }
      $('.stablish-table').empty().append(html);
    });
  });
});
</script>
@endpush

@endsection