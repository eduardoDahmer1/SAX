@if ((empty($color_gallery) && empty($material_gallery)) || (!empty($color_gallery) && !empty($material_gallery)))
<div class="col-lg-6">
    <div class="row gallery-product">
        <div class="col-12 col-6 remove-padding p-2">
            @php $mainPhoto = filter_var($productt->photo, FILTER_VALIDATE_URL) ? $productt->photo : asset('storage/images/products/' . $productt->photo); @endphp
            <a href="{{ $mainPhoto }}">
                <img class="w-100" src="{{ $mainPhoto }}" xoriginal="{{ $mainPhoto }}" />
            </a>
        </div>
        @if ($gs->ftp_folder)
            @foreach ($ftp_gallery as $ftp_image)
                @if ($ftp_image != $productt->photo)
                    <div class="col-lg-6 col-6 remove-padding p-2">
                        <a href="{{ $ftp_image }}">
                            <img class="img-fluid" src="{{ $ftp_image }}">
                        </a>
                    </div>
                @endif
            @endforeach
        @endif
        @foreach ($productt->galleries as $gal)
            <div class="col-lg-6 col-6 remove-padding p-2">
                <a href="{{ $gal->photo_url }}">
                    <img class="img-fluid" src="{{ $gal->photo_url }}">
                </a>
            </div>
        @endforeach
    </div>
</div>
@elseif (!empty($color_gallery) && empty($material_gallery))
    <div class="col-lg-6 col-md-12">
        <div class="xzoom-container">
            @php
                $first = !empty($color_gallery) ? explode('|', $color_gallery[0])[0] : $mainPhoto;
                $colorGalleryImages = !empty($color_gallery) ? $color_gallery : [$mainPhoto];
            @endphp
            <img class="xzoom5" id="xzoom-magnific" src="{{ asset('storage/images/color_galleries/' . $first) }}" />
            <div class="xzoom-thumbs">
                <div class="all-slider-color-gallery">
                    @foreach ($colorGalleryImages as $arr_key => $gal)
                        <a href="{{ asset('storage/images/color_galleries/' . $gal) }}" class="color_gallery color-{{ str_replace('#', '', $productt->color[$arr_key]) }} {{ $arr_key == 0 ? 'active' : 'hidden' }}">
                            <img class="xzoom-gallery5" width="80" src="{{ asset('storage/images/color_galleries/' . $gal) }}" title="The description goes here">
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@elseif (empty($color_gallery) && !empty($material_gallery))
    <div class="col-lg-5 col-md-12">
        <div class="xzoom-container">
            @php $firstMaterial = explode('|', $material_gallery[0])[0]; @endphp
            <img class="xzoom5" id="xzoom-magnific" src="{{ asset('storage/images/material_galleries/' . $firstMaterial) }}" />
            <div class="xzoom-thumbs">
                <div class="all-slider-material-gallery">
                    @foreach ($material_gallery as $arr_key => $material_gal)
                        <a href="{{ asset('storage/images/material_galleries/' . $material_gal) }}" class="material_gallery material-{{ $arr_key }} {{ $arr_key == 0 ? 'active' : 'hidden' }}">
                            <img class="xzoom-gallery5" width="80" src="{{ asset('storage/images/material_galleries/' . $material_gal) }}" title="The description goes here">
                        </a>
                    @endforeach
                    @foreach ($productt->galleries as $gal)
                        <a href="{{ asset('storage/images/galleries/' . $gal->photo) }}">
                            <img class="xzoom-gallery5" width="80" src="{{ asset('storage/images/galleries/' . $gal->photo) }}" title="The description goes here">
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif
