@extends('layouts.admin')
@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Coupons') }}</h4>
                <ul class="links">
                    <li><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li><a href="{{ route('admin-coupon-index') }}">{{ __('Coupons') }}</a></li>
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
                                    <th><i class="icofont-barcode icofont-lg" data-toggle="tooltip" title='{{ __('Code') }}'></i></th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Used') }}</th>
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
<div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="submit-loader">
                <img src="{{ $admstore->adminLoaderUrl }}" alt="">
            </div>
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
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header d-block text-center">
                <h4 class="modal-title d-inline-block">{{ __('Confirm Delete') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <p class="text-center">{{ __('You are about to delete this Coupon.') }}</p>
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
<script type="text/javascript">
    var table = $('#geniustable').DataTable({
        stateSave: true,
        stateDuration: -1,
        ordering: false,
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin-coupon-datatables') }}',
        columns: [
            { data: 'action', searchable: false, orderable: false },
            { data: 'code', name: 'code' },
            { data: 'type', name: 'type' },
            { data: 'price', name: 'price' },
            { data: 'used', name: 'used' },
            { data: 'status', searchable: false, orderable: false }
        ],
        language: {
            url: '{{ $datatable_translation }}',
            processing: '<img src="{{ $admstore->adminLoaderUrl }}">'
        },
        drawCallback: function() {
            $(this).find('.select').niceSelect();
            $(".checkboxStatus").on('click', function() {
                var id = $(this).attr("id").replace("checkbox-status-", "");
                var status = $(this).attr('name').slice(-1);
                var statusNovo = status == "0" ? "1" : "0";
                $.ajax({ type: 'GET', url: '{{ url('admin/coupon/status') }}' + '/' + id + '/' + statusNovo });
            });
        },
        initComplete: function() {
            $(".btn-area").append('<div class="col-sm-4 table-contents">' +
                '<a class="add-btn" data-href="{{ route('admin-coupon-create') }}" data-header="{{ __('Add New Coupon') }}" id="add-data" data-toggle="modal" data-target="#modal1">' +
                '<i class="fas fa-plus"></i> {{ __('Add New Coupon') }}</a></div>');
            $("#geniustable").on('page.dt', function() {
                sessionStorage.setItem("CurrentPage", table.page());
            });
        }
    });
</script>
<script>
    var enforceModalFocusFn = $.fn.modal.Constructor.prototype._enforceFocus;
    $.fn.modal.Constructor.prototype._enforceFocus = function() {};
</script>
<script>
    $('#modal1').on('hidden.bs.modal', function() {
        $("#ui-datepicker-div").remove();
    });
</script>
<script>
    $(document).ready(function() {
        if (sessionStorage.getItem("CurrentPage") === undefined) {
            sessionStorage.setItem("CurrentPage", 0);
        }
        $(document).on('click', 'a', function() {
            var link = $(this);
            var x = '{{ Request::route()->getPrefix() }}'.split("/");
            if (!(link.attr("data-href") || link.attr("href").indexOf("#") > -1 || link.attr("href").indexOf("javascript") > -1 || link.attr("href").indexOf(x[1]) > -1)) {
                sessionStorage.setItem("CurrentPage", 0);
                table.state.clear();
            }
        });
    });
</script>
@endsection
