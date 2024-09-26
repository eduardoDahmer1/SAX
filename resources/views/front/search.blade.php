@extends('front.themes.' . env('THEME', 'theme-15') . '.layout')
@section('content')
<div class="breadcrumb-area">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<ul class="pages">
					<li><a href="{{route('front.index')}}">{{ __("Home") }}</a></li>
					<li><a href="javascript:;">{{ __("Search") }}</a></li>
				</ul>
			</div>
		</div>
	</div>
</div>
<section class="sub-categori">
	<div class="container">
		<div class="row">
			@include('includes.catalog')
			<div class="col-lg-9 order-first order-lg-last">
				<div class="right-area">
					@if(count($products) > 0)
						@include('includes.filter')
						<div class="categori-item-area">
							<div id="ajaxContent">
								<div class="row">
									@foreach($products as $prod)
										@include('includes.product.product')
									@endforeach
								</div>

								<div class="page-center category">
									@if(isset($min) || isset($max))
										{!! $products->appends(['cat_id' => $cat_id ,'min' => $min, 'max' => $max])->links() !!}
									@elseif(!empty($sort))
										@if(!empty($category_id))
											{!! $products->appends(['category_id' => $category_id, 'search' => $search, 'sort' => $sort])->links() !!}
										@else
											{!! $products->appends(['cat_id' => $cat_id, 'min' => $min, 'max' => $max, 'sort' => $sort])->links() !!}
										@endif
									@else
										{!! $products->appends(['category_id' => $category_id, 'search' => $search])->links() !!}
									@endif
								</div>
							</div>
						</div>
					@else
						<div class="page-center">
							<h4 class="text-center">{{ __("No Product Found.") }}</h4>
						</div>
					@endif
				</div>
			</div>
		</div>
	</div>
</section>
@endsection
@section('scripts')
<script type="text/javascript">
    $("#sortby").on('change', function () {
        var sort = $(this).val();
        @if(empty($sort))
            window.location = window.location.href + '&sort=' + sort;
        @else
            var url = window.location.href.split("&sort");
            window.location = url[0] + '&sort=' + sort;
        @endif
    });
    $(function () {
        $("#slider-range").slider({
            range: true,
            orientation: "horizontal",
            min: 0,
            max: {{ $range_max }},
            values: [{{ isset($_GET['min']) ? $_GET['min'] : '0' }}, {{ isset($_GET['max']) ? $_GET['max'] : $range_max }}],
            step: 5,
            slide: function (event, ui) {
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
