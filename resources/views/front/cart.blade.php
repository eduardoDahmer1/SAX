@extends('front.themes.' . env('THEME', 'theme-15') . '.layout')

@section('styles')
@parent
<style>#freight-form input{width:190px;height:35px;background:#f3f8fc;border:1px solid rgba(0,0,0,0.1);padding:0 10px;font-size:14px;}#freight-form button{height:35px;background:#fff;border:1px solid rgba(0,0,0,0.15);font-size:14px;text-transform:uppercase;cursor:pointer;-webkit-transition:all 0.3s ease-in;-o-transition:all 0.3s ease-in;transition:all 0.3s ease-in;}#freight-form button:hover{color:#fff;}#shipping-area-aex .radio-design,#shipping-area .radio-design{border:1px solid #ccc;border-radius:10px;padding-top:10px;padding-right:10px;cursor:default;}#shipping-area-aex .radio-design input,#shipping-area .radio-design input{cursor:default;}#shipping-area-aex .radio-design .checkmark,#shipping-area .radio-design .checkmark{display:none;}</style>
@endsection
@section('content')
<div class="breadcrumb-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12"><ul class="pages"><li><a href="{{ route('front.index') }}">{{ __('Home') }}</a></li><li><a href="{{ route('front.cart') }}">{{ __('Cart') }}</a></li></ul></div>
        </div>
    </div>
</div>
<section class="cartpage">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="preloader" id="preloader_cart" style="background: url({{ asset('storage/images/' . $gs->loader) }}) no-repeat center center; background-color: rgba(0,0,0,0.5); display: none;"></div>
                <div class="left-area">
                    <div class="cart-table">
                        <table class="table">
                            @include('includes.form-success')
                            <thead>
                                <tr>
                                    <th>{{ __('Product Name') }}</th>
                                    <th width="30%">{{ __('Details') }}</th>
                                    @if($admstore->show_product_prices)
                                    <th>{{ __('Unit Price') }}</th>
                                    <th>{{ __('Sub Total') }}</th>
                                    @endif
                                    <th><i class="icofont-close-squared-alt d-flex justify-content-end"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (Session::has('cart'))
                                @foreach ($products as $product)
                                @php
                                $custom_item_id = preg_replace('/[^A-Za-z0-9]/', '',
                                $product['item']['id'] . $product['size'] . $product['color'] . $product['material'] .
                                $product['customizable_gallery'] . $product['customizable_name'] .
                                $product['customizable_number'] . $product['customizable_logo'] .
                                implode('', explode(',', $product['values']))
                                );
                                @endphp
                                <tr class="cremove{{ $custom_item_id }}">
                                    <td class="product-img">
                                        <div class="item">
                                            <img src="{{ filter_var($product['item']['photo'], FILTER_VALIDATE_URL) ? $product['item']['photo'] : asset('storage/images/products/' . $product['item']['photo']) }}" alt="product">
                                            <p class="name"><a href="{{ route('front.product', $product['item']['slug']) }}">{{ Str::limit($product['item']->name, 35) }}</a></p>
                                            @if (!empty($product['item']->ship)) <p>{{ __('Estimated shipping time') }}:
                                                {{ $product['item']->ship }}</p> @endif
                                        </div>
                                    </td>
                                    <td>
                                        @foreach (['size' => $product['size'], 'material' => $product['material'],
                                        'color' => $product['color']] as $key => $value)
                                        @if (!empty($value))
                                        <b>{{ ucfirst($key) }}</b>:
                                        {{ $product['item']['measure'] }}{{ str_replace('-', ' ', $value) }}<br>
                                        @endif
                                        @endforeach
                                        @if (!empty($product['keys']))
                                        @foreach (array_combine(explode(',', $product['keys']), explode('~',
                                        $product['values'])) as $key => $value)
                                        <b>{{ App\Models\Attribute::where('input_name', $key)->first()->name }}:
                                        </b>{{ $value }} <br>
                                        @endforeach
                                        @endif
                                        @if (env('ENABLE_CUSTOM_PRODUCT'))
                                        @foreach (['customizable_name', 'customizable_gallery', 'customizable_logo',
                                        'customizable_number'] as $field)
                                        @if (!empty($product[$field]))
                                        <b>{{ __(ucwords(str_replace('_', ' ', $field))) }}</b>:
                                        @if ($field == 'customizable_gallery')
                                        <img src="{{ asset('storage/images/galleries/' . $product[$field]) }}"
                                            style="width: 45px; border-radius: 30px; margin-left: 5px;">
                                        @elseif ($field == 'customizable_logo')
                                        <img src="{{ asset('storage/images/custom-logo/' . $product[$field]) }}"
                                            style="width: 45px; margin-left: 5px;">
                                        @else
                                        <p>{{ $product[$field] }}</p>
                                        @endif
                                        @endif
                                        @endforeach
                                        @endif
                                    </td>
                                    @if($admstore->show_product_prices)
                                    <td class="unit-price quantity">
                                        <p class="product-unit-price">{{ App\Models\Product::convertPrice($product['item']['promotion_price'] > 0 && $product['item']['promotion_price'] < $product['item']['price'] ? $product['item']['promotion_price'] : $product['item']['price']) }}</p>
                                        <p class="product-unit-price" style="font-size: smaller;">{{ App\Models\Product::signFirstPrice($product['item']['promotion_price'] > 0 && $product['item']['promotion_price'] < $product['item']['price'] ? $product['item']['promotion_price'] : $product['item']['price']) }}</p>
                                        @if ($product['item']['type'] == 'Physical')
                                        <div class="qty">
                                            <ul>
                                                @foreach (['prodid', 'itemid', 'size_qty', 'color_qty', 'material_qty',
                                                'max_quantity', 'size_price', 'material_price', 'color_price'] as
                                                $field)
                                                <input type="hidden" class="{{ $field }}" value="{{ $product[$field] }}">
                                                @endforeach
                                                <li><span class="qtminus1 reducing"><i class="icofont-minus"></i></span></li>
                                                <li><span class="qttotal1" id="qty{{ $custom_item_id }}">{{ $product['qty'] }}</span></li>
                                                <li><span class="qtplus1 adding"><i class="icofont-plus"></i></span>
                                                </li>
                                            </ul>
                                        </div>
                                        @endif
                                    </td>
                                    <td class="total-price"><p id="prc{{ $custom_item_id }}">{{ App\Models\Product::convertPrice(($product['item']['promotion_price'] > 0 && $product['item']['promotion_price'] < $product['item']['price']) ? $product['item']['promotion_price'] * $product['qty'] : $product['price']) }}</p></td>
                                    @endif
                                    <td><div class="d-flex justify-content-end"><span class="removecart cart-remove" data-class="cremove{{ $custom_item_id }}" data-href="{{ route('product.cart.remove', $custom_item_id) }}"><iclass="icofont-ui-delete"></i></span></div></td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @if (Session::has('cart'))
            <div class="col-lg-4">
                @if($admstore->show_product_prices)
                <div class="right-area">
                    <div class="order-box">
                        <h4 class="title">{{ __('PRICE DETAILS') }}</h4>
                        <ul class="order-list">
                            <li><p>{{ __('Total MRP') }}</p><p><b class="cart-total">{{ Session::has('cart') ? App\Models\Product::convertPrice($totalPrice) : '0.00' }}</b></p></li>
                            <li><p>{{ __('Tax') }}</p><p><b>{{ $tx }}%</b></p></li>
                            <li><p>{{ __('Discount') }}</p><p><b class="discount">{{ App\Models\Product::convertPrice(0) }}</b><input type="hidden" id="d-val" value="{{ App\Models\Product::convertPrice(0) }}"></p></li>
                        </ul>
                        <div class="total-price"><p>{{ __('Total') }}</p><p><span class="main-total">{{ Session::has('cart') ? App\Models\Product::convertPrice($mainTotal) : '0.00' }}</span></p></div>
                        <div class="total-price"><p><span class="main-total2">{{ Session::has('cart') ? App\Models\Product::signFirstPrice($mainTotal) : '0.00' }}</span></p></div>

                        @if ($gs->is_standard_checkout)
                        <div class="cupon-box">
                            @if ($gs->is_zip_validation)
                            <h4 class="title">{{ __('Shipping Price Calculation') }}</h4>
                            <p class="PAC-sheep border rounded p-1"><small>{{ __('Methods and prices displayed for your convenience.') }}</small></p>
                            <form id="freight-form" class="coupon"><input type="text" name="zip" id="shippingZip" placeholder="{{ __('Postal Code') }}"><button type="submit">{{ __('Calculate') }}</button><div class="shipping-area-class text-left mt-4" id="shipping-area"></div></form>
                            @endif
                            @if ($gs->is_aex && config('features.aex_shipping'))
                            <h4 class="title"><small>{{ __('AEX Shipping') }}</small></h4>
                            <form id="freight-form-aex" class="coupon">
                                <select class="form-control" id="aex_destination" name="aex_destination">
                                    <option value="">{{ __('Select City') }}</option>
                                    @foreach ($aex_cities as $city)
                                    <option value="{{ $city->codigo_ciudad }}">{{ $city->denominacion }} -
                                        {{ $city->departamento_denominacion }}</option>
                                    @endforeach
                                </select>
                                <div class="shipping-area-class text-left mt-4" id="shipping-area-aex"></div>
                            </form>
                            @endif
                            <div id="coupon-link" class="mt-3">{{ __('Have a promotion code?') }}</div>
                            <form id="coupon-form" class="coupon">
                                <input type="text" placeholder="{{ __('Coupon Code') }}" id="code" required
                                    autocomplete="off">
                                <input type="hidden" class="coupon-total" id="grandtotal"
                                    value="{{ Session::has('cart') ? App\Models\Product::convertPrice($mainTotal) : '0.00' }}">
                                <button type="submit">{{ __('Apply') }}</button>
                            </form>
                        </div>
                        @endif
                        @if ($gs->is_standard_checkout)
                        <a href="{{ route('front.checkout') }}" class="order-btn">{{ __('Place Order') }}</a>
                        @endif
                    </div>
                    @if ($gs->is_simplified_checkout && !empty($gs->simplified_checkout_number) &&
                    $admstore->show_product_prices)
                    <a href="#" class="order-btn mt-2" data-toggle="modal"
                        data-target="#simplified-checkout-modal">{{ __('Simplified Checkout') }}</a>
                    @else
                    <div class="d-grid text-center order-box">
                        <h5 class="pb-4">{{ __('Place Order') }}</h5>
                        <a href="#" class="order-btn mt-2" data-toggle="modal"
                            data-target="#simplified-checkout-modal">{{ __('Simplified Checkout') }}</a>
                    </div>
                    @endif
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>
</section>
@endsection
@section('scripts')
<script>
$(document).ready(function() {
    var pixelData = {
        name: "{{ session('pixel_name') }}",
        category: "{{ session('pixel_category') }}",
        id: "{{ session('pixel_id') }}",
        type: "{{ session('pixel_type') }}",
        price: "{{ session('pixel_price') }}",
        currency: "{{ session('pixel_currency') }}"
    };
    if (Object.values(pixelData).some(Boolean) && typeof fbq !== 'undefined') {
        fbq('track', 'AddToCart', {
            content_name: pixelData.name,
            content_category: pixelData.category,
            content_ids: pixelData.id,
            content_type: pixelData.type,
            value: pixelData.price,
            currency: pixelData.currency
        });

        if ("{{ session('is_customizable') }}" == true) {
            fbq('track', 'CustomizeProduct');
        }
    }
});
var city_id, state_id, country_id, zip_code;
function busca_cep(zip_code) {
    $.get(mainurl + "/checkout/cep", {
        cep: zip_code
    }, function(data) {
        if (data.city_id) {
            city_id = data.city_id;
            state_id = data.state_id;
            country_id = data.country_id;
            getShippings("ship");
        } else if (data.error) {
            $('#shipping-area').append('<p class="PAC-sheep border rounded p-1"><small>' + data.error +
                '</small></p>');
            $('#preloader_cart').hide();
        }
    });
}
function getShippings(bill_ship) {
    $.get(mainurl + '/checkout/getShippingsOptions', {
        city_id: city_id,
        state_id: state_id,
        country_id: country_id,
        zip_code: zip_code
    }, function(data) {
        $('#shipping-area').html(data.content);
        $('.shipping').on('click', calc_ship_pack);
        $("input:radio[name=shipping]:not(:disabled):first").prop('checked', true);
    }).fail(function(err) {
        console.log(err);
    }).always(function() {
        $('#preloader_cart').hide();
    });
}
$('#freight-form').on('submit', function(e) {
    e.preventDefault();
    $('#preloader_cart').show();
    $('#shipping-area').empty();
    zip_code = $('#shippingZip').val();
    busca_cep(zip_code);
});
function busca_aex(codigo_ciudad) {
    $.get(mainurl + "/checkout/aex", {
        codigo_ciudad: codigo_ciudad
    }, function(data) {
        var html = data.error ? '<p class="PAC-sheep border rounded p-1"><small>' + data.error +
            '</small></p>' : data;
        $('#shipping-area-aex').append(html);
        $('.shipping').on('click', calc_ship_pack);
        $("input:radio[name=shipping]:not(:disabled):first").prop('checked', true);
    }).fail(function() {
        $('#shipping-area-aex').append('<p class="PAC-sheep border rounded p-1"><small>{{ __('
            Error ') }}</small></p>');
    }).always(function() {
        $('#preloader_cart').hide();
    });
}
$('#aex_destination').on('change', function(e) {
    e.preventDefault();
    $('#preloader_cart').show();
    $('#shipping-area-aex').empty();
    busca_aex($(this).val());
});
</script>
@endsection