@extends('front.themes.' . env('THEME', 'theme-15') . '.layout')

@section('content')

<!-- Breadcrumb Area Start -->
<div class="breadcrumb-area">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <ul class="pages">
          <li><a href="{{ route('front.index') }}">{{ __("Home") }}</a></li>
          <li><a href="javascript:;">{{ __("Search") }}</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- Breadcrumb Area End -->

<!-- SubCategori Area Start -->
<section class="sub-categori">
  <div class="container">
    <div class="row">
      @include('includes.catalog')

      <div class="col-lg-9 order-first order-lg-last">
        <div class="right-area">
          @if($products->count() > 0)
            @include('includes.filter')

            <div class="categori-item-area">
              <div id="ajaxContent">
                <div class="row">
                  @foreach($products as $prod)
                    @include('includes.product.product')
                  @endforeach
                </div>

                @php
                  $paginationParams = [
                    'cat_id' => $cat_id ?? null,
                    'min' => $min ?? null,
                    'max' => $max ?? null,
                    'sort' => $sort ?? null,
                    'search' => $search ?? null,
                    'category_id' => $category_id ?? null,
                  ];
                @endphp

                <div class="page-center category">
                  {!! $products->appends(array_filter($paginationParams))->links() !!}
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
<!-- SubCategori Area End -->

@endsection

@section('scripts')
<script>
  document.addEventListener("DOMContentLoaded", () => {
    const sortSelect = document.getElementById("sortby");

    if (sortSelect) {
      sortSelect.addEventListener("change", function () {
        const url = new URL(window.location.href);
        url.searchParams.set("sort", this.value);
        window.location.href = url.toString();
      });
    }

    // jQuery UI slider de preços
    if (typeof $ !== 'undefined' && $("#slider-range").length) {
      const rangeMax = {{ $range_max }};
      const min = {{ isset($_GET['min']) ? (int) $_GET['min'] : 0 }};
      const max = {{ isset($_GET['max']) ? (int) $_GET['max'] : $range_max }};

      $("#slider-range").slider({
        range: true,
        orientation: "horizontal",
        min: 0,
        max: rangeMax,
        values: [min, max],
        step: 5,
        slide: (event, ui) => {
          if (ui.values[0] === ui.values[1]) return false;
          $("#min_price").val(ui.values[0]);
          $("#max_price").val(ui.values[1]);
        }
      });

      $("#min_price").val(min);
      $("#max_price").val(max);
    }
  });
</script>
@endsection
