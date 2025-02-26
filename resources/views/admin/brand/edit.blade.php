@extends('admin.layouts.master')
@section('content')
<section class="section">
    <div class="section-header">
      <h1>Brand</h1>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>Create Brand</h4>
            </div>
            <div class="card-body">
                <form action="{{route('admin.brand.update',$brand->id)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>Preview</label>
                        <br>
                        <img width="200" src="{{asset($brand->logo)}}" alt="">
                    </div>
                    <div class="form-group">
                        <label>Image</label>
                        <input type="file" class="form-control" name="logo">
                    </div>
                    <div class="form-group">
                        <label>name</label>
                        <input type="text" class="form-control" name="name" value="{{$brand->name}}">
                    </div>
                    <div class="form-group">
                        <label>Is Featured</label>
                        <select class="form-control" name="is_featured">
                          <option value="">Select</option>
                          <option {{$brand->is_featured == 1 ? 'selected' : ''}} value="1">Yes</option>
                          <option {{$brand->is_featured == 0 ? 'selected' : ''}} value="0">No</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" name="status">
                          <option {{$brand->status == 1 ? 'selected' : ''}} value="1">Active</option>
                          <option {{$brand->status == 0 ? 'selected' : ''}} value="0">Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
          </div>
        </div>
      </div>
    </div>
</section>
@endsection
