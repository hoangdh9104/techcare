<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BusinessNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $cardUrl;

    public function __construct($order, $cardUrl)
    {
        $this->order = $order;
        $this->cardUrl = $cardUrl;
    }

    public function build()
    {
        return $this->subject('Refund Notification - Order #' . $this->order->invocie_id)
            ->view('emails.business_notification');
    }
}