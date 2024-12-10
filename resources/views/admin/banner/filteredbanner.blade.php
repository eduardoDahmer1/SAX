@extends('layouts.admin')

@section('styles')
<style>
.mr-breadcrumb .links .action-list li {
    display: block;
}

.mr-breadcrumb .links .action-list ul {
    overflow-y: auto;
    max-height: 240px;
}

.mr-breadcrumb .links .action-list .go-dropdown-toggle {
    padding-left: 20px;
    padding-right: 30px;
}
</style>
@endsection
@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">
                    {{ __('Best Seller') }}
                    <i class="icofont-question-circle" data-toggle="tooltip" data-placement="top"
                        title="{{ __('Side banner that stays in the best-sellers section.') }}"
                        style="font-size: 15px;"></i>
                </h4>
                <ul class="links">
                    <li><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li><a href="{{ route('admin-ps-best-seller') }}">{{ __('Banners') }}</a></li>
                    <li><a href="{{ route('admin-ps-best-seller') }}">{{ __('Best Seller') }}</a></li>
                    @if (config('features.multistore'))
                    <li>
                        <div class="action-list godropdown">
                            <select id="store_filter" class="process select go-dropdown-toggle">
                                @foreach ($stores as $store)
                                <option
                                    value="{{ route('admin-stores-isconfig', ['id' => $store['id'], 'redirect' => true]) }}"
                                    {{ $store['id'] == $admstore->pagesettings->store_id ? 'selected' : '' }}>
                                    {{ $store['domain'] }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
    @include('includes.admin.partials.banner-tabs')
    <div class="add-product-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="product-description">
                    <div class="body-area">
                        <div class="gocover"
                            style="background: url({{ $gs->adminLoaderUrl }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                        </div>
                        <form id="geniusform" action="{{ route('admin-ps-update') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @include('includes.admin.form-both')
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="input-form">
                                        <h4 class="heading">
                                            {{ __('Central Banner Left') }} *
                                            <span>{{ __('(Preferred Size: 500 X 500 Pixel)') }}</span>
                                        </h4>
                                        <div class="img-upload">
                                            <div id="image-preview" class="img-preview"
                                                style="background: url({{ $admstore->pagesettings->banner_search1 ? asset('storage/images/banners/' . $admstore->pagesettings->banner_search1) : asset('assets/images/noimage.png') }});">
                                                <label for="image-upload" class="img-label" id="image-label">
                                                    <i class="icofont-upload-alt"></i>{{ __('Upload Image') }}
                                                </label>
                                                <input type="file" name="banner_search1" class="img-upload"
                                                    id="image-upload">
                                                @if ($admstore->pagesettings->banner_search1)
                                                <button type="button" class="btn btn-danger m-2 remove-banner"
                                                    tipo="banner_search1">x</button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="input-form">
                                        <h4 class="heading">
                                            {{ __('Central Banner Right') }} *
                                            <span>{{ __('(Preferred Size: 640 X 310 Pixel)') }}</span>
                                        </h4>
                                        <div class="img-upload">
                                            <div id="image-preview" class="img-preview"
                                                style="background: url({{ $admstore->pagesettings->banner_search2 ? asset('storage/images/banners/' . $admstore->pagesettings->banner_search2) : asset('assets/images/noimage.png') }});">
                                                <label for="image-upload" class="img-label" id="image-label">
                                                    <i class="icofont-upload-alt"></i>{{ __('Upload Image') }}
                                                </label>
                                                <input type="file" name="banner_search2" class="img-upload"
                                                    id="image-upload">
                                                @if ($admstore->pagesettings->banner_search2)
                                                <button type="button" class="btn btn-danger m-2 remove-banner"
                                                    tipo="banner_search2">x</button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="input-form">
                                        <h4 class="heading">
                                            {{ __('Bottom Banner Image') }} *
                                            <span>{{ __('(Preferred Size: 960 X 290 Pixel)') }}</span>
                                        </h4>
                                        <div class="img-upload">
                                            <div id="image-preview" class="img-preview"
                                                style="background: url({{ $admstore->pagesettings->banner_search3 ? asset('storage/images/banners/' . $admstore->pagesettings->banner_search3) : asset('assets/images/noimage.png') }});">
                                                <label for="image-upload" class="img-label" id="image-label">
                                                    <i class="icofont-upload-alt"></i>{{ __('Upload Image') }}
                                                </label>
                                                <input type="file" name="banner_search3" class="img-upload"
                                                    id="image-upload">
                                                @if ($admstore->pagesettings->banner_search3)
                                                <button type="button" class="btn btn-danger m-2 remove-banner"
                                                    tipo="banner_search3">x</button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="input-form">
                                        <h4 class="heading">
                                            {{ __('Warning banner') }} *
                                            <span>{{ __('(Preferred Size: 960 X 290 Pixel)') }}</span>
                                        </h4>
                                        <div class="img-upload">
                                            <div id="image-preview" class="img-preview"
                                                style="background: url({{ $admstore->pagesettings->banner_search4 ? asset('storage/images/banners/' . $admstore->pagesettings->banner_search4) : asset('assets/images/noimage.png') }});">
                                                <label for="image-upload" class="img-label" id="image-label">
                                                    <i class="icofont-upload-alt"></i>{{ __('Upload Image') }}
                                                </label>
                                                <input type="file" name="banner_search4" class="img-upload"
                                                    id="image-upload">
                                                @if ($admstore->pagesettings->banner_search4)
                                                <button type="button" class="btn btn-danger m-2 remove-banner"
                                                    tipo="banner_search4">x</button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="input-form">
                                        <h4 class="heading">
                                            {{ __('hot sales background section') }} *
                                            <span>{{ __('(Preferred Size: 960 X 290 Pixel)') }}</span>
                                        </h4>
                                        <div class="img-upload">
                                            <div id="image-preview" class="img-preview"
                                                style="background: url({{ $admstore->pagesettings->banner_search5 ? asset('storage/images/banners/' . $admstore->pagesettings->banner_search5) : asset('assets/images/noimage.png') }});">
                                                <label for="image-upload" class="img-label" id="image-label">
                                                    <i class="icofont-upload-alt"></i>{{ __('Upload Image') }}
                                                </label>
                                                <input type="file" name="banner_search5" class="img-upload"
                                                    id="image-upload">
                                                @if ($admstore->pagesettings->banner_search5)
                                                <button type="button" class="btn btn-danger m-2 remove-banner"
                                                    tipo="banner_search5">x</button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="input-form">
                                        <h4 class="heading">
                                            {{ __('gif') }} *
                                            <span>{{ __('(Preferred Size: 960 X 290 Pixel)') }}</span>
                                        </h4>
                                        <div class="img-upload">
                                            <div id="image-preview" class="img-preview"
                                                style="background: url({{ $admstore->pagesettings->banner_search6 ? asset('storage/images/banners/' . $admstore->pagesettings->banner_search6) : asset('assets/images/noimage.png') }});">
                                                <label for="image-upload" class="img-label" id="image-label">
                                                    <i class="icofont-upload-alt"></i>{{ __('Upload Image') }}
                                                </label>
                                                <input type="file" name="banner_search6" class="img-upload"
                                                    id="image-upload">
                                                @if ($admstore->pagesettings->banner_search6)
                                                <button type="button" class="btn btn-danger m-2 remove-banner"
                                                    tipo="banner_search6">x</button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr> <!-- icones preto -->
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="input-form">
                                        <h4 class="heading">
                                            {{ __('Login') }} *
                                            <span>{{ __('(Preferred Size: 960 X 290 Pixel)') }}</span>
                                        </h4>
                                        <div class="img-upload">
                                            <div id="image-preview" class="img-preview"
                                                style="background: url({{ $admstore->pagesettings->banner_search7 ? asset('storage/images/banners/' . $admstore->pagesettings->banner_search7) : asset('assets/images/noimage.png') }});">
                                                <label for="image-upload" class="img-label" id="image-label">
                                                    <i class="icofont-upload-alt"></i>{{ __('Upload Image') }}
                                                </label>
                                                <input type="file" name="banner_search7" class="img-upload"
                                                    id="image-upload">
                                                @if ($admstore->pagesettings->banner_search7)
                                                <button type="button" class="btn btn-danger m-2 remove-banner"
                                                    tipo="banner_search7">x</button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="input-form">
                                        <h4 class="heading">
                                            {{ __('Login checked') }} *
                                            <span>{{ __('(Preferred Size: 960 X 290 Pixel)') }}</span>
                                        </h4>
                                        <div class="img-upload">
                                            <div id="image-preview" class="img-preview"
                                                style="background: url({{ $admstore->pagesettings->banner_search14 ? asset('storage/images/banners/' . $admstore->pagesettings->banner_search14) : asset('assets/images/noimage.png') }});">
                                                <label for="image-upload" class="img-label" id="image-label">
                                                    <i class="icofont-upload-alt"></i>{{ __('Upload Image') }}
                                                </label>
                                                <input type="file" name="banner_search14" class="img-upload"
                                                    id="image-upload">
                                                @if ($admstore->pagesettings->banner_search14)
                                                <button type="button" class="btn btn-danger m-2 remove-banner"
                                                    tipo="banner_search14">x</button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="input-form">
                                        <h4 class="heading">
                                            {{ __('Cart') }} *
                                            <span>{{ __('(Preferred Size: 960 X 290 Pixel)') }}</span>
                                        </h4>
                                        <div class="img-upload">
                                            <div id="image-preview" class="img-preview"
                                                style="background: url({{ $admstore->pagesettings->banner_search8 ? asset('storage/images/banners/' . $admstore->pagesettings->banner_search8) : asset('assets/images/noimage.png') }});">
                                                <label for="image-upload" class="img-label" id="image-label">
                                                    <i class="icofont-upload-alt"></i>{{ __('Upload Image') }}
                                                </label>
                                                <input type="file" name="banner_search8" class="img-upload"
                                                    id="image-upload">
                                                @if ($admstore->pagesettings->banner_search8)
                                                <button type="button" class="btn btn-danger m-2 remove-banner"
                                                    tipo="banner_search8">x</button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="input-form">
                                        <h4 class="heading">
                                            {{ __('Bag') }} *
                                            <span>{{ __('(Preferred Size: 960 X 290 Pixel)') }}</span>
                                        </h4>
                                        <div class="img-upload">
                                            <div id="image-preview" class="img-preview"
                                                style="background: url({{ $admstore->pagesettings->banner_search9 ? asset('storage/images/banners/' . $admstore->pagesettings->banner_search9) : asset('assets/images/noimage.png') }});">
                                                <label for="image-upload" class="img-label" id="image-label">
                                                    <i class="icofont-upload-alt"></i>{{ __('Upload Image') }}
                                                </label>
                                                <input type="file" name="banner_search9" class="img-upload"
                                                    id="image-upload">
                                                @if ($admstore->pagesettings->banner_search9)
                                                <button type="button" class="btn btn-danger m-2 remove-banner"
                                                    tipo="banner_search9">x</button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr> <!-- icones branco -->
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="input-form">
                                        <h4 class="heading">
                                            {{ __('Login white') }} *
                                            <span>{{ __('(Preferred Size: 960 X 290 Pixel)') }}</span>
                                        </h4>
                                        <div class="img-upload">
                                            <div id="image-preview" class="img-preview"
                                                style="background: url({{ $admstore->pagesettings->banner_search10 ? asset('storage/images/banners/' . $admstore->pagesettings->banner_search10) : asset('assets/images/noimage.png') }});">
                                                <label for="image-upload" class="img-label" id="image-label">
                                                    <i class="icofont-upload-alt"></i>{{ __('Upload Image') }}
                                                </label>
                                                <input type="file" name="banner_search10" class="img-upload"
                                                    id="image-upload">
                                                @if ($admstore->pagesettings->banner_search10)
                                                <button type="button" class="btn btn-danger m-2 remove-banner"
                                                    tipo="banner_search10">x</button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="input-form">
                                        <h4 class="heading">
                                            {{ __('Login white checked') }} *
                                            <span>{{ __('(Preferred Size: 960 X 290 Pixel)') }}</span>
                                        </h4>
                                        <div class="img-upload">
                                            <div id="image-preview" class="img-preview"
                                                style="background: url({{ $admstore->pagesettings->banner_search13 ? asset('storage/images/banners/' . $admstore->pagesettings->banner_search13) : asset('assets/images/noimage.png') }});">
                                                <label for="image-upload" class="img-label" id="image-label">
                                                    <i class="icofont-upload-alt"></i>{{ __('Upload Image') }}
                                                </label>
                                                <input type="file" name="banner_search13" class="img-upload"
                                                    id="image-upload">
                                                @if ($admstore->pagesettings->banner_search13)
                                                <button type="button" class="btn btn-danger m-2 remove-banner"
                                                    tipo="banner_search13">x</button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="input-form">
                                        <h4 class="heading">
                                            {{ __('Cart white') }} *
                                            <span>{{ __('(Preferred Size: 960 X 290 Pixel)') }}</span>
                                        </h4>
                                        <div class="img-upload">
                                            <div id="image-preview" class="img-preview"
                                                style="background: url({{ $admstore->pagesettings->banner_search11 ? asset('storage/images/banners/' . $admstore->pagesettings->banner_search11) : asset('assets/images/noimage.png') }});">
                                                <label for="image-upload" class="img-label" id="image-label">
                                                    <i class="icofont-upload-alt"></i>{{ __('Upload Image') }}
                                                </label>
                                                <input type="file" name="banner_search11" class="img-upload"
                                                    id="image-upload">
                                                @if ($admstore->pagesettings->banner_search11)
                                                <button type="button" class="btn btn-danger m-2 remove-banner"
                                                    tipo="banner_search11">x</button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="input-form">
                                        <h4 class="heading">
                                            {{ __('Bag white') }} *
                                            <span>{{ __('(Preferred Size: 960 X 290 Pixel)') }}</span>
                                        </h4>
                                        <div class="img-upload">
                                            <div id="image-preview" class="img-preview"
                                                style="background: url({{ $admstore->pagesettings->banner_search12 ? asset('storage/images/banners/' . $admstore->pagesettings->banner_search12) : asset('assets/images/noimage.png') }});">
                                                <label for="image-upload" class="img-label" id="image-label">
                                                    <i class="icofont-upload-alt"></i>{{ __('Upload Image') }}
                                                </label>
                                                <input type="file" name="banner_search12" class="img-upload"
                                                    id="image-upload">
                                                @if ($admstore->pagesettings->banner_search12)
                                                <button type="button" class="btn btn-danger m-2 remove-banner"
                                                    tipo="banner_search12">x</button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <button class="addProductSubmit-btn" type="submit">{{ __('Save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
$(document).ready(function() {
    $("#store_filter").niceSelect('update');
});

$("#store_filter").on('change', function() {
    window.location.href = $(this).val();
});
</script>
@endsection