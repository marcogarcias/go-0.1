@extends('layouts.admin')
@section('title', 'Administración - Editar anuncio')
@section('scripts')
<script src="{{ asset('js/admin.advertisement.js') }}"></script>
@endsection

@section('content')

<div class="container">
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-10">
      <div class="card">
        <h5 class="card-header">EDITAR ANUNCIO</h5>
        <div class="card-body">

          <form method="POST" action="{{ route('admin.advertisements.update', $adv) }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            @if($errors->any())
              <div>
                <div class="alert alert-danger" role="alert">
                  ERROR. No se ha podido crear el anuncio. Revisa la información de cada campo.
                </div>
              </div>
            @endif
            <div class="form-group">
              <label for="nombre">{{ __('Nombre') }} <span  class="text-danger font-weight-bolder">*</span></label>
              <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre', $adv->name) }}" placeholder="Nombre del anuncio">
              {!! $errors->first('nombre', '<div class="invalid-feedback" style="display: block;">:message</div>') !!}
            </div>
            <div class="form-group">
              <label for="descripcion">{{ __('Descripción') }}</label>
              <textarea class="form-control" id="descripcion" name="descripcion" rows="3" placeholder="Descripción del anuncio">{{ old('descripcion', $adv->description) }}</textarea>
              {!! $errors->first('descripcion', '<div class="invalid-feedback" style="display: block;">:message</div>') !!}
            </div>
            <div class="form-group">
              <label for="datos">{{ __('Datos extra') }}</label>
              <input type="text" class="form-control" id="datos" name="datos" value="{{ old('datos', $adv->data) }}" placeholder="Datos extras del anuncio">
              {!! $errors->first('datos', '<div class="invalid-feedback" style="display: block;">:message</div>') !!}
            </div>
            <div class="form-group">
              <label for="notas">{{ __('Notas extra') }}</label>
              <input type="text" class="form-control" id="notas" name="notas" value="{{ old('notas', $adv->notes) }}" placeholder="Notas extra del anuncio">
              {!! $errors->first('notas', '<div class="invalid-feedback" style="display: block;">:message</div>') !!}
            </div>
            <div class="form-group">
              <div class="img-cont col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                <a href="#" data-toggle="modal" data-target="#imgImage">
                  <img class="" src="{{ asset('img/site/advertisements/'.$adv->image) }}" alt="{{ $adv->image }}" title="{{ $adv->image }}">
                </a>
              </div>
              <label for="image">{{ __('Imagen (peso máximo de 250 KB.)') }}</label>
              <input type="file"  class="form-control-file" id="image" name="image">
              {!! $errors->first('image', '<div class="invalid-feedback" style="display: block;">:message</div>') !!}
            </div>
            
            <input type="hidden" name="adv" value="{{$adv->idadvertisements}}">

            <hr>
            <button type="submit" class="btn btn-primary">{{ __('Editar') }}</button> <a class="btn btn-link" href="{{ route('admin.advertisements') }}">Regresar</a>
          </form>
        </div>
      </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="imgImage" tabindex="-1" role="dialog" aria-labelledby="image-modal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="image-modal">Anuncio</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center">
          <img class="" src="{{ asset('img/site/advertisements/'.$adv->image) }}" alt="{{ $adv->image }}" title="{{ $adv->image }}">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
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