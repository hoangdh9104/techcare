<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\CategoryDataTable;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CategoryDataTable $dataTable)
    {
        return $dataTable->render('admin.category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'icon' => ['required', 'not_in:empty'],
            'name' => ['required', 'max:200', 'unique:categories,name'],
            'status' => ['required']
        ]);

        $category = new Category();

        $category->icon = $request->icon;
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->status = $request->status;
        $category->save();

        toastr('Created Category Successfully', 'success');

        return redirect()->route('admin.category.index');
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
        $category = Category::findOrFail(($id));
        return view('admin.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Kiểm tra xem category có tồn tại không
        $category = Category::find($id);
        if (!$category) {
            return redirect()->route('admin.category.index')->withErrors('Category not found');
        }

        // Validation
        $request->validate([
            'icon' => ['required', 'not_in:empty'],
            'name' => ['required', 'max:200', 'unique:categories,name,'.$id.',id'], // Sửa lỗi unique
            'status' => ['required', 'in:0,1'] // Đảm bảo giá trị status hợp lệ
        ]);

        // Cập nhật thông tin
        $category->icon = $request->icon;
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->status = $request->status;
        $category->save();

        toastr('Updated Successfully!', 'success');

        return redirect()->route('admin.category.index');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
       $category = Category::findOrFail($id);
       $category->delete();

       return response(['status'=> 'success'], 'Deleted Duccessfully!');
    }

    public function changeStatus(Request $request)
    {
        $category = Category::findOrFail($request->id);
        $category->status = $request->status == 'true' ? 1 : 0;
        $category->save();

        return response(['message' => 'Status has been updated!']);
    }

}
