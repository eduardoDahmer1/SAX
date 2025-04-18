@extends('layouts.admin')

@section('styles')
<style>
  .special-box { border: 1px solid #ccc; padding: 15px; background: #fff; border-radius: 5px; }
</style>
@endsection

@section('content')
<div class="content-area">
  <div class="breadcrumb-header">
    <h4 class="title">{{ __('Manage Attribute') }}</h4>
    <a class="btn btn-primary" href="{{ route($type == 'category' ? 'admin-cat-index' : ($type == 'subcategory' ? 'admin-subcat-index' : 'admin-childcat-index')) }}">
      <i class="fas fa-arrow-left"></i> {{ __('Back') }}
    </a>
  </div>

  <ul class="breadcrumb">
    <li><a href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i>{{ __('Dashboard') }}</a></li>
    <li><a href="{{ route($type == 'category' ? 'admin-cat-index' : ($type == 'subcategory' ? 'admin-subcat-index' : 'admin-childcat-index')) }}">{{ __(ucfirst($type)) }}</a></li>
    <li>{{ __('Manage Attribute') }}</li>
  </ul>

  <div class="category-nav my-3">
    <ul class="nav">
      <li class="nav-item">
        <a class="nav-link active">{{ $category->name }}</a>
      </li>
    </ul>
  </div>

  <div class="special-box">
    <div class="gocover" style="display: none;"></div>
    <div class="table-responsive">
      <table class="table datatable text-center" id="attribute-table" width="100%">
        <thead>
          <tr>
            <th>{{ __('Actions') }}</th>
            <th>{{ __('Name') }}</th>
            <th>{{ __('Options') }}</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>

{{-- Modals --}}
<div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-hidden="true"></div>
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-hidden="true"></div>
@include('includes.form-success')
@include('includes.form-error')
@include('admin.category.attribute')

@endsection

@section('scripts')
<script>
  $('#modal1').on('hidden.bs.modal', function () {
    $(this).removeData('bs.modal').find('.modal-content').empty();
  });

  $(document).ready(function () {
    const table = $('#attribute-table').DataTable({
      ordering: false,
      processing: true,
      serverSide: true,
      ajax: '{{ route("admin-attr-datatables") }}?type={{ $type }}&id={{ $category->id }}',
      columns: [
        { data: 'action', searchable: false, orderable: false },
        { data: 'name' },
        { data: 'options', searchable: false, orderable: false }
      ],
      language: {!! json_encode($datatable_translation) },
      drawCallback: function () {
        $('.btn-area').html(`
          <div class="col-sm-4 table-contents">
            <a class="main-btn add-btn" data-toggle="modal" data-target="#attribute">
              <i class="fas fa-plus"></i> {{ __('Add New Attribute') }}
            </a>
          </div>
        `);
      }
    });

    const backLinks = ['admin-cat-index', 'admin-subcat-index', 'admin-childcat-index'].map(route => '{{ route("' + route + '") }}');
    if (!backLinks.includes(document.referrer)) sessionStorage.removeItem("page_attribute");
  });
</script>
@endsection
