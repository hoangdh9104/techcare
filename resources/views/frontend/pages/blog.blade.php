    @extends('frontend.layouts.master')

    {{-- @section('title')
{{$setting->site_name}}
@endsection --}}

@section('content')
<section id="wsus__breadcrumb">
    <div class="wsus_breadcrumb_overlay">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h4>Bài viết mới nhất của chúng tôi</h4>
                    <ul>
                        <li><a href="#">Trang chủ</a></li>
                        <li><a href="#">Bài viết</a></li>
                    </ul>
                </div>
            </div>
        </section>
        <!--============================
        BREADCRUMB END
    ==============================-->

<!--============================
    BLOGS PAGE START
==============================-->
<section id="wsus__blogs">
    <div class="container">
        <div class="row">
            @foreach ($blogs as $blog)
            <div class="col-xl-3">
                <div class="wsus__single_blog wsus__single_blog_2">
                    <a class="wsus__blog_img" href="#{{route('blog-details',$blog->slug)}}">
                        <img src="{{asset($blog->image)}}" alt="blog" class="img-fluid w-100">
                    </a>
                    <div class="wsus__blog_text">
                        <a class="blog_top blue" href="#">{{$blog->category->name}}</a>
                        <div class="wsus__blog_text_center">
                            <a href="{{route('blog-details', $blog->slug)}}">{!!limitText($blog->title, 45)!!}</a>
                            <p class="date">{{date('M D Y', strtotime($blog->created_at))}}</p>
                        </div>
                    @endforeach


                </div>
                <div id="pagination">
                    <div class="mt-5">
                        @if ($blogs->hasPages())
                            {{ $blogs->withQueryString()->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </section>
        <!--============================
        BLOGS PAGE END
    ==============================-->
    @endsection
