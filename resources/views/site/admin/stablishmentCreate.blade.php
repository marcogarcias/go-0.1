@extends('layouts.admin')
@section('title', 'Administración - Crear establecimiento')
@section('scripts')
<script src="{{ asset('js/admin.stablishment.js') }}" defer></script>
@endsection

@section('content')

<div class="container">
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-10">
      <div class="card">
        <h5 class="card-header">NUEVO ESTABLECIMIENTO</h5>
        <div class="card-body">

          <form method="POST" action="{{ route('admin.stablishments.create') }}" enctype="multipart/form-data">
            @csrf
            @if($errors->any())
              <div>
                <div class="alert alert-danger" role="alert">
                  ERROR. No se ha podido crear el establecimiento. Revisa la información de cada campo.
                </div>
              </div>
            @endif
            <div class="form-group">
              <label for="nombre">{{ __('Nombre') }} <span  class="text-danger font-weight-bolder">*</span></label>
              <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre') }}" placeholder="Nombre del establecimiento">
              {!! $errors->first('nombre', '<div class="invalid-feedback" style="display: block;">:message</div>') !!}
            </div>
            <div class="form-group">
              <label for="descripcion">{{ __('Descripción') }} <span  class="text-danger font-weight-bolder">*</span></label>
              <textarea class="form-control" id="descripcion" name="descripcion" rows="3" placeholder="Descripción del establecimiento">{{ old('descripcion') }}</textarea>
              {!! $errors->first('descripcion', '<div class="invalid-feedback" style="display: block;">:message</div>') !!}
            </div>
            <div class="form-group">
              <label for="descripcion2">{{ __('Descripción 2 (mapa)') }}</label>
              <input type="text" class="form-control" id="descripcion2" name="descripcion2" value="{{ old('descripcion2') }}" placeholder="Descripción para el mapa">
              {!! $errors->first('descripcion2', '<div class="invalid-feedback" style="display: block;">:message</div>') !!}
            </div>
            <div class="form-group">
              <label for="direccion">{{ __('Dirección') }} <span  class="text-danger font-weight-bolder">*</span></label>
              <input type="text" class="form-control" id="direccion" name="direccion" value="{{ old('direccion') }}" placeholder="Dirección del establecimiento">
              {!! $errors->first('direccion', '<div class="invalid-feedback" style="display: block;">:message</div>') !!}
            </div>
            <div class="form-group">
              <label for="latitud">{{ __('Latitud') }} <span  class="text-danger font-weight-bolder">*</span></label>
              <input type="text" class="form-control" id="latitud" name="latitud" value="{{ old('latitud') }}" placeholder="Latitud del establecimiento">
              {!! $errors->first('latitud', '<div class="invalid-feedback" style="display: block;">:message</div>') !!}
            </div>
            <div class="form-group">
              <label for="longitud">{{ __('Longitud') }} <span  class="text-danger font-weight-bolder">*</span></label>
              <input type="text" class="form-control" id="longitud" name="longitud" value="{{ old('longitud') }}" placeholder="Longitud del establecimiento">
              {!! $errors->first('longitud', '<div class="invalid-feedback" style="display: block;">:message</div>') !!}
            </div>
            <div class="form-group">
              <label for="logotipo">{{ __('Logotipo (Dimenciones entre 90px y 110px de ancho y 55px y 75px de alto. Peso máximo de 200kb.)') }}</label>
              <input type="file"  class="form-control-file" id="logotipo" name="logotipo">
              {!! $errors->first('logotipo', '<div class="invalid-feedback" style="display: block;">:message</div>') !!}
            </div>
            <div class="form-group">
              <label for="resumen">{{ __('Resumen Logotipo (Dimenciones entre 890px y 910px de ancho y 950px y 970px de alto. Peso máximo de 500kb.)') }}</label>
              <input type="file" class="form-control-file" id="resumen" name="resumen">
              {!! $errors->first('resumen', '<div class="invalid-feedback" style="display: block;">:message</div>') !!}
            </div>
            <div class="form-group">
              <label for="telefono">{{ __('Teléfono') }} ({{ __('máximo 13 carácteres ') }})</label>
              <input type="text" class="form-control" id="telefono" name="telefono" value="{{ old('telefono') }}" min="0" max="9999999999999" step="1" placeholder="Teléfono">
              {!! $errors->first('telefono', '<div class="invalid-feedback" style="display: block;">:message</div>') !!}
            </div>
            <div class="form-group">
              <label for="whatsapp">{{ __('Whatsapp') }} ({{ __('máximo 13 carácteres ') }})</label>
              <input type="text" class="form-control" id="whatsapp" name="whatsapp" value="{{ old('whatsapp') }}" min="0" max="9999999999999" step="1" placeholder="Whattsapp">
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
              <label for="web">{{ __('Web oficial') }}</label>
              <input type="text" class="form-control" id="web" name="web" value="{{ old('web') }}" placeholder="web">
              {!! $errors->first('web', '<div class="invalid-feedback" style="display: block;">:message</div>') !!}
            </div>
            <div class="form-group">
              <label for="horario">{{ __('Hoario') }} <span  class="text-danger font-weight-bolder">*</span></label>
              <input type="text" class="form-control" id="horario" name="horario" value="{{ old('horario') }}" placeholder="horario del establecimiento">
              {!! $errors->first('horario', '<div class="invalid-feedback" style="display: block;">:message</div>') !!}
            </div>
            <div class="form-group">
              <label for="expiracion">{{ __('Expiración') }} <span  class="text-danger font-weight-bolder">*</span></label>
              <input type="text" class="form-control" id="expiracion" name="expiracion" value="{{ old('expiracion') }}" placeholder="Longitud del establecimiento">
              {!! $errors->first('expiracion', '<div class="invalid-feedback" style="display: block;">:message</div>') !!}
            </div>

            <div class="form-group">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="oferta" name="oferta">
                <label class="custom-control-label" for="oferta">{{ __('Oferta') }}</label>
              </div>
            </div>

            <div class="form-group">
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="deshabilitado" name="deshabilitado">
                <label class="custom-control-label" for="deshabilitado">{{ __('Deshabilitado') }}</label>
              </div>
            </div>

            <div class="form-group">
              <label for="zona">{{ __('Zona') }} <span  class="text-danger font-weight-bolder">*</span></label>
              <select class="form-control" id="zona" name="zona">
                <option value="">{{ __('Seleccione una opción') }}</option>
              @forelse($zones as $zona)
                <option value="{{ $zona['idzone'] }}">{{ __($zona['name']) }}</option>
              @empty
                
              @endforelse
              </select>
              {!! $errors->first('zona', '<div class="invalid-feedback" style="display: block;">:message</div>') !!}
            </div>

            <div class="form-group">
              <label for="section">{{ __('Sección') }} <span  class="text-danger font-weight-bolder">*</span></label>
              <select class="form-control" id="section" name="section">
                <option value="">{{ __('Seleccione una opción') }}</option>
              @forelse($sections as $sec)
                <option value="{{ $sec['idsection'] }}">{{ __($sec['name']) }}</option>
              @empty
                
              @endforelse
              </select>
              {!! $errors->first('section', '<div class="invalid-feedback" style="display: block;">:message</div>') !!}
            </div>

            <div id="tags" class="form-group"></div>

            <div class="form-group">
              <label for="visitas">{{ __('Visitas') }}</label>
              <input type="number" class="form-control" id="visitas" name="visitas" value="{{ old('visitas') }}" placeholder="0">
              {!! $errors->first('visitas', '<div class="invalid-feedback" style="display: block;">:message</div>') !!}
            </div>

            <div class="form-group">
              <label for="usuario">{{ __('Usuario') }}</label>
              <select class="form-control" id="usuario" name="usuario">
                <option value="">{{ __('Seleccione una opción') }}</option>
              @forelse($users as $user)
                <option value="{{ $user['id'] }}">{{ __($user['id'].' - '.$user['email']) }}</option>
              @empty
                
              @endforelse
              </select>
            </div>
            
            <hr>
            <button type="submit" class="btn btn-primary">{{ __('Crear') }}</button> <a class="btn btn-link" href="{{ route('admin.stablishments') }}">Regresar</a>
          </form>
        </div>
      </div>
    </div>
</div>

@push('scripts')
<script type="application/javascript">
  window.addEventListener('load', function() {
    let cfg = {
      tagsurl: '{{ route('loadTags') }}'
    };
    admin.stablishment.create.init(cfg);
  });
</script>
@endpush

@endsection