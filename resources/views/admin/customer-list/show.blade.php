@extends('admin.layouts.master')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Khách hàng</h1>
    </div>

    <div class="section-body">
        <div class="invoice">
            <div class="invoice-print">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="invoice-title">
                            <h2>Chi tiết khách hàng</h2>
                        </div>
                        <hr>
                        <table class="table">
                            <tr>
                                <th>ID</th>
                                <td>{{ $customer->id }}</td>
                            </tr>
                            <tr>
                                <th>Tên</th>
                                <td>{{ $customer->name }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $customer->email }}</td>
                            </tr>
                            <tr>
                                <th>Số điện thoại</th>
                                <td>{{ $customer->phone }}</td>
                            </tr>
                            <tr>
                                <th>Trạng thái hoạt động</th>
                                <td>
                                    <label class="custom-switch mt-2">
                                        <input type="checkbox" 
                                               class="custom-switch-input change-status" 
                                               data-id="{{ $customer->id }}"
                                               {{ $customer->status === 'active' ? 'checked' : '' }}>
                                        <span class="custom-switch-indicator"></span>
                                    </label>
                                </td>
                            </tr>
                        </table>

                        {{-- Ô nhập lý do thay đổi trạng thái --}}
                        <div class="form-group mt-3" id="reason_box" style="display: none;">
                            <label for="reason_text">Lý do thay đổi trạng thái</label>
                            <textarea id="reason_text" class="form-control" rows="3" placeholder="Nhập lý do..."></textarea>
                            <button class="btn btn-primary mt-2" id="save-status-btn">Xác nhận thay đổi</button>
                        </div>

                        {{-- Lịch sử --}}
                        <div class="row mt-5">
                            <div class="col-md-12">
                                <div class="section-title">Lịch sử trạng thái khách hàng</div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>STT</th>
                                                <th>Trạng thái</th>
                                                <th>Lý do</th>
                                                <th>Người cập nhật</th>
                                                <th>Thời gian thay đổi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($customer->statusHistories as $history)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ ucfirst($history->status) }}</td>
                                                    <td>{{ $history->reason ?? 'Không có' }}</td>
                                                    <td>{{ $history->user->name ?? 'Hệ thống' }}</td>
                                                    <td>{{ date('d/m/Y H:i', strtotime($history->changed_at)) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
</section>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        let customerId = $('.change-status').data('id');
        let currentStatus = $('.change-status').is(':checked') ? 'active' : 'inactive';
        let newStatus = currentStatus;

        $('.change-status').on('change', function () {
            newStatus = $(this).is(':checked') ? 'active' : 'inactive';
            $('#reason_box').show(); // Show input for reason
        });

        $('#save-status-btn').on('click', function () {
            let reason = $('#reason_text').val().trim();

            if (reason === '') {
                toastr.error("Vui lòng nhập lý do khi thay đổi trạng thái.");
                return;
            }

            $.ajax({
                url: "{{ route('admin.customer.status-change') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: customerId,
                    status: newStatus,
                    reason: reason
                },
                beforeSend: function () {
                    $('#save-status-btn').prop('disabled', true).text('Đang cập nhật...');
                },
                success: function (response) {
                    toastr.success(response.message);
                    $('#reason_box').hide();
                    $('#reason_text').val('');
                    $('#save-status-btn').prop('disabled', false).text('Xác nhận thay đổi');
                },
                error: function (xhr) {
                    let error = xhr.responseJSON?.message || 'Có lỗi xảy ra.';
                    toastr.error(error);
                    $('#save-status-btn').prop('disabled', false).text('Xác nhận thay đổi');
                }
            });
        });
    });
</script>
@endpush
