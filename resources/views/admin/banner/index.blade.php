@extends('layouts.admin')

@section('styles')
    <style>
        .mr-breadcrumb .links .action-list li { display: block; }
        .mr-breadcrumb .links .action-list ul { overflow-y: auto; max-height: 240px; }
        .mr-breadcrumb .links .action-list .go-dropdown-toggle, .add-btn {     padding: 1em;
    justify-content: center;
    align-items: center;
    display: flex;
    margin: 0 auto; }
    </style>
@endsection

@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Top Small') }}</h4>
                    <ul class="links">
                        <li><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li><a href="{{ route('admin-ps-best-seller') }}">{{ __('Banners') }}</a></li>
                        <li><a href="{{ route('admin-sb-index') }}">{{ __('Top Small') }}</a></li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <select id="store_filters" class="process go-dropdown-toggle">
                        <option value="">{{ __('All Stores') }}</option>
                        @foreach ($storesList as $store)
                            <option value="{{ $store->id }}">{{ $store->domain }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        @include('includes.admin.partials.banner-tabs')
        <div class="product-area">
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

    {{-- MODALS --}}
    <div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="submit-loader"><img src="{{ $admstore->adminLoaderUrl }}" alt=""></div>
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
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
        var table = $('#geniustable').DataTable({
            stateSave: true,
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin-sb-datatables', 'TopSmall') }}',
            columns: [
                { data: 'action', searchable: false, orderable: false },
                { data: 'photo', searchable: false, orderable: false },
                { data: 'link' },
                { data: 'updated_at' },
                { data: 'store', name: 'store_id', searchable: true, visible: false }
            ],
            language: { url: '{{ $datatable_translation }}', processing: '<img src="{{ $admstore->adminLoaderUrl }}">' },
            initComplete: function() {
                $("#store_filters").on('change', function() {
                    table.column('store_id:name').search(this.value).draw();
                    sessionStorage.setItem('SelectedStoreFilter', this.value);
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
        });
    </script>
@endsection
