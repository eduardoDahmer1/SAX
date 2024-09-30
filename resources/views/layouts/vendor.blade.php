<!doctype html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="CrowTech">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $gs->title }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/images/' . $gs->favicon) }}" />
    <link href="{{ asset('assets/vendor/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/icofont.min.css') }}">
    <link href="{{ asset('assets/vendor/plugins/fullside-menu/css/dark-side-style.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/vendor/plugins/fullside-menu/waves.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/vendor/css/plugin.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/vendor/css/jquery.tagit.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/bootstrap-coloroicker.css') }}">
    <link href="{{ asset('assets/admin/css/trumbowyg-all.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/admin/css/ionicons.min.css') }}">@if ($slocale->rtl == '1')
    <link href="{{ asset('assets/vendor/css/rtl/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/vendor/css/rtl/custom.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/vendor/css/common.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/css/rtl/responsive.css') }}" rel="stylesheet" />@else
    <link href="{{ asset('assets/vendor/css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/vendor/css/custom.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/vendor/css/common.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/css/responsive.css') }}" rel="stylesheet" />@endif @yield('styles')
</head>
<body>
    <div class="page">
        <div class="page-main">
            <div class="header">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between">
                        <div class="menu-toggle-button"><a class="nav-link" href="javascript:;" id="sidebarCollapse">
                                <div class="my-toggl-icon"><span class="bar1"></span><span class="bar2"></span><span
                                        class="bar3"></span></div>
                            </a></div>
                        <div class="text-center" style="width: 20%"><img
                                style="width: 100%; display: block; vertical-align: middle; margin: 1rem;"
                                src="{{ asset('storage/images/') . '/' . $gs->footer_logo }}" alt=""></div>
                        <div class="right-eliment">
                            <ul class="list">
                                <li class="login-profile-area"><a class="dropdown-toggle-1" href="javascript:;">
                                        <div class="user-img">@if (Auth::user()->is_provider == 1)<img
                                                src="{{ Auth::user()->photo ? asset(Auth::user()->photo) : asset('assets/images/noimage.png') }}"
                                                alt="">@else<img
                                                src="{{ Auth::user()->photo ? asset('storage/images/users/' . Auth::user()->photo) : asset('assets/images/noimage.png') }}"
                                                alt="">@endif</div>
                                    </a>
                                    <div class="dropdown-menu">
                                        <div class="dropdownmenu-wrapper">
                                            <ul>
                                                <h5>{{ __('Welcome!') }}</h5>
                                                <li><a target="_blank"
                                                        href="{{ route('front.vendor', str_replace(' ', '-', Auth::user()->shop_name)) }}"><i
                                                            class="fas fa-eye"></i> {{ __('Visit Store') }}</a></li>
                                                <li><a target="_blank" href="#"><i
                                                            class="fas fa-bookmark"></i>{{ __('Plan') }}</a></li>
                                                <li><a href="{{ route('vendor-profile') }}"><i class="fas fa-user"></i>
                                                        {{ __('Edit Profile') }}</a></li>
                                                <li><a href="{{ route('user-reset') }}"><i class="fas fa-lock"></i>
                                                        {{ __('Change Password') }}</a></li>
                                                <li><a href="{{ route('user-logout') }}"><i
                                                            class="fas fa-power-off"></i> {{ __('Logout') }}</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="wrapper">@if (Auth::user()->checkStatus())<nav id="sidebar" class="nav-sidebar">
                    <ul class="list-unstyled components" id="accordion">
                        <li><a target="_blank"
                                href="{{ route('front.vendor', str_replace(' ', '-', Auth::user()->shop_name)) }}"
                                class="wave-effect active"><i
                                    class="fas fa-eye mr-2"></i><span>{{ __('Visit Store') }}</span></a></li>
                        <li><a href="{{ route('vendor-dashboard') }}" class="wave-effect active"><i
                                    class="fa fa-home mr-2"></i><span>{{ __('Dashbord') }}</span></a></li>@if
                        ($gs->is_cart)<li><a href="#order" class="accordion-toggle wave-effect" data-toggle="collapse"
                                aria-expanded="false"><i
                                    class="fas fa-hand-holding-usd"></i><span>{{ __('Orders') }}</span></a>
                            <ul class="collapse list-unstyled" id="order" data-parent="#accordion">
                                <li><a href="{{ route('vendor-order-index') }}"><span>{{ __('All Orders') }}</span></a>
                                </li>
                            </ul>
                        </li>@endif<li><a href="#menu2" class="accordion-toggle wave-effect" data-toggle="collapse"
                                aria-expanded="false"><i class="icofont-cart"></i><span>{{ __('Products') }}</span></a>
                            <ul class="collapse list-unstyled" id="menu2" data-parent="#accordion">
                                <li><a
                                        href="{{ route('vendor-prod-index') }}"><span>{{ __('Add New Product') }}</span></a>
                                </li>
                            </ul>
                        </li>
                        <li><a href="#general" class="accordion-toggle wave-effect" data-toggle="collapse"
                                aria-expanded="false"><i class="fas fa-cogs"></i><span>{{ __('Settings') }}</span></a>
                            <ul class="collapse list-unstyled" id="general" data-parent="#accordion">
                                <li><a href="{{ route('vendor-profile') }}"><span>{{ __('Edit Profile') }}</span></a>
                                </li>
                                <li><a
                                        href="{{ route('vendor-subscription-index') }}"><span>{{ __('Subscription') }}</span></a>
                                </li>
                                <li><a href="{{ route('vendor-service-index') }}"><span>{{ __('Services') }}</span></a>
                                </li>
                                <li><a href="{{ route('vendor-banner') }}"><span>{{ __('Banner') }}</span></a></li>@if
                                ($gs->vendor_ship_info == 1)<li><a
                                        href="{{ route('vendor-shipping-index') }}"><span>{{ __('Shipping Methods') }}</span></a>
                                </li>@endif @if ($gs->multiple_packaging == 1)<li><a
                                        href="{{ route('vendor-package-index') }}"><span>{{ __('Packagings') }}</span></a>
                                </li>@endif<li><a
                                        href="{{ route('vendor-social-index') }}"><span>{{ __('Social Links') }}</span></a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>@endif @yield('content')</div>
        </div>
    </div>@php $curr = \App\Models\Currency::where('is_default', '=', 1)->first(); @endphp<script
        type="text/javascript">
    var mainurl = "{{ url('/') }}";
    var admin_loader = {
        {
            $gs - > is_admin_loader
        }
    };
    var whole_sell = {
        {
            $gs - > wholesell
        }
    };
    var langg = {
        !!json_encode(new\ stdClass()) !!
    };
    var getattrUrl = '{{ route('
    vendor - prod - getattributes ') }}';
    var curr = {
        !!json_encode($curr) !!
    };
    var messages = {
        "image_restriction": '{{ __('
        Image height and width must be 600 x 600. ') }}',
        "image_square": '{{ __('
        Image must have square size.
        ') }}',
        "add_new": '{{ __('
        ADD NEW ') }}',
        "edit": '{{ __('
        EDIT ') }}',
        "size_name": '{{ __('
        Size Name ') }}',
        "size_qty": '{{ __('
        Size Qty ') }}',
        "size_price": '{{ __('
        Size Price ') }}',
        "enter_keyword": '{{ __('
        Enter Your Keyword ') }}',
        "license_key": '{{ __('
        License Key ') }}',
        "license_qty": '{{ __('
        License Quantity ') }}',
        "enter_qty": '{{ __('
        Enter Quantity ') }}',
        "enter_discount": '{{ __('
        Enter Discount Percentage ') }}'
    };
    </script>
    <script src="{{ asset('assets/admin/js/vendors/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/vendors/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/plugins/fullside-menu/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/plugins/fullside-menu/waves.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/plugin.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/Chart.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/tag-it.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/notify.js') }}"></script>
    <script src="{{ asset('assets/admin/js/trumbowyg/plugins/resizimg/resizable-resolveconflict.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery-resizable.js') }}"></script>
    
