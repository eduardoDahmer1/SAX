@if($ps->hot_sale == 1)
<section class="hot-and-new-item" style="padding-top: 20px; padding-bottom: 40px; 
            background-image: url('{{ $admstore->pagesettings->banner_search5 ? asset('storage/images/banners/' . $admstore->pagesettings->banner_search5) : asset('assets/images/noimage.png') }}'); 
            background-size: cover; background-position: center;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="accessories-slider">
                    <div class="slide-item">
                        <div class="row align-fest">
                            <div class="align-fest-text">
                                <h2 class="section-title" data-aos="fade-in">{{ __('Commemorative Date') }}</h2>
                                <h3 class="section-title" data-aos="fade-in">{{ __('Commemorative Date Subtitle') }}</h3>
                            </div>
                            <!-- , ['Trending', $trending_products], ['Sale', $sale_products] -->
                            @foreach([['Hot', $hot_products], ['Trending', $trending_products], ['New', $latest_products], ['Sale', $sale_products]] as [$title, $products] )
                            <div class="col-lg-12 col-sm-6">
                                <div class="categori">
                                    <div class="section-top">
                                        <h2 class="section-title" data-aos="fade-in">{{ __($title) }}</h2>
                                    </div>
                                    <div class="hot-and-new-item-slider row-theme">
                                        @foreach($products as $prod)
                                        <div class="item-slide">
                                            <ul class="item-list" data-aos="fade-in">
                                                @include('includes.product.list-product')
                                            </ul>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
