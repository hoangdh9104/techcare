@extends('vendor.layouts.master')

@section('title')
    {{ $settings->site_name }} || Biến thể sản phẩm
@endsection

@section('content')
    <!--=============================
        DASHBOARD START
      ==============================-->
    <section id="wsus__dashboard">
        <div class="container-fluid">
            @include('vendor.layouts.sidebar')

            <div class="row">
                <div class="col-xl-9 col-xxl-10 col-lg-9 ms-auto">
                    <div class="dashboard_content mt-2 mt-md-0">
                        <h3><i class="far fa-user"></i> Tạo biến thể</h3>
                        <div class="wsus__dashboard_profile">
                            <div class="wsus__dash_pro_area">
                                <form action="{{ route('vendor.products-variant.store') }}" method="POST">
                                    @csrf

                                    <div class="form-group wsus__input">
                                        <label>Tên</label>
                                        <input type="text" class="form-control" name="name" value="">
                                    </div>
                                    <div class="form-group wsus__input">
                                        <input type="hidden" class="form-control" name="product"
                                            value="{{ request()->product }}">
                                    </div>
                                    <div class="form-group wsus__input">
                                        <label for="inputState">Trạng thái</label>
                                        <select id="inputState" class="form-control" name="status">
                                            <option value="1">Đang hoạt động</option>
                                            <option value="0">Không hoạt động</option>
                                        </select>
                                    </div>
                                    <button type="submmit" class="btn btn-primary">Thêm</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--=============================
        DASHBOARD START
      ==============================-->
@endsection
