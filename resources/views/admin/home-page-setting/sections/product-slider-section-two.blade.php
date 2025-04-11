@php
    // Chuyển đổi dữ liệu JSON thành mảng
    $sliderSectionTwo = json_decode(optional($sliderSectionTwo)->value, true) ?? [];
@endphp

<div class="tab-pane fade" id="list-settings" role="tabpanel" aria-labelledby="list-settings-list">
    <div class="card border">
        <div class="card-body">
            <form action="{{ route('admin.product-slider-section-two') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Chọn Category -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Danh mục</label>
                            <select name="cat_one" class="form-control main-category">
                                <option value="">Select</option>
                                @foreach ($categories as $category)
                                    <option
                                        {{ isset($sliderSectionTwo['category']) && $category->id == $sliderSectionTwo['category'] ? 'selected' : '' }}
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
                            @php
                                $subCategories = isset($sliderSectionTwo['category'])
                                    ? \App\Models\SubCategory::where('category_id', $sliderSectionTwo['category'])->get()
                                    : [];
                            @endphp
                            <label>Danh mục phụ</label>
                            <select name="sub_cat_one" class="form-control sub-category">
                                <option value="">Select</option>
                                @foreach ($subCategories as $subCategory)
                                    <option
                                        {{ isset($sliderSectionTwo['sub_category']) && $subCategory->id == $sliderSectionTwo['sub_category'] ? 'selected' : '' }}
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
                            @php
                                $childCategories = isset($sliderSectionTwo['sub_category'])
                                    ? \App\Models\ChildCategory::where('sub_category_id', $sliderSectionTwo['sub_category'])->get()
                                    : [];
                            @endphp
                            <label>Danh mục con</label>
                            <select name="child_cat_one" class="form-control child-category">
                                <option value="">Select</option>
                                @foreach ($childCategories as $childCategory)
                                    <option
                                        {{ isset($sliderSectionTwo['child_category']) && $childCategory->id == $sliderSectionTwo['child_category'] ? 'selected' : '' }}
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
