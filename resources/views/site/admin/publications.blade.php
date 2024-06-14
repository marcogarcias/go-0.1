@extends('layouts.admin')
@section('title', 'Administración - Publicaciones')

@section('css')
  <link href="{{ asset('libs/mapbox/mapbox-gl.css') }}" rel="stylesheet">
  <link href="{{ asset('libs/mapbox/mapbox-gl-geocoder.css') }}" rel="stylesheet">
@endsection

@section('scripts')
<script src="{{ asset('libs/Parsley.js-2.9.2/dist/parsley.min.js') }}" defer></script>
<script src="{{ asset('libs/Parsley.js-2.9.2/dist/i18n/es.js') }}" defer></script>
<script src="{{ asset('libs/mapbox/mapbox-gl.js') }}"></script>
<script src="{{ asset('libs/mapbox/mapbox-gl-geocoder.min.js') }}"></script>
<script src="{{ asset('js/admin.publications.js') }}"></script>
@endsection

@section('content')

<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-10 offset-md-1 offset-lg-1 offset-xl-1">


      <div class="card">
        <h5 class="card-header">PUBLICACIONES</h5>
        <div class="card-body">
          <div>
            <p>
              <a href="" id="btn-addPublication" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Agrega una nueva publicación.">Agregar</a>
              <a href="" class="btn btn-primary" id="eliminarAll" data-toggle="tooltip" data-placement="top" title="Elimina todas las publicaciones.">Eliminar</a>
              <a href="#" class="btn btn-primary" id="addVisitsAll" data-toggle="tooltip" data-placement="top" title="Agrega una visita a todas las publicaciones.">Agregar visitas</a>
            </p>
          </div>

          <div class="table-responsive">
            <table class="table table-striped table-hover">
              <thead class="thead-light">
                <tr>
                  <th scope="col">
                    {{ __('Elegir todo') }}<br>
                    <div class="form-check">
                      <input class="form-check-input position-static" type="checkbox" id="checkAll" value="">
                    </div>
                  </th>
                  <th scope="col">{{ __('Imagen') }}</th>
                  <th scope="col">{{ __('Título') }}</th>
                  <th scope="col">{{ __('Subtítulo') }}</th>
                  <th scope="col">{{ __('Pseudónimo') }}</th>
                  <th scope="col">{{ __('Fecha') }}</th>
                  <th scope="col">{{ __('Sinopsis') }}</th>
                  <th scope="col">{{ __('Likes') }}</th>
                  <th scope="col">{{ __('Visitas') }}</th>
                  <th scope="col">{{ __('Activo') }}</th>
                  <th scope="col">{{ __('Activo Admin') }}</th>
                  <th scope="col">{{ __('Municipio') }}</th>
                  <th scope="col">{{ __('Editar') }}</th>
                  <th scope="col">{{ __('Eliminar') }}</th>
                </tr>
              </thead>
              <tbody>
              @forelse($publications as $pub)
                @php($idPub = $pub->idPublication)
                <tr>
                  <td>
                    <div class="form-check">
                      <input class="form-check-input position-static" type="checkbox" value="{{ $idPub }}" name="check">
                    </div>
                  </td>
                  <td><img src="{{ asset($pub->image) }}" class="img-thumbnail" title="{{ __($pub->title) }}" alt="{{ __($pub->title) }}"></td>
                  <td>{{ $pub['title'] }}</td>
                  <td>{{ $pub['subtitle'] }}</td>
                  <td>{{ $pub['pseudonym'] }}</td>
                  <td>{{ date('d/m/Y', strtotime($pub['datetime'])) }}</td>
                  <td>{{ Str::words($pub['synopsis'], 15, '...') }}</td>
                  <td>{{ $pub['likes'] }}</td>
                  <td>{{ $pub['visits'] }}</td>
                  <td>
                    <div class="custom-control custom-switch">
                      <input type="checkbox" class="custom-control-input enabled-check" id="enabled-{{ md5($idPub) }}" name="enabled-{{ md5($idPub) }}" data-hash-pub="{{ Crypt::encryptString($idPub) }}" {{ $pub->disabled?"":"checked" }}>
                      <label class="custom-control-label" for="enabled-{{ md5($idPub) }}"></label>
                    </div>
                  </td>
                  <td>
                     <div class="custom-control custom-switch">
                      <input type="checkbox" class="custom-control-input enabled-check" id="enabledGlobal-{{ md5($idPub) }}" name="enabledGlobal-{{ md5($idPub) }}" data-hash-pub="{{ Crypt::encryptString($idPub) }}" {{ $pub->disabledGlobal?"":"checked" }}>
                      <label class="custom-control-label" for="enabledGlobal-{{ md5($idPub) }}"></label>
                    </div>
                  </td>
                  <td>
                    {{ $pub['municipio_id'] }}
                  </td>
                  <td><a href="" class="btn btn-outline-success btn-editar" data-hashpublication="{{ $pub['hashPublication'] }}">Editar</a></td>
                  <td>
                    <form method="POST" action="">
                      @csrf @method('DELETE')
                      <button type="submit" class="btn btn-outline-danger">Eliminar</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td scope="row" colspan="14"><h4 class="text-center">SIN REGISTROS</h4></td>
                </tr>                  
              @endforelse
              </tbody>
            </table>
          </div>

          <div id="paginate">
            {{ $publications->links() }}
          </div>

        </div>
      </div>


    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalGral" tabindex="-1" role="dialog" aria-labelledby="modalGral" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalGral-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modalGral-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" id="btn-savePublication" class="btn btn-primary">Publicar</button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script type="application/javascript">
  window.addEventListener('load', function() {
    let cfg = {
      //delurl: '{{ route("admin.stablishments.elimination") }}',
      //urlAddVisitsAll: '{{ route("admin.stablishments.addVisitsAll") }}',
      //urlEnabledGlobalStab: '{{ route("admin.stablishments.enabledGlobalStab") }}',
      asset: '{{ asset('') }}',
      urlAdd: '{{ route("admin.publications.store") }}',
      urlGetPublication: '{{ route("admin.publications.getPublications") }}',
      urlGetEstados: '{{ route("admin.publications.getEstados") }}',
      urlGetMunicipios: '{{ route("admin.publications.getMunicipios") }}',
      urlGetSections: '{{ route("admin.publications.getSections") }}',
      urlGetTags: '{{ route("admin.publications.getTags") }}',
    };
    admin.publications.init(cfg);
  });
</script>
@endpush

@endsection