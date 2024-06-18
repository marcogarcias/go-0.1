@extends('layouts.publications')
@section('title', '¿Quiénes somos?')
@section('returnBtn', route('/'))

@section('content')

<div class="container-fluid go-publications">
  <div class="row">
    <div class="col-12">
      <section id="publications" class="quienesSomos">
        <div class="row">
          <section>
            <h1>¿QUIÉNES SOMOS?</h1>
            <p>
              Bienvenidos a Somos Go, tu guía definitiva para descubrir los mejores lugares y eventos en tu ciudad. Somos un grupo de amigos unidos por la pasión de explorar y compartir las joyas ocultas y las maravillas que nuestra ciudad tiene para ofrecer. Desde museos y teatros hasta restaurantes y eventos especiales, nuestro objetivo es facilitarte la vida para que siempre sepas qué hacer en tus días de descanso.
            </p>
          </section>

          <section>
            <h1>NUESTRA MISIÓN</h1>
            <p>
              En Somos Go, creemos que cada día libre es una oportunidad para vivir nuevas experiencias y crear recuerdos inolvidables. Nuestra misión es proporcionarte la información más detallada y actualizada sobre los lugares y eventos más emocionantes. Queremos inspirarte a salir, explorar y disfrutar de lo mejor que tu ciudad tiene para ofrecer.
            </p>
          </section>

          <section>
            <h1>NUESTRA HISTORIA</h1>
            <p>
              Somos Go nació de nuestra propia necesidad de encontrar actividades interesantes y lugares únicos para visitar. Hemos recorrido la ciudad descubriendo rincones increíbles y asistiendo a eventos memorables. Decidimos compartir nuestras experiencias y conocimientos a través de esta plataforma para que más personas puedan disfrutar de la riqueza cultural y de entretenimiento que nos rodea.
            </p>
            <p>
              Síguenos en nuestras redes sociales y no te pierdas nuestras actualizaciones y recomendaciones. Juntos, haremos que cada día libre sea una oportunidad para descubrir algo nuevo y emocionante.
            </p>
            <p>
              Gracias por ser parte de esta aventura. ¡Vamos a explorar juntos!
            </p>
          </section>
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