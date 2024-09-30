@if ($ps->slider == 1 && count($sliders))
    @include('includes.slider-style')

    <section class="main-slider">
        <ul class="list-unstyled social-network">
            @if ($socials->f_status == 1)
                <li class="facebook">
                    <a href="{{ $socials->facebook }}" class="icon-facebook">
                        <i class="fab fa-facebook"></i>
                    </a>
                </li>
            @endif
            @if ($socials->t_status == 1)
                <li>
                    <a href="{{ $socials->twitter }}" class="icon-twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                </li>
            @endif
            @if ($socials->i_status == 1)
                <li class="instagram">
                    <a href="{{ $socials->instagram }}">
                        <i class="fab fa-instagram"></i>
                    </a>
                </li>
            @endif
        </ul>

        <div id="main-slider">
            @foreach ($sliders as $data)
                <div class="slide">
                    <div class="container-fluid">
                        <div class="row justify-content-center">
                            <div class="img-holder">
                                <a href="{{ $data->link }}">
                                    <img loading="lazy" class='img-fluid' src="{{ asset('storage/images/sliders/' . $data->photo) }}" alt="image description">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endif
