@php
    // Chuyển đổi dữ liệu JSON thành mảng
    $sliderSectionOne = json_decode(optional($categoryProductSliderSectionOne)->value, true) ?? [];
@endphp

<div class="tab-pane fade" id="list-messages" role="tabpanel" aria-labelledby="list-messages-list">
    <div class="card border">
        <div class="card-body">
            <form action="{{ route('admin.product-slider-section-one') }}" method="POST">
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
                                        {{ isset($sliderSectionOne['category']) && $category->id == $sliderSectionOne['category'] ? 'selected' : '' }}
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
                                $subCategories = isset($sliderSectionOne['category'])
                                    ? \App\Models\SubCategory::where('category_id', $sliderSectionOne['category'])->get()
                                    : [];
                            @endphp
                            <label>Danh mục phụ</label>
                            <select name="sub_cat_one" class="form-control sub-category">
                                <option value="">Select</option>
                                @foreach ($subCategories as $subCategory)
                                    <option
                                        {{ isset($sliderSectionOne['sub_category']) && $subCategory->id == $sliderSectionOne['sub_category'] ? 'selected' : '' }}
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
                                $childCategories = isset($sliderSectionOne['sub_category'])
                                    ? \App\Models\ChildCategory::where('sub_category_id', $sliderSectionOne['sub_category'])->get()
                                    : [];
                            @endphp
                            <label>Danh mục con</label>
                            <select name="child_cat_one" class="form-control child-category">
                                <option value="">Select</option>
                                @foreach ($childCategories as $childCategory)
                                    <option
                                        {{ isset($sliderSectionOne['child_category']) && $childCategory->id == $sliderSectionOne['child_category'] ? 'selected' : '' }}
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
