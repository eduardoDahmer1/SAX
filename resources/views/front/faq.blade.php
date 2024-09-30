@extends('front.themes.' . env('THEME', 'theme-15') . '.layout')
@section('content')
<div class="breadcrumb-area">
  <div class="container">
    <div class="row">
      <div class="col-lg-12"><ul class="pages"><li><a href="{{ route('front.index') }}">{{ __("Home") }}</a></li><li><a href="{{ route('front.faq') }}">{{ __("Faq") }}</a></li></ul></div>
    </div>
  </div>
</div>
  <section class="faq-section">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
          <div id="accordion">
            @foreach($faqs as $fq)
            <h3 class="heading">{{ $fq->title }}</h3>
            <div class="content"><p>{!! $fq->details !!}</p></div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection