<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">@foreach ($seos as $seo)
    <meta name="keywords" content="{{ $seo->meta_keys }}">@endforeach
    <meta name="author" content="CrowTech">
    <title>{{ $gs->title }}</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{ asset('assets/print/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/print/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/print/Ionicons/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/print/css/style.css') }}">
    <link href="{{ asset('assets/print/css/print.css') }}" rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <link rel="icon" type="image/png" href="{{ asset('storage/images/' . $gs->favicon) }}">
    <style type="text/css">
    @page {
        size: auto;
        margin: 0mm;
    }

    @page {
        size: A4;
        margin: 0;
    }

    @media print {

        .no-print,
        .no-print * {
            display: none !important;
        }

        html,
        body {
            width: 210mm;
            height: 287mm;
        }

        ::-webkit-scrollbar {
            width: 0px;
            background: transparent;
        }
    }
    </style>
</head>

<body>
    <div class="invoice-wrap">
        <div class="invoice__title">
            <div class="row">
                <div class="col-sm-6">
                    <div class="text-left"><img src="{{ $gs->invoiceLogoUrl }}" style="height:80px;" alt=""></div>
                </div>
            </div>
        </div><br><button class="btn btn-success no-print" onclick="window.print()"
            style="margin-left:15px;background: rgb(0, 177, 0);">Imprimir</button><br>
        <div class="invoice__metaInfo">
            <div class="col-lg-6">
                <div class="invoice__orderDetails">
                    <p><strong>{{ __('Order Details') }} </strong></p><span><strong>{{ __('Invoice Number') }}
                            :</strong>
                        {{ sprintf("%'.08d", $order->id) }}</span><br><span><strong>{{ __('Order Date') }}
                            :</strong>{{ date('d-M-Y', strtotime($order->created_at)) }}</span><br><span><strong>{{ __('Order ID') }}
                            :</strong> {{ $order->order_number }}</span><br><span><strong>{{__('CEC Number')}}
                            :</strong> {{$order->number_cec}}</span><br>@if ($order->dp ==
                    0)<span><strong>{{ __('Shipping Method') }} :</strong>@if ($order->shipping ==
                        3){{ __('Pick Up') }}@else{{ __('Ship To Address') }}@endif</span><br>@endif@if
                    ($order->shipping == 3)<span><strong>{{ __('Retirar em') }}
                            :</strong>{{$order->pickup_location}}</span><br>@endif@if ($order->shipping !=
                    3)<span><strong>{{ __('Shipping Type') }} :</strong> {{ $order->shipping_type }}</span><br>@if
                    ($order->puntoentrega != null)<span><strong>{{ __('Delivery Point') }}
                            :</strong>{{ $order->puntoentrega }}<br></span><br>@endif<span><strong>{{ __('Packing Type') }}
                            :</strong>
                        {{ $order->packing_type }}</span><br>@endif<span><strong>{{ __('Payment Method') }} :</strong>
                        {{ $order->method }}</span>@if
                    (!empty($order->order_note))<br><span><strong>{{ __('Order Note') }} :</strong>
                        {{ $order->order_note }}</span>@endif
                </div>
            </div>
        </div>
        <div class="invoice__metaInfo" style="margin-top:0px;">@if ($order->dp == 0)<div class="col-lg-6">
                <div class="invoice__orderDetails" style="margin-top:5px;">
                    <p><strong>{{ __('Shipping Details') }}</strong></p>
                    <span><strong>{{ __('Customer Name') }}</strong>:
                        {{ $order->shipping_name == null ? $order->customer_name : $order->shipping_name }}</span><br><span><strong>{{ __('Address') }}</strong>:
                        {{ $order->shipping_address == null ? $order->customer_address : $order->shipping_address }}</span><br><span><strong>{{ __('Number') }}</strong>:
                        {{ $order->shipping_address_number == null ? $order->customer_address_number . ' ' . $order->customer_complement : $order->shipping_address_number . ' ' . $order->shipping_complement }}</span><br><span><strong>{{ __('District') }}</strong>:
                        {{ $order->shipping_district == null ? $order->customer_district : $order->shipping_district }}</span><br><span><strong>{{ __('City') }}</strong>:
                        {{ $order->shipping_city == null ? $order->customer_city : $order->shipping_city }}</span><br><span><strong>{{ __('State') }}</strong>:
                        {{ $order->shipping_state == null ? $order->customer_state : $order->shipping_state }}</span><br><span><strong>{{ __('Country') }}</strong>:
                        {{ $order->shipping_country == null ? $order->customer_country : $order->shipping_country }}</span><br><span><strong>{{ __('Postal Code') }}</strong>:
                        {{ $order->shipping_zip == null ? $order->customer_zip : $order->shipping_zip }}</span>
                </div>
            </div>@endif<div class="col-lg-6" style="width:50%;">
                <div class="invoice__orderDetails" style="margin-top:5px;">
                    <p><strong>{{ __('Billing Details') }}</strong></p><span><strong>{{ __('Customer Name') }}</strong>:
                        {{ $order->customer_name }}</span><br><span><strong>{{ __('Customer Email') }}</strong>:
                        {{ $order->customer_email }}</span><br><span><strong>{{ __('Customer') . ' ' . $customer_doc_str }}</strong>:
                        {{ $order->customer_document }}</span><br><span><strong>{{ __('Customer Phone') }}</strong>:
                        {{ $order->customer_phone }}</span><br><span><strong>{{ __('Address') }}</strong>:
                        {{ $order->customer_address }}</span><br><span><strong>{{ __('Number') }}</strong>:
                        {{ $order->customer_address_number }}
                    </span><br><span><strong>{{ __('Customer complement') }}</strong>:
                        {{ $order->customer_complement }}</span><br><span><strong>{{ __('District') }}</strong>:
                        {{ $order->customer_district }}</span><br><span><strong>{{ __('City') }}</strong>:
                        {{ $order->customer_city }}</span><br><span><strong>{{ __('State') }}</strong>:
                        {{ $order->customer_state }}</span><br><span><strong>{{ __('Country') }}</strong>:
                        {{ $order->customer_country }}</span><br><span><strong>{{ __('Postal Code') }}</strong>:
                        {{ $order->customer_zip }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="invoice_table">
                <div class="mr-table">
                    <div class="table-responsive">
                        <table id="example2" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                            <thead style="border-top:1px solid rgba(0, 0, 0, 0.1) !important;">
                                <tr>
                                    <th>{{ __('Product') }}</th>
                                    <th>{{ __('Details') }}</th>
                                    <th>{{ __('Total') }}</th>
                                </tr>
                            </thead>
                            <tbody>@php $subtotal = 0;$tax = 0; @endphp @foreach ($cart['items'] as $product)<tr>
                                    <td width="50%">@if ($product['item']['user_id'] != 0)@php $user =
                                        App\Models\User::find($product['item']['user_id']); @endphp @if
                                        (isset($user)){{ $product['item']['name'] }}@else{{ $product['item']['name'] }}@endif
                                        @else{{ $product['item']['name'] }}@endif @php $prod =
                                        App\Models\Product::find($product['item']['id']); @endphp @if (isset($prod))<p
                                            style="margin-bottom: 0; font-size: 10px"> {{ __('Product SKU') }} -
                                            {{ $prod->sku }}</p>
                                        <p style="font-size: 10px"> {{ __('Reference Code') }} - {{ $prod->ref_code }}
                                        </p>@if ($gs->is_invoice_photo)<p><img
                                                src="{{ asset('storage/images/products') . '/' . $prod->photo }}"
                                                width="200" alt=""></p>@endif @endif
                                    </td>
                                    <td>@if ($product['size'])<p><strong>{{ __('Size') }}:
                                            </strong>{{ str_replace('-',' ', $product['size']) }}</p>@endif @if
                                        ($product['color'])<p><strong>{{ __('color') }} :</strong> <span
                                                style="width: 20px; height: 5px; display: block; border: 10px solid {{ $product['color'] == '' ? "white" : '#' . $product['color'] }};"></span>
                                        </p>@endif<p><strong>{{ __('Price') }}
                                                :</strong>{{ $order->currency_sign }}{{ number_format($product['item']['price'] * $order->currency_value, $order_curr->decimal_digits, $order_curr->decimal_separator, $order_curr->thousands_separator) }}
                                        </p>
                                    </td>
                                    <td>@php $total = $product['price'] * $product['qty']; $subtotal += $total;
                                        @endphp{{ $order->currency_sign }}{{ number_format($total * $order->currency_value, $order_curr->decimal_digits, $order_curr->decimal_separator, $order_curr->thousands_separator) }}
                                    </td>
                                </tr>@endforeach</tbody>
                            <tfoot style="border-top:1px solid rgba(0, 0, 0, 0.1) !important;">
                                <tr>
                                    <th colspan="2" class="text-right">{{ __('Subtotal') }}</th>
                                    <th>{{ $order->currency_sign }}{{ number_format($subtotal * $order->currency_value, $order_curr->decimal_digits, $order_curr->decimal_separator, $order_curr->thousands_separator) }}
                                    </th>
                                </tr>@if ($order->shipping_cost > 0)<tr>
                                    <th colspan="2" class="text-right">{{ __('Shipping Cost') }}</th>
                                    <th>{{ $order->currency_sign }}{{ number_format($order->shipping_cost * $order->currency_value, $order_curr->decimal_digits, $order_curr->decimal_separator, $order_curr->thousands_separator) }}
                                    </th>
                                </tr>@endif@if ($order->discount > 0)<tr>
                                    <th colspan="2" class="text-right">{{ __('Discount') }}</th>
                                    <th>-
                                        {{ $order->currency_sign }}{{ number_format($order->discount * $order->currency_value, $order_curr->decimal_digits, $order_curr->decimal_separator, $order_curr->thousands_separator) }}
                                    </th>
                                </tr>@endif@if ($order->tax > 0)<tr>
                                    <th colspan="2" class="text-right">{{ __('Tax') }}</th>
                                    <th>{{ $order->currency_sign }}{{ number_format($order->tax * $order->currency_value, $order_curr->decimal_digits, $order_curr->decimal_separator, $order_curr->thousands_separator) }}
                                    </th>
                                </tr>@endif<tr>
                                    <th colspan="2" class="text-right">{{ __('Total') }}</th>
                                    <th>{{ $order->currency_sign }}{{ number_format($order->total * $order->currency_value, $order_curr->decimal_digits, $order_curr->decimal_separator, $order_curr->thousands_separator) }}
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div><br><br><br>
    </div>
    </div>
    </div>
</body>

</html>