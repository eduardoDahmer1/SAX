<!DOCTYPE html>
<html lang="{{ $current_locale }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="minimal-ui, width=device-width,initial-scale=1">
    <meta name="author" content="CrowTech - Desenvolvimento e tecnologia">
    <meta name="description" content="{{ $gs->title }}">
    <link rel="canonical" href="{{ route('admin-gs-maintenance') }}" />
    <meta name="language" content="{{ $current_locale }}" />
    <meta property="og:type" content="Website" />
    <meta property="og:title" content="{{ $gs->title }}" />
    <meta property="og:url" content="{{ route('front.index') }}" />
    <meta property="og:site_name" content="{{ $gs->title }}" />
    <meta property="og:image" content="{{ $gs->logoUrl }}" />
    <meta property="og:description" content="{{ $gs->title }}" />
    <title>{{ $gs->title }}</title>
    <link rel="icon" type="image/x-icon" href="{{ $gs->faviconUrl }}" />
    <link rel="stylesheet" href="{{ asset('assets/front/themes/shared/assets/css/crow.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/front/themes/shared/assets/css/all.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap"
        rel="stylesheet">
    @yield('styles')
    <style>
        .maintenance-lp {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 2rem;
        }
        .boxmaintenance-infos {
            max-width: 650px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0px 6px 20px rgba(0,0,0,0.2);
        }
        .boxmaintenance-infos.dark {
            background: rgba(0, 0, 0, 0.6);
        }
        .boxmaintenance-infos h2 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }
        .boxmaintenance-infos h5 {
            font-weight: 400;
            margin: 1.5rem 0;
        }
        .maintenance-image {
            max-width: 220px;
            margin: 1rem auto;
        }
        .list-socials {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin: 1.5rem 0;
            padding: 0;
            list-style: none;
        }
        .list-socials a {
            font-size: 1.3rem;
            color: inherit;
            transition: transform 0.2s ease, color 0.2s ease;
        }
        .list-socials a:hover {
            transform: scale(1.2);
            color: #0d6efd;
        }
        .extra-links {
            margin-top: 1.5rem;
        }
        .extra-links a {
            display: inline-block;
            margin: .5rem;
            padding: .75rem 1.5rem;
            border-radius: 30px;
            font-weight: 500;
            text-decoration: none;
            transition: all .2s ease;
        }
        .extra-links .btn-institucional {
            background: #0d6efd;
            color: #fff;
        }
        .extra-links .btn-institucional:hover {
            background: #0b5ed7;
        }
        .extra-links .btn-whatsapp {
            background: #25d366;
            color: #fff;
        }
        .extra-links .btn-whatsapp:hover {
            background: #1ebe57;
        }
        .copyright-maintenance {
            margin-top: 2rem;
            font-size: .9rem;
        }
    </style>
</head>
<body>
    <section class="maintenance-lp"
        style="background-image:url('{{ $gs->is_dark_mode ? asset('assets/front/themes/shared/assets/images/dark-bg-maintenance.jpeg') : asset('assets/front/themes/shared/assets/images/white-bg-maintenance.jpg') }}');">
        <div class="boxmaintenance-infos {{ $gs->is_dark_mode ? ' dark' : '' }}">
            <h2 class="animate__animated">🚧 Em manutenção</h2>
            <img class="animate__animated maintenance-image"
                style="animation-delay:.3s;" src="{{ $gs->is_dark_mode ? $gs->footerLogoUrl : $gs->logoUrl }}"
                alt="Logo">
            <h5 class="animate__animated" style="animation-delay:.5s;">
                Estamos aperfeiçoando nossa plataforma para você ter uma melhor experiência.  
                Enquanto isso, você pode acessar nosso institucional ou falar direto com nossa equipe:
            </h5>

            {{-- Links extras --}}
            <div class="extra-links">
                <a href="https://shop.saxdepartment.com/" target="_blank" class="btn-institucional">
                    🌐 Ver Institucional
                </a>
                <a href="https://api.whatsapp.com/send?phone={{ $gs->whatsapp_number }}&text=Ol%C3%A1!%20Tenho%20uma%20d%C3%BAvida..."
                   target="_blank" class="btn-whatsapp">
                    💬 Falar no WhatsApp
                </a>
            </div>

            {{-- Redes sociais --}}
            @php $social = App\Models\Socialsetting::find(1); @endphp
            <ul class="list-socials {{ $gs->is_dark_mode ? ' dark' : '' }}">
                <li><a target="_blank" href="{{ $social->facebook }}"><i class="fab fa-facebook-f animate__animated"></i></a></li>
                <li><a target="_blank" href="{{ $social->instagram }}"><i class="fab fa-instagram animate__animated"></i></a></li>
                <li><a target="_blank" href="{{ $social->linkedin }}"><i class="fab fa-linkedin-in animate__animated"></i></a></li>
            </ul>
        </div>
        <p class="copyright-maintenance animate__animated {{ $gs->is_dark_mode ? 'dark' : '' }}" style="animation-delay:.9s;">
            © {{ now()->year }} {{ $gs->title }}. Desenvolvido por <a class="link_agencia" href="">CrowTech</a>
        </p>
    </section>
    <script src="{{ asset('assets/front/themes/shared/assets/js/jquery.js') }}"></script>
    <script>
    $(document).ready(function() {
        $('.animate__animated').each(function() {
            $(this).addClass('animate__fadeInUp');
        })
    });
    </script>
</body>
</html>
