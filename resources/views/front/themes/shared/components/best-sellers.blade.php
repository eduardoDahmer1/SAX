@if($ps->best == 1)
<section class="phone-and-accessories categori-item">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 remove-padding">
                <div class="section-top">
                    <h2 class="section-title">{{ __("Best Seller") }}</h2>
                </div>
            </div>
        </div>
        <div class="row justify-content-center align-items-center">
            <div class="col-lg-10">
                <div class="row row-theme">
                    @foreach($best_products as $prod)
                        @include('front.themes.'.env('THEME', 'theme-15').'.components.home-product')
                    @endforeach
                </div>
            </div>
            <div class="col-lg-2 remove-padding d-none d-lg-block">
                <div class="aside">
                    @if($ps->best_seller_banner)
                        <a class="banner-effect sider-bar-align" href="{{ $ps->best_seller_banner_link }}">
                            <img src="{{asset('storage/images/banners/'.$ps->best_seller_banner)}}" alt="" style="width:100%;border-radius: 5px;">
                        </a>
                    @endif
                    @if($ps->best_seller_banner1)
                        <a class="banner-effect sider-bar-align" href="{{ $ps->best_seller_banner_link1 }}">
                            <img src="{{asset('storage/images/banners/'.$ps->best_seller_banner1)}}" alt="" style="width:100%;border-radius: 5px;">
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endif
