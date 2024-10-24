@extends('front.themes.' . env('THEME', 'theme-15') . '.layout')
@section('content')
<div class="breadcrumb-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <ul class="pages">
                    <li><a href="{{ route('front.index') }}">{{ __("Home") }}</a></li>
                    <li><a href="{{ route('front.blog') }}">{{ __("Blog") }}</a></li>

                    @if(isset($bcat))
                        <li><a href="{{ route('front.blogcategory', $bcat->slug) }}">{{ $bcat->name }}</a></li>
                    @elseif(isset($slug))
                        <li><a href="{{ route('front.blogtags', $slug) }}">{{ __("Tag") }}: {{ $slug }}</a></li>
                    @elseif(isset($search))
                        <li><a href="javascript:;">{{ __("Search") }}: {{ $search }}</a></li>
                    @elseif(isset($date))
                        <li><a href="javascript:;">{{ __("Archive") }}: {{ date('F Y', strtotime($date)) }}</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
<section class="blogpagearea">
    <div class="container">
        <div id="ajaxContent">
            <div class="row">
                @foreach($blogs as $blogg)
                <div class="col-md-6 col-lg-4">
                    <div class="blog-box">
                        <div class="blog-images">
                            <div class="img">
                                <img src="{{ $blogg->photo ? asset('storage/images/blogs/'.$blogg->photo) : asset('assets/images/noimage.png') }}"
                                     class="img-fluid" alt="">
                                <div class="date d-flex justify-content-center">
                                    <div class="box align-self-center">
                                        <p>{{date('d', strtotime($blogg->created_at))}}</p>
                                        <p>{{date('M', strtotime($blogg->created_at))}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="details">
                            <a href="{{route('front.blogshow',$blogg->id)}}">
                                <h4 class="blog-title">
                                    {{ mb_strlen($blogg->title, 'utf-8') > 50 ? mb_substr($blogg->title, 0, 50, 'utf-8') . "..." : $blogg->title }}
                                </h4>
                            </a>
                            <p class="blog-text">{{ \Illuminate\Support\Str::limit(strip_tags($blogg->details), 120) }}</p>
                            <a class="read-more-btn" href="{{ route('front.blogshow', $blogg->id) }}">{{ __("Read More") }}</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="page-center">
                {!! $blogs->links() !!}
            </div>
        </div>
    </div>
</section>
@endsection
@section('scripts')
<script type="text/javascript">
    $(document).on('click', '.pagination li', function (event) {
        event.preventDefault();
        const link = $(this).find('a').attr('href');
        if (link && link !== '#') {
            $('#preloader').show();
            $('#ajaxContent').load(link, function (response, status) {
                if (status === "success") {
                    $("html, body").animate({ scrollTop: 0 }, 1);
                    $('#preloader').fadeOut();
                }
            });
        }
    });
</script>
@endsection
