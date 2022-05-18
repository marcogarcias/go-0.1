@extends('layouts.site')
@section('title', 'Registrarse')
@section('css')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/2.0.0-alpha.2/cropper.min.css" integrity="sha512-6QxSiaKfNSQmmqwqpTNyhHErr+Bbm8u8HHSiinMEz0uimy9nu7lc/2NaXJiUJj2y4BApd5vgDjSHyLzC8nP6Ng==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="{{ asset('css/register.css?').microtime() }}" rel="stylesheet">
@endsection


@section('js')
  <script src="{{ asset('libs/Parsley.js-2.9.2/dist/parsley.min.js') }}" defer></script>
  <script src="{{ asset('libs/Parsley.js-2.9.2/dist/i18n/es.js') }}" defer></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/2.0.0-alpha.2/cropper.min.js" integrity="sha512-IlZV3863HqEgMeFLVllRjbNOoh8uVj0kgx0aYxgt4rdBABTZCl/h5MfshHD9BrnVs6Rs9yNN7kUQpzhcLkNmHw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="{{ asset('js/stab.js') }}"></script>
@endsection

@section('content')
<div class="container-fluid go-wallpaper">
  <div class="row">
    <div class="col-md-5 text-left logo-lateral">
      <img class="logo-lateral-img" src="{{ asset('img/site/logo-md.gif') }}" title="{{ __('Go') }}" alt="{{ __('Go') }}">
      <img class="logo-banner-img" src="{{ asset('img/site/logo-sm-slim.gif') }}" title="{{ __('Go') }}" alt="{{ __('Go') }}">
    </div>
    <div class="col-md-7">
      <div class="row">
        <div class="col-md-7 offset-md-2">
          
          <div class="card register">
            <div class="card-header">{{ __('Registro') }}</div>
            <div class="card-body">
              <form id="frmRegister" method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                @csrf

                <div class="form-group row">
                  <div class="col-3 offset-1 mr-3">
                    <input class="form-check-input userType" type="radio" name="type" id="user" value="user">
                    <label class="form-check-label" for="user">
                      Usuario
                    </label>
                  </div>
                  <div class="col-3">
                    <input class="form-check-input userType" type="radio" name="type" id="stablishment" value="stablishment" checked>
                    <label class="form-check-label" for="stablishment">
                      Empresa
                    </label>
                  </div>
                </div>

                <div class="form-group row">
                  <div id="userTypeAlert" class="alert alert-warning" role="alert">
                    <span></span>
                    <ul></ul>
                  </div>
                </div>

                <div class="form-group">
                  <label for="name">{{ __('Nombre') }}</label>
                  <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" autocomplete="name" data-parsley-minlength="5" data-parsley-maxlength="155" autofocus required>
                  @error('name')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="email">{{ __('Correo electrónico') }}</label>
                  <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" data-parsley-minlength="10" data-parsley-maxlength="155" required>
                  @error('email')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="zone">{{ __('Zona') }}</label>
                  <select id="zone" class="form-control filter @error('zone') is-invalid @enderror" name="zone" required>
                    @forelse($zones as $zona)
                      <option value="{{ $zona['idzone'] }}">{{ __($zona['name']) }}</option>
                    @empty
                    @endforelse
                  </select>
                  @error('zone')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="password">{{ __('Contraseña') }}</label>
                  <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password" data-parsley-minlength="8" data-parsley-maxlength="155" required>
                  @error('password')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="password-confirm">{{ __('Confirmar contraseña') }}</label>
                  <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password" data-parsley-minlength="8" data-parsley-maxlength="155" required>
                </div>

                <hr>

                <div id="stablishmentForm">
                  <h3>Datos de empresa</h3>

                  <div class="form-group">
                    <label for="nameStab">{{ __('Nombre') }} <span  class="text-danger font-weight-bolder">*</span></label>
                    <input type="text" class="form-control" id="nameStab" name="nameStab" value="{{ old('nameStab') }}" placeholder="Nombre del establecimiento">
                    {!! $errors->first('nameStab', '<div class="invalid-feedback" style="display: block;">:message</div>') !!}
                  </div>
                  <div class="form-group">
                    <label for="descripcion">{{ __('Descripción') }} <span  class="text-danger font-weight-bolder">*</span></label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3" placeholder="Descripción del establecimiento">{{ old('descripcion') }}</textarea>
                    {!! $errors->first('descripcion', '<div class="invalid-feedback" style="display: block;">:message</div>') !!}
                  </div>
                  <div class="form-group">
                    <label for="descripcion2">{{ __('Descripción 2 (mapa)') }} <span  class="text-danger font-weight-bolder">*</span></label>
                    <input type="text" class="form-control" id="descripcion2" name="descripcion2" value="{{ old('descripcion2') }}" placeholder="Descripción para el mapa">
                    {!! $errors->first('descripcion2', '<div class="invalid-feedback" style="display: block;">:message</div>') !!}
                  </div>
                  <div class="form-group">
                    <label for="direccion">{{ __('Dirección') }} <span  class="text-danger font-weight-bolder">*</span></label>
                    <input type="text" class="form-control" id="direccion" name="direccion" value="{{ old('direccion') }}" placeholder="Av. Villanueva, Col. San Juán, no. 55, C.P. 55450">
                    {!! $errors->first('direccion', '<div class="invalid-feedback" style="display: block;">:message</div>') !!}
                  </div>
                  <div class="form-group">
                    <label for="latitud">{{ __('Latitud') }}</label>
                    <input type="text" class="form-control" id="latitud" name="latitud" value="{{ old('latitud') }}" placeholder="Latitud del establecimiento">
                    {!! $errors->first('latitud', '<div class="invalid-feedback" style="display: block;">:message</div>') !!}
                  </div>
                  <div class="form-group">
                    <label for="longitud">{{ __('Longitud') }}</label>
                    <input type="text" class="form-control" id="longitud" name="longitud" value="{{ old('longitud') }}" placeholder="Longitud del establecimiento">
                    {!! $errors->first('longitud', '<div class="invalid-feedback" style="display: block;">:message</div>') !!}
                  </div>
                  <div class="form-group">
                    <div class="col-8 col-md-3">
                      <div id="prev-logotipo" style="background-image: url(http://i.pravatar.cc/500?img=7) background-size: cover; background-repeat: no-repeat; background-position: center;"></div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="logotipo">{{ __('Logotipo (dimenciones entre 90px y 110px de ancho y 55px y 75px de alto. Peso máximo de 200kb.)') }}</label>
                    <input type="file"  class="form-control-file" id="logotipo" name="logotipo" accept=".png, .jpg, .jpeg">
                    <input type="hidden" id="logotipoBase64" name="logotipoBase64">
                    <div id="logotipo-error" class="error" style="display: block;"></div>
                  </div>
                  <div class="form-group">
                    <label for="telefono">{{ __('Teléfono') }} ({{ __('máximo 13 carácteres ') }})</label>
                    <input type="text" class="form-control" id="telefono" name="telefono" value="{{ old('telefono') }}" min="0" max="9999999999999" step="1" placeholder="5555555555">
                    {!! $errors->first('telefono', '<div class="invalid-feedback" style="display: block;">:message</div>') !!}
                  </div>
                  <div class="form-group">
                    <label for="whatsapp">{{ __('Whatsapp') }} ({{ __('máximo 13 carácteres ') }})</label>
                    <input type="text" class="form-control" id="whatsapp" name="whatsapp" value="{{ old('whatsapp') }}" min="0" max="9999999999999" step="1" placeholder="5555555555">
                    {!! $errors->first('whatsapp', '<div class="invalid-feedback" style="display: block;">:message</div>') !!}
                  </div>
                  <div class="form-group">
                    <label for="facebook">{{ __('Facebook') }}</label>
                    <input type="text" class="form-control" id="facebook" name="facebook" value="{{ old('facebook') }}" placeholder="Facebook">
                    {!! $errors->first('facebook', '<div class="invalid-feedback" style="display: block;">:message</div>') !!}
                  </div>
                  <div class="form-group">
                    <label for="instagram">{{ __('Instagram') }}</label>
                    <input type="text" class="form-control" id="instagram" name="instagram" value="{{ old('instagram') }}" placeholder="Instagram">
                    {!! $errors->first('instagram', '<div class="invalid-feedback" style="display: block;">:message</div>') !!}
                  </div>
                  <div class="form-group">
                    <label for="twitter">{{ __('Twitter') }}</label>
                    <input type="text" class="form-control" id="twitter" name="twitter" value="{{ old('twitter') }}" placeholder="Twitter">
                    {!! $errors->first('twitter', '<div class="invalid-feedback" style="display: block;">:message</div>') !!}
                  </div>
                  <div class="form-group">
                    <label for="youtube">{{ __('Youtube') }}</label>
                    <input type="text" class="form-control" id="youtube" name="youtube" value="{{ old('youtube') }}" placeholder="Youtube">
                    {!! $errors->first('youtube', '<div class="invalid-feedback" style="display: block;">:message</div>') !!}
                  </div>
                  <div class="form-group">
                    <label for="horario">{{ __('Hoario') }} <span  class="text-danger font-weight-bolder">*</span></label>
                    <input type="text" class="form-control" id="horario" name="horario" value="{{ old('horario') }}" placeholder="08:00 am - 08:00 pm">
                    {!! $errors->first('horario', '<div class="invalid-feedback" style="display: block;">:message</div>') !!}
                  </div>

                  <div class="form-group">
                    <div class="custom-control custom-switch">
                      <input type="checkbox" class="custom-control-input" id="oferta" name="oferta">
                      <label class="custom-control-label" for="oferta">{{ __('Oferta') }}</label>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="section">{{ __('Sección') }} <span  class="text-danger font-weight-bolder">*</span></label>
                    <select class="form-control" id="section" name="section">
                      <option value="">{{ __('Seleccione una opción') }}</option>
                    @forelse($sections as $sec)
                      <option value="{{ $sec['idsection'] }}" {{ old('section') == $sec['idsection'] ? 'selected' : '' }}>{{ __($sec['name']) }}</option>
                    @empty
                      
                    @endforelse
                    </select>
                    {!! $errors->first('section', '<div class="invalid-feedback" style="display: block;">:message</div>') !!}
                  </div>

                  <div id="tags" class="form-group"></div>
                  {!! $errors->first('tags', '<div class="invalid-feedback" style="display: block;">:message</div>') !!}
                </div>

                <div class="form-group row mb-0">
                  <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                      {{ __('Registrarse') }}
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal imagecrop fade" id="window-modal-croop" tabindex="-1" role="dialog" aria-labelledby="window-modal-croop" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Editar imagen</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="img-container">
          <div class="row">
            <div class="col-md-11">
              <img id="image" src="" style="display: block; max-width: 100%;">
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary crop" id="crop">Recortar imagen</button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script type="application/javascript">
  window.addEventListener('load', function() {
    go.redimentions(false);
    let cfg = {
      tagsurl: '{{ route('loadRegisterTags') }}',
    };
    go.registerInit(cfg);
  });
</script>
@endpush

@endsection
