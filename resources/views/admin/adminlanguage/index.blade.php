@extends('layouts.admin')

@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Admin Panel Language') }}</h4>
                <ul class="links">
                    <li><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li><a href="{{ route('admin-tlang-index') }}">{{ __('Admin Panel Language') }}</a></li>
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
                        <table id="geniustable" class="table table-hover dt-responsive" width="100%">
                            <thead>
                                <tr>
                                    <th><i class="icofont-options" title='{{ __("Options") }}'></i></th>
                                    <th><i class="icofont-globe" title='{{ __("Language") }}'></i></th>
                                    <th><i class="icofont-ui-text-loading" title='{{ __("Locale") }}'></i></th>
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
    var table = $('#geniustable').DataTable({
        stateSave: true,
        stateDuration: -1,
        ordering: false,
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin-tlang-datatables') }}',
        columns: [
            { data: 'action', searchable: false, orderable: false },
            { data: 'language' },
            { data: 'name' }
        ],
        language: {
            url: '{{ $datatable_translation }}',
            processing: '<img src="{{ $admstore->adminLoaderUrl }}">'
        },
        drawCallback: function() {
            $('.select').niceSelect();
        },
        initComplete: function() {
            $("#geniustable").on('page.dt', function() {
                sessionStorage.setItem("CurrentPage", table.page());
            });
        }
    });

    $(document).ready(function() {
        if (!sessionStorage.getItem("CurrentPage")) {
            sessionStorage.setItem("CurrentPage", 0);
        }

        $(document).on('click', 'a', function(e) {
            var href = $(this).attr('href') || '';
            var prefix = '{{ Request::route()->getPrefix() }}'.split("/")[1];
            if (!(href.includes("#") || href.includes("javascript") || href.includes(prefix))) {
                sessionStorage.setItem("CurrentPage", 0);
                table.state.clear();
            }
        });
    });
</script>
@endsection
