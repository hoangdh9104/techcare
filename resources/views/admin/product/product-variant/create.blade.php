@extends('admin.layouts.master')

@section('content')
      <!-- Main Content -->
        <section class="section">
          <div class="section-header">
            <h1>Product Variant</h1>
          </div>
          <div class="mb-3">
            <a href="{{route('admin.products-variant.index', ['product' => request()->product])}}" class="btn btn-primary">Back</a>
            </div>
          <div class="section-body">

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Create Product Variant</h4>

                  </div>
                  <div class="card-body">

                    <form action="{{route('admin.products-variant.store')}}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" name="name" value="">
                        </div>
                        <div class="form-group">
                            <input type="hidden" value="{{request()->product}}" name="product">
                        </div>
                        <div class="form-group">
                            <label for="inputState">Status</label>
                            <select id="inputState" class="form-control" name="status">
                              <option value="1">Active</option>
                              <option value="0">Inactive</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </form>

                  </div>

                </div>
              </div>
            </div>

          </div>
        </section>

@endsection
