@extends('front.themes.' . env('THEME', 'theme-15') . '.layout')
@section('content')
<section class="user-dashbord">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="user-profile-details">
                    <div class="account-info">
                        <div class="header-area">
                            <h4 class="title">{{ __('Plan Details') }} <a class="mybtn1" href="{{ route('user-package') }}"><i class="fas fa-arrow-left"></i> {{ __('Back') }}</a></h4>
                        </div>
                        <div class="pack-details">
                            <div class="row">
                                <div class="col-lg-4"><h5 class="title">{{ __('Plan:') }}</h5></div>
                                <div class="col-lg-8"><p class="value">{{ $subs->title }}</p></div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4"><h5 class="title">{{ __('Price:') }}</h5></div>
                                <div class="col-lg-8"><p class="value">{{ $curr->sign }} {{ $subs->price }}</p></div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4"><h5 class="title">{{ __('Durations:') }}</h5></div>
                                <div class="col-lg-8"><p class="value">{{ $subs->days }} {{ __('Day(s)') }}</p></div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4"><h5 class="title">{{ __('Product(s) Allowed:') }}</h5></div>
                                <div class="col-lg-8"><p class="value">{{ $subs->allowed_products == 0 ? __('Unlimited') : $subs->allowed_products }}</p></div>
                            </div>
                            @if (!empty($package))
                                @if ($package->subscription_id != $subs->id)
                                    <div class="row"><div class="col-lg-4"></div><div class="col-lg-8"><span class="notic"><b>{{ __('Note:') }}</b> {{ __('Your Previous Plan will be deactivated!') }}</span></div></div><br>
                                @else <br>@endif
                            @else <br>@endif
                            <form id="subscribe-form" class="pay-form" action="{{ route('user-vendor-request-submit') }}" method="POST">
                                @include('includes.form-success')
                                @include('includes.form-error')
                                @include('includes.admin.form-error')
                                {{ csrf_field() }}

                                @if ($user->is_vendor == 0)
                                    @foreach ([
                                        ['shop_name', __('Shop Name'), true],
                                        ['vendor_corporate_name', __('Corporate Name'), true],
                                        ['vendor_phone', __('Phone'), true],
                                        ['vendor_opening_hours', __('Opening Hours'), true],
                                        ['vendor_payment_methods', __('Payment Methods'), true],
                                        ['vendor_delivery_info', __('Delivery Info'), false],
                                        ['reg_number', __('Registration Number'), true],
                                        ['owner_name', __('Owner Name'), true],
                                        ['shop_number', __('Shop Number'), true],
                                        ['shop_address', __('Shop Address'), true],
                                        ['vendor_map_embed', __('Embed Google Maps') . ' <small>' . __('(Optional)') . '</small>', false],
                                        ['shop_details', __('Shop Details'), true, 'textarea'],
                                        ['shop_message', __('Message') . ' <small>' . __('(Optional)') . '</small>', false, 'textarea'],
                                    ] as $input)
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="left-area">
                                                    <h5 class="heading">{{ $input[1] }} {{ $input[2] ? '*' : '' }}</h5>
                                                </div>
                                            </div>
                                            <div class="col-lg-8">
                                                @if (isset($input[3]) && $input[3] === 'textarea')
                                                    <textarea class="input-field trumboedit" name="{{ $input[0] }}" placeholder="{{ $input[1] }}"></textarea>
                                                @else
                                                    <input type="text" class="input-field" name="{{ $input[0] }}" placeholder="{{ $input[1] }}" {{ $input[2] ? 'required' : '' }}>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                    <br>
                                @endif
                                <input type="hidden" name="subs_id" value="{{ $subs->id }}">
                                <div class="row"><div class="col-lg-4"></div><div class="col-lg-8"><button type="submit" id="final-btn" class="mybtn1">{{ __('Submit') }}</button></div></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script type="text/javascript">
    $(document).on('submit', '#subscribe-form', function() {
        $('#preloader').show();
    });
</script>
@endsection
