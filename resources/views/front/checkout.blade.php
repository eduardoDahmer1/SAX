@extends('front.themes.theme-15.checkout_layout')

@section('content')


{{-- @if ($checked)
<!-- LOGIN MODAL -->
<div class="modal fade" id="comment-log-reg1" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="comment-log-reg-Title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" aria-label="Close">
                    <a href="{{ url()->previous() }}"><span aria-hidden="true">&times;</span></a>
                </button>
            </div>
            <div class="modal-body">
                <nav class="comment-log-reg-tabmenu">
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link login active" id="nav-log-tab" data-toggle="tab" href="#nav-log"
                            role="tab" aria-controls="nav-log" aria-selected="true">
                            {{ __('Login') }}
                        </a>
                        <a class="nav-item nav-link" id="nav-reg-tab" data-toggle="tab" href="#nav-reg" role="tab"
                            aria-controls="nav-reg" aria-selected="false">
                            {{ __('Register') }}
                        </a>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-log" role="tabpanel" aria-labelledby="nav-log-tab">
                        <div class="login-area">
                            <div class="header-area">
                                <h4 class="title" style="font-family: auto;">{{ __('LOGIN NOW') }}</h4>
                            </div>
                            <div class="login-form signin-form">
                                @include('includes.admin.form-login')
                                <form id="loginform" action="{{ route('user.login.submit') }}" method="POST">
                                    {{ csrf_field() }}
                                    <div class="form-input">
                                        <input type="email" name="email" placeholder="{{ __('Type Email Address') }} *"
                                            required="">
                                        <i class="icofont-user-alt-5"></i>
                                    </div>
                                    <div class="form-input">
                                        <input type="password" class="Password" name="password"
                                            placeholder="{{ __('Type Password') }} *" required="">
                                        <i class="icofont-ui-password"></i>
                                    </div>
                                    <div class="form-forgot-pass">
                                        <div class="left">
                                            <input type="hidden" name="modal" value="1">
                                            <input type="checkbox" name="remember" id="mrp" {{ old('remember')
                                                ? 'checked' : '' }}>
                                            <label for="mrp">{{ __('Remember Password') }}</label>
                                        </div>
                                        <div class="right">
                                            <a href="{{ route('user-forgot') }}">
                                                {{ __('Forgot Password?') }}
                                            </a>
                                        </div>
                                    </div>
                                    <input id="authdata" type="hidden" value="{{ __('Authenticating...') }}">
                                    <button type="submit" class="submit-btn">{{ __('Login') }}</button>
                                    @if (App\Models\Socialsetting::find(1)->f_check == 1 ||
                                    App\Models\Socialsetting::find(1)->g_check == 1)
                                    <div class="social-area">
                                        <h3 class="title">{{ __('Or') }}</h3>
                                        <p class="text">{{ __('Sign In with social media') }}</p>
                                        <ul class="social-links">
                                            @if (App\Models\Socialsetting::find(1)->f_check == 1)
                                            <li>
                                                <a href="{{ route('social-provider', 'facebook') }}">
                                                    <i class="fab fa-facebook-f"></i>
                                                </a>
                                            </li>
                                            @endif
                                            @if (App\Models\Socialsetting::find(1)->g_check == 1)
                                            <li>
                                                <a href="{{ route('social-provider', 'google') }}">
                                                    <i class="fab google"></i>
                                                </a>
                                            </li>
                                            @endif
                                        </ul>
                                    </div>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>


                    <div class="tab-pane fade" id="nav-reg" role="tabpanel" aria-labelledby="nav-reg-tab">
                        <div class="login-area signup-area">
                            <div class="header-area">
                                <h4 class="title">{{ __('Signup Now') }}</h4>
                            </div>
                            <div class="login-form signup-form">
                                @include('includes.admin.form-login')
                                <form id="registerform" action="{{ route('user-register-submit') }}" method="POST">
                                    {{ csrf_field() }}
                                    <div class="form-input">
                                        <input type="text" class="User Name" name="name"
                                            title="{{ __('Input first name and last name') }}"
                                            placeholder="{{ __('Full Name') }} *" required="" pattern="^(\S*)\s+(.*)$">
                                        <i class="icofont-user-alt-5"></i>
                                    </div>
                                    <div class="form-input">
                                        <input type="email" class="User Name" name="email"
                                            placeholder="{{ __('Email Address') }} *" required="">
                                        <i class="icofont-email"></i>
                                    </div>
                                    <div class="form-input">
                                        <input type="password" class="Password" name="password"
                                            placeholder="{{ __('Password') }} *" required="">
                                        <i class="icofont-ui-password"></i>
                                    </div>
                                    <div class="form-input">
                                        <input type="password" class="Password" name="password_confirmation"
                                            placeholder="{{ __('Confirm Password') }} *" required="">
                                        <i class="icofont-ui-password"></i>
                                    </div>
                                    @if ($gs->is_capcha == 1)
                                    <ul class="captcha-area">
                                        <li>
                                            <p><img class="codeimg1" src="{{ asset('storage/images/capcha_code.png') }}"
                                                    alt=""> <i class="fas fa-sync-alt pointer refresh_code "></i></p>
                                        </li>
                                    </ul>
                                    <div class="form-input">
                                        <input type="text" class="Password" name="codes"
                                            placeholder="{{ __('Enter Code') }} *" required="">
                                        <i class="icofont-refresh"></i>
                                    </div>
                                    @endif
                                    @php
                                    $url = $gs->privacy_policy ? true : false;
                                    @endphp
                                    <div class="form-forgot-pass">
                                        <div class="left">
                                            <input type="checkbox" name="agree_privacy_policy"
                                                id="agree_privacy_policy">
                                            <label for="agree_privacy_policy">{{ __("I agree with the") }} <a
                                                    target="_blank"
                                                    href="{{ $url ? route('front.privacypolicy') : '' }}"> {{
                                                    __("Privacy Policy") }}</a>.</label>
                                        </div>
                                    </div>
                                    <input id="processdata" type="hidden" value="{{ __('Processing...') }}">
                                    <button type="submit" class="submit-btn">{{ __('Register') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- LOGIN MODAL ENDS -->
@endif --}}

<body>
    <header class="bg-black text-center py-3 mb-5"><img src="https://i.ibb.co/1dFF5PK/logosax.png" alt=""></header>

    <div class="container">
        <div class="row justify-content-center">

            <div class="step-icons d-flex col-10 col-md-8 align-items-center">
                <div class="d-flex align-items-center"><i class="bi bi-bag-check-fill color-2"></i></div>
                <div class="line"></div>
                <div class="d-flex align-items-center"><i class="bi bi-person-fill"></i></div>
                <div class="line"></div>
                <div class="d-flex align-items-center"><i class="bi bi-truck"></i></div>
                <div class="line"></div>
                <div class="d-flex align-items-center"><i class="bi bi-credit-card"></i></div>
            </div>

            <form id="myform" action="/enviar-dados" method="POST" class="checkoutform">
                {{ csrf_field() }}

                <!-- Step 1 -->
                <div class="step col-10 row align-items-center justify-content-center mt-4">
                    <div class="d-flex align-items-center bg-top my-4 py-2">
                        <h6 class="col-8 text-uppercase">{{ __('Product') }}</h6>
                        <h6 class="col-2 d-lg-block d-none text-uppercase">{{ __('Amount') }}</h6>
                        <h6 class="col-2 d-lg-block d-none text-uppercase">{{ __('Price') }}</h6>
                    </div>
                    @foreach ($products as $product)
                    <div class="d-flex flex-wrap align-items-center p-0 pb-5 border-bottom-f1 mb-4">
                        <div class="col-lg-8 prod-img px-0">
                            <img src="{{ filter_var($product['item']['photo'], FILTER_VALIDATE_URL) ? $product['item']['photo'] : asset('storage/images/products/' . $product['item']['photo']) }}"
                                alt="">
                            <div class="pl-sm-4 pl-1">
                                <h5 class="fw-normal fs-16">{{ $product['item']->name }}</h5>
                                <p class="color-1">{{ __('Product code') }}: {{ $product['item']->sku }}</p>
                            </div>
                        </div>
                        <div class="col-12 d-flex align-items-center bg-top my-4 py-2 d-lg-none d-block">
                            <h6 class="col-6 text-uppercase">{{ __('Amount') }}</h6>
                            <h6 class="col-6 text-uppercase">{{ __('Price') }}</h6>
                        </div>
                        <p class="col-lg-2 col-6 m-lg-0 mt-3">{{ $product['qty'] }}</p>
                        <div class="col-lg-2 prices col-6">
                            <h5 class="mb-0 fw-semibold">{{ App\Models\Product::convertPrice($product['item']['price'])
                                }}
                            </h5>
                            <span>{{ App\Models\Product::convertPriceDolar($product['item']['price']) }}</span>
                        </div>
                    </div>
                    @endforeach


                    <div class="bg-top py-5 d-flex flex-wrap justify-content-between mt-3">
                        <div class="prices d-flex justify-content-between px-2 col-12 col-md-7">
                            <p class="color-1 m-0">{{ __('Total') }} ({{$totalQty}} {{ __('items') }}):</p>
                            <div class="px-lg-5">
                                <h5 class="mb-0 fw-semibold">{{ App\Models\Product::convertPrice($totalPrice) }}</h5>
                                <span class="color-1 m-0">{{ App\Models\Product::convertPriceDolar($totalPrice)
                                    }}</span>
                            </div>
                        </div>
                        <button class="px-5 btn-continue col-md-4 col-lg-3 col-12 mt-4 mt-md-0">{{
                            __('Continue')}}</button>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="step col-sm-10 row align-items-center justify-content-center mt-4">
                    <div class="d-flex align-items-center p-0 pb-3 border-bottom-f1">
                        <h5 class="fw-semibold">{{ __('Personal data') }}</h5>
                    </div>
                    <div class="bg-top py-5 row justify-content-center mt-5 personal-data">
                        <div class="col-md-6 mb-3">
                            <p class="m-0 color-1 fw-semibold px-1">{{ __('Full Name') }} *</p>
                            <input id="billName" name="names" class="col-12 mx-1 required-input" type="text"
                                pattern="^(\S*)\s+(.*)$" required
                                value="{{ Auth::guard('web')->check() ? Auth::guard('web')->user()->name : old('names') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="m-0 color-1 fw-semibold px-1">{{ __('Document') }} *</p>
                            <input id="billCpf" name="customer_documents" class="col-12 mx-1 required-input" type="text"
                                pattern="[0-9]+"
                                value="{{ Auth::guard('web')->check() ? Auth::guard('web')->user()->document : old('customer_documents') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="m-0 color-1 fw-semibold px-1">{{ __('Email') }} *</p>
                            <input id="billEmail" name="email" class="col-12 mx-1 required-input" type="text"
                                value="{{ Auth::guard('web')->check() ? Auth::guard('web')->user()->email : old('email') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="m-0 color-1 fw-semibold px-1">{{ __('Phone Number') }} *</p>
                            <input id="billPhone" name="phone" class="col-12 mx-1 required-input" type="text"
                                value="{{ Auth::guard('web')->check() ? Auth::guard('web')->user()->phone : old('phone') }}">
                        </div>
                        <div class="col-12 text-end mt-4 d-md-block d-none">
                            <button class="btn-back">{{ __('To go back') }}</button>
                            <button class="px-5 btn-continue" id="step-2-continue">{{ __('Continue')}}</button>
                        </div>
                        <div class="col-12 text-center mt-4 d-md-none d-block">
                            <button class="btn-back">{{ __('To go back') }}</button>
                            <button class="px-5 btn-continue" id="step-2-continue">{{ __('Continue')}}</button>
                        </div>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="step col-sm-10 row align-items-center justify-content-center mt-4">
                    <div class="d-flex align-items-center p-0 pb-3 border-bottom-f1">
                        <h5 class="fw-semibold">{{ __('Shipping method') }}</h5>
                    </div>
                    <div class="bg-top mt-5 py-4">

                        <!-- retirar no meu endereço -->
                        <div class="border-bottom-f1 pb-4 d-flex justify-content-between">
                            @if(Auth::guard('web')->check() && Auth::guard('web')->user()->address != '')
                            <div>
                                <input id="myaddress" name="shipping" value="1" type="radio">
                                <label for="myaddress">{{ __('Receive at my address') }}</label>
                                <p style="font-size: 14px;" class="mb-0 color-1 px-3">
                                    {{Auth::guard('web')->user()->address
                                    ?? ''}}
                                </p>
                            </div>
                            <h6 class="px-2 color-3">U$10.00</h6>
                            @endif
                        </div>

                        <!-- adicionar endereço -->
                        <div class="border-bottom-f1 py-4 d-flex flex-wrap justify-content-between">
                            <div>
                                <input id="newaddress" name="shipping" value="2" type="radio" checked>
                                <label for="newaddress">{{ __('Add new address') }}</label>
                            </div>
                            <h6 class="px-2 color-3">U$10.00</h6>
                            <div class="d-block col-12 mt-3 new-address">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <p style="font-size: 14px;" class="m-0 color-1 fw-semibold px-1">{{
                                            __('Country') }}</p>
                                        <select class="form-control js-state" name="shipping_state" data-type="shipping"
                                            id="shippingState"> </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <p style="font-size: 14px;" class="m-0 color-1 fw-semibold px-1">{{ __('City')
                                            }}</p>
                                        <select class="form-control js-city" name="shipping_city" data-type="shipping"
                                            id="shippingCity" readonly> </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <p style="font-size: 14px;" class="m-0 color-1 fw-semibold px-1">{{
                                            __('Address') }}</p>
                                        <input id="address" name="address" type="text">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- retirar na sax -->
                        <div class="py-4 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div>
                                    <input id="withdrawal" name="shipping" value="3" type="radio">
                                    <label for="withdrawal">{{ __('Pick up in') }} SAX</label>
                                </div>
                                <select class="select-local d-none mx-2" name="local" id="local">
                                    <option value="1">CDE</option>
                                    <option value="2">ASUNCIÓN</option>
                                </select>
                            </div>
                            <span style="font-size: 14px;">FREE</span>
                        </div>

                        <div class="col-12">
                            <iframe style="height: 300px;" class="w-100 CDE-MAP d-none"
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d14403.483045695173!2d-54.625295595894784!3d-25.509356390177906!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94f69aaaec5ef03d%3A0xff12a8b090a63ebd!2sSAX%20Department%20Store!5e0!3m2!1sen!2sbr!4v1701116211878!5m2!1sen!2sbr"
                                width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                            <iframe style="height: 300px;" class="w-100 ASUNCION-MAP d-none"
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3607.579420944204!2d-57.56840971875741!3d-25.284729820038848!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x945da8a8f48ce025%3A0x2715791645730d75!2sSAX%20Department%20Store-Asunci%C3%B3n!5e0!3m2!1sen!2sbr!4v1701116467727!5m2!1sen!2sbr"
                                width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>

                        <div class="col-12 text-end mt-4 d-md-block d-none">
                            <button class="btn-back">{{ __('To go back') }}</button>
                            <button class="px-5 btn-continue">{{ __('Continue')}}</button>
                        </div>

                        <div class="col-12 text-center mt-4 d-md-none d-block pb-4">
                            <button class="btn-back">{{ __('To go back') }}</button>
                            <button class="px-5 btn-continue">{{ __('Continue')}}</button>
                        </div>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="step col-sm-10 row justify-content-between mt-4">
                    <div class="d-flex align-items-center bg-top my-4 py-2 justify-content-between">
                        <h6 class="col-8 text-uppercase">{{ __('Method') }}</h6>
                        <h6 class="col-3 d-lg-block d-none text-uppercase">{{ __('Total') }}</h6>
                    </div>
                    <div class="pay-method d-flex gap-2 col-xl-7 p-0 mb-4 justify-content-between">
                        <div>
                            <input id="credit" type="radio" name="pay-method" value="1">
                            <label for="credit">
                                <i class="bi bi-bank"></i>
                                <p>Depósito bancario</p>
                            </label>
                        </div>


                        <div>
                            <input id="transfer" type="radio" name="pay-method" value="2">
                            <label for="transfer">
                                <i class="bi bi-credit-card"></i>
                                <p>Bancard</p>
                            </label>
                        </div>
                        <!-- <div>
                            <input id="now" type="radio" name="pay-method" value="3">
                            <label for="now">
                                <i class="bi bi-cash-coin"></i>
                                <p>Pargar na Entrega</p>
                            </label>
                        </div> -->
                    </div>
                    <div class="col-xl-5">
                        <div class="right-area">
                            <div class="order-box order-box-2">
                                <h4 class="title text-black">{{ __('PRICE DETAILS') }}</h4>
                                <div class="border-bottom-f1">
                                    <div class="d-flex justify-content-between">
                                        <p style="font-size: 14px;" class="fw-semibold m-0">{{ __('Total MRP') }}</p>
                                        <p style="font-size: 14px;" class="m-0"><b class="cart-total">{{
                                                Session::has('cart') ?
                                                App\Models\Product::convertPrice(Session::get('cart')->totalPrice) :
                                                '0.00'
                                                }}</b></p>
                                    </div>
                                    <p style="font-size: 14px;" class="m-0 text-end pb-3 mb-3"><b class="cart-total">{{
                                            Session::has('cart') ?
                                            App\Models\Product::convertPriceDolar(Session::get('cart')->totalPrice) :
                                            '0.00'
                                            }}</b></p>
                                </div>
                                <h4 class="title text-black">{{ __('Shipping method') }}</h4>
                                <div class="d-flex flex-wrap">
                                    <p id="freteText2" style="font-size: 14px;" class="fw-semibold colo-1 pr-1 d-none">
                                        {{__('Pick up in')}}</p>
                                    <p id="freteText" style="font-size: 14px;" class="fw-semibold colo-1 pr-1">
                                        {{__('Pick up in')}}</p>
                                    <p style="font-size: 14px;" class="fw-semibold colo-1 m-0 d-none">CDE</p>
                                </div>
                                <p id="freteGratis" class="fw-bold color-4 border-bottom-f1 pb-3 mb-3 d-none  text-end">
                                </p>
                                <p id="frete10"
                                    class="fw-bold color-4 border-bottom-f1 pb-3 mb-3 d-none text-danger text-end">
                                    <b class="cart-total fw-bold">{{App\Models\Product::convertPrice(10)}}</b>
                                </p>
                                <div class="total-price d-flex justify-content-between">
                                    <p style="margin-bottom:0px;">{{ __('Total') }}</p>
                                    <p><span id="total-cost2">{{ App\Models\Product::signFirstPrice($totalPrice)
                                            }}</span></p>
                                </div>
                                <div class="d-flex">
                                    <button class="btn-back d-xl-none d-block">{{ __('To go back') }}</button>
                                    <button type="submit" class="w-100 px-sm-4 btn-continue px-1">Finalizar
                                        compra</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="" style="bottom: 0;left: 0;">
                        <button class="btn-back px-5 mt-5 d-xl-block d-none">{{ __('To go back') }}</button>
                    </div>
            </form>
            <script src="{{ asset('assets/checkout/scripts.js') }}"></script>

            <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

            <script>
    $(documen                    (function                          $('#myform'                        ction (e) {
            // Impede o envio tradicional do form                               e                            );

            // Obtém os                         mul                             var fo                            .serialize();
            console.log("formData", formData)

                        //                         citação AJAX
            $.                                   type: 'POST',
                url: '/enviar-dados',
                data: formData,
                                 ss: function (data) {
                                    ógi                        cutada em caso de sucess                                  console.log(                        dat                                             ,
                error: function (error) {
                                // Lógica a ser executad                         erro
                    console.l                        error);
                             
                                            });
            </script>

            <script>
    $(document).ready(function () {
        $.ajax({
            type: 'GET',
            url: 'http://localhost:8001' + '/checkout/getStatesOptions',
            data: {
                location_id: 173 //paraguai
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                $('#shippingState').append('<option value="">{{ __('Selecione seu departamento') }}</option>');
                $('#shippingState').append(data);
            },
           t                (err) {
                console.                    ;
            },
        })


        $('#shippingSta                    'change', function () {
            // Obtém o valor sele                                var selectedValue = $(this).val();
            $                                    type: 'GET',
                url: 'http:/                    st:8001' + '/checkout/getCiti                    s',
                data: {
                    location_id: selectedValue //paraguai
                },                             headers: {
                                                    TOKEN': $('meta[name="cs                oken"]').attrt                ')
                },
                                    success: function (data) {
                    $('#shippingCity').append(data);
                                        $('#shippingCity').removeAttr('readonly');
                },
                                err                or: function (err) {
                    console.log(                err);
                },
            })

        }                    );

    });
            </script>

            <script>
    function checkIfStep2Valid() {
                
        var idcpf = document.getElementById('billCpf')                .value
        var idemail = document.getElementByI                    d('billEmail').value
        var idtelefone = document.getElementBy                Id('bi)                .value
        var i                dnome = document.getElementById('billNam                e').value


        var allFieldsFilled = true;

                        if (idcpf.trim() === '' || idemail.trim() === '' || idtelef                one.trim() === '' || idnome.trim() === '') {
                            allFieldsFilled = false;
        }

        return a                llFieldsFilled;
    }

            </script>

</body>

</html>
@endsection

@section('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.4/jquery.inputmask.min.js"></script>
<script>
window.onload =     function () {
document.getElementById("customer_nam    e").value = document.getEl    ementById("billName").value;
document.getElementById    ("customer_phone").value = docum    ent.getElementById("billPhone").value;
}

var     billName = document.getElementBy    Id("billName");

billName.addEve        ntListener("change", function ()             {
document.getElementById("customer                _name").value                 = billName.value;
}            );

        va    r bil    lPhone = document.getElementById("billPh        one");

billPhone.addEventL            istener("change", functio                n () {
document.getElementById("customer_pho                    .value = billPhone.value;
});
</script>

@include('includes.checkout-flow-scripts')
@endsection