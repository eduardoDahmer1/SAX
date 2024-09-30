@extends('layouts.vendor')
@section('content')
<input type="hidden" id="headerdata" value="PRODUCT">
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('All Affiliate Products') }}</h4>
                <ul class="links">
                    <li><a href="{{ route('vendor-dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li><a href="javascript:;">{{ __('Affiliate Products') }}</a></li>
                    <li><a href="javascript:;">{{ __('All Affiliate Products') }}</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="product-area">
        <div class="row">
            <div class="col-lg-12">
                <div class="mr-table allproduct">
                    @include('includes.admin.form-success')
                    <div class="table-responsive">
                        <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th><i class="icofont-ui-image icofont-lg" title="{{ __('Photo') }}"></i></th>
                                    <th><i class="icofont-options icofont-lg" title="{{ __('Options') }}"></i></th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Size') }}</th>
                                    <th><i class="fa fa-th-large fa-lg" title="{{ __('Stock') }}"></i></th>
                                    <th><i class="icofont-dollar icofont-lg" title="{{ __('Price') }}"></i></th>
                                    <th><i class="icofont-eye icofont-lg" title="{{ __('Status') }}"></i></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal2" tabindex="-1" role="dialog" aria-labelledby="modal2" aria-hidden="true">
    <div class="modal-dialog highlight" role="document">
        <div class="modal-content">
            <div class="submit-loader"><img src="{{ $gs->adminLoaderUrl }}" alt=""></div>
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
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
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-center">{{ __('You are about to delete this Product.') }}</p>
                <p class="text-center">{{ __('Do you want to proceed?') }}</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                <a class="btn btn-danger btn-ok">{{ __('Delete') }}</a>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="setgallery" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Image Gallery') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="top-area">
                    <div class="row">
                        <div class="col-sm-6 text-right">
                            <div class="upload-img-btn">
                                <form method="POST" enctype="multipart/form-data" id="form-gallery">
                                    {{ csrf_field() }}
                                    <input type="hidden" id="pid" name="product_id" value="">
                                    <input type="file" name="gallery[]" class="hidden" id="uploadgallery" accept="image/*" multiple>
                                    <label for="image-upload" id="prod_gallery">
                                        <i class="icofont-upload-alt"></i>{{ __('Upload File') }}
                                    </label>
                                </form>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <a href="javascript:;" class="upload-done" data-dismiss="modal">
                                <i class="fas fa-check"></i> {{ __('Done') }}
                            </a>
                        </div>
                        <div class="col-sm-12 text-center">
                            (<small>{{ __('You can upload multiple Images.') }}</small>)
                        </div>
                    </div>
                </div>
                <div class="gallery-images">
                    <div class="selected-image">
                        <div class="row"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
    var table = $('#geniustable').DataTable({
        ordering: false,
        processing: true,
        serverSide: true,
        ajax: '{{ route('vendor-import-datatables') }}',
        columns: [
            { data: 'photo', name: 'photo', render: function(data) { return '<img src="'+data+'" class="avatar" width="50" height="50"/>'; }},
            { data: 'action', searchable: false, orderable: false },
            { data: 'name', name: 'name' },
            { data: 'stock', name: 'stock', searchable: false, orderable: false },
            { data: 'price', name: 'price', searchable: false, orderable: false },
            { data: 'status', name: 'status', searchable: false, orderable: false }
        ],
        language: { url: '{{ $datatable_translation }}', processing: '<img src="{{ $gs->adminLoaderUrl }}">' },
        drawCallback: function() {
            $(".checkboxStatus").on('click', function() {
                var id = $(this).attr("id").replace("checkbox-status-", "");
                var name = $(this).attr('name');
                var status = name.slice(-1);
                var statusNovo = (status == "0") ? "1" : "0";
                $(this).attr("name", name.slice(0, -1) + statusNovo);
                $.ajax({ type: 'GET', url: '{{ url('vendor/products/status') }}' + '/' + id + '/' + statusNovo });
            });
        },
        initComplete: function() {
            $(".btn-area").append(
                '<div class="col-sm-4 table-contents">' +
                '<a class="add-btn" href="{{ route('vendor-import-create') }}">' +
                '<i class="fas fa-plus"></i> <span class="remove-mobile">{{ __('Add New Product') }}<span>' +
                '</a>' +
                '</div>'
            );
        }
    });
    $(document).on('click', 'a', function(e) {
        var link = $(this);
        var x = '{{ Request::route()->getPrefix() }}'.split("/");
        if (!(link.attr("data-href") || link.attr("href").indexOf("#") > -1 || link.attr("href").indexOf("javascript") > -1)) {
            sessionStorage.setItem("CurrentPage", 0);
            table.state.clear();
        }
    });
    $(document).on("click", ".set-gallery", function() {
        var pid = $(this).find('input[type=hidden]').val();
        $('#pid').val(pid);
        $('.selected-image .row').html('');
        $.ajax({
            type: "GET",
            url: "{{ route('vendor-gallery-show') }}",
            data: { id: pid },
            success: function(data) {
                if (data[0] == 0) {
                    $('.selected-image .row').addClass('justify-content-center').html('<h3>{{ __('No Images Found.') }}</h3>');
                } else {
                    $('.selected-image .row').removeClass('justify-content-center').html('');
                    data[1].forEach(function(el) {
                        $('.selected-image .row').append(
                            '<div class="col-sm-6">' +
                            '<div class="img gallery-img">' +
                            '<span class="remove-img"><i class="fas fa-times"></i><input type="hidden" value="'+el.id+'"></span>' +
                            '<a href="{{ asset('storage/images/galleries') }}/'+el.photo+'" target="_blank">' +
                            '<img src="{{ asset('storage/images/galleries') }}/'+el.photo+'" alt="gallery image">' +
                            '</a>' +
                            '</div>' +
                            '</div>'
                        );
                    });
                }
            }
        });
    });
    $(document).on('click', '.remove-img', function() {
        var id = $(this).find('input[type=hidden]').val();
        $(this).parent().parent().remove();
        $.get("{{ route('vendor-gallery-delete') }}", { id: id });
    });

    $('#uploadgallery').on('change', function() {
        $("#form-gallery").submit();
    });
    $(document).on('submit', '#form-gallery', function() {
        $.ajax({
            url: "{{ route('vendor-gallery-store') }}",
            method: "POST",
            data: new FormData(this),
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                if (data != 0) {
                    data.forEach(function(el) {
                        $('.selected-image .row').append(
                            '<div class="col-sm-6">' +
                            '<div class="img gallery-img">' +
                            '<span class="remove-img"><i class="fas fa-times"></i><input type="hidden" value="'+el.id+'"></span>' +
                            '<a href="{{ asset('storage/images/galleries') }}/'+el.photo+'" target="_blank">' +
                            '<img src="{{ asset('storage/images/galleries') }}/'+el.photo+'" alt="gallery image">' +
                            '</a>' +
                            '</div>' +
                            '</div>'
                        );
                    });
                }
            }
        });
        return false;
    });
</script>
@endsection
