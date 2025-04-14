@php
    $gs = $gs ?? resolve('generalSettings');
    $curr = $curr ?? session('currency', \App\Models\Currency::first());
    $scurrency = $scurrency ?? session('scurrency', \App\Models\Currency::first());
    $admstore = $admstore ?? resolve('storeSettings');

    if ($gs->switch_highlight_currency) {
        $highlight = $prod->firstCurrencyPrice();
        $small = $prod->showPrice();
    } else {
        $highlight = $prod->showPrice();
        $small = $prod->firstCurrencyPrice();
    }

    $brandName = $prod->brand->name ?? '';
@endphp

<a href="{{ route('front.product', $prod->slug) }}" class="item" data-aos="fade-in" data-aos-delay="{{ $loop->index }}00">
    <div class="item-img {{ $gs->show_products_without_stock_baw && !is_null($prod->stock) && $prod->stock == 0 ? 'baw' : '' }}">
        @if($admstore->reference_code == 1)
            <div class="sell-area ref"><span class="sale">{{ $prod->ref_code }}</span></div>
        @endif

        @if(!empty($prod->features))
            <div class="sell-area">
                @foreach($prod->features as $key => $data1)
                    <span class="sale" style="background-color:{{ $prod->colors[$key] }}">{{ $prod->features[$key] }}</span>
                @endforeach
            </div>
        @endif

        <div class="extra-list">
            <ul>
                <li>
                    @if(Auth::guard('web')->check())
                        <span class="add-to-wish" data-href="{{ route('user-wishlist-add', $prod->id) }}"
                            data-toggle="tooltip" data-placement="right" title="{{ __('Add To Wishlist') }}">
                            <i class="icofont-heart-alt"></i>
                        </span>
                    @else
                        <span class="add-to-wish" rel-toggle="tooltip" title="{{ __('Add To Wishlist') }}" 
                              data-href="{{ route('user-wishlist-add', $prod->id) }}" data-toggle="modal" 
                              data-target="#comment-log-reg">
                            <i class="icofont-heart-alt"></i>
                        </span>
                    @endif
                </li>
                <li>
                    <span class="quick-view" rel-toggle="tooltip" title="{{ __('Quick View') }}"
                          href="javascript:;" data-href="{{ route('product.quick', $prod->id) }}"
                          data-toggle="modal" data-target="#quickview">
                        <i class="icofont-eye-alt"></i>
                    </span>
                </li>
            </ul>
        </div>

        <img class="img-fluid lazyload"
            data-src="{{ filter_var($prod->photo, FILTER_VALIDATE_URL) ? $prod->photo : asset('storage/images/products/' . $prod->photo) }}"
            alt="{{ $prod->name }}"
            onerror="this.onerror=null;this.src='{{ asset('assets/images/noimage.png') }}';">
    </div>

    <div class="info">
        @if($gs->show_brand_slider && $brandName)
            <p class="brand mb-1 text-muted"><small>{{ $brandName }}</small></p>
        @endif

        <h5 class="name text-truncate">{{ $prod->name }}</h5>

        <div class="price">
            <span class="highlight">{{ $highlight }}</span>
            <span class="old-price text-muted"><small>{{ $small }}</small></span>
        </div>
    </div>

    @if ($gs->is_cart && $prod->is_available_to_buy())
        <div class="item-cart-area text-center mt-2">
            <span class="add-to-cart-btn" data-href="{{ route('product.cart.quickadd', $prod->id) }}">
                {{ __('COMPRÁ AHORA') }}
            </span>
        </div>
    @endif
</a>
