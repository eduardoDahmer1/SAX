@extends('front.themes.' . env('THEME', 'theme-15') . '.layout')
@section('content')
<style>.super-title { font-size: 60px; line-height: 50px; font-weight: bold; color: #000; text-align: center; padding-bottom: 20px; }.title { font-size: 24px; font-weight: bold; color: #000; }iframe { width: 100%; height: 450px; }</style>
<div class="vendor-banner" style="background: url({{ $vendor->shop_image ? asset('storage/images/vendorbanner/' . $vendor->shop_image) : '' }}); background-repeat: no-repeat; background-size: cover; background-position: center;{{ $vendor->shop_image ? '' : 'background-color:' . $gs->vendor_color }};"></div>
<hr>
<div class="container">
    <div class="row">
        <div class="col-xl-12 text-center">
            <p class="super-title">{{ $vendor->shop_name }}</p>
        </div>
    </div>
    <div class="row text-center">
        @foreach([
            ['title' => __('Corporate Name'), 'value' => $vendor->vendor_corporate_name],
            ['title' => __('CNPJ'), 'value' => $vendor->vendor_document],
            ['title' => __('Phone') . '(s)', 'value' => $vendor->vendor_phone],
            ['title' => __('Funcionamento'), 'value' => $vendor->vendor_opening_hours],
            ['title' => __('Payment'), 'value' => $vendor->vendor_payment_methods],
            ['title' => __('Delivery Info'), 'value' => $vendor->vendor_delivery_info],
        ] as $info)
            <div class="col-lg-4">
                <p class="title">{{ $info['title'] }}</p>
                <h4 class="sub-title">{{ $info['value'] }}</h4>
            </div>
        @endforeach
    </div>
    @if ($vendor->shop_details)
        <br><br>
        <div class="row text-center">
            <div class="col-lg-12">
                <p class="title">{{ __('Details') }}</p>
                <h4 class="sub-title">{{ $vendor->shop_details }}</h4>
            </div>
        </div>
    @endif
    @if ($vendor->vendor_map_embed)
        <br><br>
        <div class="row text-center">
            <div class="col-lg-12">
                <p class="title">{{ __('Map') }}</p>
                <h4 class="sub-title">{!! $vendor->vendor_map_embed !!}</h4>
            </div>
        </div>
    @endif
</div>
<section class="info-area">
    <div class="container">
        @foreach ($services_vendor->chunk(4) as $chunk)
            <div class="row">
                <div class="col-lg-12 p-0">
                    <div class="info-big-box">
                        <div class="row">
                            @foreach ($chunk as $service)
                                <div class="col-6 col-xl-3 p-0">
                                    <div class="info-box">
                                        <div class="icon">
                                            <img src="{{ asset('storage/images/services/' . $service->photo) }}">
                                        </div>
                                        <div class="info">
                                            <div class="details">
                                                <h4 class="title">{{ $service->title }}</h4>
                                                <p class="text">{{ $service->details }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>
<section class="sub-categori">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 order-first order-lg-last">
                <div class="right-area">
                    @if ($vprods->isNotEmpty())
                        @include('includes.vendor-filter')
                        <div class="categori-item-area">
                            <div class="row">
                                @foreach ($vprods as $prod)
                                    @include('includes.product.vendor')
                                @endforeach
                            </div>
                            <div class="page-center category">
                                {!! $vprods->appends(request()->only(['sort', 'min', 'max']))->links() !!}
                            </div>
                        </div>
                    @else
                        <div class="page-center">
                            <h4 class="text-center">{{ __('No Product Found.') }}</h4>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@if (Auth::guard('web')->check())
<div class="message-modal">
    <div class="modal" id="vendorform1" tabindex="-1" aria-labelledby="vendorformLabel1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="vendorformLabel1">{{ __('Send Message') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="emailreply">
                        @csrf
                        <ul>
                            <li><input type="text" class="input-field" readonly placeholder="Send To {{ $vendor->shop_name }}"></li>
                            <li><input type="text" class="input-field" id="subj" name="subject" placeholder="{{ __('Subject *') }}" required></li>
                            <li><textarea class="input-field textarea" name="message" id="msg" placeholder="{{ __('Your Message') }}" required></textarea></li>
                            <input type="hidden" name="email" value="{{ Auth::guard('web')->user()->email }}">
                            <input type="hidden" name="name" value="{{ Auth::guard('web')->user()->name }}">
                            <input type="hidden" name="user_id" value="{{ Auth::guard('web')->user()->id }}">
                            <input type="hidden" name="vendor_id" value="{{ $vendor->id }}">
                        </ul>
                        <button class="submit-btn" id="emlsub1" type="submit">{{ __('Send Message') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
@section('scripts')
<script>
    $(function() {
        $("#slider-range").slider({
            range: true,
            min: 0,
            max: {{ $range_max }},
            values: [{{ request('min', 0) }}, {{ request('max', $range_max) }}],
            step: 5,
            slide: function(event, ui) {
                if (ui.values[0] === ui.values[1]) return false;
                $("#min_price").val(ui.values[0]);
                $("#max_price").val(ui.values[1]);
            }
        }).slider("values", [$("#min_price").val(), $("#max_price").val()]);

        $("#emailreply").on("submit", function() {
            var $form = $(this),
                data = $form.serialize();
            $('#subj, #msg, #emlsub1').prop('disabled', true);
            $.post("{{ url('/vendor/contact') }}", data, function() {
                $('#subj, #msg').val('').prop('disabled', false);
                $('#emlsub1').prop('disabled', false);
                toastr.success("{{ __('Message Sent !!') }}");
                $('.ti-close').click();
            });
            return false;
        });
    });
</script>
@endsection
