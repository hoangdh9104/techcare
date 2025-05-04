@extends('admin.layouts.master')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Bài viết</h1>
        </div>
        <div class="mb-3">
            <a href="{{route('admin.products.index')}}" class="btn btn-primary">Quay lại</a>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Tạo mới bài viếtviết</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{route('admin.blog.store')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label>Ảnh</label>
                                    <input type="file" class="form-control" name="image">
                                </div>

                                <div class="form-group">
                                    <label>Tiêu đề</label>
                                    <input type="text" class="form-control" name="title" value="{{old('title')}}">
                                </div>
                                {{-- category --}}

                                <div class="form-group">
                                    <label for="inputState">Danh mục</label>
                                    <select id="inputState" class="form-control main-category" name="category">
                                        <option value="">Chọn danh mục</option>
                                        @foreach ($categories as $category)
                                            <option value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="form-group">
                                    <label>Mô tả</label>
                                    <textarea name="description" class="form-control summernote"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Tiêu đề Seo</label>
                                    <input type="text" class="form-control" name="seo_title" value="{{old('seo_title')}}">
                                </div>

                                <div class="form-group">
                                    <label>Mô tả Seo</label>
                                    <textarea name="seo_description" class="form-control"></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="inputState">Trạng thái</label>
                                    <select id="inputState" class="form-control" name="status">
                                        <option value="1">Hoạt động</option>
                                        <option value="0">Tắt hoạt động</option>
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

    </script>
@endpush
