@extends('layouts.vendor')
@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __("Order Invoice") }} <a class="add-btn" href="{{ route('vendor-order-show',$order->id) }}"><i class="fas fa-arrow-left"></i> {{ __("Back") }}</a></h4>
                <ul class="links">
                    <li><a href="{{ route('vendor-dashboard') }}">{{ __("Dashboard") }} </a></li>
                    <li><a href="javascript:;">{{ __("All Orders") }}</a></li>
                    <li><a href="javascript:;">{{ __("Order Invoice") }}</a></li>
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
                            <img src="{{ asset('storage/images/'.$gs->invoice_logo) }}" alt="woo commerce logo">
                        </div>
                    </div>
                    <div class="col-lg-6 text-right">
                        <a class="btn add-newProduct-btn print" href="{{route('vendor-order-print',$order->order_number)}}" target="_blank"><i class="fa fa-print"></i> {{ __("Print Invoice") }}</a>
                    </div>
                </div>
            </div>
            <br>
            <div class="row invoice__metaInfo mb-4">
                <div class="col-lg-6">
                    <div class="invoice__orderDetails">
                        <p><strong>{{ __("Order Details") }}</strong></p>
                        <span><strong>{{ __("Invoice Number") }} :</strong> {{ sprintf("%'.08d", $order->id) }}</span><br>
                        <span><strong>{{ __("Order Date") }} :</strong> {{ date('d-M-Y',strtotime($order->created_at)) }}</span><br>
                        <span><strong>{{ __("Order ID") }} :</strong> {{ $order->order_number }}</span><br>
                        @if($order->dp == 0)
                        <span><strong>{{ __("Shipping Method") }} :</strong> {{ $order->shipping == "pickup" ? __("Pick Up") : __("Ship To Address") }}</span><br>
                        @endif
                        <span><strong>{{ __("Payment Method") }} :</strong> {{$order->method}}</span>
                    </div>
                </div>
            </div>
            <div class="row invoice__metaInfo">
                @if($order->dp == 0)
                <div class="col-lg-6">
                    <div class="invoice__shipping">
                        <p><strong>{{ __("Shipping Address") }}</strong></p>
                        <span><strong>{{ __("Customer Name") }} :</strong> {{ $order->shipping_name ?? $order->customer_name }}</span><br>
                        <span><strong>{{ __("Address") }} :</strong> {{ $order->shipping_address ?? $order->customer_address }}</span><br>
                        <span><strong>{{ __("City") }} :</strong> {{ $order->shipping_city ?? $order->customer_city }}</span><br>
                        <span><strong>{{ __("Country") }} :</strong> {{ $order->shipping_country ?? $order->customer_country }}</span>
                    </div>
                </div>
                @endif
                <div class="col-lg-6">
                    <div class="buyer">
                        <p><strong>{{ __("Billing Address") }}</strong></p>
                        <span><strong>{{ __("Customer Name") }} :</strong> {{ $order->customer_name}}</span><br>
                        <span><strong>{{ __("Address") }} :</strong> {{ $order->customer_address }}</span><br>
                        <span><strong>{{ __("City") }} :</strong> {{ $order->customer_city }}</span><br>
                        <span><strong>{{ __("Country") }} :</strong> {{ $order->customer_country }}</span>
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
                                            <th>{{ __("Product") }}</th>
                                            <th>{{ __("Details") }}</th>
                                            <th>{{ __("Total") }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $subtotal = 0; $tax = 0; @endphp
                                        @foreach($cart->items as $product)
                                        @if($product['item']['user_id'] == $user->id)
                                        <tr>
                                            <td width="50%">
                                                <a target="_blank" href="{{ route('front.product', $product['item']['slug']) }}">{{ $product['item']['name']}}</a>
                                            </td>
                                            <td>
                                                @if($product['size'])<p><strong>{{ __("Size") }} :</strong> {{str_replace('-', ' ', $product['size'])}}</p>@endif
                                                @if($product['color'])<p><strong>{{ __("Color") }} :</strong> <span style="width: 40px; height: 20px; display: block; background: #{{$product['color']}};"></span></p>@endif
                                                <p><strong>{{ __("Price") }} :</strong> {{$order->currency_sign}}{{ round($product['item']['price'] * $order->currency_value, 2) }}</p>
                                                <p><strong>{{ __("Qty") }} :</strong> {{$product['qty']}} {{ $product['item']['measure'] }}</p>
                                                @foreach(array_combine(explode(',', $product['keys']), explode('~', $product['values'])) as $key => $value)
                                                <p><b>{{ ucwords(str_replace('_', ' ', $key)) }} : </b> {{ $value }}</p>
                                                @endforeach
                                            </td>
                                            <td>{{$order->currency_sign}}{{ round($product['price'] * $order->currency_value, 2) }}</td>
                                            @php $subtotal += round($product['price'] * $order->currency_value, 2); @endphp
                                        </tr>
                                        @endif
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr><td colspan="2">{{ __("Subtotal") }}</td><td>{{$order->currency_sign}}{{ round($subtotal, 2) }}</td></tr>
                                        @if(Auth::user()->id == $order->vendor_shipping_id && $order->shipping_cost != 0)
                                        <tr><td colspan="2">{{ Shipping::where('price', round($order->shipping_cost / $order->currency_value,2))->first()->title }}({{$order->currency_sign}})</td><td>{{ round($order->shipping_cost, 2) }}</td></tr>
                                        @endif
                                        @if(Auth::user()->id == $order->vendor_packing_id && $order->packing_cost != 0)
                                        <tr><td colspan="2">{{ Package::where('price', round($order->packing_cost / $order->currency_value,2))->first()->title }}({{$order->currency_sign}})</td><td>{{ round($order->packing_cost, 2) }}</td></tr>
                                        @endif
                                        @if($order->tax != 0)
                                        @php $tax = ($subtotal / 100) * $order->tax; $subtotal += $tax; @endphp
                                        <tr><td colspan="2">{{ __("TAX") }}({{$order->currency_sign}})</td><td>{{ round($tax, 2) }}</td></tr>
                                        @endif
                                        <tr><td colspan="1"></td><td>{{ __("Total") }}</td><td>{{$order->currency_sign}}{{ round($subtotal, 2) }}</td></tr>
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
