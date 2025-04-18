@extends('layouts.load')

@section('content')
<div class="content-area">
  <div class="add-product-content">
    <div class="row">
      <div class="col-lg-12">
        <div class="product-description">
          <div class="body-area">
            @include('includes.admin.form-error')
            <form id="geniusformdata" action="{{ route('admin-bank_account-create') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="input-form mb-3">
                <p><small>* {{ __("indicates a required field") }}</small></p>
              </div>
              <div class="row">
                <div class="col-xl-6">
                  <div class="input-form">
                    <h4 class="heading">{{ __('Bank Name') }} *</h4>
                    <input type="text" class="input-field" name="name" placeholder="{{ __('Bank Name') }}" required>
                  </div>
                </div>
                <div class="col-xl-6">
                  <div class="input-form">
                    <h4 class="heading">{{ __('Account info') }} *</h4>
                    <textarea class="input-field" name="info" placeholder="{{ __('Account Info') }}" required rows="5" cols="33"></textarea>
                  </div>
                </div>
              </div>
              <div class="row justify-content-center mt-3">
                <button class="addProductSubmit-btn" type="submit">{{ __('Create Bank Account') }}</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
