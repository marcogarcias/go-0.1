@component('mail::message')

<div>
  <div style="background-color: #000; width: 100%;">
    <img style="width: 100%;" src="{{ asset('img/site/logo-md-03-black.png') }}" alt="{{ config('app.name') }}" title="{{ config('app.name') }}">
  </div>
  <div style="background-color: #fff;">
    <h2>Nuevo mensaje enviado desde la sección: Contacto</h2>
    
    <div>
      <label>Fecha/hora: </label>
      <span>{{ date('d-m-Y H:i:s') }}</span>
    </div>

    <div>
      <label>Nombre: </label>
      <span>{{ $data['name'] }}</span>
    </div>

    <div>
      <label>Email: </label>
      <span>{{ $data['email'] }}</span>
    </div>

    <div>
      <label>Mensaje: </label>
      <span>{{ $data['message'] }}</span>
    </div>
  </div>
</div>
@endcomponent
