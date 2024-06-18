@extends('layouts.publications')
@section('title', 'Inicio')
@section('returnBtn', route('/'))

@section('content')

<div class="container-fluid go-publications">
  <div class="row">
    <div class="col-12">
      <section id="publications" class="home">
        <div class="row">
          <div class="col-12 col-md-9">
            <section>
              <h1>SOMOS GO</h1>
              <h2>Bienvenido a SomosGo, el lugar donde México cobra vida.</h2>
            </section>
          </div>
          <div id="imgCont" class="col-12 col-md-3">
            <img src="{{ asset('img/site/logo-only-03.png') }}" alt="Somos Go" title="Somos Go">
          </div>
          <hr>
          <div class="col-12">
            <section>
              <p>
                Aquí encontrarás una ventana al fascinante mundo de las experiencias culturales, los eventos emocionantes y los destinos cautivadores que nuestro país tiene para ofrecer.
              </p>
              <p>
                En SomosGo, nos apasiona explorar y compartir lo mejor del patrimonio mexicano. Desde museos de vanguardia que desafían la imaginación hasta festivales vibrantes que celebran nuestras tradiciones, pasando por exposiciones cautivadoras que abren nuevas perspectivas y eventos únicos que mantienen vivo el espíritu de nuestras ciudades.
              </p>
            </section>
          </div>
          <div id="btnGo" class="col-12">
            <a href="{{ route('publications') }}" class="btn btn-black">Ver listado de lugares</a>
          </div>
        </div>        
      </section>
    </div>
  </div>
</div>


@push('scripts')
<script type="application/javascript">
  window.addEventListener('load', function() {
    /*
    go.publications.init();
    */
  });
</script>
@endpush

@endsection