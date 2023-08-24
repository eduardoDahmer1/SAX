@extends('front.themes.' . env('THEME', 'theme-01') . '.layout')
@section('content')
<div class="container-fluid mt-2 mb-5">
    <div class="row px-5">
        <div class="col-12">
            <h2 class="h1 mb-3">
                {{__('Wedding List')}} {{__('of')}} {{$owner->name}}
            </h2>
            <div class="row border">
                @forelse ($products as $product)
                    <div class="col-12 py-3 px-2 aling-center border-top">
                        <div class="row">
                            <div class="col-md-2 col-4">
                                <img src="{{$product->image}}">
                            </div>
                            <div class="col-md-7 col-5">
                                <p class="m-0" style="font-weight: 500;font-size: 13px;">{{$product->brand->name}}</p>
                                <a class="d-inline name" href="{{route('front.product', $product->slug)}}">
                                    {{$product->name}}
                                </a>
                            </div>
                            <div class="col-3 d-flex flex-column justify-content-center">
                                <div class="row justify-content-center mb-3">
                                    @if (!$product->pivot->buyer)
                                        @auth
                                            @if ($owner->id != auth()->user()->id)
                                                <a class="btn btn-dark w-auto" href="{{route('user.wedding.buy', [$owner->id, $product->id])}}">
                                                    {{__('Shop Now')}}
                                                </a>
                                            @endif
                                        @else
                                            <button class="btn btn-dark w-auto" rel-toggle="tooltip"
                                                data-toggle="modal" data-target="#comment-log-reg">
                                                {{__('Shop Now')}}
                                            </button>
                                        @endauth
                                    @else
                                        <h3>{{__('Buyed by')}} {{$product->pivot->buyer->name}}</h3>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        {{__('No products added')}}
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
<script>
    function share() {
        let url = "{{ url()->current() }}"
        navigator.clipboard.writeText(url)
    }
</script>
@endsection