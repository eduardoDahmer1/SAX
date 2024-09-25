@extends('layouts.admin')
@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Order Details') }} <a class="add-btn" href="javascript:history.back();"><i class="fas fa-arrow-left"></i> {{ __('Back') }}</a></h4>
                <ul class="links">
                    <li><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li><a href="{{ route('admin-order-index') }}">{{ __('Orders') }}</a></li>
                    <li><a href="{{ route('admin-order-show', $order->id) }}">{{ __('Order Details') }}</a></li>
                    <li><a href="#">{{ __('Request Melhor Envio') }}</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="add-product-content">
        <div class="row product-description">
            <div class="col-lg-12 body-area">
                <form id="melhorenvio-request-form" action="{{ route('admin-order-select-melhorenvio-service') }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    @include('includes.form-success')
                    @if (count($order->melhorenvio_requests) > 0)
                    <div class="alert alert-warning validation">
                        <button type="button" class="close alert-close"><span>×</span></button>
                        <ul class="text-left">{{__('This order already has an Melhor Envio request')}}</ul>
                    </div>
                    @endif
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    <div class="row justify-content-center">
                        <div class="col-lg-9"><div class="row justify-content-center"><h4 class="heading">{{ __('Please confirm package') }}</h4></div></div>
                    </div>
                    @foreach (['origin_zipcode' => __('From Postal Code'), 'dest_zipcode' => __('Destination Postal Code'), 'package[height]' => __('Height') . ' (cm)', 'package[width]' => __('Width') . ' (cm)', 'package[length]' => __('Length') . ' (cm)', 'package[weight]' => __('Weight') . ' (kg)', 'options[insurance_value]' => __('Insurance value') . ' (R$)'] as $name => $label)
                    <div class="row justify-content-center">
                        <div class="col-lg-3"><div class="left-area"><h4 class="heading">{{ $label }}:</h4></div></div>
                        <div class="col-lg-6"><input name="{{ $name }}" class="input-field" value="{{ $$name }}" required type="number" step="{{ strpos($name, 'weight') !== false ? '0.001' : '1' }}"></div>
                    </div>
                    @endforeach
                    @foreach (['receipt' => __('Receipt'), 'own_hand' => __('Own hand')] as $name => $label)
                    <div class="row justify-content-center">
                        <div class="col-lg-3"><div class="left-area"><h4 class="heading">{{ $label }}:</h4></div></div>
                        <div class="col-lg-6">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="options[{{ $name }}]" class="custom-control-input" id="options[{{ $name }}]" {{ $options->$name ? "checked" : "" }} value="1">
                                <label class="custom-control-label" for="options[{{ $name }}]"></label>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <div class="row justify-content-center mt-3">
                        <button class="mybtn1" type="submit"><i class="fas fa-chevron-right"></i> {{ __('Next') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
