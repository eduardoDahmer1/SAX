@if($ps->small_banner == 1)
<!-- Banner Area One Start -->
<section class="banner-section position-top py-4">
    <div class="container">
        <div class="row pb-4">
            <div class="col-lg-12 remove-padding">
                <div class="section-top text-center">
                    <h2 class="section-title">
                        {{ __("Exclusive") }}
                    </h2>
                    <h5>{{__("Discover the next piece to add to your collection")}}</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="slider-banners-excluse">
        @foreach ($top_small_banners as $img)
            <div class="item-slider">
                <img src="{{ asset('storage/images/banners/' . $img->photo) }}" alt="">
            </div>
        @endforeach
    </div>
    <div class="container">
        <div class="row justify-content-center pt-4">
            <div class="col-md-3">
                <a class="btn btn-style-1" href="{{ route('front.category') }}">{{ __('See all')}}</a>
            </div>
        </div>
    </div>
</section>
<!-- Banner Area One Start -->
@endif
