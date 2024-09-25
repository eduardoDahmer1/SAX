@extends('front.themes.' . env('THEME', 'theme-15') . '.layout')
@section('content')
    <div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="pages">
                        <li>
                            <a href="{{ route('front.index') }}">
                                {{ __('Home') }}
                            </a>
                        </li>
                        <li>
                            <a href="javascript:;">
                                {{ __('404') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <section class="fourzerofour">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="content">
                        <img src="{{ $gs->errorBannerUrl }}" alt="">
                        <br>
                        {!! $gs->page_not_found_text !!}
                        <br>
                        <a class="mybtn1" href="{{ route('front.index') }}">{{ __('Back Home') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
