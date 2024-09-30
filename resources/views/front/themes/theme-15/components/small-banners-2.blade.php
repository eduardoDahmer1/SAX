@if ($ps->bottom_small == 1)
<section class="banner-section position-top">
    <div class="container">
        @foreach ($bottom_small_banners->chunk(3) as $chunk)
            <div class="row justify-content-center">
                @foreach ($chunk as $img)
                    <div class="{{ $loop->first ? 'col-12 col-lg-4' : 'col-6 col-lg-4' }}" 
                         style="{{ $loop->first ? 'background-color: #red;' : '' }}" 
                         data-aos="fade-in" 
                         data-aos-delay="{{ $loop->index }}00">
                        <div class="left">
                            <a class="banner-effect shadow-banner" href="{{ $img->link }}" target="_blank">
                                <img loading="lazy" src="{{ asset('storage/images/banners/' . $img->photo) }}" alt="Banner Shop Sax">
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            @break
        @endforeach
    </div>
</section>
@endif
