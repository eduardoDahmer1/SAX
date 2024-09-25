@extends('layouts.admin')

@section('styles')
<style>
    .mr-breadcrumb .links .action-list li { display: block; }
    .mr-breadcrumb .links .action-list ul { overflow-y: auto; max-height: 240px; }
    .mr-breadcrumb .links .action-list .go-dropdown-toggle { padding-left: 20px; padding-right: 30px; }
</style>
@endsection
@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('AEX Shipping') }}</h4>
                <ul class="links">
                    <li><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li><a href="{{ route('admin-shipping-index') }}">{{ __('Shipping Methods') }}</a></li>
                    <li><a href="{{ route('admin-gs-aexconf') }}">{{ __('AEX Shipping') }}</a></li>
                    @if (config('features.multistore'))
                        <li>
                            <div class="action-list godropdown">
                                <select id="store_filter" class="process select go-dropdown-toggle">
                                    @foreach ($stores as $store)
                                        <option value="{{ route('admin-stores-isconfig', ['id' => $store['id'], 'redirect' => true]) }}" {{ $store['id'] == $admstore->id ? 'selected' : '' }}>{{ $store['domain'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
    @include('includes.admin.partials.shipping-tabs')
    <div class="add-product-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="product-description">
                    <div class="body-area">
                        <div class="gocover" style="background: url({{ $admstore->adminLoaderUrl }}) no-repeat center center rgba(45, 45, 45, 0.5);"></div>
                        <div class="submit-loader"><img src="{{ $admstore->adminLoaderUrl }}" alt=""></div>
                        <form action="{{ route('admin-gs-update') }}" id="geniusform" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            @include('includes.admin.form-both')
                            @foreach ([
                                'aex_public' => __('AEX Public Key'),
                                'aex_private' => __('AEX Private Key'),
                                'aex_calle_principal' => __('Main street'),
                                'aex_calle_transversal' => __('Cross street'),
                                'aex_numero_casa' => __('Building number'),
                                'aex_telefono' => __('Telephone')
                            ] as $name => $label)
                                <div class="row justify-content-center">
                                    <div class="col-lg-3"><div class="left-area"><h4 class="heading">{{ $label }}:</h4></div></div>
                                    <div class="col-lg-6"><input name="{{ $name }}" class="input-field" value="{{ $admstore->$name }}" required=""></div>
                                </div>
                            @endforeach
                            <div class="row justify-content-center">
                                <div class="col-lg-3"><div class="left-area"><h4 class="heading">{{ __('Origin City') }}:</h4></div></div>
                                <div class="col-lg-6">
                                    <select id="aex_origin" name="aex_origin">
                                        <option value="">{{ __('Select City') }}</option>
                                        @foreach ($aex_cities as $city)
                                            <option value="{{ $city->codigo_ciudad }}" {{ $city->codigo_ciudad == $admstore->aex_origin ? 'selected' : '' }}>{{ $city->denominacion }} - {{ $city->departamento_denominacion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @foreach ([
                                'is_aex_production' => __('Production Mode'),
                                'is_aex_insurance' => __('Calculate Insurance')
                            ] as $name => $label)
                                <div class="row justify-content-center">
                                    <div class="col-lg-3"><div class="left-area"><h4 class="heading">{{ $label }}:</h4></div></div>
                                    <div class="col-lg-6">
                                        <div class="action-list">
                                            <select class="process select droplinks {{ $admstore->$name ? 'drop-success' : 'drop-danger' }}">
                                                <option data-val="1" value="{{ route('admin-gs-aex-' . strtolower(str_replace(' ', '-', $label)), 1) }}" {{ $admstore->$name ? 'selected' : '' }}>{{ __('Activated') }}</option>
                                                <option data-val="0" value="{{ route('admin-gs-aex-' . strtolower(str_replace(' ', '-', $label)), 0) }}" {{ !$admstore->$name ? 'selected' : '' }}>{{ __('Deactivated') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <div class="row justify-content-center">
                                <div class="col-lg-3"></div>
                                <div class="col-lg-6">
                                    <a class="mybtn1 btn-info btn-update-aex-cities" data-href="{{ route('admin-gs-update-aex-cities') }}">
                                        <i class="fas fa-sync-alt"></i><span>{{ __('Update AEX Cities') }}</span>
                                    </a>
                                </div>
                            </div>

                            <div class="row justify-content-center">
                                <div class="col-lg-3"></div>
                                <div class="col-lg-6">
                                    <button class="addProductSubmit-btn" type="submit">{{ __('Save') }}</button>
                                </div>
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
        $("#store_filter").niceSelect('update').on('change', function() {
            window.location.href = $(this).val();
        });

        $('.btn-update-aex-cities').on('click', function() {
            if (admin_loader == 1) $('.submit-loader').show();
            $.ajax({
                type: "GET",
                url: $(this).data('href'),
                success: function(data) {
                    if (data.errors) {
                        $('.alert-success').hide();
                        $('.alert-danger').show().find('p').html(data);
                        $.each(data.errors, function(i, error) {
                            $('.alert-danger ul').append('<li>' + error + '</li>');
                        });
                    } else {
                        $.ajax({
                            type: "GET",
                            url: '{{ route('admin-gs-load-aex-cities') }}',
                            success: function(data_cities) {
                                $("#aex_origin").html(data_cities);
                                $('.alert-danger').hide();
                                $('.alert-success').show().find('p').html(data);
                            }
                        });
                    }
                    if (admin_loader == 1) $('.submit-loader').hide();
                }
            });
            return false;
        });
    });
</script>
@endsection
