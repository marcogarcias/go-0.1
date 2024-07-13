@component('mail::message')

<div>
  <div style="background-color: #000; width: 100%;">
    <img style="width: 100%;" src="{{ asset('img/site/logo-md-03-black.png') }}" alt="{{ config('app.name') }}" title="{{ config('app.name') }}">
  </div>
  <div style="background-color: #fff;">
    <p># Hola, {{ $data['name'] }}</p>
    <p>
      Muchas gracias por comunicarte con nosotros! Tu opinión es súper importante para nosotros, más que el café de la mañana. Nos emociona saber lo que piensas y nos ayuda a mejorar. Eres genial por tomarte el tiempo de compartir tus ideas con nosotros.
    </p>
    <p>
      ¡Seguiremos trabajando para ser aún más increíbles, gracias a ti!
    </p>
    <p>
      <span style="display: block;">ATENTAMENTE</span>
      <span style="display: block;">El equipo de {{ config('app.name') }}</span>
    </p>
    <p>
      @component('mail::button', ['url' => $data['url']])
        Visítanos aquí
      @endcomponent
    </p>
  </div>
</div>
@endcomponent
