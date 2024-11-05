<div class="row wish-list-area">
    @php
        if ($gs->switch_highlight_currency) {
            $highlight = $wishlist->firstCurrencyPrice();
            $small = $wishlist->showPrice();
        } else {
            $highlight = $wishlist->showPrice();
            $small = $wishlist->firstCurrencyPrice();
        }
    @endphp
    @foreach($wishlists as $wishlist)
        <div class="col-lg-6">
            <div class="single-wish">
                <span class="remove wishlist-remove" data-href="{{ route('user-wishlist-remove', App\Models\Wishlist::where('user_id','=',$user->id)->where('product_id','=',$wishlist->id)->first()->id) }}">
                    <i class="fas fa-times"></i>
                </span>
                <div class="left">
                    <img src="{{ $wishlist->photo ? asset('storage/images/products/'.$wishlist->photo) : asset('assets/images/noimage.png') }}" alt="">
                </div>
                <div class="right">
                    <h4 class="title">
                        <a href="{{ route('front.product', $wishlist->slug) }}">{{ $wishlist->name }}</a>
                    </h4>
                    @if($gs->is_rating == 1)
                        <ul class="stars">
                            <div class="ratings">
                                <div class="empty-stars"></div>
                                <div class="full-stars" style="width:{{ App\Models\Rating::ratings($wishlist->id) }}%"></div>
                            </div>
                        </ul>
                    @endif
                    @if (env('SHOW_PRICE', false))
                        <div class="price">{{ $highlight }}<small>{{ $small }}</small></div>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>
<div class="page-center category">
    {!! isset($sort) ? $wishlists->appends(['sort' => $sort])->links() : $wishlists->links() !!}
</div>
<script type="text/javascript">
    $("#sortby").on('change', function () {
        var sort = $("#sortby").val();
        window.location = "{{url('/user/wishlists')}}?sort=" + sort;
    });
</script>
