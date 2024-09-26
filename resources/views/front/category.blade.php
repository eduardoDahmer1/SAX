@extends('front.themes.' . env('THEME', 'theme-15') . '.layout')
@section('content')
<div class="breadcrumb-area">
    <section>
        <img src="{{ $banner ?: asset('assets/front/themes/theme-15/assets/images/Banner_Sax.png') }}" class="img-fluid w-100">
    </section>
    <div class="container">
        <div class="row pt-3">
            <div class="col-lg-3">
                <ul class="pages">
                    <li><a href="{{route('front.index')}}">{{ __("Home") }}</a></li>
                    @if (!empty($cat)) <li><a href="{{route('front.category', $cat->slug)}}">{{ $cat->name }}</a></li> @endif
                    @if (!empty($subcat)) <li><a href="{{route('front.category', [$cat->slug, $subcat->slug])}}">{{ $subcat->name }}</a></li> @endif
                    @if (!empty($childcat)) <li><a href="{{route('front.category', [$cat->slug, $subcat->slug, $childcat->slug])}}">{{ $childcat->name }}</a></li> @endif
                    @if (empty($childcat) && empty($subcat) && empty($cat)) <li><a href="{{route('front.category')}}">{{ __("Search") }}</a></li> @endif
                </ul>
            </div>
            <div class="col-lg-9">
                @if(!config("features.marketplace")) @include('includes.filter') @endif
            </div>
        </div>
    </div>
</div>
<section class="sub-categori">
    <div class="container">
        <div class="row">
            @include('includes.catalog')
            <div class="col-lg-9 ajax-loader-parent">
                <div class="right-area" id="app">
                    <div class="categori-item-area">
                        <div class="row p-2" id="ajaxContent">@if(!config("features.marketplace")) @include('includes.product.filtered-products') @else @include('includes.product.aggregated-products') @endif</div>
                        <div id="ajaxLoader" class="ajax-loader" style="background: url({{asset('storage/images/'.$gs->loader)}}) no-repeat scroll center center rgba(0,0,0,.6);"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('scripts')
<script>
    $(document).ready(function() {
        addToPagination();
        $("#qty").on('change', function(){ $("#ajaxLoader").show(); filter(); });
        $(".attribute-input, #sortby").on('change', function() { $("#ajaxLoader").show(); filter(); });
        $(".filter-btn").on('click', function(e) { e.preventDefault(); $("#ajaxLoader").show(); filter(); });
    });
    function filter() {
        let filterlink = '{{route("front.category", [Request::route("category"), Request::route("subcategory"), Request::route("childcategory")])}}';
        let params = new URLSearchParams();
        if ($("#prod_name").val()) params.append('searchHttp', $("#prod_name").val());
        $(".attribute-input:checked").each(function() { params.append($(this).attr('name'), $(this).val()); });
        if ($("#qty").val()) params.append($("#qty").attr('name'), $("#qty").val());
        if ($("#sortby").val()) params.append($("#sortby").attr('name'), $("#sortby").val());
        if ($("#min_price").val()) params.append($("#min_price").attr('name'), $("#min_price").val());
        if ($("#max_price").val()) params.append($("#max_price").attr('name'), $("#max_price").val());
        $("#ajaxContent").load(filterlink + '?' + params.toString(), function() { addToPagination(); $("#ajaxLoader").fadeOut(1000); });
    }
    function addToPagination() {
        $('ul.pagination li a').each(function() {
            let url = new URL($(this).attr('href'));
            let fullUrl = '{{route("front.category", [Request::route("category"),Request::route("subcategory"),Request::route("childcategory")])}}' + '?page=' + url.searchParams.get('page');
            $(".attribute-input:checked").each(function() { fullUrl += '&' + encodeURIComponent($(this).attr('name')) + '=' + encodeURIComponent($(this).val()); });
            if($("#qty").val()) fullUrl += '&qty=' + encodeURIComponent($("#qty").val());
            if ($("#sortby").val()) fullUrl += '&sort=' + encodeURIComponent($("#sortby").val());
            if ($("#min_price").val()) fullUrl += '&min=' + encodeURIComponent($("#min_price").val());
            if ($("#max_price").val()) fullUrl += '&max=' + encodeURIComponent($("#max_price").val());
            $(this).attr('href', fullUrl);
        });
    }
    $(function() {
        $("#slider-range").slider({
            range: true, orientation: "horizontal", min: 0, max: {{ $range_max }}, values: [{{ isset($_GET['min']) ? $_GET['min'] : '0' }}, {{ isset($_GET['max']) ? $_GET['max'] : $range_max }}], step: 5,
            slide: function(event, ui) {
                if (ui.values[0] == ui.values[1]) return false;
                $("#min_price").val(ui.values[0]);
                $("#max_price").val(ui.values[1]);
            }
        });
        $("#min_price").val($("#slider-range").slider("values", 0));
        $("#max_price").val($("#slider-range").slider("values", 1));
    });
</script>
@endsection
