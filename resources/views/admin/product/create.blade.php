@extends('admin.layouts.master')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Sản phẩm</h1>
        </div>
        <div class="mb-3">
            <a href="{{ route('admin.products.index') }}" class="btn btn-primary">Quay lại</a>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Tạo mới sản phẩm</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label>Hình ảnh</label>
                                    <input type="file" class="form-control" name="image">
                                </div>

                                <div class="form-group">
                                    <label>Tên</label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                                </div>
                                {{-- category --}}
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="inputState">Danh mục</label>
                                            <select id="inputState" class="form-control main-category" name="category">
                                                <option value="">Select</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="inputState">Danh mục phụ</label>
                                            <select id="inputState" class="form-control sub-category" name="sub_category">
                                                <option value="">Select</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="inputState">Danh mục con</label>
                                            <select id="inputState" class="form-control child-category"
                                                name="child_category">
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                {{-- end category --}}
                                {{-- brand --}}
                                <div class="form-group">
                                    <label for="inputState">Thương hiệu</label>
                                    <select id="inputState" class="form-control" name="brand">
                                        <option value="">Select</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- end brand --}}

                                <div class="form-group">
                                    <label>SKU</label>
                                    <input type="text" class="form-control" name="sku" value="{{ old('sku') }}">
                                </div>

                                <div class="form-group">
                                    <label for="warranty_duration">Thời gian bảo hành</label>
                                    <select name="warranty_duration" class="form-control" id="warranty_duration">
                                        <option value="3">3 tháng</option>
                                        <option value="6">6 tháng</option>
                                        <option value="9">9 tháng</option>
                                        <option value="12">12 tháng</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Giá</label>
                                    <input type="text" class="form-control" name="price" value="{{ old('price') }}">
                                </div>

                                <div class="form-group">
                                    <label>Giá khuyến mại</label>
                                    <input type="text" class="form-control" name="offer_price"
                                        value="{{ old('offer_price') }}">
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Ngày bắt đầu</label>
                                            <input type="text" class="form-control datepicker" name="offer_start_date"
                                                value="{{ old('offer_start_date') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Ngày kết thúc</label>
                                            <input type="text" class="form-control datepicker" name="offer_end_date"
                                                value="{{ old('offer_end_date') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Tồn kho</label>
                                    <input type="number" min="0" class="form-control" name="qty"
                                        value="{{ old('qty') }}">
                                </div>

                                <div class="form-group">
                                    <label>Video Link</label>
                                    <input type="text" class="form-control" name="video_link"
                                        value="{{ old('video_link') }}">
                                </div>


                                <div class="form-group">
                                    <label>Mô tả ngắn</label>
                                    <textarea name="short_description" class="form-control">{{ old('short_description') }}</textarea>
                                </div>


                                <div class="form-group">
                                    <label>Mô tả chi tiết</label>
                                    <textarea name="long_description" class="form-control summernote">{{ old('long_description') }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="inputState">Loại</label>
                                    <select id="inputState" class="form-control" name="product_type">
                                        <option value="">Select</option>
                                        <option value="new_arrival">Hàng mới về</option>
                                        <option value="featured_product">Sản phẩm nổi bật</option>
                                        <option value="top_product">Sản phẩm bán chạy</option>
                                        <option value="best_product">Sản phẩm tốt nhất</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Seo tiêu đề</label>
                                    <input type="text" class="form-control" name="seo_title"
                                        value="{{ old('seo_title') }}">
                                </div>
                                <div class="form-group">
                                    <label>Seo Mô tả</label>
                                    <textarea name="seo_description" class="form-control">{{ old('seo_description') }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="inputState">Trạng thái</label>
                                    <select id="inputState" class="form-control" name="status">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                                <button type="submmit" class="btn btn-primary">Tạo mới</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('body').on('change', '.main-category', function(e) {
                // nếu thay đổi danh mục cha category, child category will be reset
                $('.child-category').html('<option value="">Select</option>')
                let id = $(this).val();
                $.ajax({
                    method: 'GET',
                    url: "{{ route('admin.product.get-subcategories') }}",
                    data: {
                        id: id
                    },
                    success: function(data) {
                        $('.sub-category').html('<option value="">Select</option>')

                        $.each(data, function(i, item) {
                            $('.sub-category').append(
                                `<option value="${item.id}">${item.name}</option>`)
                        })
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                })
            })
            // get child-categories
            $('body').on('change', '.sub-category', function(e) {
                let id = $(this).val();
                $.ajax({
                    method: 'GET',
                    url: "{{ route('admin.product.get-child-categories') }}",
                    data: {
                        id: id
                    },
                    success: function(data) {
                        $('.child-category').html('<option value="">Select</option>')

                        $.each(data, function(i, item) {
                            $('.child-category').append(
                                `<option value="${item.id}">${item.name}</option>`)
                        })
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                })
            })
        })
    </script>
@endpush
