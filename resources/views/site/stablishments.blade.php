@extends('layouts.site')
@section('title', 'Lugares')
@section('returnBtn', route('/'))

@section('content')

<div class="container-fluid go-wallpaper">
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-5 logo-lateral">
      <img class="logo-lateral-img" src="{{ asset('img/site/logo-md.gif') }}" title="{{ __('Go') }}" alt="{{ __('Go') }}">
    </div>
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 col-xl-7 sections text-center">
      <div class="">
        <div class="mt-4 mb-4 filters-btns-cont text-center">
            <div class="mr-4">
              <a class="" id="filtersOpen">
                <img src="{{ asset('img/site/btn/btn-buscar.png') }}" title="{{ __('Filtros de búsqueda') }}">
              </a>
            </div>
            <div>
              <a class="" href="{{ route('cercaDeTi') }}">
                <img src="{{ asset('img/site/btn/btn-cercaDeTi.png') }}" title="{{ __('Cerca de tí') }}">
              </a>
            </div>
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
      
        <div id="stablishCont" class="row">
          @forelse($stablish as $stab)
            @if($stab->disabledGlobal)
              @continue;
            @endif
            @if($stab->disabled)
              @continue;
            @endif
            @php($style = "background: url('/$stab->image'); background-position: center; background-repeat: no-repeat; background-size: cover;") 
            @php($styleBtns = "right: 40%;") 
            @auth
              @php($styleBtns = "right: 15%;") 
            @endauth
            <div class="col-xs-12 col-sm-12 col-md-5 stablishContainer">
              <div class="stablishImgCont" style="{{ $style }}"></div>
              <div class="stablishDesc">
                <h5>{{ $stab->name }}</h5>
                <hr>
                <h5>{{ $stab->description }}</h5>
              </div>
              <div class="stablishLink" style="{{ $styleBtns }}">
                <a href="{{ route('stablishment', $stab->idstablishment) }}" class="btn btn-black">Ir al sitio >></a>
              </div>
              @auth
                <div class="stablish-add" data-stab="{{ Crypt::encryptString($stab->idstablishment) }}" data-name="{{ Crypt::encryptString($stab->name) }}">
                  <a href="#" class="btn btn-green">Guardar</a>
                </div>
              @endauth
            </div>
          @empty

          @endforelse
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
    let style = "";
    let styleBtns = "";

    go.initStablisments(cfg, function(stablish){
      stab = stablish.stablish ? stablish.stablish : {};
      html='';
      for(let x in stab){
        gotToStab = gotToStab_.replace('#STAB#', stab[x].idstablishment);
        if(stab[x].disabledGlobal || stab[x].disabled) continue;
        style = `background: url('/${stab[x].image}'); background-position: center; background-repeat: no-repeat; background-size: cover;`;
        styleBtns = auth ? "right: 15%;" : "right: 40%;";
        html += `
          <div class="col-xs-12 col-sm-12 col-md-5 stablishContainer">
            <div class="stablishImgCont" style="${style}}"></div>
            <div class="stablishDesc">
              <h5>${stab[x].name}</h5>
              <hr>
              <h5>${stab[x].description}</h5>
            </div>
            <div class="stablishLink" style="${styleBtns}">
              <a href="${gotToStab}" class="btn btn-black">Ir al sitio >></a>
            </div>`;
        if(auth){
          html += `
            <div class="stablish-add" data-stab="{{ Crypt::encryptString('`+stab[x].idstablishment+`') }}" data-name="{{ Crypt::encryptString('`+stab[x].name+`') }}">
              <a href="#" class="btn btn-green">Guardar</a>
            </div>`;
        }
        html += `</div>`;
      }
      $('#stablishCont').empty().append(html);
    });
  });
});
</script>
@endpush

@endsection