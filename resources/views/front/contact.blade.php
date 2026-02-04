@extends('front.themes.' . env('THEME', 'theme-15') . '.layout')

@section('content')
<style>
    /* Estilos Gerais */
    .contact-us { padding: 60px 0; background-color: #fff; font-family: 'Poppins', sans-serif; }
    .contact-us h3 { font-weight: 300; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 20px; }
    .contact-us .lead { font-size: 16px; color: #666; margin-bottom: 50px; }
    .contact-us .contact-form .submit-btn{
            text-align: center;
    justify-content: center;
    align-items: center;
    display: flex;
    }

    /* Inputs e Formulário */
    .form-input { position: relative; margin-bottom: 20px; }
    .form-input input, .form-input textarea { 
        width: 100%; padding: 12px 15px; border: 1px solid #000; 
        border-radius: 0; outline: none; transition: all 0.3s; 
    }
    .form-input textarea { height: 165px; resize: none; }

    /* Captcha e Botão */
    .captcha-area { display: flex; align-items: center; gap: 10px; list-style: none; padding: 0; margin-bottom: 20px; }
    .captcha-area img { max-height: 45px; border: 1px solid #eee; }
    .refresh_code { cursor: pointer; margin-left: 10px; }
    .submit-btn { 
        background: #111; color: #fff; padding: 15px 40px; border: none; 
        text-transform: uppercase; font-weight: bold; width: 100%; cursor: pointer; 
    }
    .submit-btn:hover { background: #333; }

    /* Cards de Info */
    .contact-info-card { 
        background: #fff; padding: 30px 20px; text-align: center; border: 1px solid #eee; 
        box-shadow: 0 10px 30px rgba(0,0,0,0.05); height: 100%; transition: transform 0.3s; 
    }
    .contact-info-card:hover { transform: translateY(-5px); }
    .contact-info-card a { display: block; color: #333; text-decoration: none; margin-bottom: 5px; word-break: break-all; }

    /* Seção de Mapas */
    .map-container { margin-top: 50px; }
    .map-title { 
        font-size: 14px; font-weight: 700; text-transform: uppercase; 
        margin-bottom: 15px; border-bottom: 2px solid #000; display: inline-block; padding-bottom: 5px; 
    }
    .map-wrapper { 
        background: #f9f9f9; padding: 10px; border: 1px solid #eee; margin-bottom: 20px;
    }
    .map-wrapper iframe { width: 100%; height: 300px; border: 0; filter: grayscale(100%); transition: 0.5s; }
    .map-wrapper iframe:hover { filter: grayscale(0%); }

    /* Mobile adjustments */
    @media (max-width: 768px) {
        .contact-us .text-center.w-75 { width: 100% !important; }
        .map-wrapper iframe { height: 250px; }
    }
</style>

<div class="breadcrumb-area">
    <div class="container">
        <ul class="pages">
            <li><a href="{{ route('front.index') }}">{{ __('Home') }}</a></li>
            <li><a href="{{ route('front.contact') }}">{{ __('Contact Us') }}</a></li>
        </ul>
    </div>
</div>

<section class="contact-us">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center m-auto w-75">
                <h3>Envía tu mensaje y te responderemos a la brevedad.</h3>
                <p class='lead'>Bienvenido al mundo de SAX. La tienda de artículos de lujo más grande de Sudamérica.</p>
            </div>
        </div>

        <div class="contact-form">
            <div class="gocover" style="background: url({{ asset('storage/images/' . $gs->loader) }}) no-repeat center rgba(45, 45, 45, 0.5);"></div>
            <form id="contactform" action="{{ route('front.contact.submit') }}" method="POST">
                {{ csrf_field() }}
                @include('includes.admin.form-both')
                <div class="row justify-content-center">
                    <div class="col-md-5">
                        <div class="form-input"><input type="text" name="name" placeholder="{{ __('Name') }} *" required></div>
                        <div class="form-input"><input type="text" name="phone" placeholder="{{ __('Phone Number') }} *"></div>
                        <div class="form-input"><input type="email" name="email" placeholder="{{ __('Email Address') }} *" required></div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-input"><textarea name="text" placeholder="{{ __('Your Message') }} *" required></textarea></div>
                    </div>
                </div>
                <div class="row justify-content-center mt-3">
                    <div class="col-md-5">
                        @if ($gs->is_capcha == 1)
                            <div class="captcha-area">
                                <img class="codeimg1" src="{{ asset('storage/images/capcha_code.png') }}" alt="">
                                <i class="fas fa-sync-alt refresh_code"></i>
                                <input name="codes" type="text" class="form-control" placeholder="{{ __('Code') }}" required style="max-width: 120px; border-radius: 0;">
                            </div>
                        @endif
                    </div>
                    <div class="col-md-5">
                        <input type="hidden" name="to" value="{{ $ps->contact_email }}">
                        <button class="submit-btn" type="submit">{{ __('Send Message') }}</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="row mt-5 pt-4 g-4 justify-content-center">
            @if ($ps->site || $ps->email)
            <div class="col-lg-4 col-md-6">
                <div class="contact-info-card">
                    <div class="icon"><i class="fas fa-link fa-2x"></i></div>
                    <div class="content">
                        @if($ps->site)<a href="{{ $ps->site }}" target="_blank">{{ str_replace(['http://', 'https://'], '', $ps->site) }}</a>@endif
                        @if($ps->email)<a href="mailto:{{ $ps->email }}">{{ $ps->email }}</a>@endif
                    </div>
                </div>
            </div>
            @endif
            @if ($ps->street)
            <div class="col-lg-4 col-md-6">
                <div class="contact-info-card">
                    <div class="icon"><i class="fas fa-map-marker-alt fa-2x"></i></div>
                    <div class="content">{!! $ps->street !!}</div>
                </div>
            </div>
            @endif
            @if ($ps->phone)
            <div class="col-lg-4 col-md-6">
                <div class="contact-info-card">
                    <div class="icon"><i class="fas fa-phone fa-2x"></i></div>
                    <div class="content"><a href="tel:{{ $ps->phone }}">{{ $ps->phone }}</a></div>
                </div>
            </div>
            @endif
        </div>

        <div class="map-container">
            <div class="row text-center">
                <div class="col-lg-4 col-md-12">
                    <h4 class="map-title">Pedro Juan Caballero</h4>
                    <div class="map-wrapper">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d58954.282839244144!2d-55.76757534513292!3d-22.555054301589653!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94626f0079a38969%3A0xc5b346bd463b3b48!2sSAX%20Department%20Store%20-%20Pedro%20Juan%20Caballero!5e0!3m2!1spt-BR!2spy!4v1770210046629!5m2!1spt-BR!2spy" loading="lazy"></iframe>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12">
                    <h4 class="map-title">Asunción</h4>
                    <div class="map-wrapper">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d28864.905476014617!2d-57.634103725683595!3d-25.266777699999995!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x945da76681b0d661%3A0x2e9754f73b54e3a5!2sSAX%20Department%20Store%20-%20Asunci%C3%B3n!5e0!3m2!1spt-BR!2spy!4v1770210083150!5m2!1spt-BR!2spy" loading="lazy"></iframe>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12">
                    <h4 class="map-title">Ciudad del Este</h4>
                    <div class="map-wrapper">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1865759.3980800086!2d-57.434554686265194!3d-24.028902543292244!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94f69aaaec5ef03d%3A0xff12a8b090a63ebd!2sSAX%20Department%20Store!5e0!3m2!1spt-BR!2spy!4v1770210106773!5m2!1spt-BR!2spy" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection