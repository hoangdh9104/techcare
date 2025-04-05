<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Refund Task Created</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        h1 {
            color: #4CAF50;
            text-align: center;
        }

        .email-container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 20px;
            max-width: 600px;
            margin: 0 auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .email-container ul {
            list-style-type: none;
            padding: 0;
        }

        .email-container ul li {
            margin: 8px 0;
            padding: 8px;
            background-color: #f9f9f9;
            border-radius: 4px;
        }

        .email-container ul li strong {
            color: #4CAF50;
        }

        .email-container p {
            font-size: 16px;
            line-height: 1.5;
        }

        .button {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            text-align: center;
        }

        .button:hover {
            background-color: #45a049;
        }

        .footer {
            font-size: 12px;
            text-align: center;
            color: #777;
            margin-top: 40px;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <h1>New Refund Task Created</h1>
        <p>A new refund task has been created for order #{{ $order->invoice_id }}.</p>
        <p><strong>Order Details:</strong></p>
        <ul>
            <li><strong>User:</strong> {{ $order->user->name }} ({{ $order->user->email }})</li>
            <li><strong>Phone:</strong> {{ $order->user->phone ?? 'No phone number' }}</li>
            <li><strong>Amount:</strong> {{ $order->amount }} {{ $order->currency_icon }}</li>
            <li><strong>Reason for Refund:</strong> {{ $reason }}</li>
        </ul>
        <p>Click the button below to view the task on Trello:</p>
        <a href="{{ $cardUrl }}" class="button">View on Trello</a>
        <p class="footer">If you have any questions, feel free to reach out to us.</p>
    </div>
</body>

</html>
