@if ($ps->service == 1)
<section class="info-area">
    <div class="container">
        @if($services->isNotEmpty())
            @php $chunk = $services->chunk(4)->first(); @endphp
            <div class="row">
                <div class="col-lg-12 p-0">
                    <div class="info-big-box">
                        <div id="services-carousel">
                            @foreach ($chunk as $service)
                                <div class="item-slide d-flex align-items-center" data-aos="fade-in" data-aos-delay="{{ $loop->index }}00">
                                    <a target="_blank" href="{{ $service->link }}">
                                        <div class="info-box">
                                            <div class="icon">
                                                <img class="img-fluid-service" src="{{ asset('storage/images/services/' . $service->photo) }}" alt="{{ $service->title }}">
                                            </div>
                                            <div class="info">
                                                <h4 class="title">{{ $service->title }}</h4>
                                                <div class="details">
                                                    <h6 class="text">{!! $service->details !!}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    @if (!$loop->last)
                                    <div class="border-left d-block h-100"></div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
@endif
