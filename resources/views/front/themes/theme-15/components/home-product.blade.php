@php
    if ($gs->switch_highlight_currency) {
        $highlight = $prod->firstCurrencyPrice();
        $small = $prod->showPrice();
    } else {
        $highlight = $prod->showPrice();
        $small = $prod->firstCurrencyPrice();
    }
@endphp

<div class="col-lg-3 col-sm-6">
    <a href="{{ route('front.product', $prod->slug) }}" class="item" data-aos="fade-in" data-aos-delay="{{ $loop->index }}00">
        @if (!is_null($prod->discount_percent))
            <span class="badge badge-danger descont-card">
                {{ $prod->discount_percent }}% <span style="font-weight: lighter">OFF</span>
            </span>
        @endif

        <div class="item-img {{ $gs->show_products_without_stock_baw && $prod->stock === 0 ? 'baw' : '' }}">
            @if ($admstore->reference_code == 1 && $prod->ref_code)
                <div class="sell-area ref"><span class="sale">{{ $prod->ref_code }}</span></div>
            @endif

            @if (!empty($prod->features))
                <div class="sell-area">
                    @foreach ($prod->features as $key => $feature)
                        <span class="sale" style="background-color:{{ $prod->colors[$key] ?? '#ccc' }}">{{ $feature }}</span>
                    @endforeach
                </div>
            @endif

            <div class="extra-list">
                <ul>
                    <li>
                        @if (Auth::check())
                            <span class="add-to-wish" data-href="{{ route('user-wishlist-add', $prod->id) }}" data-toggle="tooltip" title="{{ __('Add To Wishlist') }}">
                                <i class="icofont-heart-alt"></i>
                            </span>
                        @else
                            <span class="add-to-wish" data-toggle="modal" data-target="#comment-log-reg" title="{{ __('Add To Wishlist') }}">
                                <i class="icofont-heart-alt"></i>
                            </span>
                        @endif
                    </li>
                    <x-wedding.product-add-icon :id="$prod->id" />
                    <li>
                        <span class="quick-view" data-toggle="modal" data-target="#quickview" data-href="{{ route('product.quick', $prod->id) }}" title="{{ __('Quick View') }}">
                            <i class="icofont-eye-alt"></i>
                        </span>
                    </li>
                </ul>
            </div>

            <img loading="lazy" class="img-fluid" src="{{ filter_var($prod->thumbnail, FILTER_VALIDATE_URL) ? $prod->thumbnail : asset('storage/images/thumbnails/' . $prod->thumbnail) }}" alt="{{ $prod->showName() }}">

            @if ($gs->is_rating == 1)
                <div class="stars">
                    <div class="ratings">
                        <div class="empty-stars"></div>
                        <div class="full-stars" style="width:{{ App\Models\Rating::ratings($prod->id) }}%"></div>
                    </div>
                </div>
            @endif
        </div>

        <div class="info">
            <p class="m-0" style="font-weight: 500; font-size: 13px;">{{ $prod->brand->name ?? '' }}</p>
            <h5 class="name">{{ $prod->showName() }}</h5>

            @if ($admstore->show_product_prices)
                @if ($prod->promotion_price > 0 && $prod->promotion_price < $prod->price)
                    <span style="text-decoration: line-through; color: #bababa;">{{ $highlight }}</span>
                    @if (env('SHOW_PRICE', false))
                        <h4 class="price">{{ $curr->sign }}{{ $prod->promotion_price }}
                            @if ($curr->id != $scurrency->id)
                                <small>{{ $small }}</small>
                            @endif
                        </h4>
                    @endif
                @else
                    <span style="text-decoration: line-through; color: #bababa;"> <br> </span>
                    @if (env('SHOW_PRICE', false))
                        <h4 class="price">{{ $highlight }}
                            @if ($curr->id != $scurrency->id)
                                <small>{{ $small }}</small>
                            @endif
                        </h4>
                    @endif
                @endif
            @endif
        </div>

        @if ($gs->is_cart)
            <div class="item-cart-area">
                @if ($prod->product_type == 'affiliate')
                    <span class="add-to-cart-btn affilate-btn" data-href="{{ route('affiliate.product', $prod->slug) }}">{{ __('Buy Now') }}</span>
                @else
                    @if ($prod->emptyStock())
                        <span class="add-to-cart-btn cart-out-of-stock">
                            <i class="icofont-close-circled"></i> {{ __('Out of Stock!') }}
                        </span>
                    @else
                        @if ($prod->is_available_to_buy())
                            <span class="add-to-cart add-to-cart-btn" data-href="{{ route('product.cart.add', $prod->id) }}">
                                <i class="fas fa-cart-plus"></i>
                            </span>
                            <span class="add-to-cart-quick add-to-cart-btn" data-href="{{ route('product.cart.quickadd', $prod->id) }}">
                                {{ __('Buy Now') }}
                            </span>
                        @else
                            <span class="add-to-cart-btn" href="{{ route('front.product', $prod->slug) }}">{{ __('Details') }}</span>
                        @endif
                    @endif
                @endif
            </div>
        @else
            <span class="add-to-cart-btn text-center" href="{{ route('front.product', $prod->slug) }}">{{ __('Details') }}</span>
        @endif
    </a>
</div>
