@extends('layouts.site')
@section('title', 'Mi espacio')
@section('css')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/2.0.0-alpha.2/cropper.min.css" integrity="sha512-6QxSiaKfNSQmmqwqpTNyhHErr+Bbm8u8HHSiinMEz0uimy9nu7lc/2NaXJiUJj2y4BApd5vgDjSHyLzC8nP6Ng==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('js')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/2.0.0-alpha.2/cropper.min.js" integrity="sha512-IlZV3863HqEgMeFLVllRjbNOoh8uVj0kgx0aYxgt4rdBABTZCl/h5MfshHD9BrnVs6Rs9yNN7kUQpzhcLkNmHw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="{{ asset('js/menus.js') }}"></script>
  <script src="{{ asset('js/stab.js') }}"></script>
@endsection

@section('returnBtn', route('home'))

@section('content')
<div class="container-fluid go-wallpaper">
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-5 text-left logo-lateral">
      <img class="logo-lateral-img" src="{{ asset('img/site/logo-md.gif') }}" title="{{ __('Go') }}" alt="{{ __('Go') }}">
      <img class="logo-banner-img" src="{{ asset('img/site/logo-sm-slim.gif') }}" title="{{ __('Go') }}" alt="{{ __('Go') }}">
    </div>
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 col-xl-7 sections">
    @if($iAmStab && is_object($myStab))
      <div id="buttons-seccions">
        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3 mb-2">
            <div class="text-center">
              <a class="btn btn-line" href="#">{{ __('Visitas').': '.$myStab['range'] }}</a>
            </div>
          </div>

          <!--
          <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 mb-2">
            <div class="text-center">
              <a class="btn btn-purple" href="#" data-toggle="modal" data-target="#addVacant-modal">{{ __('Agregar vacante') }}</a>
            </div>
          </div>

          <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 mb-2">
            <div class="text-center">
              <a class="btn btn-purple" href="#" data-toggle="modal" data-target="#addAd-modal">{{ __('Agregar anuncio') }}</a>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3 mb-2">
            <div class="text-center">
              <a class="btn btn-purple" href="{{ route('stablishment', $myStab['idstablishment']) }}">{{ __('Ir a publicación') }}</a>
            </div>
          </div>

          <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 mb-2">
            <div class="text-center">
              <a class="btn btn-purple" href="{{ route('home') }}">{{ __('Inicio') }}</a>
            </div>
          </div>

          <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 mb-2">
            <div class="text-center">
              <a id="btnChat" class="btn btn-purple" data-stab="{{ Crypt::encryptString($myStab['idstablishment']) }}">{{ __($chat ? 'Desactivar chat':'Activar chat') }}</a>
            </div>
          </div>
          -->
        </div>

        <!--
        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3 mb-2">
            <div class="text-center">
              <a id="btn-stab" class="btn btn-purple btn-menu" href="#" data-toggle="modal" data-target="#window-modal">{{ __('Datos de empresa') }}</a>
            </div>
          </div>
          <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3 mb-2">
            <div class="text-center">
              <a id="btn-menus" class="btn btn-purple btn-menu" href="#" data-toggle="modal" data-target="#window-modal">{{ __('Menus') }}</a>
            </div>
          </div>
        </div>
        -->

        <div class="row mt-5">
          <div class="col-6 col-md-3 mb-4">
            <a href="{{ route('home') }}">
              <div class="card btnTable">
                <div class="btnTableImgCont">
                  <img src="{{ asset('img/site/btn/btn-myspace-home.png') }}" class="card-img-top" alt="Inicio">
                </div>
                <div>
                  <h5 class="p-3 text-center">Ir al inicio</h5>
                </div>
              </div>
            </a>
          </div>
          <div class="col-6 col-md-3 mb-4">
            <a href="{{ route('stablishment', $myStab['idstablishment']) }}">
              <div class="card btnTable">
                <div class="btnTableImgCont">
                  <img src="{{ asset('img/site/btn/btn-myspace-x1.png') }}" class="card-img-top" alt="Publicación">
                </div>
                <div>
                  <h5 class="p-3 text-center">Ver mis publicaciones</h5>
                </div>
              </div>
            </a>
          </div>
          <div class="col-6 col-md-3 mb-4">
            <div id="btn-stab" class="card btnTable">
              <div class="btnTableImgCont">
                <img src="{{ asset('img/site/btn/btn-myspace-stablishment.png') }}" class="card-img-top" alt="Empresa">
              </div>
              <div>
                <h5 class="p-3 text-center">Editar datos de empresa</h5>
              </div>
            </div>
          </div>
          <div class="col-6 col-md-3 mb-4">
            <div id="btn-menus" class="card btnTable">
              <div class="btnTableImgCont">
                <img src="{{ asset('img/site/btn/btn-myspace-menus.png') }}" class="card-img-top" alt="Menus">
              </div>
              <div>
                <h5 class="p-3 text-center">Editar mis Menus</h5>
              </div>
            </div>
          </div>
          <div class="col-6 col-md-3 mb-4">
            <div class="card btnTable" data-toggle="modal" data-target="#addAd-modal">
              <div class="btnTableImgCont">
                <img src="{{ asset('img/site/btn/btn-myspace-addAd.png') }}" class="card-img-top" alt="Anuncio">
              </div>
              <div>
                <h5 class="p-3 text-center">Crear nuevos anuncios</h5>
              </div>
            </div>
          </div>
          <div class="col-6 col-md-3 mb-4">
            <div class="card btnTable" data-toggle="modal" data-target="#addVacant-modal">
              <div class="btnTableImgCont">
                <img src="{{ asset('img/site/btn/btn-myspace-vacant.png') }}" class="card-img-top" alt="Vacantes">
              </div>
              <div>
                <h5 class="p-3 text-center">Publicar vacantes</h5>
              </div>
            </div>
          </div>
          <!-- <div class="col-6 col-md-3 mb-4">
            <div class="card btnTable" data-stab="{{ Crypt::encryptString($myStab['idstablishment']) }}">
              <div class="btnTableImgCont">
                <img src="{{ asset('img/site/btn/btn-myspace-chat.png') }}" class="card-img-top" alt="Activar chat">
              </div>
              <div>
                <h5 class="p-3 text-center">Activar chat</h5>
              </div>
            </div>
          </div> -->
        </div>

        <br>
      </div>
    @endif
      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
          <div class="text-center">
            <a class="btn btn-line" href="#">{{ __('Elige una opción') }}</a>
          </div>
        </div>
      </div>
      <br>
      <div class="overflow-auto stablish-cont">
        <div class="row stablishments">
          @forelse($mySpace as $my)
            <div id="{{ md5($my->idmystablishment) }}-cont" class="mySpace-stablish-cont mb-3">
              <div class="mySpace-stablish-body">
                <div class="mySpace-stablish-body-img">
                  <a href="{{ route('stablishment', $my->idstablishment) }}">
                    <img src="{{ asset('img/site/stablishments/logos/'.$my->image) }}" title="{{ __($my->description) }}" alt="{{ __($my->name) }}">
                  </a>
                </div>
              </div>
              <div class="mySpace-stablish-footer">
                @if($my->offer)
                  <img src="{{ asset('img/site/btn/offer-01.png') }}" title="{{ __('Oferta disponible') }}" alt="{{ __('Oferta disponible') }}">
                @endif
                @auth
                  <a href="#">
                    <img class="stablish-del" src="{{ asset('img/site/btn/btn-del-01.png') }}" title="{{ __('Eliminar') }}" alt="{{ __('Eliminar') }}" data-id="{{ md5($my->idmystablishment) }}" data-stab="{{ Crypt::encryptString($my->idmystablishment) }}" data-name="{{ Crypt::encryptString($my->name) }}">
                  </a>
                @endauth
              </div>
            </div>
          @empty
          @endforelse
        </div>
      </div>
    </div>
  </div>
</div>


@if($iAmStab)
<div class="modal fade" id="window-modal" tabindex="-1" role="dialog" aria-labelledby="window-modal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">CARGANDO CONTENIDO...</h5>
        @if(count($menus))
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        @endif
      </div>
      <div class="modal-body">
        CARGANDO CONTENIDO...
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addStab-modal" tabindex="-1" role="dialog" aria-labelledby="addStab-modal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="image-modal">NEGOCIO</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
          <form id="stabFrm" action="">
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
              <label for="logotipo">{{ __('Logotipo (Dimenciones entre 90px y 110px de ancho y 55px y 75px de alto. Peso máximo de 200kb.)') }}</label>
              <input type="file"  class="form-control-file" id="logotipo" name="logotipo">
              {!! $errors->first('logotipo', '<div class="invalid-feedback" style="display: block;">:message</div>') !!}
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
          </form>
        </div>
      </div>
      <div class="modal-footer">
        <button id="addStab" class="btn btn-primary">{{ __('Crear') }}</button>
        <button class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
@endif

@if($iAmStab && is_object($myStab))
<!-- Modal -->
<div class="modal fade" id="addVacant-modal" tabindex="-1" role="dialog" aria-labelledby="addVacant-modal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="image-modal">VACANTES</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
          <form id="jobsFrm" action="">
            <div class="form-group">
              <label for="vacante">{{ __('Puesto') }} <span  class="text-danger font-weight-bolder">*</span></label>
              <input type="text" class="form-control" id="vacante" name="vacante" value="{{ old('vacante') }}" placeholder="Nombre de la vacante">
              {!! $errors->first('vacante', '<div class="invalid-feedback" style="display: block;">:message</div>') !!}
            </div>
            <div class="form-group">
              <label for="descripcion">{{ __('Descripción de la vacante (requisitos)') }}</label>
              <textarea class="form-control" id="descripcion" name="descripcion" rows="3" placeholder="Descripción del anuncio">{{ old('descripcion') }}</textarea>
              {!! $errors->first('descripcion', '<div class="invalid-feedback" style="display: block;">:message</div>') !!}
            </div>
            <div>Presentar:</div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="doc" id="solicitud" value="solicitud" checked>
              <label class="form-check-label" for="solicitud">
                Solicitud de empleo
              </label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="doc" id="cv" value="cv">
              <label class="form-check-label" for="cv">
                CV (Curriculum Vitae)
              </label>
            </div>
            <input type="hidden" id="stab" name="stab" value="{{ Crypt::encryptString($myStab['idstablishment']) }}">
            <input type="hidden" id="job" name="job" value="" class="reset">
            <br><br>
            <div class="form-group">
              <a href="#" id="addJob" class="btn btn-primary">{{ __('Agregar') }}</a>
              <input type="reset" class="btn btn-primary" value="{{ __('Limpiar formulario') }}" data-frm="jobsFrm">
            </div>
          </form>

          <hr>
          <div class="table-responsive">
            <h3>Tus vacantes</h3>
            <table id="jobsTable" class="table table-striped table-hover">
              <thead class="thead-light">
                <tr>
                  <th scope="col">
                    {{ __('Elegir todo') }}<br>
                    <div class="form-check">
                      <input class="form-check-input position-static" type="checkbox" id="checkAll" value="">
                    </div>
                  </th>
                  <th scope="col">{{ __('Puesto') }}</th>
                  <th scope="col">{{ __('Descripción') }}</th>
                  <th scope="col">{{ __('Presentar') }}</th>
                  <th scope="col">{{ __('Editar') }}</th>
                  <th scope="col">{{ __('Eliminar') }}</th>
                </tr>
              </thead>
              <tbody>
                @forelse($myJobs as $job)
                  <tr id="{{ md5($job['idjob']) }}">
                    <td>
                      <div class="form-check">
                        <input class="form-check-input position-static" type="checkbox" value="{{ $job->idjob }}" name="check">
                      </div>
                    </td>
                    <td>{{ $job->name }}</td>
                    <td>{!! html_entity_decode($job->description, ENT_QUOTES, 'UTF-8') !!}</td>
                    <td>{{ $job->documentation }}</td>
                    <td><a href="" id="updJob" class="btn btn-outline-success" data-job="{{ Crypt::encryptString($job['idjob']) }}" data-tr="{{ md5($job->idjob) }}" data-name="{{ $job->name }}">Editar</a></td>
                    <td><a href="" id="delJob" class="btn btn-outline-danger" data-job="{{ Crypt::encryptString($job['idjob']) }}" data-tr="{{ md5($job->idjob) }}" data-name="{{ $job->name }}">Eliminar</a></td>
                  </tr>
                @empty
                <tr id="tr-none">
                  <td scope="row" colspan="10"><h4 class="text-center">SIN REGISTROS</h4></td>
                </tr>                  
              @endforelse
              </tbody>
            </table>
          </div>

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addAd-modal" tabindex="-1" role="dialog" aria-labelledby="addAd-modal" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="image-modal">ANUNCIO</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
          <form id="adsFrm" action="">
            <div class="form-group">
              <label for="descripcionAd">{{ __('Descripción del anuncio') }}</label>
              <textarea class="form-control" id="descripcionAd" name="descripcionAd" rows="3" placeholder="Descripción del anuncio">{!! isset($myAds->description) && $myAds->description ? html_entity_decode($myAds->description, ENT_QUOTES, 'UTF-8') : '' !!}</textarea>
              {!! $errors->first('descripcionAd', '<div class="invalid-feedback" style="display: block;">:message</div>') !!}
            </div>
            <input type="hidden" name="stab" value="{{ Crypt::encryptString($myStab['idstablishment']) }}">
            <input type="hidden" id="ad" name="ad" class="reset" value="{{ isset($myAds['idad']) && $myAds['idad'] ? Crypt::encryptString($myAds['idad']) : '' }}">
            <br><br>
            <div class="form-group">
              <a href="#" id="addAd" class="btn btn-primary">{{ __('Publicar') }}</a>
              <input type="button" class="btn btn-primary" id="delAd" value="{{ __('Borrar') }}">
            </div>
          </form>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
@endif

@push('scripts')
<script type="application/javascript">
window.addEventListener('load', function() {
  $(document).ready(function() {
    let cfg = {
      showBtnHelp: false,
      urlTags: '{{ route("loadRegisterTags") }}',
      urlAjax: '{{ route("delStablishment") }}'
    };
    //let cfgMenus = {};
    @if($iAmStab)
      cfg.urlAddStab = '{{ route("myspace.addStab") }}';

      cfg.jobsSection = true;
      cfg.urlAddJob = '{{ route("myspace.addJob") }}';
      cfg.urlUpdJob = '{{ route("myspace.updJob") }}';
      cfg.urlDelJob = '{{ route("myspace.delJob") }}';
      cfg.urlMyJobs = '{{ route("myspace.myJobs") }}';

      cfg.urlAddAd = '{{ route("myspace.addAd") }}';
      //cfg.urlUpdAd = '';
      cfg.urlDelAd = '{{ route("myspace.delAd") }}';
      cfg.urlMyDatas = '{{ route("myspace.myDatas") }}';
      cfg.chat = '{{ $chat ? 'true' : 'false' }}';
      cfg.urlEnableChat = '{{ route("myspace.enableChat") }}';
      
      // configuración para el js de menus.js
      cfg.menu = {};
      cfg.menu.haveMenus = "{{ count($menus) }}";
      cfg.menu.urlLoadMenus = '{{ route("myspace.loadMenus") }}';
      cfg.menu.urlAddMenu = '{{ route("myspace.addMenu") }}';
      cfg.menu.urlLoadProducts = '{{ route("myspace.loadProducts") }}';
      cfg.menu.urlDelProduct = '{{ route("myspace.delProduct") }}';
      //menus.init(cfgMenus);

      // configuración para el js del establecimiento.js
      cfg.stab = {};
      cfg.stab.urlAsset = "{{ asset("/") }}"
      cfg.stab.urlLoadStab = '{{ route("myspace.loadStab") }}';
      cfg.stab.urlUpdateStablishment = '{{ route("myspace.updateStablishment") }}';
    @endif
    go.initStablisments(cfg);

  });
});
</script>
@endpush

@endsection