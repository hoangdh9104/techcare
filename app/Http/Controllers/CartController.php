namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon; // Đảm bảo model Coupon tồn tại

class CartController extends Controller
{
    public function cartDetail()
    {
        $cartItems = \Cart::content(); // Lấy danh sách sản phẩm trong giỏ hàng
        $coupons = Coupon::where('status', 1) // Chỉ lấy mã giảm giá đang hoạt động
            ->whereDate('start_date', '<=', now()) // Ngày bắt đầu phải nhỏ hơn hoặc bằng ngày hiện tại
            ->whereDate('end_date', '>=', now()) // Ngày kết thúc phải lớn hơn hoặc bằng ngày hiện tại
            ->orderBy('start_date', 'desc') // Sắp xếp theo ngày bắt đầu
            ->get(); // Lấy tất cả mã giảm giá hợp lệ

        // Debug dữ liệu
        dd($coupons); // Hoặc ghi log: \Log::info($coupons);

        return view('frontend.pages.cart-detail', compact('cartItems', 'coupons'));
    }
}