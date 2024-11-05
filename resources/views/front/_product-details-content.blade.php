<div class="col-lg-5 pl-lg-5">
    <div class="right-area">
        <div class="product-info">
            <a href="{{ route('front.brand', $productt->brand->slug) }}" class="d-block mb-2"><img src="{{$productt->brand->image ? asset('storage/images/brands/'.$productt->brand->image) : asset('assets/images/noimage.png') }}"alt="{{$productt->brand->name}}" width="55px"></a>
            <h4 class="product-name">{{ $productt->name }}</h4>
            @if($productt->product_size != null)
            <h4>
                <span class="badge badge-primary" style="background-color: {{$admstore->ref_color}}">{{ __('ReferenceCode') }}: <span id="size-code"></span></span>
            </h4>
            @else
            <h4>
                <span class="badge badge-primary" style="background-color: {{$admstore->ref_color}}">{{ __('Reference
                    Code') }}: {{$productt->ref_code}}</span>
                </span>
            </h4>
            @endif
            @if($productt->show_price)
            @include('front._product-details-show-price')
            @endif
            @include('front._product-details-info-meta-1')
            @include('front._product-details-info-meta-2')
            @if(!empty($productt->size))
            @include('front._product-details-size')
            @endif
            <div class="row">
                <div class="col-lg-6">
                    @if(!empty($productt->color))
                        @include('front._product-details-color')
                    @endif
                </div>
                <div class="col-lg-6 list-attr">
                    <div class="product-color product-size">
                        @if ($productt->product_size)
                            <p class="title">{{__("Sizes")}} :</p>
                            <span class="boxassociatedProductSize">
                                <input class="input-associatedProductSize" @disabled($productt->stock == 0) @checked($productt->stock > 0) 
                                name="associatedProductsBySize" 
                                type="radio" 
                                id="associatedProductsBySize0"
                                data-product-stock="{{$productt->stock}}" 
                                data-product-id="{{$productt->id}}"
                                data-product-code="{{$productt->ref_code}}">
                                <label for="associatedProductsBySize0">{{$productt->product_size}}</label>
                            </span>
                        @endif
                        @if ( preg_match("/[0-9]/", $productt->product_size) )
                            @foreach ($productt->associatedProductsBySize->sortBy('product_size') as $productSize)
                                <span class="boxassociatedProductSize">
                                    <input class="input-associatedProductSize"
                                    @disabled($productSize->stock == 0)
                                    @checked($productt->stock == 0 && $productSize->stock != 0)
                                    name="associatedProductsBySize" 
                                    type="radio" 
                                    id="associatedProductsBySize{{$loop->index + 1}}"
                                    data-product-stock="{{$productSize->stock}}" 
                                    data-product-id="{{$productSize->id}}"
                                    data-product-code="{{$productSize->ref_code}}">
                                    <label for="associatedProductsBySize{{$loop->index+1}}">{{ str_replace(',', '.', $productSize->product_size) }}</label>
                                </span>
                            @endforeach
                        @else
                            @foreach ($productt->associatedProductsBySize->sortByDesc('product_size') as $productSize)
                                <span class="boxassociatedProductSize">
                                    <input class="input-associatedProductSize"
                                    @disabled($productSize->stock == 0)
                                    @checked($productt->stock == 0 && $productSize->stock != 0)
                                    name="associatedProductsBySize" 
                                    type="radio" 
                                    id="associatedProductsBySize{{$loop->index + 1}}"
                                    data-product-stock="{{$productSize->stock}}" 
                                    data-product-id="{{$productSize->id}}"
                                    data-product-code="{{$productSize->ref_code}}">
                                    <label for="associatedProductsBySize{{$loop->index+1}}">{{$productSize->product_size}}</label>
                                </span>
                            @endforeach
                        @endif
                    </div>
                </div>
                @include('front._product-details-info-meta-3')
            </div>
            @php
            $stck = (string) $productt->stock;
            @endphp
            @if($stck != null)
            <input type="hidden" id="stock" value="{{ $stck }}">
            @elseif($productt->type != 'Physical')
            <input type="hidden" id="stock" value="0">
            @else
            <input type="hidden" id="stock" value="">
            @endif
            <input type="hidden" id="product_price"value="{{ round($productt->vendorPrice(),2) * $product_curr->value,2 }}">
            <input type="hidden" id="product_id" value="{{ $productt->id }}">
            <input type="hidden" id="curr_pos" value="{{ $gs->currency_format }}">
            <input type="hidden" id="dec_sep" value="{{ $product_curr->decimal_separator }}">
            <input type="hidden" id="tho_sep" value="{{ $product_curr->thousands_separator }}">
            <input type="hidden" id="dec_dig" value="{{ $product_curr->decimal_digits }}">
            <input type="hidden" id="dec_sep2" value="{{ $first_curr->decimal_separator }}">
            <input type="hidden" id="tho_sep2" value="{{ $first_curr->thousands_separator }}">
            <input type="hidden" id="dec_dig2" value="{{ $first_curr->decimal_digits }}">
            <input type="hidden" id="curr_sign" value="{{ $product_curr->sign }}">
            <input type="hidden" id="first_sign" value="{{ $first_curr->sign }}">
            <input type="hidden" id="currency_value" value="{{ $product_curr->value }}">
            <input type="hidden" id="curr_value" value="{{ $product_curr->value }}">
            @if($gs->is_back_in_stock && $productt->emptyStock())
            @include('front._product-details-back-in-stock')
            @endif
            @if($productt->ship != null)
            <p class="estimate-time">{{ __("Estimated Shipping Time") }}: <b>{{ $productt->ship }}</b></p>
            @endif
            @if($productt->sku != null)
            <p class="p-sku">{{ __("SKU") }}: <span class="idno">{{ $productt->sku }}</span>
            </p>
            @endif
            <script async src="https://static.addtoany.com/menu/page.js"></script>
            @if($gs->is_report)
            @endif
        </div>
    </div>
    <div class="py-4">
        <h3 style="text-transform: uppercase;font-weight: 300;">{{__('Product details')}}</h3>
        <div style="color:#848484;font-weight:300;">{!! nl2br($productt->details) !!}</div>
    </div>
</div>
<script>
    const spans = document.querySelectorAll('.boxassociatedProductSize');
    const sizes = Array.from(spans).map(element => {
        const label = element.querySelector('label');
        return label ? label.innerText.trim() : null;
    }).filter(Boolean);
    const isNumber = value => !isNaN(+value);
    const divSizes = document.querySelector('.product-size');
    const spansArray = Array.from(divSizes.querySelectorAll('span'));
    const sizesOrder = ['XS', 'S', 'M', 'L', 'XL', 'XXL', '2XL', 'XXXL', '3XL', '4XL'];
    const compareSizes = (a, b, order) => {
        const sizeA = a.querySelector('label').textContent.trim();
        const sizeB = b.querySelector('label').textContent.trim();
        return order.indexOf(sizeA) - order.indexOf(sizeB);
    };
    const sortSizes = () => {
        const numberOrder = sizes.sort((a, b) => a - b);
        const order = isNumber(sizes[0]) ? numberOrder : sizesOrder;

        spansArray.sort((a, b) => compareSizes(a, b, order));

        spansArray.forEach(span => divSizes.appendChild(span));
    };
    const updateStockStatus = () => {
        const valueStock = $("[name='associatedProductsBySize']:checked").data('product-stock') ?? {{$productt->stock}};
        const message = valueStock > 3 ? `{{ __("In Stock") }}: ${valueStock}` : `{{ __("There are only") }} ${valueStock} !`;
        $('#rest_of').html(message);
    };
    const updateSizeCode = () => {
        const sizeCodeElement = document.querySelector('#size-code');
        const productCode = document.querySelector('[name="associatedProductsBySize"]:checked').getAttribute('data-product-code');
        sizeCodeElement.textContent = productCode;
    };
    sortSizes();
    updateStockStatus();
    updateSizeCode();
    $("[name='associatedProductsBySize']").change(() => {
        updateStockStatus();
        updateSizeCode();
    });
</script>

