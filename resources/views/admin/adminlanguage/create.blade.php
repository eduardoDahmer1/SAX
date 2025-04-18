@extends('layouts.admin')

@section('styles')
    <style>textarea.input-field { padding: 20px; border-radius: 0; }</style>
@endsection

@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <h4 class="heading">{{ __('Add Language') }} 
            <a class="add-btn" href="{{ route('admin-tlang-index') }}">
                <i class="fas fa-arrow-left"></i> {{ __('Back') }}
            </a>
        </h4>
        <ul class="links">
            <li><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
            <li><a href="{{ route('admin-tlang-index') }}">{{ __('Admin Panel Language') }}</a></li>
            <li><a href="{{ route('admin-tlang-create') }}">{{ __('Add Language') }}</a></li>
        </ul>
    </div>

    <div class="add-product-content">
        <div class="product-description">
            <div class="body-area">
                <div class="gocover" style="background: url({{ $admstore->adminLoaderUrl }}) center no-repeat rgba(45,45,45,0.5);"></div>

                <form id="geniusform" action="{{ route('admin-tlang-create') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="input-form"><p><small>* {{ __('indicates a required field') }}</small></p></div>

                    @include('includes.admin.form-both')

                    <div class="row">
                        <div class="col-lg-4">
                            <div class="input-form">
                                <h4 class="heading">{{ __('Language') }} *</h4>
                                <input type="text" name="language" class="input-field" required placeholder="{{ __('Language') }}">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="input-form">
                                <h4 class="heading">{{ __('Locale') }} * <span>{{ __('Ex: en, pt-br, es') }}</span></h4>
                                <input type="text" name="locale" class="input-field" required placeholder="{{ __('en') }}">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="input-form">
                                <h4 class="heading">{{ __('Language Direction') }} *</h4>
                                <select name="rtl" class="input-field" required>
                                    <option value="0">{{ __('Left To Right') }}</option>
                                    <option value="1">{{ __('Right To Left') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <button class="addProductSubmit-btn" type="submit">{{ __('Create Language') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
