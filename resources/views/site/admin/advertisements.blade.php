@extends('layouts.admin')
@section('title', 'Administración - Anuncios')

@section('content')

<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-10 offset-md-1 offset-lg-1 offset-xl-1">


      <div class="card">
        <h5 class="card-header">ANUNCIOS</h5>
        <div class="card-body">
          <div>
            <p>
              <a href="{{ route('admin.advertisements.create') }}" class="btn btn-primary">Agregar</a>
              <a href="" class="btn btn-primary" id="eliminarAll">Eliminar</a>
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
                  <th scope="col">{{ __('Descripción') }}</th>
                  <th scope="col">{{ __('Datos') }}</th>
                  <th scope="col">{{ __('Notas') }}</th>
                  <th scope="col">{{ __('Alta') }}</th>
                  <th scope="col">{{ __('Editar') }}</th>
                  <th scope="col">{{ __('Eliminar') }}</th>
                </tr>
              </thead>
              <tbody>
              @forelse($ads as $ad)
                <tr>
                  <td>
                    <div class="form-check">
                      <input class="form-check-input position-static" type="checkbox" value="{{ $ad->idadvertisements }}" name="check">
                    </div>
                  </td>
                  <td><img class="table-img" src="{{ asset('img/site/advertisements/'.$ad['image']) }}" title="{{ __($ad->name) }}" alt="{{ __($ad->name) }}"></td>
                  <td>{{ $ad['name'] }}</td>
                  <td>{{ $ad['description'] }}</td>
                  <td>{{ $ad['data'] }}</td>
                  <td>{{ $ad['notes'] }}</td>
                  <td>{{ date('d/m/Y', strtotime($ad['created_at'])) }}</td>
                  <td><a href="{{ route('admin.advertisements.edit', $ad) }}" class="btn btn-outline-success">Editar</a></td>
                  <td>
                    <form method="POST" action="{{ route('admin.advertisements.destroy', $ad) }}">
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
    let url;
    let options='';
    let empresa;
    let ads=[];

    $('#checkAll').on('click', function(){
      if(!$(this).val()){
        $(this).val('all');
        $('input[name=check]').prop("checked", true);
      }else{
        $(this).val('');
        $('input[name=check]').prop("checked", '');
      }
    });

    $('#eliminarAll').on('click', function(e){
      url = '{{ route("admin.advertisements.elimination") }}';
      e.preventDefault();
      
      $("input[name=check]").each(function () {
        if($(this).is(':checked')){
          ads.push($(this).val());
        }
      });
      
      axios.post(url, {
        ads: ads,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        responseType: 'json'
      }).then(function(res){
        if(res.status==200){
          window.location='{{ route("admin.advertisements") }}';
          console.log('resultado:',res);
        }
      }).catch(function(err){
        console.log('err: ', err);
      }).then(function(){ });
    });
  });
</script>
@endpush

@endsection