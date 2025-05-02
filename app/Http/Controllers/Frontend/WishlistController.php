<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $wishlistProducts = Wishlist::with('product')->where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
        // dd($wishlistProducts);
        return view('frontend.pages.wishlist', compact('wishlistProducts'));
        // return view('frontend.pages.wishlist');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function addToWishlist(Request $request)
    {
        if(!Auth::check() ) {
            return response(['status' => 'error', 'message' => 'Hãy đăng nhập để thêm vào mục yêu thích!']); 
        } 
        $wishlistCount = Wishlist::where(['product_id' => $request->id, 'user_id' => Auth::user()->id])->count(); 
        if ($wishlistCount > 0) {
            return response(['status' => 'error', 'message' => 'Sản phẩm đã có trong danh sách yêu thích!']); 
        }                               
        $wishlist = new Wishlist(); 
        $wishlist -> user_id = Auth::user()->id;
        $wishlist -> product_id = $request->id;
        $wishlist -> save();  
        
        $count = Wishlist::where('user_id', Auth::user()->id)->count();
        
        return response(['status' => 'success', 'message' => 'Đã thêm vào mục yêu thích!', 'count' => $count]); 
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $wishlistProducts = Wishlist::where('id', $id)->firstOrFail();
        if($wishlistProducts->user_id != Auth::user()->id) {
            return redirect()->back();
        }
        $wishlistProducts -> delete();

        toastr('Sản phẩm đã xóa khỏi mục yêu thích!', 'success', 'success');
        return redirect()->back();
        // Delete the wishlist entry
        // DB::table('wishlists')->where('id', $id)->delete();

        // return response()->json(['message' => 'Product removed from wishlist successfully']);
    }
}
