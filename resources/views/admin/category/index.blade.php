@extends('layouts.admin')

@section('styles')
<style>
.presentation-pos {
    color: black;
    background-color: #e9e9ed;
    text-align: center;
    width: 30%;
    border: 1px dashed #b5b5b582;
    border-radius: 7px;
    margin-left: 30%;
    float: left;
}
</style>
@endsection

@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Main Categories') }}</h4>
                <ul class="links">
                    <li><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li><a href="{{ route('admin-cat-index') }}">{{ __('Categories') }}</a></li>
                    <li><a href="{{ route('admin-cat-index') }}">{{ __('Main Categories') }}</a></li>
                </ul>
            </div>
        </div>
        <div class="action-list godropdown">
            <select id="category_filters" class="process select go-dropdown-toggle">
                @foreach ($filters as $filter => $name)
                    <option value="{{ route('admin-cat-datatables', $filter) }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    @include('includes.admin.partials.category-tabs')

    <div class="product-area">
        <div class="row">
            <div class="col-lg-12">
                <div class="mr-table allproduct">
                    @include('includes.admin.form-success')
                    <div class="table-responsiv">
                        <table id="geniustable" class="table table-hover dt-responsive" width="100%">
                            <thead>
                                <tr>
                                    <th width="20%"><i class="icofont-options icofont-lg" title='{{ __('Options') }}'></i></th>
                                    <th>{{ __('Name') }}</th>
                                    <th><i class="icofont-basket icofont-lg" title='{{ __('Products') }}'></i></th>
                                    <th>{{ __('Attribute Count') }}</th>
                                    <th><i class="icofont-eye icofont-lg" title='{{ __('Status') }}'></i></th>
                                    <th>{{ __('Presentation Position') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach (['modal1', 'attribute', 'confirm-delete', 'setgallery'] as $modal)
<div class="modal fade" id="{{ $modal }}" tabindex="-1" role="dialog" aria-labelledby="{{ $modal }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered{{ $modal === 'setgallery' ? ' modal-lg' : '' }}" role="document">
        <div class="modal-content">
            <div class="submit-loader"><img src="{{ $admstore->adminLoaderUrl }}" alt=""></div>
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button></div>
        </div>
    </div>
</div>
@endforeach
@endsection

@section('scripts')
<script>
var table = $('#geniustable').DataTable({
    stateSave: true,
    ordering: false,
    processing: true,
    serverSide: true,
    ajax: '{{ route('admin-cat-datatables') }}',
    columns: [
        { data: 'action', searchable: false, orderable: false },
        { data: 'name', render: (d, t, r) => r.name + '<br><small> slug: ' + r.slug + '</small>' },
        { data: 'products', searchable: false, orderable: false },
        { data: 'attr_count', searchable: false },
        { data: 'status', searchable: false, orderable: false },
        { data: 'presentation_position', searchable: false, orderable: false }
    ],
    language: {
        url: '{{ $datatable_translation }}',
        processing: '<img src="{{ $admstore->adminLoaderUrl }}">'
    },
    drawCallback: function() {
        $(this).find('.select').niceSelect();
        $(".checkboxStatus").on('click', function() {
            var id = this.id.replace("checkbox-status-", "");
            var status = this.name.slice(-1) === "0" ? "1" : "0";
            $.get('{{ url('admin/category/status') }}/' + id + '/' + status);
        });
        $('.presentation-pos').on('change', function() {
            var id = $(this).data('cat'), pos = Math.abs($(this).val());
            $.get('{{ url('admin/category/changeCatPos') }}/' + id + '/' + pos);
            $.notify('{{ __('New Category Position Added') }}', "success");
        });
    },
    initComplete: function() {
        $(".btn-area").append('<div class="col-sm-4 table-contents"><a class="add-btn" data-href="{{ route('admin-cat-create') }}" data-header="{{ __('Add New Category') }}" id="add-data" data-toggle="modal" data-target="#modal1"><i class="fas fa-plus"></i> {{ __('Add New Category') }}</a></div>');
        $("#geniustable").on('page.dt', () => sessionStorage.setItem("CurrentPage", table.page()));
    }
});

$(document).ready(function() {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
    if (!sessionStorage.getItem("CurrentPage")) sessionStorage.setItem("CurrentPage", 0);
    $(document).on('click', 'a', function() {
        var link = $(this);
        if (!(link.data("href") || link.attr("href").includes("#") || link.attr("href").includes("javascript") || !link.attr("href").includes("category"))) {
            sessionStorage.setItem("CurrentPage", 0);
            table.state.clear();
        }
    });
});

function tableRowCountReset() {
    $("#bulk_all").prop('checked', false);
    sessionStorage.setItem("CurrentPage", 0);
    $(".bulkeditbtn, .bulkremovebtn").hide();
}

$('#category_filters').on('change', function() {
    tableRowCountReset();
    sessionStorage.setItem('SelectedCategoriesFilter', $(this).val());
    table.ajax.url(sessionStorage.getItem('SelectedCategoriesFilter')).load();
});
</script>
@endsection
