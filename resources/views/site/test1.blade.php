@extends('layouts.site')
@section('returnBtn', route('/'))

@section('content')

<div class="container-fluid go-wallpaper">
  <div class="row">
    <div class="col-md-5 text-left logo-lateral">
      <img class="logo-lateral-img" src="{{ asset('img/site/logo-md.gif') }}" title="{{ __('Go') }}" alt="{{ __('Go') }}">
    </div>
    <div class="col-md-7 sections text-center">
      <div class="">
        <div id="mimapa"></div>

      </div>
    </div>
  </div>
</div>
@push('scripts')
<script type="application/javascript">
  window.addEventListener('load', function() {
    $(document).ready(function() {
      go.initGeo('{{ route("test2") }}');
    });
  });
</script>
@endpush

@endsection