@extends('admin.layouts.master')
@section('content')
    <div class="container-fluid">


        {{-- Thông tin sản phẩm --}}
        <div class="card mb-4 shadow-sm">
            <h4 class="mb-4">Chi tiết sản phẩm: <strong>{{ $product->name }}</strong></h4>
            <div class="card-body row">
                <div class="col-md-4">
                    <img src="{{ asset($product->thumb_image) }}" class="img-fluid rounded" alt="Ảnh sản phẩm">
                </div>
                <div class="col-md-8">
                    <table class="table table-borderless">
                        <tr>
                            <th>Danh mục:</th>
                            <td>{{ optional($product->category)->name }}</td>
                        </tr>
                        <tr>
                            <th>Thương hiệu:</th>
                            <td>{{ optional($product->brand)->name }}</td>
                        </tr>
                        <tr>
                            <th>Giá:</th>
                            <td>{{ number_format($product->price) }}đ</td>
                        </tr>
                        @if ($product->offer_price)
                            <tr>
                                <th>Giá khuyến mãi:</th>
                                <td>{{ number_format($product->offer_price) }}đ</td>
                            </tr>
                        @endif

                        <tr>
                            <th>Thời gian bảo hành:</th>
                            <td>{{ $product->warranty_duration }} Tháng</td>
                        </tr>
                        
                        <tr>
                            <th>Tồn kho:</th>
                            <td>{{ $product->qty }}</td>
                        </tr>
                        <tr>
                            <th>Loại:</th>
                            <td>{{ ucfirst(str_replace('_', ' ', $product->product_type)) }}</td>
                        </tr>
                        <tr>
                            <th>Trạng thái:</th>
                            <td>
                                <span class="badge badge-{{ $product->status ? 'success' : 'secondary' }}">
                                    {{ $product->status ? 'Hiển thị' : 'Ẩn' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>SEO Title:</th>
                            <td>{{ $product->seo_title }}</td>
                        </tr>
                        <tr>
                            <th>SEO Description:</th>
                            <td>{{ $product->seo_description }}</td>
                        </tr>
                        <tr>
                            <th>Mô tả ngắn:</th>
                            <td>{!! $product->short_description !!}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- Mô tả chi tiết --}}
        {{-- <div class="card mb-4 shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Mô tả chi tiết</h5>
            </div>
            <div class="card-body">
                {!! $product->long_description !!}
            </div>
        </div> --}}

        {{-- Biến thể sản phẩm --}}
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Biến thể sản phẩm</h5>
                <a href="{{ route('admin.variants.create', $product->id) }}" class="btn btn-primary btn-sm">
                    Tạo sản phẩm biến thể
                </a>
            </div>
            <div class="card-body">
                @if ($product->variants && $product->variants->count() > 0)
                    <ul class="list-group">
                        @foreach ($product->variants as $variant)
                            <li class="list-group-item">
                                <strong>{{ $variant->name }}</strong> :
                                @foreach ($variant->productVariantItem as $item)
                                    <span>{{ $item->name }}</span> -
                                @endforeach
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted mb-0">Chưa có biến thể nào cho sản phẩm này.</p>
                @endif
            </div>
        </div>
        {{-- Các tổ hợp biến thể đã tạo --}}
        @if ($product->variantCombinations && $product->variantCombinations->count() > 0)
            <div class="mt-4">
                <h5 class="mb-3">Danh sách tổ hợp biến thể đã tạo:</h5>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>STT</th>
                                <th>Biến thể</th>
                                <th>Hình ảnh</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($product->variantCombinations as $index => $combination)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $combination->name }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            @php
                                                $values = json_decode($combination->value, true);
                                            @endphp

                                            @if ($values && is_array($values))
                                                @foreach ($values as $key => $val)
                                                    <div><strong>{{ ucfirst($key) }}:</strong> {{ implode(', ', $val) }}
                                                    </div>
                                                @endforeach
                                            @endif
                                        </small>
                                    </td>
                                    <td>
                                        @if ($combination->image)
                                            <img src="{{ asset($combination->image) }}" alt="Hình biến thể"
                                                class="img-thumbnail" width="80">
                                        @else
                                            <span class="text-muted">Không có</span>
                                        @endif
                                    </td>
                                    <td>{{ number_format($combination->price, 0) }}đ</td>
                                    <td>{{ $combination->quantity }}</td>
                                    <td>
                                        @if ($combination->status == 1)
                                            <label class="custom-switch mt-2">
                                                <input type="checkbox" checked name="custom-switch-checkbox"
                                                    data-id="{{ $combination->id }}"
                                                    class="custom-switch-input change-status">
                                                <span class="custom-switch-indicator"></span>
                                            </label>
                                        @else
                                            <label class="custom-switch mt-2">
                                                <input type="checkbox" name="custom-switch-checkbox"
                                                    data-id="{{ $combination->id }}"
                                                    class="custom-switch-input change-status">
                                                <span class="custom-switch-indicator"></span>
                                            </label>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.variants.edit', $combination->id) }}"
                                            class="btn btn-sm btn-warning">Sửa</a>
                                        {{-- <form action="#" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger"
                                                onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</button>
                                        </form> --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <p class="text-muted mt-3">Chưa có tổ hợp biến thể chi tiết nào.</p>
        @endif
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('body').on('click', '.change-status', function() {
                let isChecked = $(this).is(':checked') ? 1 : 0;
                let id = $(this).data('id');

                $.ajax({
                    url: "{{ route('admin.products-value-variant.change-status') }}",
                    type: "PUT",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                        status: isChecked
                    },
                    success: function(data) {
                        toastr.success(data.message);
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                        toastr.error('Something went wrong!');
                    }
                });
            });
        });
    </script>
@endpush
