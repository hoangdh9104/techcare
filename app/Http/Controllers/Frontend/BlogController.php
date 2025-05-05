<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Backend\BlogCategory;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogComment;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function blog()
    {
        $blogs = Blog::where('status', 1)->orderBy('id', 'DESC')->paginate(12);
        return view('frontend.pages.blog', compact('blogs'));
    }
    public function blogDetails(string $slug)
    {
        $blog = Blog::with('comments')->where('slug', $slug)->where('status', 1)->firstOrFail();
        $moreBlogs = Blog::where('slug', '!=', $slug)->where('status', 1)->orderBy('id', 'DESC')->take(15)->get();
        $comments = $blog->comments()->paginate(20);
        $currentCategoryId = $blog->category ? $blog->category->id : 0;
        $blogCategories = BlogCategory::where('id', '!=', $currentCategoryId)->get();
        
        // Lấy 5 bài viết mới nhất (bao gồm cả bài hiện tại nếu có)
        $latestBlogs = Blog::where('status', 1)->orderBy('id', 'DESC')->take(5)->get();
    
        return view('frontend.pages.blog-detail', compact('blog', 'moreBlogs', 'comments', 'blogCategories', 'latestBlogs'));
    }
    public function comment(Request $request)
    {
        $request->validate([
            'comment' => ['required', 'max:1000']
        ]);

        $comment = new BlogComment();
        $comment->user_id = auth()->user()->id;
        $comment->blog_id = $request->blog_id;
        $comment->comment = $request->comment;
        $comment->save();
        toastr('Bình luận thành cống', 'success', 'success');

        return redirect()->back();
    }
}
