@extends('front.themes.' . env('THEME', 'theme-15') . '.layout')
@section('content')
    <div class="breadcrumb-area">
        <div class="container">
            <ul class="pages"><li><a href="{{ route('front.index') }}">{{ __('Home') }}</a></li><li><a href="{{ route('front.contact') }}">{{ __('Contact Us') }}</a></li></ul>
        </div>
    </div>
    <section class="contact-us">
        <div class="container-lg m-auto">
            <div class="text-center w-75 m-auto">
                <h3>Envía tu mensaje y te responderemos a la brevedad.</h3>
                <p class='lead'>Bienvenido al mundo de SAX. La tienda de artículos de lujo más grande de Sudamérica. Por favor, siéntase libre de enviar su mensaje.</p>
            </div>
            <div class="contact-form m-auto">
                <div class="gocover" style="background: url({{ asset('storage/images/' . $gs->loader) }}) no-repeat center rgba(45, 45, 45, 0.5);"></div>
                <form id="contactform" action="{{ route('front.contact.submit') }}" method="POST">
                    {{ csrf_field() }}
                    @include('includes.admin.form-both')
                    <div class="row justify-content-center">
                        <div class="col-xl-4 col-lg-5">
                            <div class="form-input"><input type="text" name="name" placeholder="{{ __('Name') }} *" required><i class="icofont-user-alt-5"></i></div>
                            <div class="form-input"><input type="text" name="phone" placeholder="{{ __('Phone Number') }} *"><i class="icofont-ui-call"></i></div>
                            <div class="form-input"><input type="email" name="email" placeholder="{{ __('Email Address') }} *" required><i class="icofont-email"></i></div>
                        </div>
                        <div class="col-xl-4 col-lg-5">
                            <div class="form-input"><textarea name="text" placeholder="{{ __('Your Message') }} *" required></textarea></div>
                        </div>
                    </div>
                    <div class="row mt-4 justify-content-center">
                        <div class="col-xl-4 col-lg-5 d-flex justify-content-center">
                            @if ($gs->is_capcha == 1)
                                <ul class="captcha-area">
                                    <li><p><img class="codeimg1" src="{{ asset('storage/images/capcha_code.png') }}" alt=""><i class="fas fa-sync-alt pointer refresh_code"></i></p></li>
                                    <li><input name="codes" type="text" class="input-field" placeholder="{{ __('Enter Code') }}" required></li>
                                </ul>
                            @endif
                        </div>
                        <div class="col-xl-4 col-lg-5 d-flex justify-content-center">
                            <input type="hidden" name="to" value="{{ $ps->contact_email }}">
                            <button class="submit-btn" type="submit">{{ __('Send Message') }}</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row mt-5 justify-content-center">
                <div class="col-xl-12">
                    <div class="row justify-content-center">
                        @if ($ps->site || $ps->email)
                        <div class="col-xl-3 contact-info">
                            <div class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24"><path d="M4.222 19.778a4.983 4.983 0 0 0 3.535 1.462 4.986 4.986 0 0 0 3.536-1.462l2.828-2.829-1.414-1.414-2.828 2.829a3.007 3.007 0 0 1-4.243 0 3.005 3.005 0 0 1 0-4.243l2.829-2.828-1.414-1.414-2.829 2.828a5.006 5.006 0 0 0 0 7.071zm15.556-8.485a5.008 5.008 0 0 0 0-7.071 5.006 5.006 0 0 0-7.071 0L9.879 7.051l1.414 1.414 2.828-2.829a3.007 3.007 0 0 1 4.243 0 3.005 3.005 0 0 1 0 4.243l-2.829 2.828 1.414 1.414 2.829-2.828z"></path><path d="m8.464 16.95-1.415-1.414 8.487-8.486 1.414 1.415z"></path></svg></div>
                            <div class="content text-center">
                                <div>
                                    @if ($ps->site && $ps->email)
                                        <a href="{{ $ps->site }}" target="_blank">{{ $ps->site }}</a>
                                        <a href="mailto:{{ $ps->email }}">{{ $ps->email }}</a>
                                    @elseif($ps->site)
                                        <a href="{{ $ps->site }}" target="_blank">{{ $ps->site }}</a>
                                    @else
                                        <a href="mailto:{{ $ps->email }}">{{ $ps->email }}</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                        @if ($ps->street)
                        <div class="col-xl-3 contact-info">
                            <div class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24"><path d="M12 2C7.589 2 4 5.589 4 9.995 3.971 16.44 11.696 21.784 12 22c0 0 8.029-5.56 8-12 0-4.411-3.589-8-8-8zm0 12c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4z"></path></svg></div>
                            <div class="content text-center">{!! $ps->street !!}</div>
                        </div>
                        @endif
                        @if ($ps->phone || $ps->fax)
                        <div class="col-xl-3 contact-info">
                            <div class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24"><path d="m20.487 17.14-4.065-3.696a1.001 1.001 0 0 0-1.391.043l-2.393 2.461c-.576-.11-1.734-.471-2.926-1.66-1.192-1.193-1.553-2.354-1.66-2.926l2.459-2.394a1 1 0 0 0 .043-1.391L6.859 3.513a1 1 0 0 0-1.391-.087l-2.17 1.861a1 1 0 0 0-.29.649c-.015.25-.301 6.172 4.291 10.766C11.305 20.707 16.323 21 17.705 21c.202 0 .326-.006.359-.008a.992.992 0 0 0 .648-.291l1.86-2.171a.997.997 0 0 0-.085-1.39z"></path></svg></div>
                            <div class="content text-center">
                                <div>
                                    @if ($ps->phone && $ps->fax)
                                        <a href="tel:{{ $ps->phone }}">{{ $ps->phone }}</a>
                                        <a href="tel:{{ $ps->fax }}">{{ $ps->fax }}</a>
                                    @elseif($ps->phone)
                                        <a href="tel:{{ $ps->phone }}">{{ $ps->phone }}</a>
                                    @else
                                        <a href="tel:{{ $ps->fax }}">{{ $ps->fax }}</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="row justify-content-center mt-4"><div class="col-lg-8 col-xl-6"><div class="map-area"><iframe src="{{ $ps->map_link }}" width="100%" height="400" frameborder="0" style="border:0;" allowfullscreen=""></iframe></div></div></div>
                </div>
            </div>
        </div>
    </section>
@endsection
