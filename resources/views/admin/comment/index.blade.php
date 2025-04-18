@extends('layouts.admin')
@section('content')
<div class="content-area">
  <div class="mr-breadcrumb">
    <div class="row">
      <div class="col-lg-12">
        <h4 class="heading">{{ __('Comments') }}</h4>
        <ul class="links">
          <li><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
          <li><a href="javascript:;">{{ __('Support') }}</a></li>
          <li><a href="{{ route('admin-rating-index') }}">{{ __('Products') }}</a></li>
        </ul>
      </div>
    </div>
  </div>

  @include('includes.admin.partials.support-product-tabs')

  <div class="product-area">
    <div class="row">
      <div class="col-lg-12">
        <div class="mr-table allproduct">
          @include('includes.admin.form-success')
          <div class="table-responsiv">
            <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th><i class="icofont-options icofont-lg" data-toggle="tooltip" title="{{ __('Options') }}"></i></th>
                  <th width="20%">{{ __('Product') }}</th>
                  <th width="20%">{{ __('Commenter') }}</th>
                  <th width="40%">{{ __('Comment') }}</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- ADD / EDIT MODAL --}}
<div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="submit-loader"><img src="{{ $gs->adminLoaderUrl }}" alt=""></div>
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

{{-- DELETE MODAL --}}
<div class="modal fade" id="confirm-delete">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header text-center"><h4 class="modal-title w-100">{{ __('Confirm Delete') }}</h4></div>
      <div class="modal-body text-center">
        <p>{{ __('You are about to delete this Comment.') }}</p>
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
  ajax: '{{ route('admin-comment-datatables') }}',
  columns: [
    { data: 'action', searchable: false, orderable: false },
    { data: 'product', name: 'product', searchable: false, orderable: false },
    { data: 'commenter', name: 'commenter' },
    { data: 'text', name: 'text' }
  ],
  language: {
    url: '{{ $datatable_translation }}',
    processing: '<img src="{{ $gs->adminLoaderUrl }}">'
  },
  initComplete: function() {
    $("#geniustable").on('page.dt', function() {
      sessionStorage.setItem("CurrentPage", table.page());
    });
  }
});

$(document).ready(function() {
  if (!sessionStorage.getItem("CurrentPage")) sessionStorage.setItem("CurrentPage", 0);
  $(document).on('click', 'a', function(e) {
    let link = $(this), x = '{{ Request::route()->getPrefix() }}', y = x.split("/");
    if (!(link.attr("data-href") || link.attr("href").includes("#") || link.attr("href").includes("javascript") || link.attr("href").includes(y[1]))) {
      sessionStorage.setItem("CurrentPage", 0);
      table.state.clear();
    }
  });
});
</script>
@endsection
