@extends('layouts.admin')

@section('content')
<div class="content-area">
  <div class="mr-breadcrumb">
    <div class="row">
      <div class="col-lg-12">
        <h4 class="heading">{{ __('Back in Stock') }}</h4>
        <ul class="links">
          <li><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
          <li><a href="{{ route('admin-backinstock-index') }}">{{ __('Back in Stock') }}</a></li>
        </ul>
      </div>
    </div>
  </div>

  <div class="product-area">
    <div class="row">
      <div class="col-lg-12">
        <div class="mr-table allproduct">
          @include('includes.admin.form-success')
          <div class="table-responsiv">
            <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th><i class="icofont-envelope icofont-lg" data-toggle="tooltip" title='{{ __('Email') }}'></i></th>
                  <th>{{ __('Product') }}</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  let table;

  $(document).ready(function () {
    if (sessionStorage.getItem("CurrentPage") === null) {
      sessionStorage.setItem("CurrentPage", 0);
    }

    table = $('#geniustable').DataTable({
      stateSave: true,
      stateDuration: -1,
      ordering: false,
      processing: true,
      serverSide: true,
      ajax: '{{ route('admin-backinstock-datatables') }}',
      columns: [
        { data: 'email', name: 'email' },
        { data: 'product', name: 'product' }
      ],
      language: {
        url: '{{ $datatable_translation }}',
        processing: '<img src="{{ $admstore->adminLoaderUrl }}">'
      },
      initComplete: () => {
        table.page(parseInt(sessionStorage.getItem("CurrentPage"))).draw(false);
        $("#geniustable").on('page.dt', () => {
          sessionStorage.setItem("CurrentPage", table.page());
        });
      }
    });

    $(document).on('click', 'a', function () {
      const href = $(this).attr("href");
      const isInternal = !$(this).data("href") && href && !href.includes("#") && !href.includes("javascript") && !href.includes("cartabandonments");
      if (isInternal) {
        sessionStorage.setItem("CurrentPage", 0);
        table.state.clear();
      }
    });
  });
</script>
@endsection
