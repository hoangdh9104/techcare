<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AutoConfirmOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-confirm-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Tìm các đơn hàng đã giao hơn 1 phút nhưng chưa xác nhận nhận hàng
        $orders = DB::table('order_status_histories as osh')
            ->select('osh.order_id')
            ->where('osh.status', 'delivered')
            // ->where('osh.changed_at', '<=', now()->subMinutes(1))
            ->where('osh.changed_at', '<=', now()->subDays(3))
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('order_status_histories as r')
                    ->whereRaw('r.order_id = osh.order_id')
                    ->where('r.status', 'received');
            })
            ->groupBy('osh.order_id') // ❗ Chỉ group theo order_id là đủ
            ->get();

        foreach ($orders as $order) {
            // Cập nhật trạng thái trong bảng orders
            DB::table('orders')
                ->where('id', $order->order_id)
                ->update(['order_status' => 'received']);

            // Ghi nhận vào lịch sử trạng thái
            DB::table('order_status_histories')->insert([
                'order_id'   => $order->order_id,
                'status'     => 'received',
                'changed_at' => now(),
            ]);

            // In log chi tiết (nếu cần)
            $this->info("✅ Đã tự động xác nhận đơn hàng #{$order->order_id}");
        }

        $this->info("🎉 Đã tự động xác nhận " . $orders->count() . " đơn hàng.");
    }
}
