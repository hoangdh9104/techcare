@extends('frontend.dashboard.layouts.master')

@section('title')
    {{ $settings->site_name }} || Danh sách đơn hàng
@endsection
<strong>

</strong>
@section('content')
    <!--=============================
                                                                                                                                                                                                                                                        DASHBOARD START
                                                                                                                                                                                                                                                      ==============================-->
    <section id="wsus__dashboard">
        <div class="container-fluid">
            @include('frontend.dashboard.layouts.sidebar')
            <div class="row">
                <div class="col-xl-9 col-xxl-10 col-lg-9 mx-auto">
                    <div class="dashboard_content mt-2 mt-md-0">
                        <h3><i class="far fa-user"></i> Đơn hàng</h3>
                        <div class="wsus__dashboard_profile">
                            <div class="wsus__dash_pro_area">
                                {{ $dataTable->table() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <!-- Modal Nhập Lý Do Hủy -->
        <div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cancelOrderLabel">
                            Nhập lý do hủy đơn hàng</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="order-id">
                        <div class="mb-3">
                            <label for="cancel-reason" class="form-label">Lý do hủy đơn hàng:</label>
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
    <!--=============================DASHBOARD START==============================-->
    <!-- Confirm Order Received Modal -->
    <div class="modal fade" id="confirmReceivedModal" tabindex="-1" aria-labelledby="confirmReceivedLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg rounded-4">
                <div class="modal-header bg-success text-white rounded-top-4">
                    <h5 class="modal-title" id="confirmReceivedLabel">Xác nhận đã nhận đơn hàng</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <p class="mb-0 fs-5">Bạn có chắc chắn đã nhận được đơn hàng này không?</p>
                </div>
                <div class="modal-footer justify-content-center border-0 pb-4">
                    <button type="button" class="btn btn-outline-secondary px-4 rounded-pill"
                        data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-success px-4 rounded-pill" id="confirm-received-btn">Xác
                        nhận</button>
                </div>
            </div>
        </div>
    </div> --}}
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
                const $btn = $(this);
                const originalHtml = $btn.html();

                if (reason === '') {
                    toastr.error('Vui lòng nhập lý do hủy đơn hàng!');
                    return;
                }

                // Spinner + disable nút
                $btn.html(
                    `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Đang xử lý...`
                );
                $btn.prop('disabled', true);

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
                    },
                    error: function() {
                        toastr.error('Đã xảy ra lỗi!');
                    },
                    complete: function() {
                        $btn.html(originalHtml);
                        $btn.prop('disabled', false);
                    }
                });
            });

            // Xử lý xác nhận đã nhận hàng
            let selectedReceivedOrderId = null;

            // Mở modal khi nhấn nút xác nhận đã nhận
            $(document).on('click', '.confirm-received', function() {
                selectedReceivedOrderId = $(this).data('id');
                $('#confirmReceivedModal').modal('show');
            });

            // Gửi AJAX khi người dùng xác nhận
            $('#confirm-received-btn').on('click', function() {
                const $btn = $(this);
                const originalHtml = $btn.html();

                // Hiển thị spinner và disable nút
                $btn.html(
                    `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Đang xử lý...`
                );
                $btn.prop('disabled', true);

                $.ajax({
                    url: "{{ route('user.orders.received', ':id') }}".replace(':id',
                        selectedReceivedOrderId),
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.status === "success") {
                            toastr.success(response.message);
                            $('#userorder-table').DataTable().ajax.reload();
                            $('#confirmReceivedModal').modal('hide');
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        toastr.error('Đã xảy ra lỗi!');
                    },
                    complete: function() {
                        // Khôi phục nút về trạng thái ban đầu
                        $btn.html(originalHtml);
                        $btn.prop('disabled', false);
                    }
                });
            });

        });
    </script>
@endpush
