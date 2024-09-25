@extends('layouts.admin')
@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Order Invoice') }} 
                    <a class="add-btn" href="javascript:history.back();"><i class="fas fa-arrow-left"></i> {{ __('Back') }}</a>
                </h4>
                <ul class="links">
                    <li><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li><a href="{{ route('admin-order-index') }}">{{ __('Orders') }}</a></li>
                    <li><a href="{{ route('admin-order-invoice', $order->id) }}">{{ __('Invoice') }}</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="order-table-wrap">
        <div class="invoice-wrap">
            <div class="invoice__title">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="invoice__logo text-left">
                            <img src="{{ $admstore->invoiceLogoUrl }}" alt="">
                        </div>
                    </div>
                    <div class="col-lg-6 text-right">
                        <a class="btn add-newProduct-btn print" href="{{ route('admin-order-print', $order->id) }}" target="_blank">
                            <i class="fa fa-print"></i> {{ __('Generate Invoice') }}
                        </a>
                    </div>
                </div>
            </div>
            <br>
            <div class="row invoice__metaInfo mb-4">
                <div class="col-lg-6">
                    <div class="invoice__orderDetails">
                        <p><strong>{{ __('Order Details') }}</strong></p>
                        <span><strong>{{ __('Invoice Number') }} :</strong>{{ sprintf("%'.08d", $order->id) }}</span><br>
                        <span><strong>{{ __('Order Date') }} :</strong>@php setlocale(LC_ALL, \App\Helpers\Helper::strLocaleVariations($lang->locale)); @endphp{{ $order->created_at->formatLocalized('%d-%b-%Y %T') }}</span><br>
                        <span><strong>{{ __('Order ID') }} :</strong> {{ $order->order_number }}</span><br>
                        <span><strong>{{__('CEC Number')}} :</strong> {{$order->number_cec}}</span><br>
                        @if ($order->dp == 0)
                            <span><strong>{{ __('Shipping Method') }} :</strong>@if ($order->shipping == 3){{ __('Pick Up') }}@else{{ __('Ship To Address') }}@endif</span><br>
                        @endif
                        @if ($order->shipping == 3)
                            <span>{{ $order->pickup_location }}</span><br>
                        @endif
                        @if ($order->shipping != 3)
                            <span><strong>{{ __('Shipping Type') }} :</strong> {{ $order->shipping_type }}</span><br>
                            <span><strong>{{ __('Packing Type') }} :</strong> {{ $order->packing_type }}</span><br>
                            @if (!empty($order->puntoentrega))
                                <span><strong>{{ __('Delivery Point') }} :</strong>{{ $order->puntoentrega }}</span><br>
                            @endif
                        @endif
                        <span><strong>{{ __('Payment Method') }} :</strong> {{ $order->method }}</span>
                        @if (!empty($order->order_note))
                            <br><span><strong>{{ __('Order Note') }} :</strong> {{ $order->order_note }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6">
                    @if ($order->dp == 0)
                        <div class="invoice__shipping">
                            <p><strong>{{ __('Shipping Address') }}</strong></p>
                            <span><strong>{{ __('Customer Name') }}</strong>: {{ $order->shipping_name ?? $order->customer_name }}</span><br>
                            <span><strong>{{ __('Address') }}</strong>: {{ $order->shipping_address ?? $order->customer_address }}</span><br>
                            <span><strong>{{ __('Number') }}</strong>: {{ $order->shipping_address_number ?? $order->customer_address_number . " " . $order->customer_complement }}</span><br>
                            <span><strong>{{ __('District') }}</strong>: {{ $order->shipping_district ?? $order->customer_district }}</span><br>
                            <span><strong>{{ __('City') }}</strong>: {{ $order->shipping_city ?? $order->customer_city }}</span><br>
                            <span><strong>{{ __('State') }}</strong>: {{ $order->shipping_state ?? $order->customer_state }}</span><br>
                            <span><strong>{{ __('Country') }}</strong>: {{ $order->shipping_country ?? $order->customer_country }}</span><br>
                            <span><strong>{{ __('Postal Code') }}</strong>: {{ $order->shipping_zip ?? $order->customer_zip }}</span>
                        </div>
                    @endif
                    <div class="buyer">
                        <p><strong>{{ __('Billing Details') }}</strong></p>
                        <span><strong>{{ __('Customer Name') }}</strong>: {{ $order->customer_name }}</span><br>
                        <span><strong>{{ __('Customer Email') }}</strong>: {{ $order->customer_email }}</span><br>
                        <span><strong>{{ __('Customer') . ' ' . $customer_doc_str }}</strong>: {{ $order->customer_document }}</span><br>
                        <span><strong>{{ __('Customer Phone') }}</strong>: {{ $order->customer_phone }}</span><br>
                        <span><strong>{{ __('Address') }}</strong>: {{ $order->customer_address }}</span><br>
                        <span><strong>{{ __('Number') }}</strong>: {{ $order->customer_address_number }}</span><br>
                        <span><strong>{{ __('Customer complement') }}</strong>: {{ $order->customer_complement }}</span><br>
                        <span><strong>{{ __('District') }}</strong>: {{ $order->customer_district }}</span><br>
                        <span><strong>{{ __('City') }}</strong>: {{ $order->customer_city }}</span><br>
                        <span><strong>{{ __('State') }}</strong>: {{ $order->customer_state }}</span><br>
                        <span><strong>{{ __('Country') }}</strong>: {{ $order->customer_country }}</span><br>
                        <span><strong>{{ __('Postal Code') }}</strong>: {{ $order->customer_zip }}</span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="invoice_table">
                        <div class="mr-table">
                            <div class="table-responsive">
                                <table id="example2" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Product') }}</th>
                                            @if ($gs->is_invoice_photo)
                                                <th>{{ __('Photo') }}</th>
                                            @endif
                                            <th>{{ __('Details') }}</th>
                                            <th>{{ __('Total') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $subtotal = 0; @endphp
                                        @foreach ($cart['items'] ?? [] as $product)
                                            <tr>
                                                <td width="30%">
                                                    <p>{{ $product['item']['name'] }}</p>
                                                    @php $prod = App\Models\Product::find($product['item']['id']); @endphp
                                                    @if (isset($prod))
                                                        <p style="margin-bottom: 0; font-size: 10px">{{ __('Product SKU') }} - {{ $prod->sku }}</p>
                                                        <p style="font-size: 10px"> {{ __('Reference Code') }} - {{ $prod->ref_code }}</p>
                                                    @endif
                                                </td>
                                                @if ($gs->is_invoice_photo)
                                                    <td width="10%">
                                                        <img src="{{ asset('storage/images/products/' . $prod->photo) }}" width="285" alt="">
                                                    </td>
                                                @endif
                                                <td>
                                                    @if ($product['size'])<p><strong>{{ __('Size') }}: </strong>{{ str_replace('-', ' ', $product['size']) }}</p>@endif
                                                    @if ($product['color'])
                                                        <p>
                                                            <strong>{{ __('Color') }}:</strong> 
                                                            <span style="width: 40px; height: 20px; display: block; background: #{{ $product['color'] }};"></span>
                                                        </p>
                                                    @endif
                                                    <p><strong>{{ __('Price') }} :</strong>{{ $order->currency_sign }}{{ number_format($product['item']['price'] * $order->currency_value, $order_curr->decimal_digits, $order_curr->decimal_separator, $order_curr->thousands_separator) }}</p>
                                                    <p><small>{{ $first_curr->sign . ' ' . __('Price') }} : {{ $first_curr->sign }}{{ number_format($product['item']['price'], $first_curr->decimal_digits, $first_curr->decimal_separator, $order_curr->thousands_separator) }}</small></p>
                                                    <p><strong>{{ __('Qty') }} :</strong> {{ $product['qty'] }} {{ $product['item']['measure'] }}</p>
                                                    @if (!empty($product['customizable_name']))<p><strong>{{ __('Custom Name') }}: {{ $product['customizable_name'] }}</strong></p>@endif
                                                    @if (!empty($product['customizable_number']))<p><strong>{{ __('Custom Number') }}: {{ $product['customizable_number'] }}</strong></p>@endif
                                                    @if (!empty($product['keys']))
                                                        @foreach (array_combine(explode(',', $product['keys']), explode('~', $product['values'])) as $key => $value)
                                                            <p><b>{{ App\Models\Attribute::where('input_name', $key)->first()->name }} : </b> {{ $value }}</p>
                                                        @endforeach
                                                    @endif
                                                </td>
                                                <td style="text-align: center;">
                                                    {{ $order->currency_sign }}{{ number_format($product['price'] * $order->currency_value, $order_curr->decimal_digits, $order_curr->decimal_separator, $order_curr->thousands_separator) }}
                                                    <br><small>{{ $first_curr->sign }}{{ number_format($product['price'], $first_curr->decimal_digits, $first_curr->decimal_separator, $order_curr->thousands_separator) }}</small>
                                                </td>
                                                @php $subtotal += $product['price'] * $order->currency_value; @endphp
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            @if ($gs->is_invoice_photo)<td></td>@endif
                                            <td colspan="2">{{ __('Subtotal') }}</td>
                                            <td>{{ $order->currency_sign }}{{ number_format($subtotal, $order_curr->decimal_digits, $order_curr->decimal_separator, $order_curr->thousands_separator) }}</td>
                                        </tr>
                                        @if (!empty($order->shipping_price))
                                            <tr>
                                                @if ($gs->is_invoice_photo)<td></td>@endif
                                                <td colspan="2">{{ __('Shipping') }}</td>
                                                <td>{{ $order->currency_sign }}{{ number_format($order->shipping_price * $order->currency_value, $order_curr->decimal_digits, $order_curr->decimal_separator, $order_curr->thousands_separator) }}</td>
                                            </tr>
                                        @endif
                                        @if ($order->discount_price > 0)
                                            <tr>
                                                @if ($gs->is_invoice_photo)<td></td>@endif
                                                <td colspan="2">{{ __('Discount') }}</td>
                                                <td>{{ $order->currency_sign }}{{ number_format($order->discount_price * $order->currency_value, $order_curr->decimal_digits, $order_curr->decimal_separator, $order_curr->thousands_separator) }}</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            @if ($gs->is_invoice_photo)<td></td>@endif
                                            <td colspan="2">{{ __('Total') }}</td>
                                            <td>{{ $order->currency_sign }}{{ number_format($order->pay_amount * $order->currency_value, $order_curr->decimal_digits, $order_curr->decimal_separator, $order_curr->thousands_separator) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
