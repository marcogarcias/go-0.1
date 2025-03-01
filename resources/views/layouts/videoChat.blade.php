<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="theme-color" content="#353367">
  <title>VIDEO CHAT</title>

  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="{{ asset('libs/toastr/toastr.min.css') }}" rel="stylesheet">

  @yield('css')

  <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}" defer></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" integrity="sha512-RXf+QSDCUQs5uwRKaDoXt55jygZZm2V++WUZduaU/Ui/9EGp3f/2KZVahFZBKGH0s774sd3HmrhUy+SgOFQLVQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="{{ asset('libs/toastr/toastr.min.js') }}" defer></script>

  @yield('js')

@if(env('APP_ENV')==='production')
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-HFTHY5JBSH"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-HFTHY5JBSH');
  </script>
@endif
</head>
<body>
  <div id="app">
    <header>
      
    </header>

    <main>
      @yield('content')
    </main>

    <footer>
      
    </footer>  
    <div id="newElement" class="" style="display: none;"></div>
  </div>



  <!-- Modal -->
  <div class="modal fade" id="help-modal" tabindex="-1" role="dialog" aria-labelledby="help-modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        
      </div>
      <div class="btn btn-black close-modal">CERRAR</div>
    </div>
  </div>

@stack('scripts')
<script type="application/javascript">
window.addEventListener('load', function() {
  $(document).ready(function() {
    
  });
});
</script>
</body>
</html>
