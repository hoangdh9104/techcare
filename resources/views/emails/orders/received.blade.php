@component('mail::message')
    # 🎉 Thank you for confirming your order!

    Hello **{{ $order->user->name }}**,
    We’re happy to know that you've successfully received your order.

    ---

    ### 🧾 Order Details:
    - Order ID: **#{{ $order->invocie_id }}**
    - Order Date: {{ $order->created_at->format('d/m/Y') }}
    - Confirmation Date: {{ $order->updated_at->format('d/m/Y H:i') }}
    - Status: ✅ **Completed**

    @component('mail::button', ['url' => route('user.orders.show', $order->id)])
        View Order Details
    @endcomponent

    ---

    We hope you’re satisfied with our products and service.
    If you have any feedback, please don’t hesitate to share it with us.

    ---

    Thanks,
    **Techcare**
@endcomponent
