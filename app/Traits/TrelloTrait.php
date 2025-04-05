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
            'key'    => env('TRELLO_API_KEY'),
            'token'  => env('TRELLO_TOKEN'),
            'idList' => env('TRELLO_REFUND_LIST_ID'),
            'name'   => $cardTitle,
            'desc'   => $cardDesc
        ]);

        $responseBody = $response->json();
        dd($responseBody)
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