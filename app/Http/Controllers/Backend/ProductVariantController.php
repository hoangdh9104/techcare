<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\ProductVariantDataTable;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductVariantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, ProductVariantDataTable $dataTable)
    {
        $product = Product::findOrFail($request->product);
        return $dataTable->render('admin.product.product-variant.index', compact(('product')));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.product.product-variant.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product' => ['integer', 'required'],
            'name' => [
                'required',
                'max:200',
                Rule::unique('product_variants', 'name')
                    ->where(function ($query) use ($request) {
                        return $query->where('product_id', $request->product);
                    }),
            ],
            'status' => ['required']
        ]);

        $varinat = new ProductVariant();
        $varinat->product_id = $request->product;
        $varinat->name = $request->name;
        $varinat->status = $request->status;
        $varinat->save();

        toastr('Created Successfully!', 'success', 'success');

        return redirect()->route('admin.products-variant.index', ['product' => $request->product]);
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
        $variant = ProductVariant::findOrFail($id);
        return view('admin.product.product-variant.edit', compact('variant'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', 'max:200', Rule::unique('product_variants', 'name')->ignore($id)], // Bỏ qua ID hiện tại khi update
            'status' => ['required']
        ]);

        $variant = ProductVariant::findOrFail($id);
        $variant->name = $request->name;
        $variant->status = $request->status;
        $variant->save();

        toastr('Updated Successfully!', 'success', 'success');

        return redirect()->route('admin.products-variant.edit', ['products_variant' => $variant->id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $variant = ProductVariant::findOrFail($id);
        $variantItemcheck = ProductVariantItem::where('product_variant_id', $variant->id)->count();
        if ($variantItemcheck > 0) {
            return response(['status' => 'error', 'message' => 'This variant containt variant in it delete
            the variant item first for delete this variant!']);
        }
        $variant->delete();

        return response(['status' => 'success', 'message' => 'Deleted Successfully!']);
    }
    public function changeStatus(Request $request)
    {
        $varinat = ProductVariant::findOrFail($request->id);
        $varinat->status = $request->status;
        $varinat->save();

        return response(['message' => 'Status has been updated!']);
    }
}
