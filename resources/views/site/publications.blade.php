@extends('layouts.publications')
@section('title', 'Publicaciones')
@section('returnBtn', route('home'))

@section('content')

<div class="container-fluid go-publications">
  <div class="row">
    <div class="col-12">
      <section id="publications">
        <div class="row">

          @forelse($publications as $pub)
            @php($idPub = $pub->idPublication)
            @php($text = $pub->synopsis ? $pub->synopsis : $pub->description)
            <div class="pub-item container">
              <div class="col-12 col-md-3 imagen-fondo" style="background-image: url('{{ asset("/storage/".$pub->image)  }}');" alt="{{ $pub->title }}" title="{{ $pub->title }}"></div>
              <div class="col-12 col-md-9 contenido">
                <h3 class="titulo"> <a href="{{ route('publication', $pub) }}">{{ $pub->title }}</a></h3>
                <div class="autor-date">
                  <span class="autor">Por: {{ $pub->pseudonym ? $pub->pseudonym : $pub->pseudonym }}</span> -
                  <span class="date">{{ date('d/m/Y', strtotime($pub->datetime)) }}</span> -
                  <span class="hour">{{ date('H:i', strtotime($pub->datetime)) }}</span>
                </div>
                <div class="synopsis">{{ (strlen($text) > 250) ? substr($text, 0, 250) . '...' : $text }}</div>
                <div class="interactions">
                  <span>Visitas: {{ $pub->visits ? $pub->visits : 0 }}</span>
                  <span>Me gusta: {{ $pub->likes ? $pub->likes : 0 }}</span>
                </div>
              </div>
            </div>     
          @empty
            <h3 class="m-auto">SIN PUBLICACIONES</h3>                 
          @endforelse
          

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