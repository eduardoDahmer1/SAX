@extends('layouts.admin')
@section('content')
<div class="content-area">
    <div class="submit-loader"><img src="{{ $admstore->adminLoaderUrl }}" alt=""></div>
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-6"><h4 class="heading">{{ __('Brands') }}</h4>
                <ul class="links">
                    <li><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li><a href="{{ route('admin-brand-index') }}">{{ __('Brands') }}</a></li>
                </ul>
            </div>
            <div class="col-lg-6 text-right">
                <button class="add-btn" id="generateThumbnails" href="{{ route('admin-brand-generatethumbnails') }}">
                    <i class="fas fa-sync-alt"></i> {{ __('Update Thumbnails') }}
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <ul class="links">
                    <li>
                        <div class="action-list godropdown">
                            <select id="brands_filters" class="process select go-dropdown-toggle">
                                @foreach ($filters as $filter => $name)
                                    <option value="{{ route('admin-brand-datatables', $filter) }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </li>
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
                                    <th><i class="icofont-options icofont-lg" data-toggle="tooltip" title='{{ __('Options') }}'></i></th>
                                    <th width="20%">{{ __('Name') }}</th>
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
{{-- ADD / EDIT MODAL --}}
<div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="submit-loader"><img src="{{ $admstore->adminLoaderUrl }}" alt=""></div>
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>
{{-- DELETE MODAL --}}
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header d-block text-center">
                <h4 class="modal-title d-inline-block">{{ __('Confirm Delete') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <p class="text-center">{{ __('You are about to delete this Brand.') }}</p>
                <p class="text-center">{{ __('Do you want to proceed?') }}</p>
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
        stateSave: true, stateDuration: -1, ordering: false, processing: true, serverSide: true,
        ajax: '{{ route('admin-brand-datatables') }}',
        columns: [
            { data: 'action', searchable: false, orderable: false },
            { data: 'name', name: 'name', searchable: true },
            { data: 'products', searchable: false, orderable: false },
            { data: 'status', searchable: false, orderable: false },
            { data: 'slug', name: 'slug', visible: false, searchable: true }
        ],
        language: { url: '{{ $datatable_translation }}', processing: '<img src="{{ $admstore->adminLoaderUrl }}">'},
        drawCallback: function() {
            $(".checkboxStatus").on('click', function() {
                var id = $(this).attr("id").replace("checkbox-status-", "");
                var status = $(this).attr('name').slice(-1);
                var newStatus = (status == "0") ? "1" : "0";
                $.ajax({ type: 'GET', url: '{{ url('admin/brand/status') }}' + '/' + id + '/' + newStatus });
            });
        },
        initComplete: function() {
            $(".btn-area").append('<div class="col-sm-4 table-contents"><a class="add-btn" data-href="{{ route('admin-brand-create') }}" data-header="{{ __('Add New Brand') }}" id="add-data" data-toggle="modal" data-target="#modal1"><i class="fas fa-plus"></i> {{ __('Add New Brand') }}</a></div>');
            $("#geniustable").on('page.dt', function() { sessionStorage.setItem("CurrentPage", table.page()); });
        }
    });

    $(document).ready(function() {
        $("#generateThumbnails").on('click', function(e) {
            e.preventDefault();
            $('#generateThumbnails').prop('disabled', true);
            $('.submit-loader').show();
            $.ajax({
                type: 'GET', url: $(this).attr('href'),
                success: function(data) {
                    $('#generateThumbnails').prop('disabled', false);
                    $('.submit-loader').hide();
                    $.notify(data.message, data.status ? (data.alert ? 'info' : 'success') : 'error');
                    if (data.errors) { for (var error in data.errors) { $.notify(data.errors[error], 'error'); } }
                }
            });
        });

        if (!sessionStorage.getItem("CurrentPage")) { sessionStorage.setItem("CurrentPage", 0); }

        $(document).on('click', 'a[href]', function() {
            var link = $(this), prefix = '{{ Request::route()->getPrefix() }}';
            if (!(link.attr("data-href") || link.attr("href").indexOf("#") > -1 || link.attr("href").indexOf("javascript") > -1 || link.attr("href").indexOf(prefix.split("/")[1]) > -1)) {
                sessionStorage.setItem("CurrentPage", 0); table.state.clear();
            }
        });

        $('#brands_filters').on('change', function() {
            sessionStorage.setItem('SelectedCategoriesFilter', $(this).val());
            table.ajax.url(sessionStorage.getItem('SelectedCategoriesFilter')).load();
        });
    });

    function deleteImage(id, target, targetBtn) {
        $.ajax({
            url: '{{ route('admin-brand-delete-image') }}',
            type: 'POST',
            data: { 'id': id, 'target': target },
            success: function(data) {
                if (data.status) { $('#modal1 .alert-success').show().find('p').html(data.message); $(targetBtn).closest('.img-preview').css({"background": data.noimage}); }
                if (data.errors) { for (var error in data.errors) { $.notify(data.errors[error], 'error'); } }
            }
        });
    }
</script>
@endsection
