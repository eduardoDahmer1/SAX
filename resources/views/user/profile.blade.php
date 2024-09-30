@extends('front.themes.' . env('THEME', 'theme-15') . '.layout')
@section('content')
<section class="user-dashbord">
    <div class="container">
        <div class="row">
            @include('includes.user-dashboard-sidebar')
            <div class="col-lg-8">
                <div class="user-profile-details">
                    <div class="account-info">
                        <div class="header-area"><h4 class="title">{{ __('Edit Profile') }}</h4></div>
                        <div class="edit-info-area">
                            <div class="body">
                                <div class="edit-info-area-form">
                                    <div class="gocover" style="background: url({{ $gs->loaderUrl }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
                                    @if (session('unsuccess'))
                                        <div class="alert alert-danger validation"><button type="button" class="close alert-close"><span>×</span></button><p class="text-left">{{ session('unsuccess') }}</p></div>
                                    @endif
                                    <form id="userform" action="{{ route('user-profile-update') }}" method="POST" enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        @include('includes.admin.form-both')
                                        <div class="upload-img">
                                            <div class="img"><img src="{{ $user->photoUrl }}"></div>
                                            @if ($user->is_provider != 1)
                                                <div class="file-upload-area"><div class="upload-file"><input type="file" name="photo" class="upload"><span>{{ __('Upload') }}</span></div></div>
                                            @endif
                                        </div>
                                        <div class="alert alert-warning" id="errorMsg" style="display:none;">{{ __('Invalid Zip Code. Please fill the fields manually!') }}</div>
                                        <div class="row">
                                            <div class="col-lg-6"><input name="name" type="text" class="input-field" placeholder="{{ __('User Name') }}" required="" value="{{ $user->name }}"></div>
                                            <div class="col-lg-6"><input name="email" type="email" class="input-field" placeholder="{{ __('Email Address') }}" required="" value="{{ $user->email }}" disabled></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6"><input name="document" type="text" class="input-field" placeholder="{{ $customer_doc_str }}" required="" value="{{ $user->document }}"></div>
                                            @if ($gs->country_ship == 'PY')
                                                <div class="col-lg-6"><input class="input-field" type="hidden" name="zip" id="billZip" placeholder="{{ __('Postal Code') }}" value=""></div>
                                            @else
                                                @if ($gs->is_zip_validation)
                                                    <div class="col-lg-6"><input class="input-field" type="text" name="zip" id="billZip" placeholder="{{ __('Postal Code') }}" required="" value="{{ $user->zip ?? '' }}"></div>
                                                @endif
                                            @endif
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6"><input name="phone" type="text" class="input-field" placeholder="{{ __('Phone Number') }}" required="" value="{{ $user->phone }}"></div>
                                            <div class="col-lg-6"><input class="input-field" type="text" name="address" id="billAddress" placeholder="{{ __('Address') }}" required="" value="{{ $user->address }}"></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6"><input class="input-field" type="text" name="address_number" id="billAdressNumber" placeholder="{{ __('Number') }}" required="" value="{{ $user->address_number }}"></div>
                                            <div class="col-lg-6"><input type="text" class="input-field" name="complement" placeholder="{{ __('Complement') }}" value="{{ $user->complement }}"></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6"><input class="input-field" type="text" name="district" id="billDistrict" placeholder="{{ __('District') }}" required="" value="{{ $user->district }}"></div>
                                            <div class="col-lg-6">
                                                <select class="form-control" name="country_id" id="billCountry" required="">
                                                    @if ($countries->count() > 1)
                                                        <option value="" data-code="">{{ __('Select Country') }}</option>
                                                    @endif
                                                    @foreach ($countries as $country)
                                                        <option value="{{ $country->id }}" {{ $user->country == $country->country_name ? 'selected' : '' }} data-code="{{ $country->country_code }}">{{ $country->country_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6"><select class="form-control" name="state_id" id="billState" required="" readonly><option value="{{ $user->state_id }}">{{ $user->state }}</option></select></div>
                                            <div class="col-lg-6"><select class="form-control" name="city_id" id="billCity" required="" readonly><option value="{{ $user->city_id }}">{{ $user->city }}</option></select></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 mt-3">
                                                <input type="date" class="form-control" name="birthday" id="birthday" required value="{{ $user->birth_date }}">
                                            </div>
                                            <div class="col-lg-6 mt-3">
                                                <select class="form-control" name="gender" id="gender">
                                                    <option value="">{{ __("Gender") }}</option>
                                                    <option value="M" {{ $user->gender == 'M' ? 'selected' : '' }}> {{ __("Male") }}</option>
                                                    <option value="F" {{ $user->gender == 'F' ? 'selected' : '' }}>  {{ __("Female") }}</option>
                                                    <option value="O" {{ $user->gender == 'O' ? 'selected' : '' }}>  {{ __("Other") }}</option>
                                                    <option value="N" {{ $user->gender == 'N' ? 'selected' : '' }}>  {{ __("Not Declared") }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-links">
                                            <button class="submit-btn" type="submit">{{ __('Save') }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('scripts')
<script>
    $('#billCountry').change(function(e, zipdata) {
        $('#preloader').show();
        $('#errorMsg').hide();
        if (!$(this).val()) {
            $('#billState').attr('readonly', true).html('<option value="">{{ __('Select country first') }}</option>');
            $('#billCity').attr('readonly', true).html('<option value="">{{ __('Select state first') }}</option>');
            $('#preloader').hide();
            return;
        }
        $.ajax({
            type: 'GET',
            url: mainurl + '/checkout/getStatesOptions',
            data: { location_id: $(this).val() },
            success: function(data) {
                $('#billState').html('<option value="">{{ __('Select State') }}</option>').append(data).removeAttr('readonly');
                if (zipdata) {
                    $('#billState').val(zipdata['state_id']).trigger('change', zipdata);
                }
            },
            error: function(err) { console.log(err); },
            complete: function() { $('#preloader').hide(); }
        });
    });
    $('#billState').change(function(e, zipdata) {
        $('#preloader').show();
        if (!$(this).val()) {
            $('#billCity').attr('readonly', true).html('<option value="">{{ __('Select state first') }}</option>');
            $('#preloader').hide();
            return;
        }
        $.ajax({
            type: 'GET',
            url: mainurl + '/checkout/getCitiesOptions',
            data: { location_id: $(this).val() },
            success: function(data) {
                $('#billCity').html('<option value="">{{ __('Select City') }}</option>').append(data).removeAttr('readonly');
                if (zipdata) {
                    $('#billCity').val(zipdata['city_id']).trigger('change');
                }
            },
            error: function(err) { console.log(err); },
            complete: function() { $('#preloader').hide(); }
        });
    });
    $('#billZip').on('change', function() {
        $('#preloader').show();
        $('#errorMsg').hide();
        $.ajax({
            type: 'GET',
            url: mainurl + '/checkout/cep',
            data: { cep: $(this).val() },
            success: function(data) {
                $('#billAddress').val(data.street);
                $('#billDistrict').val(data.neighborhood);
                $('#billCity').val(data.city).trigger('change', data);
                $('#billState').val(data.state_id).trigger('change', data);
            },
            error: function(err) {
                $('#errorMsg').show();
                console.log(err);
            },
            complete: function() {
                $('#preloader').hide();
            }
        });
    });
</script>
@endsection
