<footer class="footer" id="footer">
    <div class="container">
        <div class="row justify-content-between">
            {{-- Logo e descrição --}}
            <div class="col-12 col-xl-3 col-md-4 d-flex flex-column justify-content-start align-items-center">
                <div class="footer-info-area">
                    <div class="footer-logo">
                        <a href="{{ route('front.index') }}" class="logo-link">
                            <img loading="lazy" src="{{ $gs->footerLogoUrl }}" alt="{{ $gs->title }}">
                        </a>
                    </div>
                    <div class="text m-0">
                        <p>{!! $gs->footer !!}</p>
                    </div>
                </div>
            </div>

            {{-- Primeiros 5 links personalizados --}}
            <div class="col-xl-2 col-md-4">
                <div class="footer-widget info-link-widget">
                    <h4 class="title mb-1">{{ __('Important Links') }}</h4>
                    <ul class="link-list">
                        @foreach ($pfooter->take(5) as $data)
                            <li class="py-2"><a href="{{ route('front.page', $data->slug) }}">{{ $data->title }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- Links padrão + links personalizados extras --}}
            <div class="col-xl-2 col-md-4">
                <div class="footer-widget info-link-widget">
                    <h4 class="title mb-1">{{ __('Footer Links') }}</h4>
                    <ul class="link-list">
                        <li><a href="{{ route('front.index') }}">{{ __('Home') }}</a></li>
                        <li><a href="{{ route('front.contact') }}">{{ __('Contact Us') }}</a></li>
                        @if ($gs->crow_policy)<li><a href="{{ route('front.crowpolicy') }}">{{ __('General Terms of Service') }}</a></li>@endif
                        @if ($gs->privacy_policy)<li><a href="{{ route('front.privacypolicy') }}">{{ __('Privacy Policy') }}</a></li>@endif
                        @if ($gs->bank_check)<li><a href="{{ route('front.receipt') }}">{{ __('Upload Order Receipt') }}</a></li>@endif
                        @if ($gs->team_show_footer == 1)<li><a href="{{ route('front.team_member') }}">{{ __('Team') }}</a></li>@endif
                    </ul>
                    @if($pfooter->count() > 5)
                        <ul class="link-list mt-2">
                            @foreach ($pfooter->slice(5) as $data)
                                <li class="py-2"><a href="{{ route('front.page', $data->slug) }}">{{ $data->title }}</a></li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            {{-- Categorias em destaque --}}
            @if ($ps->featured_category == 1 && $categories->where('is_featured', 1)->isNotEmpty())
                <div class="col-xl-2 col-md-6 mb-3">
                    <div class="footer-widget info-link-widget">
                        <h4 class="title mb-1">{{ __('Departaments') }}</h4>
                        <ul class="link-list">
                            @foreach ($categories->where('is_featured', 1) as $cat)
                                <li><a href="{{ route('front.category', $cat->slug) }}">{{ $cat->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- Redes sociais e direitos --}}
            <div class="col-xl-2 col-md-6">
                <div>{!! $gs->copyright !!}</div>
                <div class="fotter-social-links mt-3">
                    <h4 class="title mb-2">{{ __('Social networks') }}</h4>
                    <ul class="d-flex">
                        @foreach (['facebook', 'instagram', 'twitter', 'linkedin', 'dribble', 'youtube'] as $platform)
                            @php $status = $socials->{$platform[0] . '_status'} ?? 0; @endphp
                            @if ($status == 1)
                                <li>
                                    <a href="{{ $socials->{$platform} }}" class="{{ $platform }}" target="_blank">
                                        <i class="fab fa-{{ $platform }}"></i>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Rodapé final --}}
    <div class="copy-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <small class="text-white d-block">
                        {{ $gs->title }} © {{ date('Y') }}.
                        {{ $gs->company_document ? '| ' . $gs->document_name . ' - ' . $gs->company_document . ' |' : '' }}
                        {{ __('All Rights Reserved') }}.
                    </small>
                    <small class="text-white d-block">
                        {{ __('Developed By') }} <a id="agcrow" href="https://kalivma.com.br">Kalivma Tecnologia</a>
                    </small>
                </div>
            </div>
        </div>
    </div>
</footer>
