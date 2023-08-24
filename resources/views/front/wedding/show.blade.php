@extends('front.themes.' . env('THEME', 'theme-01') . '.layout')
@section('content')
<div class="container-fluid mt-2 mb-5">
    <div class="row px-5">
        <div class="col-12">
            <div class="row w-100 justify-content-between mx-1">
                <div>
                    <h2 class="h1 mb-3">
                        {{__('Wedding List')}} {{__('of')}} {{$owner->name}}
                    </h2>
                    <p class="text-muted mb-1">{{__('Email')}}: {{$owner->email}}</p>
                    @if ($owner->phone)
                        <p class="text-muted">{{__('Phone')}}: {{$owner->phone}}</p>
                    @endif
                </div>
                @auth
                    @if ($owner->id === auth()->user()->id)
                        <div>
                            <div class="d-flex">
                                @if ($owner->is_wedding)    
                                    <div id="share" class="mr-3 cursor-pointer" onclick="share()" style="margin-top: 4px;">
                                        <i class="fas fa-share-alt"></i>
                                        {{__('Share')}}
                                    </div>
                                @endif
                                <div>
                                    <input @checked($owner->is_wedding) class="styled-checkbox" id="checkboxPrivacy" type="checkbox" name="privacy">
                                    <label for="checkboxPrivacy">{{ __('Public Wedding List') }}?</label>
                                </div>
                            </div>
                        </div>
                    @endif
                @endauth
            </div>
            <div class="row border mx-1">
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
                                            @else
                                                <x-wedding.product-add-icon 
                                                    class="btn w-auto deleteProdWedd cursor-pointer" 
                                                    icon="trash" :id="$product->id" title="Delete" />
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

    $('.deleteProdWedd').on('click', function(ele) {
        $( this ).parent().parent().parent().parent().remove();
    });

    $("#checkboxPrivacy").on('click', function() {
        var url = "{{route('user.wedding.privacy')}}"
        
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            type: 'POST',
            url: url,
            success: () => window.location.reload()
        });
    });
</script>
@endsection