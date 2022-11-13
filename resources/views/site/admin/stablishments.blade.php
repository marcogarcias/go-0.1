@extends('layouts.admin')
@section('title', 'Administración - Establecimientos')
@section('scripts')
<script src="{{ asset('js/admin.stablishment.js') }}"></script>
@endsection

@section('content')

<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-10 offset-md-1 offset-lg-1 offset-xl-1">


      <div class="card">
        <h5 class="card-header">ESTABLECIMIENTOS</h5>
        <div class="card-body">
          <div>
            <p>
              <a href="{{ route('admin.stablishments.create') }}" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Agrega un nuevo establecimiento.">Agregar</a>
              <a href="" class="btn btn-primary" id="eliminarAll" data-toggle="tooltip" data-placement="top" title="Elimina todos los establecimientos.">Eliminar</a>
              <a href="#" class="btn btn-primary" id="addVisitsAll" data-toggle="tooltip" data-placement="top" title="Agrega una visita a todos los establecimientos.">Agregar visitas</a>
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
                  <th scope="col">{{ __('Logo') }}</th>
                  <th scope="col">{{ __('Nombre del establecimiento') }}</th>
                  <th scope="col">{{ __('Dirección') }}</th>
                  <th scope="col">{{ __('Zona') }}</th>
                  <th scope="col">{{ __('Teléfono') }}</th>
                  <th scope="col">{{ __('Horario') }}</th>
                  <th scope="col">{{ __('Oferta') }}</th>
                  <th scope="col">{{ __('Deshabilitado') }}</th>
                  <th scope="col">{{ __('Sección') }}</th>
                  <th scope="col">{{ __('Alta') }}</th>
                  <th scope="col">{{ __('Activo') }}</th>
                  <th scope="col">{{ __('Editar') }}</th>
                  <th scope="col">{{ __('Eliminar') }}</th>
                </tr>
              </thead>
              <tbody>
              @forelse($stabs as $stab)
                @php($idStab = $stab->idstablishment)
                <tr>
                  <td>
                    <div class="form-check">
                      <input class="form-check-input position-static" type="checkbox" value="{{ $idStab }}" name="check">
                    </div>
                  </td>
                  <td><img src="{{ asset($stab->image) }}" class="img-thumbnail" title="{{ __($stab->name) }}" alt="{{ __($stab->name) }}"></td>
                  <td>{{ $stab['name'] }}</td>
                  <td>{{ $stab['direction'] }}</td>
                  <td>{{ $stab['zone'] }}</td>
                  <td>{{ $stab['phone'] }}</td>
                  <td>{{ $stab['hour'] }}</td>
                  <td>{{ $stab['offer'] }}</td>
                  <td>{{ $stab['disabled'] }}</td>
                  <td>{{ $stab['section'] }}</td>
                  <td>{{ date('d/m/Y', strtotime($stab['created_at'])) }}</td>
                  <td>
                    <div class="custom-control custom-switch">
                      <input type="checkbox" class="custom-control-input enabled-check" id="enabled-{{ md5($idStab) }}" name="enabled-{{ md5($idStab) }}" data-hashStab="{{ Crypt::encryptString($idStab) }}" {{ $stab->disabledGlobal?"":"checked" }}>
                      <label class="custom-control-label" for="enabled-{{ md5($idStab) }}"></label>
                    </div>
                  </td>
                  <td><a href="{{ route('admin.stablishments.edit', $stab) }}" class="btn btn-outline-success">Editar</a></td>
                  <td>
                    <form method="POST" action="{{ route('admin.stablishments.destroy', $stab) }}">
                      @csrf @method('DELETE')
                      <button type="submit" class="btn btn-outline-danger">Eliminar</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td scope="row" colspan="10"><h4 class="text-center">SIN REGISTROS</h4></td>
                </tr>                  
              @endforelse
              </tbody>
            </table>
          </div>

        </div>
      </div>


    </div>
  </div>
</div>
@push('scripts')
<script type="application/javascript">
  window.addEventListener('load', function() {
    let cfg = {
      delurl: '{{ route("admin.stablishments.elimination") }}',
      urlAddVisitsAll: '{{ route("admin.stablishments.addVisitsAll") }}',
      urlEnabledGlobalStab: '{{ route("admin.stablishments.enabledGlobalStab") }}',
    };
    admin.stablishment.view.init(cfg);
  });
</script>
@endpush

@endsection