@extends('frontend.dashboard.layouts.master')

@section('content')
    <section id="wsus__dashboard">
        <div class="container-fluid">
            @include('shipper.layouts.sidebar')

            <div class="row">
                <div class="col-xl-9 col-xxl-10 col-lg-9 ms-auto">
                    <div class="dashboard_content">
                        <div class="wsus__dashboard">
                            <div class="section">
                                <div class="section-header mb-4">
                                    <h1 class="h4">Đơn hàng chờ nhận</h1>
                                </div>
                                <div class="section-body">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="mb-0">Danh sách đơn chờ shipper nhận</h4>
                                        </div>
                                        <div class="card-body table-responsive">
                                            {{ $dataTable->table(['class' => 'table table-bordered table-striped']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
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
