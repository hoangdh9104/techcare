<?php

namespace App\Http\Controllers\Backend;
use App\Models\Order;
use Carbon\Carbon;
use App\DataTables\ProductDataTable;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\ProductImageGallery;
use App\Models\ProductVariant;
use App\Models\SubCategory;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Str;

class ProductController extends Controller
{
    use ImageUploadTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(ProductDataTable $dataTable)
    {

        return $dataTable->render('admin.product.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        $subCategories = SubCategory::all();
        $childCategories = ChildCategory::all();
        return view('admin.product.create', compact('categories', 'brands', 'subCategories', 'childCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
{
    // Xác thực dữ liệu
    $request->validate([
        'image' => ['required', 'image', 'max:3000'],
        'name' => ['required', 'max:200'],
        'category' => ['required'],
        'brand' => ['required'],
        'warranty_code' => 'nullable|string|max:100|unique:products', // Kiểm tra mã bảo hành duy nhất
        'warranty_duration' => 'required|in:3,6,9,12', // Thêm xác thực cho thời gian bảo hành
        'price' => ['required'],
        'qty' => ['required'],
        'short_description' => ['required', 'max:600'],
        'long_description' => ['required'],
        'seo_title' => ['nullable', 'max:200'],
        'seo_description' => ['nullable', 'max:250'],
        'status' => ['required']
    ], [
        'image.required' => 'Vui lòng chọn hình ảnh.',
        'image.image' => 'Tệp tải lên phải là hình ảnh.',
        'image.max' => 'Kích thước hình ảnh không được vượt quá 3MB.',
        'name.required' => 'Vui lòng nhập tên sản phẩm.',
        'name.max' => 'Tên sản phẩm không được vượt quá 200 ký tự.',
        'category.required' => 'Vui lòng chọn danh mục sản phẩm.',
        'brand.required' => 'Vui lòng chọn thương hiệu.',
        'price.required' => 'Vui lòng nhập giá sản phẩm.',
        'qty.required' => 'Vui lòng nhập số lượng sản phẩm.',
        'short_description.required' => 'Vui lòng nhập mô tả ngắn.',
        'short_description.max' => 'Mô tả ngắn không được vượt quá 600 ký tự.',
        'long_description.required' => 'Vui lòng nhập mô tả chi tiết.',
        'warranty_duration.required' => 'Vui lòng chọn thời gian bảo hành.',
        'seo_title.max' => 'Tiêu đề SEO không được vượt quá 200 ký tự.',
        'seo_description.max' => 'Mô tả SEO không được vượt quá 250 ký tự.',
        'status.required' => 'Vui lòng chọn trạng thái hiển thị của sản phẩm.',
    ]);

    // Tải hình ảnh
    $imagePath = $this->uploadImage($request, 'image', 'uploads');

    // Tạo sản phẩm mới
    $product = new Product();
    $product->thumb_image = $imagePath;
    $product->name = $request->name;
    $product->slug = Str::slug($request->name);
    $product->vendor_id = Auth::user()->vendor->id;
    $product->category_id = $request->category;
    $product->sub_category_id = $request->sub_category;
    $product->child_category_id = $request->child_category;
    $product->brand_id = $request->brand;
    $product->qty = $request->qty;
    $product->short_description = $request->short_description;
    $product->long_description = $request->long_description;
    $product->video_link = $request->video_link;
    $product->sku = $request->sku;
    $product->price = $request->price;
    $product->offer_price = $request->offer_price;
    $product->offer_start_date = $request->offer_start_date;
    $product->offer_end_date = $request->offer_end_date;
    $product->product_type = $request->product_type;
    $product->status = $request->status;
    $product->warranty_code = $request->warranty_code;
    $product->warranty_duration = $request->warranty_duration;
    // $product->warranty_dwarranty_expiration_dateuration = $request->warranty_expiration_date;
    $product->warranty_expiration_date = $request->warranty_expiration_date;



    // Xác định ngày hết hạn bảo hành
    if ($request->warranty_code) {
        $warrantyDuration = $request->warranty_duration; // Lấy thời gian bảo hành từ request
        $product->warranty_expiration_date = Carbon::now()->addMonths($warrantyDuration);
    }

    // Sản phẩm khi được tạo bởi admin thì is_approved === true
    $product->is_approved = 1;
    $product->seo_title = $request->seo_title;
    $product->seo_description = $request->seo_description;

    // Lưu sản phẩm
    $product->save();

    toastr('Tạo sản phẩm thành công!', 'success');

    return redirect()->route('admin.products.index');
}
    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('admin.product.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::with('variants.productVariantItem')->findOrFail($id);
        if ($product->status == 0) {
            return redirect()->route('admin.products.index')->with('error', 'Sản phẩm này đã bị vô hiệu hóa và không thể chỉnh sửa.');
        }

        $subCategories = SubCategory::where('category_id', $product->category_id)->get();
        $childCategories = ChildCategory::where('sub_category_id', $product->sub_category_id)->get();
        $categories = Category::all();
        $brands = Brand::all();

        // Tính tổng tồn kho từ các biến thể
        $totalQty = 0;
        if ($product->variants && $product->variants->isNotEmpty()) {
            foreach ($product->variants as $variant) {
                foreach ($variant->productVariantItem as $item) {
                    $totalQty += $item->qty ?? 0;
                }
            }
        } else {
            $totalQty = $product->qty ?? 0;
        }

        return view('admin.product.edit', compact('product', 'categories', 'brands', 'subCategories', 'childCategories', 'totalQty'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // $product = Product::findOrFail($id);
        // $request->validate(
        //     [
        //         'image' => ['nullable', 'image', 'max:3000'],
        //         'name' => ['required', 'max:200'],
        //         'category' => ['required'],
        //         'brand' => ['required'],
        //         'price' => ['required'],

        //         'short_description' => ['required', 'max: 600'],
        //         'long_description' => ['required'],
        //         'seo_title' => ['nullable', 'max:200'],
        //         'seo_description' => ['nullable', 'max:250'],
        //         'status' => ['required']
        //     ],
        //     [
        //         'image.image' => 'Tệp tải lên phải là hình ảnh.',
        //         'image.max' => 'Kích thước hình ảnh không được vượt quá 3MB.',

        //         'name.required' => 'Vui lòng nhập tên sản phẩm.',
        //         'name.max' => 'Tên sản phẩm không được vượt quá 200 ký tự.',

        //         'category.required' => 'Vui lòng chọn danh mục sản phẩm.',
        //         'brand.required' => 'Vui lòng chọn thương hiệu.',

        //         'price.required' => 'Vui lòng nhập giá sản phẩm.',
        //         'qty.required' => 'Vui lòng nhập số lượng sản phẩm.',

        //         'short_description.required' => 'Vui lòng nhập mô tả ngắn.',
        //         'short_description.max' => 'Mô tả ngắn không được vượt quá 600 ký tự.',

        //         'long_description.required' => 'Vui lòng nhập mô tả chi tiết.',

        //         'seo_title.max' => 'Tiêu đề SEO không được vượt quá 200 ký tự.',
        //         'seo_description.max' => 'Mô tả SEO không được vượt quá 250 ký tự.',

        //         'status.required' => 'Vui lòng chọn trạng thái hiển thị của sản phẩm.',
        //     ]
        // );
        // // ✅ Nếu sản phẩm CHƯA có biến thể thì yêu cầu nhập qty
        // if ($product->variantCombinations->count() == 0) {
        //     $rules['qty'] = ['required', 'integer', 'min:0'];
        // }
        $product = Product::findOrFail($id);

        // Định nghĩa rules cơ bản
        $rules = [
            'image' => ['nullable', 'image', 'max:3000'],
            'name' => ['required', 'max:200'],
            'category' => ['required'],
            'brand' => ['required'],
            'warranty_code' => 'nullable|string|max:100|unique:products', // Kiểm tra mã bảo hành duy nhất
            'warranty_duration' => 'required|in:3,6,9,12', // Thêm xác thực cho thời gian bảo hành
            'price' => ['required'],
            'short_description' => ['required', 'max:600'],
            'long_description' => ['required'],
            'seo_title' => ['nullable', 'max:200'],
            'seo_description' => ['nullable', 'max:250'],
            'status' => ['required'],
        ];

        // ✅ Nếu sản phẩm CHƯA có biến thể thì yêu cầu nhập số lượng
        if ($product->variantCombinations->count() == 0) {
            $rules['qty'] = ['required', 'integer', 'min:0'];
        }

        // Thông báo lỗi
        $messages = [
            'image.image' => 'Tệp tải lên phải là hình ảnh.',
            'image.max' => 'Kích thước hình ảnh không được vượt quá 3MB.',

            'name.required' => 'Vui lòng nhập tên sản phẩm.',
            'name.max' => 'Tên sản phẩm không được vượt quá 200 ký tự.',

            'category.required' => 'Vui lòng chọn danh mục sản phẩm.',
            'brand.required' => 'Vui lòng chọn thương hiệu.',

            'price.required' => 'Vui lòng nhập giá sản phẩm.',
            'qty.required' => 'Vui lòng nhập số lượng sản phẩm.',
            'qty.integer' => 'Số lượng phải là số nguyên.',
            'qty.min' => 'Số lượng không được nhỏ hơn 0.',

            'short_description.required' => 'Vui lòng nhập mô tả ngắn.',
            'short_description.max' => 'Mô tả ngắn không được vượt quá 600 ký tự.',

            'long_description.required' => 'Vui lòng nhập mô tả chi tiết.',
            'warranty_duration.required' => 'Vui lòng chọn thời gian bảo hành.',
            'seo_title.max' => 'Tiêu đề SEO không được vượt quá 200 ký tự.',
            'seo_description.max' => 'Mô tả SEO không được vượt quá 250 ký tự.',

            'status.required' => 'Vui lòng chọn trạng thái hiển thị của sản phẩm.',
        ];

        //  Thực hiện validate cuối cùng
        $validatedData = $request->validate($rules, $messages);
        // $product = Product::findOrFail($id);
        $imagePath = $this->updateImage($request, 'image', 'uploads', $product->thumb_image);
        $product->thumb_image = empty(!$imagePath) ? $imagePath : $product->thumb_image;
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->vendor_id = Auth::user()->vendor->id;
        $product->category_id = $request->category;
        $product->sub_category_id = $request->sub_category;
        $product->child_category_id = $request->child_category;
        $product->brand_id = $request->brand;
        if (isset($validatedData['qty'])) {
            $product->qty = $validatedData['qty'];
            $product->save();
        }
        $product->short_description = $request->short_description;
        $product->long_description = $request->long_description;
        $product->video_link = $request->video_link;
        $product->sku = $request->sku;
        $product->price = $request->price;
        $product->offer_price = $request->offer_price;
        $product->offer_start_date = $request->offer_start_date;
        $product->offer_end_date = $request->offer_end_date;
        $product->product_type = $request->product_type;
        $product->status = $request->status;
        $product->warranty_code = $request->warranty_code;
        $product->warranty_duration = $request->warranty_duration;
        $product->warranty_expiration_date = $request->warranty_expiration_date;
         // Xác định ngày hết hạn bảo hành
    if ($request->warranty_code) {
        $warrantyDuration = $request->warranty_duration; // Lấy thời gian bảo hành từ request
        $product->warranty_expiration_date = Carbon::now()->addMonths($warrantyDuration);
    }
        // sản phẩm khi đc tạo bởi admin thì is_approved === true
        $product->is_approved = 1;
        $product->seo_title = $request->seo_title;
        $product->seo_description = $request->seo_description;
        $product->save();

        toastr('Đã cập nhật sản phẩm!', 'success');

        return redirect()->route('admin.products.edit', $product->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        if (OrderProduct::where('product_id', $product->id)->count() > 0) {
            return response(['status' => 'error', 'message' => 'Danh mục này có sản phẩm đặt hàng bạn không thể xóa nó !']);
        }
        $this->deleteImage($product->thumb_image);

        // delete product gallery images
        $galleryImages = ProductImageGallery::where('product_id', $product->id)->get();
        foreach ($galleryImages as $galleryImage) {
            $this->deleteImage($galleryImage->image);
            $galleryImage->delete();
        }
        // delete product variant if exist
        $variants = ProductVariant::where('product_id', $product->id)->get();
        foreach ($variants as $variant) {
            $variant->productVariantItem()->delete();
            $variant->delete();
        }
        $product->delete();
        return response(['status' => 'success', 'message' => 'Xóa sản phẩm thành công!']);
    }
    // get all product sub categories
    public function getSubCategories(Request $request)
    {
        $subCategories = SubCategory::where('category_id', $request->id)->get();
        return $subCategories;
    }
    public function getChildCategories(Request $request)
    {
        $childCategories = ChildCategory::where('sub_category_id', $request->id)->get();
        return $childCategories;
    }
    public function changeStatus(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->status = $request->status;
        $product->save();
        return response(['message' => 'Trạng thái đã được cập nhật!']);
    }
    // public function changeStatus(Request $request)
    // {
    //     $product = Product::findOrFail($request->id);
    //     $product->update(['status' => $request->status]);

    //     return response()->json(['message' => 'Đã cập nhật trạng thái']);
    // }
}
