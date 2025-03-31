@extends('frontend.dashboard.layouts.master')

@section('title')
    {{ $settings->site_name }} || Order
@endsection

@section('content')
    <!--=============================
                                                                                    DASHBOARD START
                                                                                  ==============================-->
    <section id="wsus__dashboard">
        <div class="container-fluid">
            @include('frontend.dashboard.layouts.sidebar')
            <div class="row">
                <div class="col-xl-9 col-xxl-10 col-lg-9 ms-auto">
                    <div class="dashboard_content mt-2 mt-md-0">
                        <h3><i class="far fa-user"></i> Orders</h3>
                        <div class="wsus__dashboard_profile">
                            <div class="wsus__dash_pro_area">
                                {{ $dataTable->table() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Nhập Lý Do Hủy -->
        <div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cancelOrderLabel">Nhập lý do hủy đơn hàng</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="order-id">
                        <div class="mb-3">
                            <label for="cancel-reason" class="form-label">Lý do hủy:</label>
                            <textarea class="form-control" id="cancel-reason" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="button" class="btn btn-danger" id="confirm-cancel">Xác nhận hủy</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--=============================
                                                                                    DASHBOARD START
                                                                                  ==============================-->
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    <script>
        $(document).ready(function() {
            let selectedOrderId = null;

            // Mở modal khi nhấn nút hủy đơn
            $(document).on('click', '.cancel-order', function() {
                selectedOrderId = $(this).data('id');
                $('#cancelOrderModal').modal('show'); // Hiển thị modal
            });

            // Gửi AJAX khi xác nhận hủy đơn
            $('#confirm-cancel').on('click', function() {
                let reason = $('#cancel-reason').val().trim();

                if (reason === '') {
                    toastr.error('Vui lòng nhập lý do hủy đơn hàng!');
                    return;
                }

                $.ajax({
                    url: "{{ route('user.orders.cancel', ':id') }}".replace(':id', selectedOrderId),
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        reason: reason
                    },
                    success: function(response) {
                        if (response.status === "success") {
                            toastr.success(response.message);
                            $('#userorder-table').DataTable().ajax.reload();
                            $('#cancelOrderModal').modal('hide');
                        } else {
                            toastr.error(response.message);
                        }
                    }
                });
            });
        });
    </script>
@endpush
