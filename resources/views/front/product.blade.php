@extends('front.themes.' . env('THEME', 'theme-15') . '.layout')
@section('styles')
@parent
<style>.active {display: block;}.hidden {display: none;}</style>
@endsection
@section('content')
@include('front._product-header')
@include('front._product-details')
@include('front._product-custom-gallery-modal')
@if(config('features.marketplace'))
    @include('front._product-features-marketplace')
@endif
@if($gs->is_report)
    @include('front._product-report-modal')
@endif
@endsection
@section('scripts')
<script>
    const isColorGallery = "{{ !is_null($color_gallery) ? $color_gallery[0] : false }}";
    const showStock = "{{ $gs->show_stock }}";
    const productID = "{{ $productt->id }}";
    const productName = "{{ $productt->name }}";
    const productCategory = "{{ $productt->category->name }}";
    const productPrice = "{{ $productt->price }}";
    const productCurrency = "{{ $product_curr->name }}";
    if (isColorGallery) {
        const colors = "{{ $productt->color ? implode(',', $productt->color) : '' }}";
    }
    function updateStockInfo(stockQty) {
        $("#stock").val(stockQty);
        $("#stock_qty").html(stockQty);
    }
    $(document).on('click', '.product-size .siz-list .box', function() {
        const sizeQty = $(this).find('.size_qty').val();
        if (showStock == 1) updateStockInfo(sizeQty);
    });
    $(document).on('click', '.product-color .color-list .box', function() {
        const colorQty = $(this).find('.color_qty').val();
        const color = $(this).find('.color').val();
        if (showStock == 1) updateStockInfo(colorQty);
        const selectedColor = `.color-${color.replace('#', '')}`;
        if (isColorGallery) {
            $(".owl-item.active").addClass("hidden").removeClass("active");
            $(".owl-item").addClass("hidden");

            $(selectedColor).parent().removeClass("hidden").addClass("active");
            $(".owl-item.active " + selectedColor).parent().removeClass("hidden").addClass("active");

            $(selectedColor).removeClass("hidden active");
            $(".owl-item.active " + selectedColor).addClass("active").trigger("click");
        }
    });
    $(document).on('change', '#select-materials', function() {
        const material = $(this).val();
        const selectedMaterial = `.material-${material}`;
        const materialPrice = $(this).find("option:selected").data('material-price');
        const materialKey = $(this).find("option:selected").data('material-key');
        const materialName = $(this).find("option:selected").data('material-name');
        const materialQty = $(this).find("option:selected").data('material-qty');
        if (showStock == 1) updateStockInfo(materialQty);
        $(".material").val(materialName);
        $(".material_qty").val(materialQty);
        $(".material_price").val(materialPrice);
        $(".material_key").val(materialKey);

        $(".owl-item.active").addClass("hidden").removeClass("active");
        $(".owl-item").addClass("hidden");
        $(selectedMaterial).parent().removeClass("hidden").addClass("active");
        $(".owl-item.active " + selectedMaterial).parent().removeClass("hidden").addClass("active");

        $(selectedMaterial).removeClass("hidden active");
        $(".owl-item.active " + selectedMaterial).addClass("active").trigger("click");
    });
    $(document).ready(function() {
        const stockQty = [$('.size_qty'), $('.color_qty'), $('.material_qty')].reduce((acc, el) => {
            return acc || (el.val() > 0 ? el.val() : null);
        }, null);
        if (stockQty) updateStockInfo(stockQty);
        if (typeof fbq !== 'undefined') {
            fbq('track', 'ViewContent', {
                content_name: productName,
                content_category: productCategory,
                content_ids: productID,
                content_type: 'Product',
                value: productPrice,
                currency: productCurrency
            });
        }
    });
    $(document).on('keydown', '#customizable_number', function() {
        const value = $(this).val();
        if (value.length > 1) $(this).val(value.substring(0, value.length - 1));
    });
    $(document).on("submit", "#emailreply1, #emailreply", function() {
        const form = $(this);
        const token = form.find('input[name=_token]').val();
        const subject = form.find('input[name=subject]').val();
        const message = form.find('textarea[name=message]').val();
        const url = form.attr('id') === 'emailreply1' ? "{{ URL::to('/user/admin/user/send/message') }}" : "{{ URL::to('/vendor/contact') }}";
        form.find('input, textarea').prop('disabled', true);
        $.ajax({
            type: 'post',
            url: url,
            data: {
                '_token': token,
                subject: subject,
                message: message,
                ...(form.attr('id') === 'emailreply' && {
                    email: form.find('input[name=email]').val(),
                    name: form.find('input[name=name]').val(),
                    user_id: form.find('input[name=user_id]').val(),
                    vendor_id: form.find('input[name=vendor_id]').val()
                })
            },
            success: function(data) {
                form.find('input, textarea').prop('disabled', false).val('');
                if (data == 0) {
                    toastr.error("Oops Something Went Wrong !!");
                } else {
                    toastr.success("{{ __('Message Sent !!') }}");
                }
                $('.close').click();
            }
        });
        return false;
    });
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).on('submit', '#logoUpload', function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{ url('admin/customprod/store') }}",
            type: "POST",
            data: new FormData(this),
            dataType: 'JSON',
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                toastr[data.success ? 'success' : 'error'](data.success ? "{{ __('Logo Uploaded Successfully!!') }}" : data.message);
                if (!data.success) $("#image-upload").val(null);
            },
            error: function(data) {
                console.log(data);
            }
        });
    });
    function toggleLogoField() {
        const logoField = document.getElementById("logoField");
        logoField.style.display = document.getElementById("customLogo").checked ? "block" : "none";
    }
    $("#openBtn").click(function() {
        $("#openOptions").modal("show");
    });
    $(".checkclick").change(function() {
        $(this).val($(this).is(":checked") ? 1 : 0);
    });
    $(".textureIcons, .textureIconsModal").click(function() {
        const imageSrc = $(this).find('img').attr('src');
        $('input[id=customizable_gallery]').val(imageSrc);
        const replaced = imageSrc.replace("thumbnails", "galleries");
        $("#currentGallery, .overlayCurrentSpan, .textureCurrentOverlay").attr("src", replaced).css("display", "block");
        $("#openOptions").modal("hide");
        toastr.success("{{ __('Image checked!!') }}");
    });
</script>
@endsection
