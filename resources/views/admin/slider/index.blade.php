@extends('admin.layouts.master')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Slider</h1>
            {{-- <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Trang chủ</a></div>
                <div class="breadcrumb-item"><a href="#">Giao diện website</a></div>
                <div class="breadcrumb-item">Slider</div>
            </div> --}}
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Bảng đơn giản</h4>
                            <div class="card-header-action">
                                <a href="{{ route('admin.slider.create') }}" class="btn btn-primary"><i
                                        class="fas fa-plus"></i> Tạo mới</a>
                            </div>
                        </div>
                        <div class="card-body">
                            {{ $dataTable->table() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
