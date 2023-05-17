@if($ps->featured == 1)
<!-- Trending Item Area Start -->
<section class="trending">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 remove-padding">
                <div class="section-top text-center">
                    <h2 class="section-title" data-aos="fade-in">
                        {{ __("Featured") }}
                    </h2>
                    <h5 data-aos="fade-in" data-aos-delay="100">{{__("Discover the next piece to add to your collection")}}</h5>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 remove-padding">
                <div class="trending-item-slider">
                    @foreach($feature_products as $prod)
                    @include('front.themes.'.env('THEME', 'theme-01').'.components.slider-product')
                    @endforeach
                </div>
            </div>

        </div>
        <div class="row justify-content-center pt-5">
            <div class="col-md-3" data-aos="fade-in">
                <a class="btn btn-style-1" href="{{ route('front.category') }}">{{ __('See all')}}</a>
            </div>
        </div>
    </div>
</section>
<!-- Tranding Item Area End -->
@endif
