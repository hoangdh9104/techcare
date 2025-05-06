<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\ChildCategoryDataTable;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\HomePageSetting;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Str;

class ChildCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ChildCategoryDataTable $dataTable)
    {
        return $dataTable->render('admin.child-category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.child-category.create', compact('categories'));
    }

    /**
     * Get sub categories based on selected category.
     */
    public function getSubCategories(Request $request)
    {
        $subCategories = SubCategory::where('category_id', $request->id)
            ->where('status', 1)
            ->get();
        return $subCategories;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category'     => 'required',
            'sub_category' => 'required',
            'name'         => 'required|max:200|unique:child_categories,name',
            'status'       => 'required'
        ]);

        $childCategory = new ChildCategory();
        $childCategory->category_id     = $request->category;
        $childCategory->sub_category_id = $request->sub_category;
        $childCategory->name            = $request->name;
        $childCategory->slug            = Str::slug($request->name);
        $childCategory->status          = $request->status;
        $childCategory->save();

        toastr('Tạo danh mục con thành công!', 'success');
        return redirect()->route('admin.child-category.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Optionally implement if needed.
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $categories    = Category::all();
        $childCategory = ChildCategory::findOrFail($id);
        $subCategories = SubCategory::where('category_id', $childCategory->category_id)->get();
        return view('admin.child-category.edit', compact('categories', 'childCategory', 'subCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'category'     => 'required',
            'sub_category' => 'required',
            'name'         => 'required|max:200|unique:child_categories,name,' . $id,
            'status'       => 'required'
        ]);

        $childCategory = ChildCategory::findOrFail($id);
        $childCategory->category_id     = $request->category;
        $childCategory->sub_category_id = $request->sub_category;
        $childCategory->name            = $request->name;
        $childCategory->slug            = Str::slug($request->name);
        $childCategory->status          = $request->status;
        $childCategory->save();

        toastr('Updated Successfully!', 'success');
        return redirect()->route('admin.child-category.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $childCategory = ChildCategory::findOrFail($id);
        if(Product::where('child_category_id',$childCategory->id)->count()>0){
            return response(['status' => 'error', 'message' => 'Danh mục này có có chứa quan hệ bạn không thể xóa nó !']);
        }
        $homeSetting = HomePageSetting::all();
        foreach($homeSetting as $item){
            $array = json_decode($item->value,true);
            $collection = collect($array);
            if($collection->contains('child_category',$childCategory->id)){
            return response(['status' => 'error', 'message' => 'Danh mục này có có chứa quan hệ bạn không thể xóa nó !']);

            }
        }
        $childCategory->delete();

        return redirect()->route('admin.child-category.index')
            ->with('success', 'Xóa danh mục con thành công!');
    }


    /**
     * Change status of the specified resource.
     */
    public function changeStatus(Request $request)
    {
        $childCategory = ChildCategory::findOrFail($request->id);
        $childCategory->status = $request->status;
        $childCategory->save();

        return response(['message' => 'Trạng thái đã được cập nhật!']);
    }
}
