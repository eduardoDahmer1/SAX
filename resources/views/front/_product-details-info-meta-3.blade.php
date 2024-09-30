<div class="col-lg-12">
    <div class="info-meta-3">
        <ul class="meta-list">
            @php
                $attrArr = !empty($productt->attributes) ? json_decode($productt->attributes, true) : [];
            @endphp
            
            @if(!empty($attrArr))
                @include($gs->attribute_clickable ? 'front._product-details-attribute-clickable' : 'front._product-details-attribute-normal')
            @endif
            
            @include('front._product-details-info-meta-3-custom')
            @include('front._product-details-info-meta-3-custom-number')
        </ul>
    </div>
</div>
<div class="col-lg-12">
    <div class="info-meta-3">
        <ul class="meta-list">
            <div class="d-flex flex-wrap">
                @if($gs->is_cart)
                    @if($productt->product_type == "affiliate")
                        <li class="addtocart"><a href="{{ route('affiliate.product', $productt->slug) }}" target="_blank"><i class="icofont-cart"></i>{{ __("Buy Now") }}</a></li>
                    @else
                        @if($productt->emptyStock() && !$productt->associatedProductsBySize->contains(fn($product) => $product->stock))
                            <li class="addtocart"><a href="javascript:;" class="cart-out-of-stock"><i class="icofont-close-circled"></i>{{ __("Out of Stock!") }}</a></li>
                        @else
                            <li class="addtocart"><a href="javascript:;" id="addcrt"><img width="{{ env('ENABLE_SAX_BRIDAL') ? '22px' : '19px' }}" class="mr-1" src="{{ asset('assets/images/theme15/' . (env('ENABLE_SAX_BRIDAL') ? 'wishicone.png' : 'bagicone.png')) }}" alt="">{{ __("+ Add to Bag") }}</a></li>
                            <li class="addtocart"><a id="qaddcrt" href="javascript:;"><img width="19px" class="mr-1" src="{{ asset('assets/images/theme15/carrinho.png') }}" alt="">{{ __("Buy Now") }}</a></li>
                        @endif
                    @endif
                @endif
                <li class="favorite">
                    @if(Auth::guard('web')->check() && !env('ENABLE_SAX_BRIDAL'))
                        <a href="javascript:;" class="add-to-wish" data-href="{{ route('user-wishlist-add', $productt->id) }}"><img width="22px" src="{{ asset('assets/images/theme15/wishicone.png') }}" alt=""></a>
                    @else
                        <a href="javascript:;" data-toggle="modal" data-target="#comment-log-reg"><img width="22px" src="{{ asset('assets/images/theme15/wishicone.png') }}" alt=""></a>
                    @endif
                </li>
                @if(!env('ENABLE_SAX_BRIDAL'))
                    <x-wedding.product-add-icon class="d-flex flex-column justify-content-center cursor-pointer" :id="$productt->id" />
                    <li class="compare"><a href="javascript:;" class="add-to-compare" data-href="{{ route('product.compare.add', $productt->id) }}"><i class="icofont-exchange"></i></a></li>
                @endif
            </div>
        </ul>
    </div>
</div>
