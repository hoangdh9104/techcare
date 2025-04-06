<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Mail\BusinessNotification;

trait TrelloTrait
{
    protected function createTrelloRefundTask($order, $reason)
    {
        // Đảm bảo rằng lý do được truyền vào khi gọi hàm
        $cardTitle = "Refund for Order #{$order->invocie_id}";
        $cardDesc = "User: {$order->user->name} ({$order->user->email})\n"
            . "Phone: " . ($order->user->phone ?? 'Phone number not found') . "\n"
            . "Payment Method: {$order->payment_method}\n"
            . "Amount: {$order->amount} {$order->currency_icon} \n"
            . "Reason: {$reason}";  // Sử dụng lý do được truyền vào

        // Tạo công việc trên Trello
        $response = Http::post("https://api.trello.com/1/cards", [
            // 'key'    => '240d7547081422a3478f673a7fbffdbe',
            // 'token'  => 'ATTAd339724f00c8052df016af20a9e931402ecff7dad360357d1cb12832376b38c961E7EB9C',
            // 'idList' => '67f12e7a1732288377a67623',
            'key'    => env('TRELLO_API_KEY'),
            'token'  => env('TRELLO_TOKEN'),
            'idList' => env('TRELLO_REFUND_LIST_ID'),
            'name'   => $cardTitle,
            'desc'   => $cardDesc
        ]);

        // Lấy dữ liệu phản hồi từ API
        $responseBody = $response->json();

        // Log toàn bộ phản hồi để kiểm tra
        \Log::info('Trello API response: ' . json_encode($responseBody));
        // Kiểm tra xem card có được tạo thành công không
        if (isset($responseBody['id'])) {
            $cardUrl = "https://trello.com/c/{$responseBody['id']}"; // Lấy URL của card

            try {
                // Gửi email thông báo cho bộ phận kinh doanh
                Mail::to('nguyenquangthuong23082004@gmail.com')  // Địa chỉ email của bộ phận kinh doanh
                    ->send(new BusinessNotification($order, $cardUrl));

                \Log::info('Created Trello Card: ' . $responseBody['id']);
            } catch (\Exception $e) {
                // Nếu có lỗi khi gửi email, log lỗi này
                \Log::error('Failed to send email: ' . $e->getMessage());
            }
        } else {
            // Nếu không tạo được card, log lỗi từ Trello API
            \Log::error('Failed to create Trello card. Response: ' . json_encode($responseBody));
        }
    }
}
