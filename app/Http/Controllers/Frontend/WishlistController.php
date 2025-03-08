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
        $wishlistProducts = Wishlist::with('product')->where('user_id', Auth::id())->get();
        return view('frontend.pages.wishlist', compact('wishlistProducts'));
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
            return response(['status' => 'error', 'message' => 'login before add a product into wishlist']); 
        } 
        $wishlist = Wishlist::where(['product_id' => $request->id, 'user_id' => Auth::id()])->count(); 
        if ($wishlist > 0) {
            return response(['status' => 'error', 'message' => 'The product is already at wishlist!']); 
        }                               
        $wishlist = new Wishlist(); 
        $wishlist -> user_id = Auth::id();
        $wishlist -> product_id = $request->id;
        $wishlist -> save();                                                          
        
        return response(['status' => 'success', 'message' => 'Product added into the wishlist!']); 
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

        toastr('Product removed successfully', 'success', 'success');
        return redirect()->back();
        // Delete the wishlist entry
        // DB::table('wishlists')->where('id', $id)->delete();

        // return response()->json(['message' => 'Product removed from wishlist successfully']);
    }
}
