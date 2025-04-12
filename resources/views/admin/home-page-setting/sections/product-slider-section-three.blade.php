@php
    // Chuyển đổi dữ liệu JSON thành mảng
    $sliderSectionThree = json_decode(optional($sliderSectionThree)->value, true) ?? [];

    // Lấy dữ liệu cho Part 1 và Part 2
    $partOne = $sliderSectionThree[0] ?? [];
    $partTwo = $sliderSectionThree[1] ?? [];

    // Lấy danh sách subcategories
    $subCategoriesOne = isset($partOne['category'])
        ? \App\Models\SubCategory::where('category_id', $partOne['category'])->get()
        : [];

    $subCategoriesTwo = isset($partTwo['category'])
        ? \App\Models\SubCategory::where('category_id', $partTwo['category'])->get()
        : [];

    // Lấy danh sách child categories
    $childCategoriesOne = isset($partOne['sub_category'])
        ? \App\Models\ChildCategory::where('sub_category_id', $partOne['sub_category'])->get()
        : [];

    $childCategoriesTwo = isset($partTwo['sub_category'])
        ? \App\Models\ChildCategory::where('sub_category_id', $partTwo['sub_category'])->get()
        : [];
@endphp

<div class="tab-pane fade" id="list-slider-three" role="tabpanel">
    <div class="card border">
        <div class="card-body">
            <form action="{{ route('admin.product-slider-section-three') }}" method="POST">
                @csrf
                @method('PUT')

                <!-- PART 1 -->
                <h5>Phần sản phẩm 1</h5>
                <div class="row">
                    <!-- Chọn Category -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Danh mục</label>
                            <select name="cat_one" class="form-control main-category">
                                <option value="">Select</option>
                                @foreach ($categories as $category)
                                    <option
                                        {{ isset($partOne['category']) && $category->id == $partOne['category'] ? 'selected' : '' }}
                                        value="{{ $category->id }}">
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Chọn Sub Category -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Danh mục phụ</label>
                            <select name="sub_cat_one" class="form-control sub-category">
                                <option value="">Select</option>
                                @foreach ($subCategoriesOne as $subCategory)
                                    <option
                                        {{ isset($partOne['sub_category']) && $subCategory->id == $partOne['sub_category'] ? 'selected' : '' }}
                                        value="{{ $subCategory->id }}">
                                        {{ $subCategory->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Chọn Child Category -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Danh mục con</label>
                            <select name="child_cat_one" class="form-control child-category">
                                <option value="">Select</option>
                                @foreach ($childCategoriesOne as $childCategory)
                                    <option
                                        {{ isset($partOne['child_category']) && $childCategory->id == $partOne['child_category'] ? 'selected' : '' }}
                                        value="{{ $childCategory->id }}">
                                        {{ $childCategory->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- PART 2 -->
                <h5>Phần sản phẩm 2</h5>
                <div class="row">
                    <!-- Chọn Category -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Danh mục</label>
                            <select name="cat_two" class="form-control main-category">
                                <option value="">Select</option>
                                @foreach ($categories as $category)
                                    <option
                                        {{ isset($partTwo['category']) && $category->id == $partTwo['category'] ? 'selected' : '' }}
                                        value="{{ $category->id }}">
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Chọn Sub Category -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Danh mục phụ</label>
                            <select name="sub_cat_two" class="form-control sub-category">
                                <option value="">Select</option>
                                @foreach ($subCategoriesTwo as $subCategory)
                                    <option
                                        {{ isset($partTwo['sub_category']) && $subCategory->id == $partTwo['sub_category'] ? 'selected' : '' }}
                                        value="{{ $subCategory->id }}">
                                        {{ $subCategory->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Chọn Child Category -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Danh mục con</label>
                            <select name="child_cat_two" class="form-control child-category">
                                <option value="">Select</option>
                                @foreach ($childCategoriesTwo as $childCategory)
                                    <option
                                        {{ isset($partTwo['child_category']) && $childCategory->id == $partTwo['child_category'] ? 'selected' : '' }}
                                        value="{{ $childCategory->id }}">
                                        {{ $childCategory->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Cập nhật</button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $('body').on('change', '.main-category', function(e) {
                let id = $(this).val();
                let row = $(this).closest('.row');

                $.ajax({
                    method: 'GET',
                    url: "{{ route('admin.get-subcategories') }}",
                    data: { id: id },
                    success: function(data) {
                        let selector = row.find('.sub-category');
                        selector.html('<option value="">Select</option>');
                        $.each(data, function(i, item) {
                            selector.append(`<option value="${item.id}">${item.name}</option>`);
                        });
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            });

            $('body').on('change', '.sub-category', function(e) {
                let id = $(this).val();
                let row = $(this).closest('.row');

                $.ajax({
                    method: 'GET',
                    url: "{{ route('admin.product.get-child-categories') }}",
                    data: { id: id },
                    success: function(data) {
                        let selector = row.find('.child-category');
                        selector.html('<option value="">Select</option>');
                        $.each(data, function(i, item) {
                            selector.append(`<option value="${item.id}">${item.name}</option>`);
                        });
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            });
        });
    </script>
@endpush
