@extends('layouts.admin')

@section('styles')
<style>
    .mr-breadcrumb .links .action-list li { display: block; }
    .mr-breadcrumb .links .action-list ul { overflow-y: auto; max-height: 240px; }
    .mr-breadcrumb .links .action-list .go-dropdown-toggle, .add-btn { padding: 0 30px; margin-bottom: 20px; }
</style>
@endsection

@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Large') }}</h4>
                <ul class="links">
                    <li><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li><a href="{{ route('admin-ps-best-seller') }}">{{ __('Banners') }}</a></li>
                    <li><a href="{{ route('admin-sb-large') }}">{{ __('Large') }}</a></li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <ul class="links">
                    <li>
                        <div class="action-list godropdown">
                            <select id="store_filters" class="process go-dropdown-toggle">
                                <option value="">{{ __('All Stores') }}</option>
                                @foreach ($storesList as $store)
                                    <option value='{{ $store->id }}'>{{ $store->domain }}</option>
                                @endforeach
                            </select>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    @include('includes.admin.partials.banner-tabs')

    <div class="product-area">
        <div class="row">
            <div class="col-lg-12">
                <div class="mr-table allproduct">
                    @include('includes.admin.form-success')
                    <div class="table-responsiv">
                        <table id="geniustable" class="table table-hover dt-responsive" width="100%">
                            <thead>
                                <tr>
                                    <th><i class="icofont-options icofont-lg" title="{{ __('Options') }}"></i></th>
                                    <th><i class="icofont-ui-image icofont-lg" title="{{ __('Featured Image') }}"></i></th>
                                    <th>{{ __('Link') }}</th>
                                    <th>{{ __('Updated at') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL: ADD / EDIT --}}
<div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="submit-loader"><img src="{{ $admstore->adminLoaderUrl }}" alt=""></div>
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL: DELETE --}}
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title">{{ __('Confirm Delete') }}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body text-center">
                <p>{{ __('You are about to delete this Banner.') }}</p>
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
    var qtde = 0;
    function tableRowCountReset() {
        qtde = 0;
        sessionStorage.setItem("CurrentPage", 0);
    }

    var table = $('#geniustable').DataTable({
        stateSave: true,
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin-sb-datatables', 'Large') }}',
        columns: [
            { data: 'action', searchable: false, orderable: false },
            { data: 'photo', searchable: false, orderable: false },
            { data: 'link' },
            { data: 'updated_at' },
            { data: 'store', searchable: true, visible: false }
        ],
        language: {
            url: '{{ $datatable_translation }}',
            processing: '<img src="{{ $admstore->adminLoaderUrl }}">'
        },
        drawCallback: function() {
            $('#geniustable_length').on('change', function() {
                tableRowCountReset(); table.ajax.reload();
            });
        },
        initComplete: function() {
            $(".btn-area").append('<div class="col-sm-4 table-contents"><a class="add-btn" data-href="{{ route('admin-sb-create-large') }}" data-header="{{ __('Add New Banner') }}" id="add-data" data-toggle="modal" data-target="#modal1"><i class="fas fa-plus"></i> {{ __('Add New Banner') }}</a></div>');
            $("#store_filters").on('change', function() {
                tableRowCountReset();
                table.column('store_id:name').search(this.value).draw();
                sessionStorage.setItem('SelectedStoreFilter', $(this).val());
            });
        }
    });

    $(document).ready(function() {
        $('#store_filters').niceSelect();
        if (!sessionStorage.getItem("CurrentPage")) sessionStorage.setItem("CurrentPage", 0);
        if (!sessionStorage.getItem('SelectedStoreFilter')) {
            sessionStorage.setItem('SelectedStoreFilter', $("#store_filters").val());
        } else {
            table.column('store_id:name').search(sessionStorage.getItem("SelectedStoreFilter")).draw();
        }

        $(document).on('click', 'a', function() {
            sessionStorage.setItem('SelectedStoreFilter', $("#store_filters").val());
            var link = $(this);
            if (!(link.attr("data-href") || link.attr("href").includes("#") || link.attr("href").includes("javascript"))) {
                sessionStorage.setItem("CurrentPage", 0);
                sessionStorage.setItem('SelectedStoreFilter', $("#store_filters option:first").val());
                table.state.clear();
            }
            if (link.attr("href").includes("banner")) {
                sessionStorage.setItem("CurrentPage", 0);
                sessionStorage.setItem('SelectedStoreFilter', $("#store_filters option:first").val());
                table.state.clear();
            }
        });
    });
</script>
@endsection
