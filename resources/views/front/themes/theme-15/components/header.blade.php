<!-- Logo Header Area Start -->
<div class="menufixed">
    <section class="logo-header">
        <div class="container">
            <div class="row justify-content-between align-items-center align-items-lg-end py-lg-3" style="position: relative;">

                <div class="col-lg-2 col-2 order-2 order-lg-1" style="position: static;">
                    <div class="button-open-search">
                        <input type="checkbox">
                        <i class="icofont-search-1"></i>
                    </div>
                    <div class="search-box-wrapper">
                        <div class="search-box">
                            <form id="searchForm" class="search-form" action="{{ route('front.category') }}"
                                method="GET">

                                <button type="submit"><i class="icofont-search-1"></i></button>

                                @if (!empty(request()->input('sort')))
                                    <input type="hidden" name="sort" value="{{ request()->input('sort') }}">
                                @endif

                                @if (!empty(request()->input('minprice')))
                                    <input type="hidden" name="minprice" value="{{ request()->input('minprice') }}">
                                @endif

                                @if (!empty(request()->input('maxprice')))
                                    <input type="hidden" name="maxprice" value="{{ request()->input('maxprice') }}">
                                @endif

                                <input type="text" id="prod_name" name="searchHttp"
                                    placeholder="{{ __('What are you looking for?') }}"
                                    value="{{ request()->input('searchHttp') }}" autocomplete="off">
                                <div class="autocomplete">
                                    <div id="myInputautocomplete-list" class="autocomplete-items"></div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8 col-4 order-3 order-lg-2 d-flex justify-content-center">
                    <div class="logo">
                        <a href="{{ route('front.index') }}">
                            <img src="{{ $gs->logoUrl }}" alt="">
                        </a>
                    </div>
                </div>

                <div class="col-lg-2 col-4 order-4 order-lg-3">
                    <div class="helpful-links">
                        <ul class="helpful-links-inner">

                            <li data-toggle="tooltip" data-placement="top" class="loginbutton"
                            title="{{ !Auth::guard('web')->check() ? __('Login') :  __('Profile') }}" >
                                @if (!Auth::guard('web')->check())
                                    <a href="{{ route('user.login') }}" class="profile carticon">
                                        <img class="img-fluid icons-header" src="{{ asset('assets/images/theme15/iconelogin.png')}}" alt="">
                                    </a>
                                @else
                                    <a href="{{ route('user-dashboard') }}" class="profile carticon">
                                        <img class="img-fluid icons-header" src="{{ asset('assets/images/theme15/iconelogin.png')}}" alt="">
                                    </a>
                                @endif
                            </li>

                            <li class="wishlist" data-toggle="tooltip" data-placement="top"
                                title="{{ __('Wish') }}">
                                @if (Auth::guard('web')->check())

                                    <a href="{{ route('user-wishlists') }}" class="wish">
                                        <img class="img-fluid icons-header" src="{{ asset('assets/images/theme15/wishicone.png')}}" alt="">
                                        <span id="wishlist-count">{{ count(Auth::user()->wishlists) }}</span>
                                    </a>

                                @else
                                    <a href="javascript:;" data-toggle="modal" id="wish-btn"
                                        data-target="#comment-log-reg" class="wish">
                                        <img class="img-fluid icons-header" src="{{ asset('assets/images/theme15/wishicone.png')}}" alt="">
                                        <span id="wishlist-count">0</span>
                                    </a>
                                @endif
                            </li>

                            @if ($gs->is_cart)
                                <li class="my-dropdown">
                                    <a href="javascript:;" class="cart carticon">
                                        <div class="icon">
                                            <img class="img-fluid icons-header" src="{{ asset('assets/images/theme15/bagicone.png')}}" alt="">
                                            <span class="cart-quantity" id="cart-count">
                                                {{ Session::has('cart') ? count(Session::get('cart')->items) : '0' }}
                                            </span>
                                        </div>
                                    </a>
                                    <div class="my-dropdown-menu" id="cart-items">
                                        @include('load.cart')
                                    </div>
                                </li>
                            @endif

                            {{-- <li class="compare" data-toggle="tooltip" data-placement="top"
                                title="{{ __('Compare') }}">
                                <a href="{{ route('product.compare') }}" class="wish compare-product">
                                    <div class="icon">
                                        <svg class="img-fluid icons-header" width="30" height="30"
                                            viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M20 17.5L13.75 11.25L20 5L21.75 6.78125L18.5313 10H27.5V12.5H18.5313L21.75 15.7188L20 17.5ZM10 25L16.25 18.75L10 12.5L8.25 14.2813L11.4688 17.5H2.5V20H11.4688L8.25 23.2188L10 25Z"
                                                fill="#333333" />
                                        </svg>

                                        <span id="compare-count">
                                            {{ Session::has('compare') ? count(Session::get('compare')->items) : '0' }}
                                        </span>
                                    </div>
                                </a>
                            </li> --}}
                        </ul>
                    </div>
                </div>

                <div class="col-lg-12 col-2 order-1 order-lg-4">
                    <div class="saxnavigation">
                        <svg class="icone-menu-nav" width="35" height="24" viewBox="0 0 35 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect y="21.5" width="35" height="2" rx="1" fill="black"/>
                            <rect y="10.75" width="35" height="2" rx="1" fill="black"/>
                            <rect width="35" height="2" rx="1" fill="black"/>
                        </svg>
                            
                        <ul class="menu-navigation">
                            <li>
                                <a href="{{ route('front.index') }}">
                                    {{ __('Home') }}                                   
                                </a>
                            </li>
                
                            <li class="menudrop">
                                <a href="{{ route('front.categories') }}">
                                    {{ __('Categories') }}
                                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <rect width="14" height="14" fill="url(#pattern0)"/>
                                        <defs>
                                            <pattern id="pattern0" patternContentUnits="objectBoundingBox" width="1" height="1">
                                                <use xlink:href="#image0_422_2143" transform="scale(0.00195312)"/>
                                            </pattern>
                                            <image id="image0_422_2143" width="512" height="512" xlink:href="data:image/png;base64,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"/>
                                        </defs>
                                    </svg>
                                </a>
                                {{-- <div class="box-categories">
                                    <ul>
                                        @foreach ($categories->take(6) as $category)
                                            <li>{{ $category->name }}</li>
                                        @endforeach
                                        <li><a href="{{ route('front.categories')}}"></a></li>
                                    </ul>
                                </div> --}}
                            </li>
                
                            <li>
                                <a href="https://saxdepartment.com/">
                                    {{ __('Institutional') }}
                                </a>
                            </li>
                
                            <li>
                                <a href="https://saxdepartment.com/sax-palace">
                                    {{ __('Sax Palace') }}
                                </a>
                            </li>
                
                            <li>
                                <a href="https://saxdepartment.com/bridal-word">
                                    {{ __('Sax Bridal World') }}
                                </a>
                            </li>
                
                        
                            @if ($gs->is_contact == 1)
                                <li>
                                    <a href="{{ route('front.contact') }}">
                                        {{ __('Contact Us') }}
                                    </a>
                                </li>
                            @endif
                
                
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Logo Header Area End -->

    <!--Main-Menu Area Start-->
    {{-- <div class="mainmenu-area mainmenu-bb">
        <div class="container">
            <div class="row mainmenu-area-innner">
                <div class="col-6 col-lg-10 d-flex justify-content-center align-items-center">
                    <!--categorie menu start-->
                    <div class="categories_menu vertical">
                        <div class="categories_title">
                            <h2 class="categori_toggle"><i class="fas fa-layer-group"></i> {{ __('Categories') }}
                                <i class="fa fa-angle-down arrow-down"></i>
                            </h2>
                        </div>
                        <div class="categories_menu_inner">
                            <ul style="width:100%;">
                                @php
                                    $i = 1;
                                @endphp

                                @foreach ($categories as $category)
                                    @php
                                        $count = count($category->subs_order_by);
                                    @endphp
                                    <li
                                        class="{{ $count ? 'dropdown_list' : '' }}
                                        {{ $i >= 15 ? 'rx-child' : '' }} qntd">

                                        @if ($count)
                                            @if ($category->photo)
                                                <div class="img">
                                                    <img src="{{ asset('storage/images/categories/' . $category->photo) }}"
                                                        alt="">
                                                </div>
                                            @endif
                                            <div class="link-area">
                                                <span><a href="{{ route('front.category', $category->slug) }}">
                                                        {{ $category->name }}</a>
                                                </span>

                                                @if ($count)
                                                    <a href="javascript:;">
                                                        <i class="fa fa-angle-right" aria-hidden="true"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        @else
                                            <a href="{{ route('front.category', $category->slug) }}">
                                                @if ($category->photo)
                                                    <img
                                                        src="{{ asset('storage/images/categories/' . $category->photo) }}">
                                                @endif
                                                {{ $category->name }}
                                            </a>
                                        @endif

                                        @if ($count)
                                            @php
                                                $ck = 0;

                                                foreach ($category->subs_order_by as $subcat):
                                                    if (count($subcat->childs_order_by) > 0):
                                                        $ck = 1;
                                                        break;
                                                    endif;
                                                endforeach;

                                            @endphp

                                            <ul
                                                class="{{ $ck == 1 ? 'categories_mega_menu' : 'categories_mega_menu column_1' }}">

                                                @foreach ($category->subs_order_by as $subcat)
                                                    <li>
                                                        <a
                                                            href="{{ route('front.subcat', ['slug1' => $category->slug, 'slug2' => $subcat->slug]) }}">
                                                            {{ $subcat->name }}
                                                        </a>

                                                        @if (count($subcat->childs_order_by) > 0)
                                                            <div class="categorie_sub_menu">
                                                                <ul>
                                                                    @foreach ($subcat->childs_order_by as $childcat)
                                                                        <li>
                                                                            <a
                                                                                href="{{ route('front.childcat', ['slug1' => $category->slug, 'slug2' => $subcat->slug, 'slug3' => $childcat->slug]) }}">
                                                                                {{ $childcat->name }}
                                                                            </a>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        @endif
                                                    </li>
                                                @endforeach

                                            </ul>
                                        @endif

                                    </li>

                                    @php
                                        $i++;
                                    @endphp

                                    @if ($i == 15)
                                        <li>
                                            <a href="{{ route('front.categories') }}"><i class="fas fa-plus"></i>
                                                {{ __('See All Categories') }}
                                            </a>
                                        </li>
                                    @break
                                @endif
                            @endforeach

                        </ul>
                    </div>
                </div>
                <div class="categories_menu horizontal">
                    <div class="categories_title_horizontal">
                        <h2 class="categori_toggle"><i class="fa fa-bars"></i> {{ __('Categories') }}
                            <i class="fa fa-angle-down arrow-down"></i>
                        </h2>
                    </div>
                    <div class="categories_menu_inner_horizontal">
                        <ul>
                            @php
                                $i = 1;
                            @endphp

                            @foreach ($categories as $category)
                                @php
                                    $count = count($category->subs_order_by);
                                @endphp
                                <li
                                    class="{{ $count ? 'dropdown_list' : '' }}
                                        {{ $i >= 15 ? 'rx-child' : '' }}">

                                    @if ($count)
                                        @if ($category->photo)
                                            <div class="img">
                                                <img src="{{ asset('storage/images/categories/' . $category->photo) }}"
                                                    alt="">
                                            </div>
                                        @endif
                                        <div class="link-area">
                                            <span><a href="{{ route('front.category', $category->slug) }}">
                                                    {{ $category->name }}</a>
                                            </span>

                                            @if ($count)
                                                <a href="javascript:;">
                                                    <i class="fa fa-angle-right" aria-hidden="true"></i>
                                                </a>
                                            @endif
                                        </div>
                                    @else
                                        <a href="{{ route('front.category', $category->slug) }}">
                                            @if ($category->photo)
                                                <img
                                                    src="{{ asset('storage/images/categories/' . $category->photo) }}">
                                            @endif
                                            {{ $category->name }}
                                        </a>
                                    @endif

                                    @if ($count)
                                        @php
                                            $ck = 0;

                                            foreach ($category->subs_order_by as $subcat):
                                                if (count($subcat->childs_order_by) > 0):
                                                    $ck = 1;
                                                    break;
                                                endif;
                                            endforeach;

                                        @endphp

                                        <ul
                                            class="{{ $ck == 1 ? 'categories_mega_menu' : 'categories_mega_menu column_1' }}">

                                            @foreach ($category->subs_order_by as $subcat)
                                                <li>
                                                    <a
                                                        href="{{ route('front.subcat', ['slug1' => $category->slug, 'slug2' => $subcat->slug]) }}">
                                                        {{ $subcat->name }}
                                                    </a>

                                                    @if (count($subcat->childs_order_by) > 0)
                                                        <div class="categorie_sub_menu">
                                                            <ul>
                                                                @foreach ($subcat->childs_order_by as $childcat)
                                                                    <li>
                                                                        <a
                                                                            href="{{ route('front.childcat', ['slug1' => $category->slug, 'slug2' => $subcat->slug, 'slug3' => $childcat->slug]) }}">
                                                                            {{ $childcat->name }}
                                                                        </a>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    @endif
                                                </li>
                                            @endforeach

                                        </ul>
                                    @endif

                                </li>

                                @php
                                    $i++;
                                @endphp

                                @if ($i == 6)
                                    <li>
                                        <a href="{{ route('front.categories') }}"><i class="fas fa-plus"></i>
                                            {{ __('See All Categories') }}
                                        </a>
                                    </li>
                                @break
                            @endif
                        @endforeach

                    </ul>
                </div>
            </div>
            <!--categorie menu end-->
        </div>

        <div class="col-6 col-lg-2">
            <div class="box-button-site" data-menu-toggle-main="#menu-browse-site">
                <i class="fas fa-bars"></i>
                <p>{{ __('Browse the site') }}</p>
                <div id="menu-browse-site" class="container-menu">
                    
                </div>
            </div>

        </div>
    </div> --}}
</div>
</div>
</div>
<!--Main-Menu Area End-->


{{-- @if ($gs->is_blog == 1)
<li>
    <a href="{{ route('front.blog') }}">
        {{ __('Blog') }}

    </a>
</li>
@endif

@if ($gs->is_faq == 1)
<li>
    <a href="{{ route('front.faq') }}">
        {{ __('Faq') }}

    </a>
</li>
@endif
@if ($gs->policy)
<li>
    <a href="{{ route('front.policy') }}">
        {{ __('Buy & Return Policy') }}

    </a>
</li>
@endif

@foreach ($pheader as $data)
<li>
    <a href="{{ route('front.page', $data->slug) }}">
        {{ $data->title }}

    </a>
</li>
@endforeach

@if ($gs->is_cart)
<li>
    <a href="javascript:;" data-toggle="modal" data-target="#track-order-modal">
        {{ __('Track Order') }}

    </a>
</li>
@endif

@if ($gs->team_show_header == 1)
<li>
    <a href="{{ route('front.team_member') }}">
        {{ __('Team') }}

    </a>
</li>
@endif --}}


<script>
    document.querySelector(".icone-menu-nav").addEventListener('click', () =>  {
        document.querySelector(".menu-navigation").classList.toggle("showNav")
    })
</script>