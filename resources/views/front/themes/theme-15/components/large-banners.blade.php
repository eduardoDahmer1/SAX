@if($ps->large_banner == 1)
<!-- Banner Area One Start -->
<section class="banner-section" data-aos="fade-in" data-aos-delay="100">
    @foreach($large_banners->chunk(1) as $chunk)
    @foreach($chunk as $img)
        <div class="img">
            <a class="{{ env('THEME') == " theme-15" ? "" : "banner-effect" }} banner-w100"
                href="{{ $img->link }}">
                <figure>
                    <img src="{{asset('storage/images/banners/'.$img->photo)}}" alt="Banner">
                </figure>
            </a>
        </div>
    @endforeach
    @break
    @endforeach
</section>
<!-- Banner Area One Start -->
@endif
