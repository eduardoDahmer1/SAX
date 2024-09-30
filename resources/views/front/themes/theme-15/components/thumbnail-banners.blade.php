<section class="banner-section" data-aos="fade-in" data-aos-delay="100">
    <div class="container">
        <div class="row">
            @foreach($thumbnail_banners->take(1) as $img)
                <div class="col-lg-12">
                    <div class="img">
                        <a class="{{ env('THEME') == 'theme-15' ? '' : 'banner-effect' }} banner-w100" href="{{ $img->link }}">
                            <figure>
                                <img src="{{ asset('storage/images/banners/' . $img->photo) }}" alt="Banner">
                            </figure>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
