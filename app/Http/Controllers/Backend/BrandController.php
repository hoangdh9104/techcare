<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\BrandDataTable;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Product;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Str;

class BrandController extends Controller
{
    use ImageUploadTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(BrandDataTable $dataTable)
    {
        return $dataTable->render('admin.brand.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.brand.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'logo' => ['image', 'max:2000', 'required'],
            'name' => ['required', 'max:200'],
            'is_featured' => ['required'],
            'status' => ['required'],
        ]);
        $logoPath = $this->uploadImage($request, 'logo', 'uploads');
        $brand = new Brand();
        $brand->logo =  $logoPath;
        $brand->name =  $request->name;
        $brand->slug =  Str::slug($request->name);
        $brand->is_featured =  $request->is_featured;
        $brand->status =  $request->status;
        $brand->save();
        toastr('Đã tạo thương hiệu thành công!', 'Success');
        return redirect()->route('admin.brand.index');
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
        $brand = Brand::findOrFail($id);
        return view('admin.brand.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'logo' => ['image', 'max:2000'],
            'name' => ['required', 'max:200'],
            'is_featured' => ['required'],
            'status' => ['required'],
        ]);
        $brand = Brand::findOrFail($id);
        $logoPath = $this->updateImage($request, 'logo', 'uploads', $brand->logo);
        $brand->logo =  empty(!$logoPath) ? $logoPath : $brand->logo;
        $brand->name =  $request->name;
        $brand->slug =  Str::slug($request->name);
        $brand->is_featured =  $request->is_featured;
        $brand->status =  $request->status;
        $brand->save();
        toastr('Đã cập nhật thương hiệu thành công!', 'success');
        return redirect()->route('admin.brand.edit', $brand->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $brand = Brand::findOrFail($id);
        if(Product::where('brand_id',$brand->id)->count()>0){
        return response(['status' => 'Lỗi', 'message' => 'Danh mục này có sản phẩm bạn không thể xóa nó !']);
        }
        $this->deleteImage($brand->logo);
        $brand->delete();
        return response(['status' => 'Thành công', 'message' => 'Đã xóa thương hiệu thành công!']);
    }
    public function changeStatus(Request $request)
    {
        $brand = Brand::findOrFail($request->id);
        $brand->status = $request->status;
        $brand->save();
        return response(['message' => 'Trạng thái đã được cập nhật!']);
    }
}
