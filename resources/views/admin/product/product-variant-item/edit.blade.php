@extends('admin.layouts.master')

@section('content')
      <!-- Main Content -->
        <section class="section">
          <div class="section-header">
            <h1>Biến thể sản phẩm</h1>
          </div>
          <div class="mb-3">
            <a class="btn btn-primary"  href="{{route(
                'admin.products-variant-item.index',
                ['productId' => $variantItem->productVariant->product_id, 'variantId' => $variantItem->product_variant_id]
                )}}"> Quay lại
                </a>
          </div>
          <div class="section-body">

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Cập nhật sản phẩm biến thể</h4>

                  </div>
                  <div class="card-body">
                    <form action="{{route('admin.products-variant-item.update', $variantItem->id)}}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label>Tên biến thể</label>
                            <input type="text" class="form-control" name="variant_name" value="{{$variantItem->productVariant->name}}" readonly>
                        </div>

                        <div class="form-group">
                            <label>Tên sản phẩm biến thể</label>
                            <input type="text" class="form-control" name="name" value="{{$variantItem->name}}">
                        </div>

                        <div class="form-group">
                            <label>Giá <code>(Để 0 nếu giá giữ nguyên)</code></label>
                            <input type="text" class="form-control" name="price" value="{{$variantItem->price}}">
                        </div>
                        <div class="form-group">
                          <label>Tồn kho</label>
                          <input type="text" class="form-control" name="qty" value="{{$variantItem->qty}}">
                      </div>
                        <div class="form-group">
                            <label for="inputState">Mặc định</label>
                            <select id="inputState" class="form-control" name="is_default">
                                <option value="">Select</option>
                              <option {{$variantItem->is_default == 1 ? 'selected' : ''}} value="1">Có</option>
                              <option {{$variantItem->is_default == 0 ? 'selected' : ''}} value="0">Không</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="inputState">Trạng thái</label>
                            <select id="inputState" class="form-control" name="status">
                              <option {{$variantItem->status == 1 ? 'selected' : ''}} value="1">Active</option>
                              <option {{$variantItem->status == 0 ? 'selected' : ''}} value="0">Inactive</option>
                            </select>
                        </div>
                        <button type="submmit" class="btn btn-primary">Cập nhật</button>
                    </form>
                  </div>

                </div>
              </div>
            </div>

          </div>
        </section>

@endsection
