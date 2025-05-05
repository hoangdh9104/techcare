@extends('frontend.layouts.master')
@section('title')
{{$settings->site_name}} || wishlist
@endsection

@section('content')
<div class="container my-5">
    <h2 class="text-center mb-4">Chính Sách Bán Hàng</h2>

    <div class="row">
        <div class="col-md-12">
            <h2 class="mt-4">1. Giới Thiệu</h2>
            <p>Chào mừng bạn đến với website của chúng tôi! Chúng tôi chuyên cung cấp các sản phẩm điện tử chất lượng cao từ các thương hiệu nổi tiếng. Chúng tôi cam kết mang đến cho khách hàng trải nghiệm mua sắm tốt nhất với dịch vụ tận tâm.</p>
        </div>

        <div class="col-md-12">
            <h3 class="mt-4">2. Chính Sách Đổi Trả</h3>
            <p><strong>Thời Gian Đổi Trả:</strong> Khách hàng có quyền đổi hoặc trả hàng trong vòng 14 ngày kể từ ngày nhận sản phẩm.</p>
            <p><strong>Điều Kiện Đổi Trả:</strong> Sản phẩm phải còn nguyên vẹn, chưa qua sử dụng, có đầy đủ hóa đơn mua hàng và các phụ kiện kèm theo.</p>
            <p><strong>Quy Trình Đổi Trả:</strong></p>
            <ul class="list-group mb-4">
                <li class="list-group-item">Liên hệ với bộ phận chăm sóc khách hàng qua email hoặc số điện thoại để thông báo về việc đổi/trả hàng.</li>
                <li class="list-group-item">Gửi sản phẩm về địa chỉ của chúng tôi kèm theo hóa đơn mua hàng.</li>
                <li class="list-group-item">Chúng tôi sẽ kiểm tra sản phẩm và xử lý yêu cầu trong vòng 5-7 ngày làm việc.</li>
            </ul>
        </div>

        <div class="col-md-12">
            <h3 class="mt-4">3. Chính Sách Giao Hàng</h3>
            <p><strong>Phí Giao Hàng:</strong> Miễn phí giao hàng cho đơn hàng từ 1.000.000 VNĐ trong khu vực nội thành. Đối với các đơn hàng dưới mức này, phí giao hàng sẽ được tính theo bảng giá hiện hành.</p>
            <p><strong>Thời Gian Giao Hàng:</strong> Thời gian giao hàng từ 1-3 ngày làm việc tùy thuộc vào vị trí của khách hàng. Chúng tôi sẽ thông báo cho khách hàng khi sản phẩm được giao.</p>
            <p><strong>Theo Dõi Đơn Hàng:</strong> Khách hàng có thể theo dõi trạng thái đơn hàng qua trang web của chúng tôi hoặc liên hệ với bộ phận hỗ trợ khách hàng.</p>
        </div>

        <div class="col-md-12">
            <h3 class="mt-4">4. Bảo Hành Sản Phẩm</h3>
            <p><strong>Thời Gian Bảo Hành:</strong> Tất cả sản phẩm đều được bảo hành theo chính sách của nhà sản xuất, thời gian bảo hành sẽ được ghi trên hóa đơn.</p>
            <p><strong>Điều Kiện Bảo Hành:</strong> Sản phẩm phải được sử dụng đúng cách và không có dấu hiệu bị sửa chữa hoặc can thiệp.</p>
        </div>

        <div class="col-md-12">
            <h3 class="mt-4">5. Chính Sách Bảo Mật</h3>
            <p><strong>Thông Tin Cá Nhân:</strong> Chúng tôi cam kết bảo mật thông tin cá nhân của khách hàng. Thông tin sẽ chỉ được sử dụng cho mục đích xử lý đơn hàng và cải thiện dịch vụ.</p>
            <p><strong>Chia Sẻ Thông Tin:</strong> Chúng tôi không chia sẻ thông tin cá nhân với bên thứ ba mà không có sự đồng ý của khách hàng, trừ khi yêu cầu bởi pháp luật.</p>
        </div>

        <div class="col-md-12">
            <h3 class="mt-4">6. Chính Sách Thanh Toán</h3>
            <p><strong>Phương Thức Thanh Toán:</strong> Chúng tôi hỗ trợ nhiều phương thức thanh toán như chuyển khoản ngân hàng, thẻ tín dụng, và thanh toán khi nhận hàng.</p>
            <p><strong>Xác Nhận Thanh Toán:</strong> Sau khi nhận được thanh toán, chúng tôi sẽ gửi xác nhận qua email cho khách hàng.</p>
        </div>

        <div class="col-md-12">
            <h3 class="mt-4">7. Liên Hệ</h3>
            <p>Nếu bạn có bất kỳ câu hỏi nào về chính sách, vui lòng liên hệ với chúng tôi qua:</p>
            <ul class="list-group mb-4">
                <li class="list-group-item"><strong>Email:</strong> Techcare@gmail.com</li>
                <li class="list-group-item"><strong>Số Điện Thoại:</strong> 0123 456 789</li>
            </ul>
        </div>

        <div class="col-md-12">
            <h3 class="mt-4">8. Quy Định Chung</h3>
            <p><strong>Thay Đổi Chính Sách:</strong> Chúng tôi có quyền sửa đổi chính sách này vào bất kỳ thời điểm nào mà không cần thông báo trước. Khách hàng nên thường xuyên kiểm tra để cập nhật những thay đổi.</p>
            <p><strong>Hiệu Lực:</strong> Chính sách này có hiệu lực từ ngày [06/01/2025].</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')

@endpush