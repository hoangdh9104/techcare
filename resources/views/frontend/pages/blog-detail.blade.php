@extends('frontend.layouts.master')

@section('title')
    {{ $settings->site_name }} || Giới thiệu
@endsection

@section('content')
    <section id="wsus__breadcrumb">
        <div class="wsus_breadcrumb_overlay">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h4>Chi tiết blog</h4>
                        <ul>
                            <li><a href="#">blog</a></li>
                            <li><a href="#">Chi tiết blog</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================
                BREADCRUMB END
            ==============================-->


<!--============================
    BLOGS DETAILS START
==============================-->
<section id="wsus__blog_details">
    <div class="container">
        <div class="row">
            <div class="col-xxl-9 col-xl-8 col-lg-8">
                <div class="wsus__main_blog">
                    <div class="wsus__main_blog_img">
                        <img src="{{asset($blog->image)}}" alt="blog" class="img-fluid w-100">
                    </div>
                    <p class="wsus__main_blog_header">
                        <span><i class="fas fa-user-tie"></i> bởi {{$blog->user->name}}</span>
                        <span><i class="fal fa-calendar-alt"></i> {{date('M d Y' , strtotime($blog->created_at))}}</span>
                        {{-- <span><i class="fal fa-comment-alt-smile"></i> 0 Comment</span>
                        <span><i class="far fa-eye"></i> 11 Views</span> --}}
                    </p>
                    <div class="wsus__description_area">
                        <h1>{!!$blog->title!!}</h1>
                        {!!$blog->description!!}
                    </div>
                    <div class="wsus__share_blog">
                        <p>Chia sẻ:</p>
                        <ul>
                            <li><a class="facebook" href="https://www.facebook.com/sharer/sharer.php?u={{url()->current()}}
                                "><i class="fab fa-facebook-f"></i></a></li>
                            <li><a class="twitter" href="https://twitter.com/share?url={{url()->current()}}&text=
                                "><i class="fab fa-twitter"></i></a></li>
                            <li><a class="linkedin" href="https://www.linkedin.com/shareArticle?url={{url()->current()}}&title={{$blog->title}}&summary={{limitText($blog->description, 50)}}
                                "><i class="fab fa-linkedin-in"></i></a></li>
                        </ul>
                    </div>
                    <div class="wsus__related_post">
                        <div class="row">
                            <div class="col-xl-12">
                                <h5>Thêm bài đăng</h5>
                            </div>
                        </div>
                        <div class="row blog_det_slider">
                            @foreach ($moreBlogs as $blogItem)                            
                            <div class="col-xl-3">
                                <div class="wsus__single_blog wsus__single_blog_2">
                                    <a class="wsus__blog_img" href="#{{route('blog-details',$blogItem->slug)}}">
                                        <img src="{{asset($blogItem->image)}}" alt="blog" class="img-fluid w-100">
                                    </a>
                                    <div class="wsus__blog_text">
                                        <a class="blog_top blue" href="#">{{$blogItem->category->name}}</a>
                                        <div class="wsus__blog_text_center">
                                            <a href="{{route('blog-details', $blogItem->slug)}}">{!!limitText($blogItem->title, 45)!!}</a>
                                            <p class="date">{{date('M D Y', strtotime($blogItem->created_at))}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="wsus__comment_area">
                        <h4>Bình luận <span>{{count($comments)}}</span></h4>
                        @foreach ($comments as $comment)

                        <div class="wsus__main_comment">
                            <div class="wsus__comment_img">
                                <img style="width: 80px;height: 80px;object-fit: contain;" src="{{asset($comment->user->image)}}" alt="user" class="img-fluid w-100">
                            </div>
                            <div class="wsus__comment_text replay">
                                <h6>{{$comment->user->name}} <span>{{date('d M Y', strtotime($comment->created_at))}}</span></h6>
                                <p>{{$comment->comment}}</p>

                            </div>
                        </div>
                        @endforeach
                        @if (count($comments) === 0)
                         <i>Hãy là người đầu tiên bình luận! </i>
                        @endif

                        <div id="pagination">
                            <div class="mt-5">
                                @if ($comments->hasPages())
                                    {{$comments->withQueryString()->links()}}
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="wsus__post_comment">
                        <h4>Đăng 1 bình luận</h4>
                        @if (auth()->check())
                        <form action="{{route('blog-comment')}}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="wsus__single_com">
                                        <textarea rows="5" placeholder="Bình luận của bạn" name="comment" required></textarea>
                                        <input type="hidden" name="blog_id" value="{{$blog->id}}">
                                    </div>
                                </div>
                            </div>
                            <button class="common_btn" type="submit">Đăng bình luận</button>
                        </form>
                        @else
                        <p>Vui lòng đăng nhập để comment</p>
                        <a class="common_btn" href="{{route('login')}}" >Đăng nhập</a>
                        @endif
                    </div>
                </div>
            </div>
            
                <div class="col-xxl-3 col-xl-4 col-lg-4">
                <div class="wsus__blog_sidebar" id="sticky_sidebar">
                    {{-- <div class="wsus__blog_search">
                        <h4>Tìm kiếm</h4>
                        <form>
                            <input type="text" placeholder="Search">
                            <button type="submit" class="common_btn"><i class="far fa-search"></i></button>
                        </form>
                    </div> --}}
                    <div class="wsus__blog_category">
                        <h4>Thể loại</h4>
                        <ul>
                            @foreach ($blogCategories as $blogCategory)
                                <li><a href="{{ $blogCategory->id }}">{{ $blogCategory->name }}</a></li>
                            @endforeach

                        </ul>
                    </div>
                    <div class="wsus__blog_post">
                        <h4>Bài đăng phổ biến</h4>
                        @foreach ($latestBlogs as $item)
                            <div class="wsus__blog_post_single">
                                <a href="{{ route('blog-details', $item->slug) }}" class="wsus__blog_post_img">
                                    <img src="{{ asset($item->image) }}" alt="blog" class="imgofluid w-100">
                                </a>
                                <div class="wsus__blog_post_text">
                                    <a href="{{ route('blog-details', $item->slug) }}">{!! $item->title !!}</a>
                                    {{-- <p> <span>Ngày 29 tháng 2 năm 2025 </span> 2 Bình luận </p> --}}
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                </div>
            
            </div>
        </div>
    </section>
@endsection
