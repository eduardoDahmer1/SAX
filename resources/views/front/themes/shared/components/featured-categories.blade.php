@if ($ps->featured_category == 1)
    @php
        $categoryhasimage = $categories->where('is_featured', 1)->contains(function($cat) {
            return !empty($cat->image);
        });
    @endphp
    <section class="slider-buttom-category categorias-destaq">
        <div class="container">
            <div class="row">
                @if ($categoryhasimage)
                    <div class="col-md-6">
                        <div class="box-center-text">
                            <h3 class="titulo-categorias">{{ __('Visit our categories') }}</h3>
                            <p class="desc-categorias">{{ __('Buy by department by clicking on the category and discover all our products in this segment.') }}</p>
                        </div>
                    </div>
                @endif
                @foreach ($categories->where('is_featured', 1) as $cat)
                    <div class="col-6 col-md-3">
                        <a href="{{ route('front.category', $cat->slug) }}" class="single-category">
                            <div class="box-center-text infos-internas">
                                <h5 class="title">{{ $cat->name }}</h5>
                                <p class="count">{{ count($cat->products) }} {{ __('Item(s)') }}</p>
                            </div>
                            <img class="img-fluid" src="{{ asset('storage/images/categories/' . $cat->image) }}" alt="">
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
