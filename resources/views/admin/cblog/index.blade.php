@extends('layouts.admin')
@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Categories') }}</h4>
                <ul class="links">
                    <li><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li><a href="{{ route('admin-blog-index') }}">{{ __('Blog') }}</a></li>
                    <li><a href="{{ route('admin-cblog-index') }}">{{ __('Categories') }}</a></li>
                </ul>
            </div>
        </div>
    </div>

    @include('includes.admin.partials.blog-tabs')
    @include('includes.admin.form-success')

    <div class="product-area">
        <div class="mr-table allproduct">
            <div class="table-responsiv">
                <table id="example" class="table table-hover dt-responsive" width="100%">
                    <thead>
                        <tr>
                            <th><i class="icofont-options icofont-lg" data-toggle="tooltip" title='{{ __('Options') }}'></i></th>
                            <th>{{ __('Name') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal1" tabindex="-1" role="dialog">
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

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title">{{ __('Confirm Delete') }}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body text-center">
                <p>{{ __('You are about to delete this Category. Everything will be deleted under this Category.') }}</p>
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
    var table = $('#example').DataTable({
        stateSave: true,
        stateDuration: -1,
        ordering: false,
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin-cblog-datatables') }}',
        columns: [
            { data: 'action', searchable: false, orderable: false },
            { data: 'name', name: 'name' },
            { data: 'slug', name: 'slug', visible: false }
        ],
        language: {
            url: '{{ $datatable_translation }}',
            processing: '<img src="{{ $admstore->adminLoaderUrl }}">'
        },
        drawCallback: () => $('.select').niceSelect(),
        initComplete: function() {
            $(".btn-area").append(`<div class="col-sm-4 text-right">
                <a class="add-btn" data-href="{{ route('admin-cblog-create') }}" data-header="{{ __('Add New Category') }}"
                   id="add-data" data-toggle="modal" data-target="#modal1">
                   <i class="fas fa-plus"></i> {{ __('Add New Category') }}
                </a></div>`);
            $("#geniustable").on('page.dt', () => sessionStorage.setItem("CurrentPage", table.page()));
        }
    });

    $(document).ready(function () {
        if (!sessionStorage.getItem("CurrentPage")) sessionStorage.setItem("CurrentPage", 0);
        $(document).on('click', 'a', function () {
            let link = $(this), prefix = '{{ Request::route()->getPrefix() }}'.split("/");
            if (link.attr("href")?.includes("blog") || !(link.attr("data-href") || link.attr("href")?.includes("#") || link.attr("href")?.includes("javascript") || link.attr("href")?.includes(prefix[1]))) {
                sessionStorage.setItem("CurrentPage", 0);
                table.state.clear();
            }
        });
    });
</script>
@endsection
