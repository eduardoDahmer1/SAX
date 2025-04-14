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

<script>
  document.addEventListener("DOMContentLoaded", () => {
    const sortSelect = document.querySelector("#sortby");

    if (sortSelect) {
      sortSelect.addEventListener("change", function () {
        const url = new URL(window.location.href);
        url.searchParams.set("sort", this.value);
        window.location.href = url.toString();
      });
    }

    // Ativa tooltips com jQuery (se jQuery estiver carregado)
    if (typeof $ !== 'undefined') {
      $('[data-toggle="tooltip"], [rel-toggle="tooltip"]').tooltip().on('click', function () {
        $(this).tooltip('hide');
      });
    }
  });
</script>
