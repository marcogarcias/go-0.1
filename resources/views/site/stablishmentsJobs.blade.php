@extends('layouts.site')
@section('title', 'Cerca de tí')

@section('css')

@endsection

@section('js')
  <script src="{{ asset('js/jobs.js') }}"></script>
@endsection

@section('returnBtn', route('home'))

@section('content')

<div class="container-fluid go-wallpaper">
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-5 logo-lateral">
      <img class="logo-lateral-img" src="{{ asset('img/site/logo-md.gif') }}" title="{{ __('Go') }}" alt="{{ __('Go') }}">
      <img class="logo-banner-img" src="{{ asset('img/site/logo-sm-slim.gif') }}" title="{{ __('Go') }}" alt="{{ __('Go') }}">
    </div>
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 col-xl-7">
      <div id="jobsCont" class="row"></div>
    </div>
  </div>
</div>
@push('scripts')
<script type="application/javascript">
  window.addEventListener('load', function() {
    $(document).ready(function() {
      let jobsArr = [];
      @forelse($jobs as $j)
        jobsArr.push({imgPath: '{{ asset($j->image) }}', stabName: '{{ $j->stabName }}', jobName: '{{ $j->jobName }}'});
      @empty
          
      @endforelse
      jobs.initSection({jobsArr: jobsArr});
    });
  });
</script>
@endpush

@endsection