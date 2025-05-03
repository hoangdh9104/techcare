<?php

namespace App\Http\Controllers\Backend;

use App\Events\MessageEvent;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $userId = auth()->user()->id;

        // Lấy danh sách user(sender_id) đã chat với seller, không lấy lại id người gửi(sender_id)
        $chatUsers = Chat::with('senderProfile')->select(['sender_id'])
            ->where('receiver_id', $userId)
            ->where('sender_id', '!=', $userId)
            ->groupBy('sender_id')
            ->get();

        return view('admin.messenger.index', compact('chatUsers'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => ['required'],
            'receiver_id' => ['required'],
        ]);

        // Tạo mới đoạn chat
        $message = new Chat();
        $message->sender_id = auth()->user()->id;
        $message->receiver_id = $request->receiver_id;
        $message->message = $request->message;
        $message->save();

        // Gửi sự kiện đến client
        broadcast(new MessageEvent($message->message, $message->receiver_id, $message->created_at));

        return response(['status' => 'success', 'message' => 'Message sent successfully']);
    }

    public function getMessages(Request $request)
    {
        $senderId = auth()->user()->id;
        $receiverId = $request->receiver_id;
        // Lấy tất cả các tin nhắn giữa sender_id và receiver_id
        $messages = Chat::whereIn('sender_id', [$senderId, $receiverId])
            ->whereIn('receiver_id', [$senderId, $receiverId])
            ->orderBy('created_at', 'asc')
            ->get();
            
        // Đánh dấu tin nhắn đã đọc
        Chat::where(['sender_id' => $receiverId, 'receiver_id' => $senderId])->update(['seen' => 1]);
        return response($messages);
    }
}
