@component('mail::message')
<div>
  <div>
    <img src="{{ asset('img/site/logo-md-03-black.png') }}" alt="{{ config('app.name') }}" title="{{ config('app.name') }}">
  </div>
</div>
# Hola, {{ $data['name'] }}

Este es el contenido de tu correo.

@component('mail::button', ['url' => $data['url']])
Button Text
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent
