@extends('front.themes.' . env('THEME', 'theme-15') . '.layout')
@section('content')
<div class="breadcrumb-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <ul class="pages">
                    <li><a href="{{ route('front.index') }}">{{ __('Home') }}</a></li>
                    <li><a href="{{ route('front.team_member') }}">{{ __('Blog') }}</a></li>
                    @if(isset($scat)) 
                        <li><a href="{{ route('front.team_membercategory', $scat->slug) }}">{{ $scat->name }}</a></li>
                    @elseif(isset($slug)) 
                        <li><a href="{{ route('front.team_membertags', $slug) }}">{{ __('Tag') }}: {{ $slug }}</a></li>
                    @elseif(isset($search)) 
                        <li><a href="javascript:;">{{ __('Search') }}</a></li>
                        <li><a href="javascript:;">{{ $search }}</a></li>
                    @elseif(isset($date)) 
                        <li><a href="javascript:;">{{ __('Archive') }}: {{ date('F Y', strtotime($date)) }}</a></li>
                    @else 
                        <li><a href="javascript:;">{{ __('Team') }}</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
<section class="blogpagearea">
    <div class="container">
        <div id="ajaxContent">@include('front.pagination.team_member')</div>
    </div>
</section>
@endsection
@section('scripts')
<script>
    $(document).on('click', '.pagination li', function (e) {
        e.preventDefault();
        const link = $(this).find('a').attr('href');
        if (link && link !== '#') {
            $('#preloader').show();
            $('#ajaxContent').load(link, function (response, status) {
                if (status === "success") {
                    $("html,body").animate({ scrollTop: 0 }, 1);
                    $('#preloader').fadeOut();
                }
            });
        }
    });
</script>
@endsection
