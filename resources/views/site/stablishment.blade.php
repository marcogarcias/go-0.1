@extends('layouts.site')
@section('title', 'Resumen')

@section('css')
  <link href="{{ asset('css/chatbox_v1.css?').microtime() }}" rel="stylesheet">
@endsection

@auth
  @unless(session('isStablishment'))
    @section('js')
      <script src="{{ asset('js/chatbox_v1.js?').microtime() }}" defer></script>
    @endsection
  @endunless
@endauth

@section('content')
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'>

<div class="container-fluid go-wallpaper">
  <div class="row">
    <div class="col-md-5 text-left logo-lateral">
      <img class="logo-lateral-img" src="{{ asset('img/site/logo-md.gif') }}" title="{{ __('Go') }}" alt="{{ __('Go') }}">
      <img class="logo-banner-img" src="{{ asset('img/site/logo-sm-slim.gif') }}" title="{{ __('Go') }}" alt="{{ __('Go') }}">
    </div>
    <div class="col-md-7 sections text-center">
      <div class="row">
        <div class="stablish-summary">
          <img src="{{ asset('img/site/stablishments/summary/'.$stablish->summary) }}" title="{{ $stablish->description }}" alt="{{ $stablish->name }}">
          <!-- <embed src="{{ asset('img/site/stablishments/summary/negocio-1-summary.pdf') }}" type="application/pdf" width="100%" height="600px" /> -->
        </div>
      </div>

      <div class="row">
        <div class="col-md-12 mt-3 mb-3">
          <div class="text-center">
            <a class="btn btn-dark" href="{{ route('cercaDeTi', $stablish->idstablishment) }}">{{ __('Ir al mapa') }}</a>
          </div>
        </div>
      </div>

      @if(count($jobs))
      <div class="row">
        <div class="col-md-12 mt-3 mb-3">
          <div class="text-center">
            <a class="btn btn-dark" href="#" data-toggle="modal" data-target="#jobs-modal">{{ __('Únete a nuestro equipo') }}</a>
          </div>
        </div>
      </div>
      @endif

      @if($ads)
      <div class="row">
        <div class="col-md-12 mb-5">
          <div class="card-transparent">
            <div class="card-header">
              {{ __('Anuncios') }}
            </div>
            <div class="card-body">
              {!! html_entity_decode($ads->description, ENT_QUOTES, 'UTF-8') !!}
            </div>
          </div>
        </div>
      </div>
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
    </div>
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

@push('scripts')
<script type="application/javascript">
  window.addEventListener('load', function() {
    go.redimentions(false);
    console.log();
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