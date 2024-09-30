@php
$sections = [
    [
        'condition' => $ps->best == 1,
        'title' => __("Best Seller"),
        'products' => $best_products,
        'banner' => [$ps->best_seller_banner, $ps->best_seller_banner1],
        'banner_links' => [$ps->best_seller_banner_link, $ps->best_seller_banner_link1],
        'col_class' => $ps->best_seller_banner || $ps->best_seller_banner1 ? 'col-lg-10' : 'col-lg-12',
        'template' => 'includes.product.home-product',
    ],
    [
        'condition' => $ps->flash_deal == 1,
        'title' => __("Flash Deal"),
        'products' => $discount_products,
        'template' => 'includes.product.flash-product',
    ],
    [
        'condition' => $ps->large_banner == 1,
        'banners' => $large_banners,
        'template' => 'banners',
    ],
    [
        'condition' => $ps->top_rated == 1,
        'title' => __("Top Rated"),
        'products' => $top_products,
        'template' => 'includes.product.top-product',
    ],
    [
        'condition' => $ps->bottom_small == 1,
        'banners' => $bottom_small_banners,
        'template' => 'banners',
        'chunk_size' => 3,
    ],
    [
        'condition' => $ps->big == 1 && ($ps->big_save_banner || $ps->big_save_banner1),
        'title' => __("Big Save"),
        'products' => $best_products,
        'banner' => [$ps->big_save_banner, $ps->big_save_banner1],
        'banner_links' => [$ps->big_save_banner_link, $ps->big_save_banner_link1],
        'col_class' => $ps->big_save_banner || $ps->big_save_banner1 ? 'col-lg-10' : 'col-lg-12',
        'template' => 'includes.product.home-product',
    ],
    [
        'condition' => $ps->hot_sale == 1,
        'hot_title' => __("Hot"),
        'new_title' => __("New"),
        'trending_title' => __("Trending"),
        'sale_title' => __("Sale"),
        'hot_products' => $hot_products,
        'latest_products' => $latest_products,
        'trending_products' => $trending_products,
        'sale_products' => $sale_products,
        'template' => 'includes.product.list-product',
    ],
    [
        'condition' => $ps->review_blog == 1,
        'reviews' => $reviews,
        'extra_blogs' => $extra_blogs,
    ],
    [
        'condition' => $ps->partners == 1,
        'title' => __("Brands"),
        'partners' => $partners,
    ],
];
@endphp

@foreach($sections as $section)
    @if($section['condition'])
        <section class="categori-item {{ isset($section['template']) ? 'electronics-section' : 'banner-section' }}">
            <div class="container">
                @if(isset($section['title']))
                    <div class="row">
                        <div class="col-lg-12 remove-padding">
                            <div class="section-top">
                                <h2 class="section-title">{{ $section['title'] }}</h2>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="row">
                    <div class="{{ $section['col_class'] ?? 'col-lg-12' }}">
                        <div class="row">
                            @if(isset($section['products']))
                                @foreach($section['products'] as $prod)
                                    @include($section['template'])
                                @endforeach
                            @elseif(isset($section['banners']))
                                @foreach($section['banners']->chunk($section['chunk_size'] ?? 1) as $chunk)
                                    <div class="row">
                                        @foreach($chunk as $img)
                                            <div class="col-lg-4 remove-padding">
                                                <div class="left">
                                                    <a class="banner-effect" href="{{ $img->link }}" target="_blank">
                                                        <img src="{{ asset('storage/images/banners/' . $img->photo) }}" alt="">
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    @if(isset($section['banner']))
                        <div class="col-lg-2 remove-padding d-none d-lg-block">
                            <div class="aside">
                                @foreach($section['banner'] as $key => $banner)
                                    @if($banner)
                                        <a class="banner-effect sider-bar-align" href="{{ $section['banner_links'][$key] }}">
                                            <img src="{{ asset('storage/images/' . $banner) }}" alt="" style="width:100%;border-radius: 5px;">
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif
@endforeach

<script src="{{ asset('assets/front/js/mainextra.js') }}"></script>
