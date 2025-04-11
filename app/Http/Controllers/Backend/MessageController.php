<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(){
        $userId = auth()->user()->id;

        // Lấy danh sách user(sender_id) đã chat với seller, không lấy lại id người gửi(sender_id)
        $chatUsers = Chat::with('senderProfile')->select(['sender_id'])
            ->where('receiver_id', $userId)
            ->where('sender_id', '!=', $userId)
            ->groupBy('sender_id')
            ->get();

        return view('admin.messenger.index', compact('chatUsers'));
    }
}
