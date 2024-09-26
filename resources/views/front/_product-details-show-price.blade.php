<div class="product-price">
    @php
        $highlight = $gs->switch_highlight_currency ? $productt->firstCurrencyPrice() : $productt->showPrice();
        $small = $gs->switch_highlight_currency ? $productt->showPrice() : $productt->firstCurrencyPrice();
        $size_price_value = $productt->vendorPrice() * $product_curr->value;
        $previous_price_value = $productt->promotion_price * $product_curr->value * (1 + ($gs->product_percent / 100));
    @endphp
    <p class="price">
        @if (!config("features.marketplace"))
            @if (!$productt->promotion_price && $admstore->show_product_prices)
                <span id="sizeprice">{{ $highlight }}</span>
            @elseif ($productt->promotion_price < $productt->price && $productt->promotion_price > 0)
                <small><span style="font-weight: 400; text-decoration: line-through; color: #bababa;">{{ $highlight }}</span></small>
                <span id="sizeprice">{{ $curr->sign }}{{ $productt->promotion_price }}</span>
            @else
                <span id="sizeprice">{{ $highlight }}</span>
            @endif
            <input type="hidden" id="previous_price_value" value="{{ round($previous_price_value, 2) }}">
            @if ($curr->id != $scurrency->id)
                <small><span id="originalprice">{{ $small }}</span></small>
            @endif
        @else
            <span id="originalprice">{{ $productt->showVendorMinPrice() }} até {{ $productt->showVendorMaxPrice() }}</span>
            @if ($curr->id != $scurrency->id)
                <small><span id="originalprice">{{ $small }}</span></small>
            @endif
        @endif
    </p>
    @if ($productt->youtube)
        <a href="{{ $productt->youtube }}" class="video-play-btn mfp-iframe"><i class="fas fa-play"></i></a>
    @endif
    <span class="info-meta-3 ml-4"><ul class="meta-list">@include('front._product-details-info-meta-3-vendor')</ul></span>
</div>
