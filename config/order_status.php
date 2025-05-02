<?php

return [

    // 'order_status_admin' => [
    //     'pending' => [
    //         'status' => 'Pending',
    //         'details' => 'Your order is currently pending'
    //     ],
    //     'processed_and_ready_to_ship' => [
    //         'status' => 'Processed and ready to ship',
    //         'details' => 'Your pacakge has been processed and will be with our delivery parter soon'
    //     ],
    //     'dropped_off' => [
    //         'status' => 'Dropped Off',
    //         'details' => 'Your package has been dropped off by the seller'
    //     ],
    //     'shipped' => [
    //         'status' => 'Shipped',
    //         'details' => 'Your package has arrived at our logistics facility'
    //     ],
    //     // 'out_for_delivery' => [
    //     //     'status' => 'Out For Delivery',
    //     //     'details' => 'Our delivery partner will attempt to delivery your package'
    //     // ],
    //     'delivered' => [
    //         'status' => 'Delivered',
    //         'details' => 'Delivered'
    //     ],
    //     'received' => [
    //         'status' => 'Received',
    //         'details' => 'The customer has received the package successfully'
    //     ],
    //     'canceled' => [
    //         'status' => 'Canceled',
    //         'details' => 'Canceled'
    //     ]

    // ],
    'order_status_admin' => [
        'pending' => [
            'status' => 'Đang chờ xử lý',
            'details' => 'Đơn hàng đang chờ xử lý.'
        ],

        'processed_and_ready_to_ship' => [
            'status' => 'Đã xác nhận ',
            'details' => 'Đơn hàng đã được xử lý .'
        ],
        'dropped_off' => [
            'status' => 'Đã đóng gói',
            'details' => 'Gói hàng của bạn đã được người bán giao đi'
        ],
        'shipped' => [
            'status' => 'Người giao đã lấy',
            'details' => 'Đơn hàng đã được giao cho đối tác.'
        ],
        'delivered' => [
            'status' => 'Đã gửi hàng cho khách',
            'details' => 'Đơn hàng đã được giao bởi đối tác.'
        ],
        'received' => [
            'status' => 'Đã nhận',
            'details' => 'Khách hàng đã nhận đơn hàng'
        ],
        'canceled' => [
            'status' => 'Hủy đơn',
            'details' => 'Đơn hàng đã bị hủy.'
        ]
    ],

    'order_status_vendor' => [
        'pending' => [
            'status' => 'Pending',
            'details' => 'Your order is currently pending'
        ],
        'processed_and_ready_to_ship' => [
            'status' => 'Processed and ready to ship',
            'details' => 'Your pacakge has been processed and will be with our delivery parter soon'
        ]
    ]
];
