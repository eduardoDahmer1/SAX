@if ($ps->slider == 1 && $sliders->isNotEmpty())
    @include('includes.slider-style')

    <section class="main-slider">
        {{-- Redes sociais --}}
        <ul class="list-unstyled social-network">
            @foreach (['facebook' => 'f_status', 'twitter' => 't_status', 'instagram' => 'i_status'] as $platform => $status)
                @if ($socials->{$status} ?? 0)
                    <li class="{{ $platform }}">
                        <a href="{{ $socials->{$platform} }}" class="icon-{{ $platform }}" target="_blank">
                            <i class="fab fa-{{ $platform }}"></i>
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>

        {{-- Sliders --}}
        <div id="main-slider">
            @foreach ($sliders as $data)
                <div class="slide">
                    <div class="container-fluid">
                        <div class="row justify-content-center">
                            <div class="img-holder">
                                <a href="{{ $data->link }}">
                                    <img loading="lazy"
                                         class="img-fluid"
                                         src="{{ asset('storage/images/sliders/' . $data->photo) }}"
                                         alt="Banner {{ $loop->iteration }}"
                                         onerror="this.onerror=null;this.src='{{ asset('assets/images/noimage.png') }}';">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endif
