<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $todaysOrder = Order::whereDate('created_at', Carbon::today())->count();

        $todaysPendingOrder = Order::whereDate('created_at', Carbon::today())
            ->where('order_status', 'pending')
            ->count();

        $totaltOrder = Order::count();

        $totalPendingOrders = Order::where('order_status', 'pending')->count();

        $totalCanceledOrders = Order::where('order_status', 'canceled')->count();

        $totalCompleteOrders = Order::where('order_status', 'delivered')->count();

        $todaysEarnings = Order::where('order_status', '!=', 'canceled')
            ->where('payment_status', 1)
            ->whereDate('created_at', Carbon::today())
            ->sum('sub_total');

        $monthEarnings = Order::where('order_status', '!=', 'canceled')
            ->where('payment_status', 1)
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('sub_total');

        $yearEarnings = Order::where('order_status', '!=', 'canceled')
            ->where('payment_status', 1)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('sub_total');

        $totalReview = ProductReview::count();

        $totalBrands = Brand::count();

        $totalCategories = Category::count();

        $totalBlogs = Blog::count();

        $totalVendors = User::where('role', 'vendor')->count();
        $totalUsers = User::where('role', 'user')->count();



        return view('admin.dashboard', compact(
            'todaysOrder',
            'todaysPendingOrder',
            'totaltOrder',
            'totalPendingOrders',
            'totalCanceledOrders',
            'totalCompleteOrders',
            'todaysEarnings',
            'monthEarnings',
            'yearEarnings',
            'totalReview',
            'totalBrands',
            'totalCategories',
            'totalBlogs',
            'totalVendors',
            'totalUsers'
        ));
    }
    public function login()
    {
        return view('admin.auth.login');
    }
    public function ecommerceDashboard()
    {
        $todaysOrder = Order::whereDate('created_at', Carbon::today())->count();

        $todaysPendingOrder = Order::whereDate('created_at', Carbon::today())
            ->where('order_status', 'pending')
            ->count();

        $totaltOrder = Order::count();

        $totalPendingOrders = Order::where('order_status', 'pending')->count();

        $totalCanceledOrders = Order::where('order_status', 'canceled')->count();

        $totalCompleteOrders = Order::where('order_status', 'delivered')->count();

        $todaysEarnings = Order::where('order_status', '!=', 'canceled')
            ->where('payment_status', 1)
            ->whereDate('created_at', Carbon::today())
            ->sum('sub_total');

        $monthEarnings = Order::where('order_status', '!=', 'canceled')
            ->where('payment_status', 1)
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('sub_total');

        $yearEarnings = Order::where('order_status', '!=', 'canceled')
            ->where('payment_status', 1)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('sub_total');

        $totalReview = ProductReview::count();

        $totalBrands = Brand::count();

        $totalCategories = Category::count();

        $totalBlogs = Blog::count();

        $totalVendors = User::where('role', 'vendor')->count();
        $totalUsers = User::where('role', 'user')->count();
        return view('admin.ecommerce-dashboard', compact(
            'todaysOrder',
            'todaysPendingOrder',
            'totaltOrder',
            'totalPendingOrders',
            'totalCanceledOrders',
            'totalCompleteOrders',
            'todaysEarnings',
            'monthEarnings',
            'yearEarnings',
            'totalReview',
            'totalBrands',
            'totalCategories',
            'totalBlogs',
            'totalVendors',
            'totalUsers'
        ));
    }
    public function getMonthlyRevenueChart(Request $request)
    {
        // Lấy năm từ input, nếu không có thì mặc định là năm hiện tại
        $year = $request->input('year', Carbon::now()->year);

        // Kiểm tra xem năm có hợp lệ không (phải là một số và có độ dài 4 ký tự)
        if (!is_numeric($year) || strlen($year) != 4) {
            return response()->json([
                'error' => 'Năm không hợp lệ. Vui lòng nhập năm đúng (4 chữ số).'
            ], 400); // Trả về lỗi với mã trạng thái 400 (Bad Request)
        }

        // Lấy tháng bắt đầu từ input, nếu không có thì mặc định là tháng 1
        $startMonth = 1;
        // Truy vấn doanh thu từ tháng bắt đầu đến tháng 12
        $revenues = DB::table('orders')
            ->select(
                DB::raw('MONTH(updated_at) as month'),
                DB::raw('SUM(amount) as total')
            )
            ->whereYear('updated_at', $year)
            ->whereMonth('updated_at', '>=', $startMonth)
            ->where('payment_status', 1)
            ->groupBy(DB::raw('MONTH(updated_at)'))
            ->orderBy('month')
            ->pluck('total', 'month');

        // Tạo dữ liệu từ tháng bắt đầu đến tháng 12
        $monthlyRevenue = [];
        $labels = [];
        for ($i = $startMonth; $i <= 12; $i++) {
            $monthlyRevenue[] = $revenues[$i] ?? 0;
            $labels[] = 'Tháng ' . $i;
        }

        return response()->json([
            'labels' => $labels,
            'data' => $monthlyRevenue
        ]);
    }
    public function getMonthlyOrderStatistics(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');
        if (!$month || !$year) {
            return response()->json([], 400); // Hoặc trả về giá trị mặc định
        }
        $query = DB::table('orders')
            ->select('order_status', DB::raw('COUNT(*) as total'))
            ->when($month, function ($q) use ($month) {
                $q->whereMonth('updated_at', $month);
            })
            ->when($year, function ($q) use ($year) {
                $q->whereYear('updated_at', $year);
            })
            ->groupBy('order_status')
            ->pluck('total', 'order_status');

        return response()->json($query);
    }
    public function topCustomers(Request $request)
    {
        $filter = $request->get('filter', 5); // Mặc định là tháng này

        // Xác định khoảng thời gian theo filter
        switch ($filter) {
            case 1: // Hôm nay
                $from = now()->startOfDay();
                $to = now()->endOfDay();
                break;
            case 2: // Hôm qua
                $from = now()->subDay()->startOfDay();
                $to = now()->subDay()->endOfDay();
                break;
            case 3: // Tuần này
                $from = now()->startOfWeek();
                $to = now()->endOfWeek();
                break;
            case 4: // Tuần trước
                $from = now()->subWeek()->startOfWeek();
                $to = now()->subWeek()->endOfWeek();
                break;
            case 5: // Tháng này
                $from = now()->startOfMonth();
                $to = now()->endOfMonth();
                break;
            case 6: // Tháng trước
                $from = now()->subMonth()->startOfMonth();
                $to = now()->subMonth()->endOfMonth();
                break;
            case 7: // Năm nay
                $from = now()->startOfYear();
                $to = now()->endOfYear();
                break;
            case 8: // Năm trước
                $from = now()->subYear()->startOfYear();
                $to = now()->subYear()->endOfYear();
                break;
            default:
                $from = now()->startOfMonth();
                $to = now()->endOfMonth();
        }

        // Truy vấn dữ liệu
        $data = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->select(
                'users.id as user_id',
                'users.name',
                DB::raw('SUM(orders.product_qty) as total_products')
            )
            ->where('orders.order_status', 'received')
            ->whereBetween('orders.updated_at', [$from, $to]) // <-- điều kiện thời gian
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_products')
            ->take(10)
            ->get();

        return response()->json($data);
    }
    // top 5 sản phẩm bán chạy
    public function showTopSelling(Request $request)
    {
        $filter = $request->get('filter', 5); // Mặc định là Tháng này

        // Xác định khoảng thời gian theo filter
        switch ($filter) {
            case 1: // Hôm nay
                $from = now()->startOfDay();
                $to = now()->endOfDay();
                break;
            case 2: // Hôm qua
                $from = now()->subDay()->startOfDay();
                $to = now()->subDay()->endOfDay();
                break;
            case 3: // Tuần này
                $from = now()->startOfWeek();
                $to = now()->endOfWeek();
                break;
            case 4: // Tuần trước
                $from = now()->subWeek()->startOfWeek();
                $to = now()->subWeek()->endOfWeek();
                break;
            case 5: // Tháng này
                $from = now()->startOfMonth();
                $to = now()->endOfMonth();
                break;
            case 6: // Tháng trước
                $from = now()->subMonth()->startOfMonth();
                $to = now()->subMonth()->endOfMonth();
                break;
            case 7: // Năm nay
                $from = now()->startOfYear();
                $to = now()->endOfYear();
                break;
            case 8: // Năm trước
                $from = now()->subYear()->startOfYear();
                $to = now()->subYear()->endOfYear();
                break;
            default:
                $from = now()->startOfMonth();
                $to = now()->endOfMonth();
        }

        // Truy vấn lấy 5 sản phẩm bán chạy nhất trong khoảng thời gian
        $topSellingProducts = OrderProduct::select(
            'order_products.product_id',
            DB::raw('SUM(order_products.qty) as total_sold'),
            DB::raw('SUM(order_products.qty * (order_products.unit_price + order_products.variant_total)) as total_revenue')
        )
            ->join('orders', 'orders.id', '=', 'order_products.order_id')
            ->where('orders.order_status', 'received') // lọc trạng thái đơn hàng
            ->whereBetween('orders.updated_at', [$from, $to])
            ->groupBy('order_products.product_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();
        // Lấy thông tin sản phẩm
        $topSellingProductsWithDetails = $topSellingProducts->map(function ($orderProduct) {
            $product = Product::find($orderProduct->product_id);

            return [
                'product_id' => $product->id,
                'name' => $product->name,
                'total_sold' => (int) $orderProduct->total_sold,
                'total_revenue' => (int) $orderProduct->total_revenue,
                'image' => asset($product->thumb_image),
            ];
        });

        return response()->json($topSellingProductsWithDetails);
    }
    public function getDailyRevenue(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');
        // Validate: month và year phải là số hợp lệ
        if (!is_numeric($month) || $month < 1 || $month > 12 || !is_numeric($year) || strlen($year) != 4) {
            return response()->json(['error' => 'Tháng hoặc năm không hợp lệ.'], 400);
        }

        try {
            // Số ngày trong tháng
            $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;

            // Lấy doanh thu mỗi ngày
            $revenues = DB::table('orders')
                ->select(
                    DB::raw('DAY(updated_at) as day'),
                    DB::raw('SUM(amount) as total')
                )
                ->whereYear('updated_at', $year)
                ->whereMonth('updated_at', $month)
                ->where('payment_status', 1)
                ->groupBy(DB::raw('DAY(updated_at)'))
                ->pluck('total', 'day');

            // Chuẩn bị dữ liệu trả về
            $labels = [];
            $data = [];

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $labels[] = 'Ngày ' . $day;
                $data[] = $revenues[$day] ?? 0;
            }

            return response()->json([
                'labels' => $labels,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Đã xảy ra lỗi xử lý dữ liệu.'], 500);
        }
    }
}
