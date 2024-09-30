@extends('layouts.vendor')
@section('content')
<div class="content-area" style="position:relative;display:flex;align-items:center;justify-content:center;">
    <img style="position:fixed;opacity:0.3;max-width:800px;width:100%;" src="{{ asset('storage/images/'.$gs->logo) }}" alt="">
    @if($user->checkWarning())
    <div class="alert alert-danger validation text-center">
        <h3>{{ $user->displayWarning() }}</h3>
        <a href="{{ route('vendor-warning',$user->verifies()->where('admin_warning','1')->orderBy('id','desc')->first()->id) }}">{{ __("Verify Now") }}</a>
    </div>
    @endif
    @include('includes.form-success')
    <div style="align-self:start;" class="row row-cards-one">
        @foreach ([
            ['bg1', 'Orders Pending!', count($pending), 'vendor-order-index', 'icofont-dollar'],
            ['bg2', 'Orders Processing!', count($processing), 'vendor-order-index', 'icofont-truck-alt'],
            ['bg3', 'Orders Completed!', count($completed), 'vendor-order-index', 'icofont-check-circled'],
            ['bg4', 'Total Products!', count($user->products), 'vendor-prod-index', 'icofont-cart-alt'],
            ['bg5', 'Total Item Sold!', App\Models\VendorOrder::where('user_id', $user->id)->where('status', 'completed')->sum('qty'), null, 'icofont-shopify'],
            ['bg6', 'Total Earnings!', App\Models\Product::vendorConvertPrice(App\Models\VendorOrder::where('user_id', $user->id)->where('status', 'completed')->sum('price')), null, 'icofont-dollar-true']
        ] as $card)
        <div class="col-md-12 col-lg-6 col-xl-4">
            <div class="mycard {{ $card[0] }}">
                <div class="left">
                    <h5 class="title">{{ __($card[1]) }}</h5>
                    <span class="number">{{ $card[2] }}</span>
                    @if($card[3])<a href="{{ route($card[3]) }}" class="link">{{ __("View All") }}</a>@endif
                </div>
                <div class="right d-flex align-self-center">
                    <div class="icon"><i class="{{ $card[4] }}"></i></div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
