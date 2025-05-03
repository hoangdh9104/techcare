@extends('admin.layouts.master')

@section('content')
      <!-- Main Content -->
        <section class="section">
          <div class="section-header">
            <h1>Danh mục bài viết</h1>
          </div>

          <div class="section-body">

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Cập nhật danh mục bài viết</h4>

                  </div>
                  <div class="card-body">

                    <form action="{{route('admin.blog-category.update', $category->id)}}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label>Tên</label>
                            <input type="text" class="form-control" name="name" value="{{$category->name}}">
                        </div>
                        <div class="form-group">
                            <label for="inputState">Trạng thái</label>
                            <select id="inputState" class="form-control" name="status">
                              <option {{$category->status == 1 ? 'selected' : ''}} value="1">Hoạt động</option>
                              <option {{$category->status == 0 ? 'selected' : ''}} value="0">Tắt hoạt động</option>
                            </select>
                          </div>
                          <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </form>

                  </div>

                </div>
              </div>
            </div>

          </div>
        </section>

@endsection
