@extends('layouts.load')
@section('content')
<div class="top-area">
    <div class="row">
        <div class="col-sm-6 text-right">
            <div class="upload-img-btn">
                <form method="POST" enctype="multipart/form-data" id="form-gallery">
                    {{ csrf_field() }}
                    <input type="hidden" id="cid" name="category_id" value="{{ $data->id }}">
                    <input type="file" name="gallery[]" class="hidden" id="uploadgallery" accept="image/*" multiple>
                    <label for="image-upload" id="cat_gallery"><i class="icofont-upload-alt"></i>{{ __("Upload File") }}</label>
                </form>
            </div>
        </div>
        <div class="col-sm-6">
            <a href="javascript:;" class="upload-done" data-dismiss="modal"><i class="fas fa-check"></i>{{ __("Done") }}</a>
        </div>
        <div class="col-sm-12 text-center"><small>{{ __("You can upload multiple Images") }}.</small></div>
    </div>
</div>
<div class="gallery-images">
    <div class="selected-image"><div class="row"><div><span></span></div></div></div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function(){
    var cid = $('#cid').val();
    $('.selected-image .row').html('');
    $.get('{{ route("admin-categorygallery-show") }}',{id:cid},function(data){
        let row = $('.selected-image .row');
        row.removeClass('justify-content-center').html('');
        if(data[0]==0){
            row.addClass('justify-content-center').html('<h3>{{ __("No Images Found.") }}</h3>');
        }else{
            let imgs = data[2] || $.map(data[1], e => e);
            $.each(imgs, function(i,img){
                let id = img.id || '';
                let src = img.customizable_gallery ? '{{ asset("storage/images/galleries") }}/'+img.customizable_gallery : img;
                let html = '<div class="col-sm-4"><div class="img gallery-img"><span class="remove-img"><i class="fas fa-times"></i><input type="hidden" value="'+id+'"></span>';
                if(img.customizable_gallery){
                    html += '<div class="gallery-img-id"><span>'+id+'</span></div>';
                }
                html += '<a href="'+src+'" target="_blank"><img src="'+src+'" alt="gallery image"></a></div></div>';
                row.append(html);
            });
        }
    });
});

$(document).on('click','.remove-img',function(){
    var id = $(this).find('input[type=hidden]').val();
    $(this).closest('.col-sm-4').remove();
    $.get('{{ route("admin-categorygallery-delete") }}',{id:id});
});

$('#cat_gallery').click(function(){ $('#uploadgallery').click(); });
$('#uploadgallery').change(function(){ $('#form-gallery').submit(); });

$('#form-gallery').submit(function(){
    $.ajax({
        url:'{{ route("admin-categorygallery-store") }}',
        method:'POST',
        data:new FormData(this),
        dataType:'JSON',
        contentType:false,
        cache:false,
        processData:false,
        success:function(data){
            if(data!=0){
                let row = $('.selected-image .row');
                row.removeClass('justify-content-center').find('h3').remove();
                $.each($.map(data, e=>e), function(i,img){
                    let html = '<div class="col-sm-4"><div class="img gallery-img"><span class="remove-img"><i class="fas fa-times"></i><input type="hidden" value="'+img.id+'"></span><a href="{{ asset("storage/images/galleries") }}/'+img.customizable_gallery+'" target="_blank"><img src="{{ asset("storage/images/galleries") }}/'+img.customizable_gallery+'" alt="gallery image"></a></div></div>';
                    row.append(html);
                });
            }
        }
    });
    return false;
});
</script>
@endsection
