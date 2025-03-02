@extends('admin.layouts.master')
@section('content')
<section class="section">
    <div class="section-header">
      <h1>Shipping Rule</h1>
    </div>
    <div class="mb-3">
        <a href="{{route('admin.shipping-rule.index')}}" class="btn btn-primary">Back</a>
    </div>
    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>Edit Shipping Rule</h4>
            </div>
            <div class="card-body">
                <form action="{{route('admin.shipping-rule.update',$shipping->id)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>name</label>
                        <input type="text" class="form-control" name="name" value="{{$shipping->name}}">
                    </div>
                    <div class="form-group">
                        <label>Type</label>
                        <select class="form-control shipping-type" name="type">
                          <option {{$shipping->type == 'flat_cost' ? 'selected' : ''}} value="flat_cost">Flat cost</option>
                          <option {{$shipping->type == 'min_cost' ? 'selected' : ''}} value="min_cost">Minium order Amount</option>
                        </select>
                    </div>
                    <div class="form-group min-cost d-none">
                        <label>Minium Amount</label>
                        <input type="text" class="form-control" id="min_cost" name="min_cost" value="{{$shipping->min_cost}}">
                    </div>
                    <div class="form-group">
                        <label>Cost</label>
                        <input type="text" class="form-control" name="cost" value="{{$shipping->cost}}">
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" name="status">
                            <option {{$shipping->status == 1 ? 'selected' : ''}} value="1">Active</option>
                            <option {{$shipping->status == 0 ? 'selected' : ''}} value="0">Inactive</option>
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
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelector('.shipping-type').value === 'min_cost' ?  document.querySelector('.min-cost').classList.remove('d-none') : document.querySelector('.min-cost').classList.add('d-none');
        document.body.addEventListener('click', function (event) {
            if (event.target.classList.contains('shipping-type')) {
                const value = event.target.value;
                if (value !== 'min_cost' ) {
                    document.querySelector('.min-cost').classList.add('d-none');
                    document.querySelector('#min_cost').value =0;
                }else{
                    document.querySelector('.min-cost').classList.remove('d-none');
                }
            }
        });
    });
</script>
@endpush
