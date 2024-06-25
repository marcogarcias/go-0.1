@extends('layouts.publications')
@section('title', 'Contáctanos')
@section('returnBtn', route('/'))

@section('js')
<script src="{{ asset('libs/Parsley.js-2.9.2/dist/parsley.min.js') }}" defer></script>
<script src="{{ asset('libs/Parsley.js-2.9.2/dist/i18n/es.js') }}" defer></script>
<script src="{{ asset('js/contact.js') }}" defer></script>
@endsection

@section('content')

<div class="container-fluid go-publications">
  <div class="row">
    <div class="col-12">
      <section id="publications" class="contact">
        <div class="row">
          <div class="col-12 col-md-10 m-auto">
            <section>
              <h1>CONTÁCTANOS</h1>
              <h2>En SOMOS GO  ¡Queremos escucharte!</h2>
              <hr>
              <div id="formImgCont">
                <form id="formCont">
                  <p>
                    Ponte en contacto con nosotros. Ya sea que tengas una pregunta o una sugerencia, estamos aquí para ti.
                  </p>
                  <div class="form-group">
                    <input type="text" class="form-control" id="name" placeholder="Nombre" data-parsley-minlength="2" data-parsley-maxlength="50" data-parsley-errors-container="#error-parsley-name">
                    <div id="error-parsley-name"></div>
                  </div>
                  <div class="form-group">
                    <input type="email" class="form-control" id="email" placeholder="Email" data-parsley-minlength="5" data-parsley-maxlength="50" data-parsley-errors-container="#error-parsley-email">
                    <div id="error-parsley-email"></div>
                  </div>
                  <div class="form-group">
                    <textarea class="form-control" id="message" rows="3" placeholder="Mensaje, opinión, etc." data-parsley-minlength="10" data-parsley-maxlength="250" data-parsley-errors-container="#error-parsley-message" required></textarea>
                    <div id="error-parsley-message"></div>
                  </div>
                  <button id="btnContactForm" class="btn btn-primary" type="submit">Enviar comentarios</button>
                </form>
                <div id="imgCont">
                  <img src="{{ asset('img/site/logo-only-03.png') }}" alt="Somos Go" title="Somos Go">
                </div>
              </div>
            </section>
          </div>
        </div>        
      </section>
    </div>
  </div>
</div>


@push('scripts')
<script type="application/javascript">
window.addEventListener('load', function() {
  $(document).ready(function() {
    let cfg = {
      asset: '{{ asset('/') }}',
      urlSendContact: '{{ route("sendContact") }}'
    };
    goContact.init(cfg);
  });
});
</script>
@endpush

@endsection