@extends('layouts.admin')
@section('content')
<div class="content-area">
  <div class="mr-breadcrumb">
    <div class="row">
      <div class="col-lg-12">
        <h4 class="heading">{{ __('Child Categories') }}</h4>
        <ul class="links">
          <li><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
          <li><a href="{{ route('admin-cat-index') }}">{{ __('Categories') }}</a></li>
          <li><a href="{{ route('admin-childcat-index') }}">{{ __('Child Categories') }}</a></li>
        </ul>
      </div>
    </div>
  </div>
  @include('includes.admin.partials.category-tabs')
  <div class="product-area">
    <div class="row">
      <div class="col-lg-12">
        <div class="mr-table allproduct">
          @include('includes.admin.form-success')
          <div class="table-responsiv">
            <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th><i class="icofont-options icofont-lg" data-toggle="tooltip" title='{{ __('Options') }}'></i></th>
                  <th>{{ __('Name') }}</th>
                  <th>{{ __('Category') }}</th>
                  <th>{{ __('Sub Category') }}</th>
                  <th><i class="icofont-basket icofont-lg" data-toggle="tooltip" title='{{ __('Products') }}'></i></th>
                  <th><i class="icofont-eye icofont-lg" data-toggle="tooltip" title='{{ __('Status') }}'></i></th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@foreach(['modal1', 'attribute'] as $id)
<div class="modal fade" id="{{ $id }}" tabindex="-1" role="dialog" aria-labelledby="{{ $id }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="submit-loader"><img src="{{ $admstore->adminLoaderUrl }}" alt=""></div>
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
      </div>
    </div>
  </div>
</div>
@endforeach

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header d-block text-center">
        <h4 class="modal-title d-inline-block">{{ __('Confirm Delete') }}</h4>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body text-center">
        <p>{{ __('You are about to delete this Child Category. Everything under this child category will be deleted.') }}</p>
        <p>{{ __('Do you want to proceed?') }}</p>
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
        <a class="btn btn-danger btn-ok">{{ __('Delete') }}</a>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  var table = $('#geniustable').DataTable({
    stateSave: true,
    stateDuration: -1,
    ordering: false,
    processing: true,
    serverSide: true,
    ajax: '{{ route('admin-childcat-datatables') }}',
    columns: [
      { data: 'action', searchable: false, orderable: false },
      { data: 'name', render: (d, t, r) => r.name + '<br><small> slug: ' + r.slug + '</small>' },
      { data: 'category', searchable: false, orderable: false },
      { data: 'subcategory', searchable: false, orderable: false },
      { data: 'products', searchable: false, orderable: false },
      { data: 'status', searchable: false, orderable: false }
    ],
    language: {
      url: '{{ $datatable_translation }}',
      processing: '<img src="{{ $admstore->adminLoaderUrl }}">'
    },
    drawCallback: function() {
      $(this).find('.select').niceSelect();
      $(".checkboxStatus").on('click', function() {
        var id = this.id.replace("checkbox-status-", "");
        var status = this.name.slice(-1);
        var newStatus = status == "0" ? "1" : "0";
        this.name = this.name.slice(0, -1) + newStatus;
        $.get(`{{ url('admin/childcategory/status') }}/${id}/${newStatus}`);
      });
    },
    initComplete: function() {
      $(".btn-area").append(`<div class="col-sm-4 table-contents">
        <a class="add-btn" data-href="{{ route('admin-childcat-create') }}" data-header="{{ __('Add New Child Category') }}" id="add-data" data-toggle="modal" data-target="#modal1">
          <i class="fas fa-plus"></i> {{ __('Add New Child Category') }}
        </a></div>`);
      $("#geniustable").on('page.dt', () => sessionStorage.setItem("CurrentPage", table.page()));
    }
  });

  $(document).ready(function() {
    if (!sessionStorage.getItem("CurrentPage")) sessionStorage.setItem("CurrentPage", 0);
    $(document).on('click', 'a', function() {
      let link = $(this), prefix = '{{ Request::route()->getPrefix() }}'.split("/")[1];
      if (!(link.attr("data-href") || link.attr("href").includes("#") || link.attr("href").includes("javascript") || link.attr("href").includes(prefix))) {
        sessionStorage.setItem("CurrentPage", 0);
        table.state.clear();
      }
    });
  });
</script>
@endsection
