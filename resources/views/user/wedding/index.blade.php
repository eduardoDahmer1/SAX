@extends('front.themes.' . env('THEME', 'theme-01') . '.layout')

@section('content')
    <section class="user-dashbord">
        <div class="container">
            <div class="row">
                @include('includes.user-dashboard-sidebar')
                <div class="col-8">
                    <h1 class="h1">{{__('Wedding List')}}</h1>
                    <div class="row mt-4">
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
                                            <button class="btn btn-dark add-to-cart-quick w-auto" data-href="{{route('product.cart.quickadd', $product->id)}}">
                                                {{__('Shop Now')}}
                                            </button>
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
    </section>
@endsection