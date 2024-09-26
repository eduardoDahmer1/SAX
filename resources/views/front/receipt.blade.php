@extends('front.themes.' . env('THEME', 'theme-15') . '.layout')
@section('content')
<section class="user-dashbord">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h4 class="titlereceipt">{{ __("Upload Receipt") }}</h4>
                <div class="receipt-content">
                    <form id="r-form" class="receipt-form">
                        {{ csrf_field() }}
                        <div class="box-form"><label for="code">{{ __("Enter the order number") }}</label><input type="text" id="code" placeholder="Ex: 6x7X1655555589" required value="{{ isset($order_number) ? $order_number : null }}"><i class="icofont-search-1"></i></div>
                        <button type="submit" class="mybtn1">{{ __("Search") }}</button>
                        <button type="button" id="btnClear" class="mybtn1">{{ __("Clear") }}</button>
                    </form>
                    <div class="modal-body" id="order-receipt"></div>
                </div>
                <div id="hiddenForm" class="col-xl-5" hidden>
                    <img id="preview" width="350px" src="" alt="">
                    <h5 class="titlereceipt">{{ __("Receipt for Order Number")}}: <b id="order_number"></b></h5>
                    <form id="uploadReceipt" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="box-insert-img"><label for="receipt">{{ __('Choose File') }}</label><input type="file" name="receipt" id="receipt" onchange="readURL(this)" required><button type="submit" id="btnUpload" class="btn btn-success btn-ok">{{ __('Send Receipt') }}</button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('scripts')
<script type="text/javascript">
$(document).ready(function(){
    if($("#code").val() != "") $("#r-form").submit();
    
    $("#btnClear").click(function(){
        $('#code').val("").focus();
        $("#uploadReceipt").attr("action", "");
        $("#preview").attr("src", "");
        $("#hiddenForm").attr("hidden", true);
    });
    $('#r-form').on('submit', function(e){
        e.preventDefault();
        $.get('{{ url("receipt/search") }}/' + $('#code').val(), function(data) {
            if (data.errors || !data.success) {
                toastr.error(data.errors ? data.error : data.msg);
                return;
            }
            $("#order_number").text($('#code').val());
            $("#hiddenForm").removeAttr("hidden");
            $("#uploadReceipt").attr("action", '{{ route("front.upload-receipt") }}/' + data.order_id);
            $("#preview").attr("src", data.has_receipt ? '{{ asset("storage/images/receipts/") }}/' + data.receipt : "");
            toastr[data.has_receipt ? 'warning' : 'success'](data.msg);
            $('#code').val("");
        });
    });
    $("#uploadReceipt").on('submit', function(e){
        e.preventDefault();
        $.ajax({
            method: "POST",
            url: $(this).attr('action'),
            data: new FormData(this),
            contentType: false,
            processData: false,
            success: function(data) {
                toastr[data.success ? 'success' : 'error'](data.msg);
                $("#preview").attr("src", "").end().trigger("reset");
                $("#hiddenForm").attr("hidden", true);
            }
        });
    });
});
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#preview').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
