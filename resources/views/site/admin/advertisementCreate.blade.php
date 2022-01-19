@extends('layouts.admin')
@section('title', 'Administración - Crear anuncio')
@section('scripts')
<script src="{{ asset('js/admin.advertisement.js') }}"></script>
@endsection

@section('content')

<div class="container">
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-10">
      <div class="card">
        <h5 class="card-header">NUEVO ANUNCIO</h5>
        <div class="card-body">

          <form method="POST" action="{{ route('admin.advertisements.create') }}" enctype="multipart/form-data">
            @csrf
            @if($errors->any())
              <div>
                <div class="alert alert-danger" role="alert">
                  ERROR. No se ha podido crear el anuncio. Revisa la información de cada campo.
                </div>
              </div>
            @endif
            <div class="form-group">
              <label for="nombre">{{ __('Nombre') }} <span  class="text-danger font-weight-bolder">*</span></label>
              <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre') }}" placeholder="Nombre del anuncio">
              {!! $errors->first('nombre', '<div class="invalid-feedback" style="display: block;">:message</div>') !!}
            </div>
            <div class="form-group">
              <label for="descripcion">{{ __('Descripción') }}</label>
              <textarea class="form-control" id="descripcion" name="descripcion" rows="3" placeholder="Descripción del anuncio">{{ old('descripcion') }}</textarea>
              {!! $errors->first('descripcion', '<div class="invalid-feedback" style="display: block;">:message</div>') !!}
            </div>
            <div class="form-group">
              <label for="datos">{{ __('Datos extra') }}</label>
              <input type="text" class="form-control" id="datos" name="datos" value="{{ old('datos') }}" placeholder="Datos extras del anuncio">
              {!! $errors->first('datos', '<div class="invalid-feedback" style="display: block;">:message</div>') !!}
            </div>
            <div class="form-group">
              <label for="notas">{{ __('Notas extra') }}</label>
              <input type="text" class="form-control" id="notas" name="notas" value="{{ old('notas') }}" placeholder="Notas extra del anuncio">
              {!! $errors->first('notas', '<div class="invalid-feedback" style="display: block;">:message</div>') !!}
            </div>
            <div class="form-group">
              <label for="image">{{ __('Imagen (peso máximo de 250 KB.)') }}</label>
              <input type="file"  class="form-control-file" id="image" name="image">
              {!! $errors->first('image', '<div class="invalid-feedback" style="display: block;">:message</div>') !!}
            </div>
            
            <hr>
            <button type="submit" class="btn btn-primary">{{ __('Crear') }}</button> <a class="btn btn-link" href="{{ route('admin.advertisements') }}">Regresar</a>
          </form>
        </div>
      </div>
    </div>
</div>

@push('scripts')
<script type="application/javascript">
  window.addEventListener('load', function() {
    //let cfg = { };
    //admin.stablishment.create.init(cfg);
  });
</script>
@endpush

@endsection