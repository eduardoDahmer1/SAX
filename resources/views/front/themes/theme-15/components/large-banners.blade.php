@if($ps->large_banner == 1)
<section class="banner-section" data-aos="fade-in" data-aos-delay="100">
    @if($large_banners->isNotEmpty())
        @php $img = $large_banners->first(); @endphp
        <div class="img">
            <a class="{{ env('THEME') == 'theme-15' ? '' : 'banner-effect' }} banner-w100" href="{{ $img->link }}">
                <figure>
                    <img src="{{ asset('storage/images/banners/'.$img->photo) }}" alt="Banner">
                </figure>
            </a>
        </div>
    @endif
</section>
@endif
