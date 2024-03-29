@extends('layouts.site')
@section('title', 'Resumen')

@section('css')
  <link href="{{ asset('css/chatbox_v1.css?').microtime() }}" rel="stylesheet">
@endsection


@section('js')
  <script src="{{ asset('js/gallery.js') }}"></script>
  <script src="{{ asset('js/socialmedia.js') }}"></script>
  <script src="{{ asset('js/menus.js') }}"></script>
  @auth
    @unless(session('isStablishment'))
      <script src="{{ asset('js/chatbox_v1.js?').microtime() }}" defer></script>
    @endunless
  @endauth
@endsection

@section('content')
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'>

<div class="container-fluid go-wallpaper">
  <div class="row">
    <div class="col-md-5 text-left logo-lateral">
      <img class="logo-lateral-img" src="{{ asset('img/site/logo-md.gif') }}" title="{{ __('Go') }}" alt="{{ __('Go') }}">
      <img class="logo-banner-img" src="{{ asset('img/site/logo-sm-slim.gif') }}" title="{{ __('Go') }}" alt="{{ __('Go') }}">
    </div>
    <div class="col-md-7 sections text-center">
      @if(!$stablish->disabledGlobal && !$stablish->disabled)
        <!-- <div class="row">
          <div class="stablish-summary">
            <img src="{{ asset('img/site/stablishments/summary/'.$stablish->summary) }}" title="{{ $stablish->description }}" alt="{{ $stablish->name }}">
          </div>
        </div> -->

        <div class="row">
          <div class="col-12 mt-3 mb-3 text-center stablishButtons1">
            @if(floatval($stablish->lat) && floatval($stablish->lng))
              <a class="mr-4" href="{{ route('cercaDeTi', $stablish->idstablishment) }}">
                <img src="{{ asset('img/site/btn/btn-stab-ubicacion.png') }}" title="Cerca de ti">
              </a>
            @endif
            @if(count($jobs))
              <a class="mr-4" href="#" data-toggle="modal" data-target="#jobs-modal">
                <img src="{{ asset('img/site/btn/btn-stab-vacantes.png') }}" title="Vacantes">  
              </a>
            @endif
            <a id="btn-social" class="mr-4" href="#" data-toggle="modal" data-target="#window-modal">
              <img src="{{ asset('img/site/btn/btn-stab-web.png') }}" title="Redes Sociales">
            </a>
          </div>
        </div>

        <div class="row">
          <div class="col-12 col-md-7 mt-3 mb-3 mx-auto">
            <h1 class="text-white"><strong>{{ $stablish->name }}</strong></h1>
            <div class="text-left border text-white py-5 px-4 shadow menuCont">
              @if(count($menus))
                <div class="menuImgHeaderCont text-center mb-5">
                  <img style="border-radius: 10%" src="{{ asset($stablish->image) }}" alt="">
                </div>
                @foreach($menus as $menu)
                    <div class="mb-4">
                      <h3 class="border border-top-0 border-right-0 border-warning text-warning pb-2 pl-2">{{ $menu['menu']['name'] }}</h3>
                      <!-- <table class="menuTable">
                        @foreach($menu['products'] as $prod)
                          <tr>
                            <td>{{ $prod['name'] }}</td>
                            <td>- - - -</td>
                            <td>${{ $prod['price'] }}</td>
                            <td>
                              <div class="col-12 menuProdDesc {{ $prod['description'] ? '' : 'd-none' }}" data-desc="{{ $prod['description'] }}" title="Ver descripción del producto"><i class="fas fa-question-circle"></i></div>
                            </td>
                          </tr>
                        @endforeach
                      </table> -->
                      
                      <!-- <div>
                        <ul>
                          @foreach($menu['products'] as $prod)
                            <li class="row">
                              <div class="col-10 menuProd">{{ $prod['name'] }} - - - ${{ $prod['price'] }}</div>
                              <div class="col-2 menuProdDesc {{ $prod['description'] ? '' : 'd-none' }}" data-desc="{{ $prod['description'] }}" title="Ver descripción del producto"><i class="fas fa-question-circle"></i></div>
                            </li>
                          @endforeach
                        </ul>
                      </div> -->
                      <div>
                        <ul>
                          @foreach($menu['products'] as $prod)
                            <li class="row">
                              <div class="col-5 menuProd">{{ $prod['name'] }}</div>
                              <div class="col-4 menuDots"> </div>
                              <div class="col-2 menuPrice">${{ $prod['price'] }}</div>
                              <div class="col-1 menuProdDesc {{ $prod['description'] ? '' : 'd-none' }}" data-desc="{{ $prod['description'] }}" title="Ver descripción del producto"><i class="fas fa-question-circle"></i></div>
                            </li>
                          @endforeach
                        </ul>
                      </div>
                    </div>
                @endforeach
              @else
                <div class="mb-4">
                  <h3 class="border border-top-0 border-right-0 border-warning text-warning pb-2 pl-2">Próximamente más información.</h3>
                </div>
              @endif
            </div>
          </div>
        </div>

        @if(count($menuFile))
          <div class="row">
            <div class="col-12 col-md-7 mt-3 mb-3 mx-auto">
              <h1 class="text-white"><strong><a href="#" id="btn-pdf-menu" data-toggle="modal" data-target="#window-modal">Menú PDF</a></strong></h1>
              <div class="align-center" >
                <object id="menuFile" data='{{ asset("/storage/".$menuFile->path."/".$menuFile->pdf) }}' type='' width='100%' height='450'>
                  <p>Sin PDF del menú.</p>
                </object>
              </div>
              <br>
            </div>
          </div>
        @endif

        @if(count($gallery))
          <div class="row">
            <div class="col-12 col-md-7 mt-3 mb-3 mx-auto">
              <h1 class="text-white"><strong>Galería</strong></h1>
              <div class="align-center" >
                <ul class="gallery-list">
                  @forelse($gallery as $image)
                    <li>
                      <a class="gallery-list-a" href="#" data-img="{{ asset($image->path."/".$image->image).'?'.microtime() }}" data-name="{{ __("Galería") }}" data-toggle="modal" data-target="#gallery-modal">
                        <img src="{{ asset($image->path."/".$image->image) }}" title="{{ __("Galería") }}" alt="{{ __("Galería") }}">
                      </a>
                    </li>
                  @empty

                  @endforelse
                </ul>
              </div>
              <br>
            </div>
          </div>
        @endif

        @if($ads && count($ads))
          @foreach($ads as $ad)
            @php($style = "background: ".$adsBg[toKey($ad->name)]."; padding: 0.75rem 1.25rem; margin-bottom: 0; border-bottom: 1px solid rgba(0, 0, 0, 0.125);");
          <div class="row">
            <div class="col-md-12">
              <div class="card-transparent">
                <div class="" style="{{ $style }};">
                  {{ __($ad->name) }}
                </div>
                <div class="card-body">
                  {!! html_entity_decode($ad->description, ENT_QUOTES, 'UTF-8') !!}
                </div>
              </div>
            </div>
          </div>
          @endforeach
        @endif

        <div class="row">
          <div class="col-md-12 text-center">
            <ul class="btns-social">
              @if($stablish->facebook)
                <li><a href="https://{{ $stablish->facebook }}" target="_blank">
                  <img src="{{ asset('img/site/btn/icon-facebook-01.png') }}" alt="Facebook">
                </a></li>
              @endif
              @if($stablish->instagram)
                <li><a href="https://{{ $stablish->instagram }}" target="_blank">
                  <img src="{{ asset('img/site/btn/icon-instagram-01.png') }}" alt="Instagram">
                </a></li>
              @endif
              @if($stablish->youtube)
                <li><a href="https://{{ $stablish->youtube }}" target="_blank">
                  <img src="{{ asset('img/site/btn/icon-youtube-01.png') }}" alt="Youtube">
                </a></li>
              @endif
              @if($stablish->twitter)
                <li><a href="https://{{ $stablish->twitter }}" target="_blank">
                  <img src="{{ asset('img/site/btn/icon-twitter-01.png') }}" alt="Twitter">
                </a></li>
              @endif
            </ul>
          </div>
        </div>
      @else
        <div class="row">
          <div class="col-12 mt-3 mb-3">
            <div class="text-center">
              <h2 class="font-weight-bold text-white">Este sitio ha sido deshabilitado</h2>
            </div>
          </div>
        </div>
      @endif
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="gallery-modal" tabindex="-1" role="dialog" aria-labelledby="gallery-modal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content ads">
      <img id="gallery-img" src="" title="" alt="">
    </div>
    <div class="btn btn-black close-modal">CERRAR</div>
  </div>
</div>

@auth
  @if(!session('isStablishment') && $stablish->enablechat)
    <div id="btn-openChat" data-userStablishment="{{ Crypt::encryptString($stablish->user_id) }}" data-userClient="{{ Crypt::encryptString($idUser) }}">
      <div class="chat-icon">
        <i class="far fa-comment-dots"></i>
      </div>
      <div class="newMsgGeneralIcon">
        <i class="fa fa-envelope" aria-hidden="true" title="Nuevo mensaje"></i>
      </div>
    </div>

    <!-- INICIA VENTANA DE CHAT -->
    <div id="chatbox-client">
      <div id="chatview" class="p1">
        <div id="profile" class="animate">

          <div id="close">
            <div class="cy s1 s2 s3"></div>
            <div class="cx s1 s2 s3"></div>
          </div>
          <p class="animate">{{ $stablish->name }}</p>
          <span>{{ $stablish->hour }}</span>
        </div>

        <div id="chat-messages" class="animate">
          <label>Thursday 02</label>
        </div>
          
        <div id="sendmessage">
          <input type="text" value="Escribir mensage">
          <div class="d-inline" id="send">
            <i class="fas fa-paper-plane"></i>
          </div>
        </div>
      </div>
      <img src="{{ asset('img/site/stablishments/logos/'.$stablish->image) }}" class="floatingImg" style="top: 20px; height: 68px; width: 78px; left: 108px;">
    </div>
    <!-- TERMINA VENTANA DE CHAT -->
  @endif
@endauth

<!-- INICIA VENTANA MODAL GRAL -->
@if(is_array($menus) && count($menus))
<div class="modal fade" id="window-modal" tabindex="-1" role="dialog" aria-labelledby="window-modal" aria-hidden="true">
  <div class="modal-dialog text-center" role="document">
    <div class="modal-content modal-content-gray">
      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <div class="row">
          <div class="col-md-12 mb-5">
            <div class="mt-4 text-left">
              <div class="modal-title"> </div>
              <div class="modal-body">
                CARGANDO CONTENIDO...
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endif
<!-- TERMINA VENTANA MODAL GRAL -->

<!-- INICIA VENTANA DE VACANTES -->
<div class="modal fade" id="jobs-modal" tabindex="-1" role="dialog" aria-labelledby="jobs-modal" aria-hidden="true">
  <div class="modal-dialog text-center" role="document">
    <div class="modal-content modal-content-gray">
      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
        @if(count($jobs))
        <div class="row">
          <div class="col-md-12 mb-5">
            @forelse($jobs as $job)
              <div class="job mt-4 text-left">
                <div class="job-title">
                  {{ $job->name }}
                </div>
                <div class="job-content">
                  {!! html_entity_decode($job->description, ENT_QUOTES, 'UTF-8') !!}
                  <hr>
                  <p>Presentar: {{ $job->documentation }}</p>
                </div>
              </div>
            @empty
            @endforelse
          </div>
        </div>
        @endif
      </div>
    </div>
    <div class="btn btn-black close-modal mt-4">CERRAR</div>
  </div>
</div>
<!-- TERMINA VENTANA DE VACANTES -->

<!-- INICIA ICONOS DE COMPARTIR Y WHATSAPP -->
<div id="buttonsFloats" class="row">
  <div class="col-12 mb-4">
    <!-- <a href="https://api.whatsapp.com/send?phone=0123456789">Envíanos un mensaje de WhatsApp</a> -->
    <!-- <a href="https://api.whatsapp.com/send?phone=0123456789&text=Hola, Nececito mas informacion!">Envíanos un mensaje de WhatsApp</a> -->
    <!-- <a href="whatsapp://send?text=Hola, Index.pe&phone=+12 346 678 910&abid=+12 346 678 910">+12 346 678 910</a> -->
    @if($stablish->whatsapp)
      <a href="https://api.whatsapp.com/send?phone=52{{ $stablish->whatsapp }}" target="_blank">
        <img src="{{ asset('img/site/btn/icon-whatsapp-01.png') }}" alt="Whatsapp">
      </a>
    @endif    
  </div>
  <div class="col-12">
    <a class="btnShare" href="#">
      <img src="{{ asset('img/site/btn/btn-share.png') }}" title="Compartir">
    </a>
  </div>
</div>
<!-- TERMINA ICONOS DE COMPARTIR Y WHATSAPP -->

@push('scripts')
<script type="application/javascript">
  window.addEventListener('load', function() {
    go.redimentions(false);
    go.initStablishment();
    gallery.initStabGallery();

    let cfgSocial ={
      assets: "{{ asset('img') }}",
      hashStab: "{{ $stablish->hashStab ? $stablish->hashStab : "" }}",
      facebook: "{{ $stablish->facebook ? $stablish->facebook : "" }}",
      instagram: "{{ $stablish->instagram ? $stablish->instagram : "" }}",
      twitter: "{{ $stablish->twitter ? $stablish->twitter : "" }}",
      youtube: "{{ $stablish->youtube ? $stablish->youtube : "" }}",
      web: "{{ $stablish->web ? $stablish->web : "" }}",
    };
    social.initStabSocial(cfgSocial);

    @if(count($menuFile))
      let cfgPdfMenu ={
        pathPdfMenu: "{{ asset("/storage/".$menuFile->path."/".$menuFile->pdf) }}",
      };
      menus.initStabMenu(cfgPdfMenu);
    @endif

    // 
    @auth
      @unless(session('isStablishment'))
        let cfg = {
          type: 'clientToStablishment',
          urlLoadAllMessages: '{{ route('chat.loadAllMessages') }}',
          urlLoadMessages: '{{ route('chat.loadMessages') }}',
          urlSaveMessage: '{{ route('chat.messageSave') }}',
          urlLoadNewMsgGeneral: '{{ route('chat.loadNewMsgGeneral') }}',
          img: '{{ asset('img') }}',
          logo: '{{ $stablish->image }}',
        };
        chatClient.init(cfg);
      @endunless
    @endauth
  });
</script>
@endpush

@endsection