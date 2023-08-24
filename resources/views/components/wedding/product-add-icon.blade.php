@props(['id'])

<li {{ $attributes->merge(['class']) }}>
    @wedding
        @if (Auth::guard('web')->check())
            <span class="add-to-wedding"
                data-href="{{ route('user.wedding.store', $id) }}"
                data-toggle="tooltip" data-placement="right"
                title="{{ __('Add To Wedding List') }}" data-placement="right">
                <i class="fas fa-gift" style="font-size: 1.3rem"></i>
            </span>
        @else
            <span rel-toggle="tooltip" title="{{ __('Add To Wedding List') }}"
                data-toggle="modal" id="wish-btn" data-target="#comment-log-reg"
                data-placement="right">
                <i class="fas fa-gift" style="font-size: 1.3rem"></i>
            </span>
        @endif
    @endwedding
</li>