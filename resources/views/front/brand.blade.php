@extends('front.themes.' . env('THEME', 'theme-15') . '.layout')
@section('content')
<div class="breadcrumb-area">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <ul class="pages">
          <li><a href="{{route('front.index')}}">{{ __("Home") }}</a></li>
          <li><a href="{{route('front.brands')}}">{{ __("Brands") }}</a></li>
          <li><a href="{{route('front.brand', $brand->slug)}}">{{ $brand->name }}</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>
@if($brand->banner)
<div class="row"><div class="col-lg-12 text-center"><div class="intro-content"><img src="{{asset('storage/images/brands/banners/'.$brand->banner)}}"></div></div></div>
@endif
<section class="sub-categori">
  <div class="container">
    <div class="row">
      <div class="col-lg-3 col-md-6">
        <div class="left-area">
          <div class="filter-result-area">
            <div class="header-area"><h4 class="title">{{ $brand->name }}</h4></div>
            <div class="body-area">
              <img src="{{$brand->image ? asset('storage/images/brands/'.$brand->image) : asset('assets/images/noimage.png') }}" alt="{{$brand->name}}">
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-9 order-first order-lg-last ajax-loader-parent">
        <div class="right-area" id="app">
          @include('includes.filter')
          <div class="categori-item-area">
            <div class="row" id="ajaxContent">
              @include('includes.product.filtered-products')
            </div>
            <div id="ajaxLoader" class="ajax-loader" style="background: url({{asset('storage/images/'.$gs->loader)}}) no-repeat center center rgba(0,0,0,.6);"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
@section('scripts')
<script>
  $(document).ready(function() {
    addToPagination();
    $("#qty").on('change', function() {
      $("#ajaxLoader").show();
      filter();
    });
    $("#sortby").on('change', filter);
    $(".filter-btn").on('click', function(e) {
      e.preventDefault();
      $("#ajaxLoader").show();
      filter();
    });
  });
  function filter() {
    let filterlink = '{{route('front.brand', [Request::route('brand')])}}';

    if($("#qty").val() != '') filterlink += '?'+$("#qty").attr('name')+'='+$("#qty").val();
    if($("#sortby").val() != '') filterlink += (filterlink.includes('?') ? '&' : '?')+$("#sortby").attr('name')+'='+$("#sortby").val();
    
    window.location.href = encodeURI(filterlink);
  }
  function addToPagination() {
    $('ul.pagination li a').each(function() {
      let url = $(this).attr('href');
      let page = new URLSearchParams('?' + url.split('?')[1]).get('page');
      let fullUrl = '{{route('front.brand', $brand->slug)}}?page=' + page;

      if($("#qty").val() != '') fullUrl += '&qty='+encodeURI($("#qty").val());
      if($("#sortby").val() != '') fullUrl += '&sort='+encodeURI($("#sortby").val());

      $(this).attr('href', fullUrl);
    });
  }
</script>
@endsection
