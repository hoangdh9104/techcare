<?php

namespace App\Http\Controllers\Frontend;

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
        $blog = Blog::with('comments')->where('slug', operator: $slug)->where('status', 1)->firstOrFail();
        $moreBlogs = Blog::where('slug', '!=', $slug)->where('status', 1)->orderBy('id', 'DESC')->take(15)->get();
        $comments = $blog->comments()->paginate(20);
        return view('frontend.pages.blog-detail', compact('blog', 'moreBlogs', 'comments'));
    }
    public function comment(Request $request)
    {
        // dd($request->all());
        $request->validate([

            'comment' => ['required', 'max:1000'],
            // 'blog_id' => ['required', 'exists:blogs,id']

        ]);

        $comment = new BlogComment();
        $comment->user_id = auth()->user()->id;
        $comment->blog_id = $request->blog_id;
        $comment->comment = $request->comment;
        $comment->save();
        toastr('Đã thêm bình luận !', 'success', 'success');

        return redirect()->back();
    }
}
