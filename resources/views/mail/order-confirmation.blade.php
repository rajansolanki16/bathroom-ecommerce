<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f5f5f5;
            color: #444;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            border: 1px solid #e5e5e5;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 20px;
            text-align: center;
            color: white;
        }

        .header h1 {
            font-size: 28px;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .header p {
            font-size: 14px;
            opacity: 0.9;
        }

        .content {
            padding: 30px 20px;
        }

        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
            color: #333;
        }

        .section-title {
            font-size: 14px;
            font-weight: 700;
            color: #333;
            margin-top: 25px;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }

        .order-info {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            font-size: 13px;
        }

        .order-info-row {
            display: table-row;
        }

        .info-label {
            display: table-cell;
            font-weight: 600;
            color: #666;
            padding: 8px 0;
            width: 40%;
            vertical-align: top;
        }

        .info-value {
            display: table-cell;
            color: #333;
            padding: 8px 0;
            padding-left: 15px;
        }

        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 13px;
        }

        .products-table thead {
            background-color: #f9f9f9;
            border-top: 1px solid #e5e5e5;
            border-bottom: 2px solid #e5e5e5;
        }

        .products-table th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #e5e5e5;
        }

        .products-table td {
            padding: 12px;
            border-bottom: 1px solid #e5e5e5;
            color: #333;
        }

        .products-table tbody tr:last-child td {
            border-bottom: none;
        }

        .product-name {
            font-weight: 500;
            color: #333;
        }

        .product-sku {
            font-size: 12px;
            color: #999;
            margin-top: 3px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .totals-section {
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 13px;
            border-bottom: 1px solid #e5e5e5;
        }

        .total-row.final {
            background-color: #f9f9f9;
            padding: 12px;
            margin: 0 -12px;
            font-size: 16px;
            font-weight: 700;
            color: #667eea;
            border-top: 2px solid #667eea;
            border-bottom: none;
        }

        .total-label {
            color: #666;
        }

        .total-value {
            font-weight: 600;
            color: #333;
        }

        .discount {
            color: #27ae60;
        }

        .billing-shipping {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .billing-col {
            display: table-cell;
            width: 48%;
            vertical-align: top;
            font-size: 13px;
        }

        .billing-col:first-child {
            padding-right: 30px;
        }

        .address-block {
            background-color: #f9f9f9;
            padding: 12px;
            border-radius: 4px;
        }

        .address-block strong {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
        }

        .address-block p {
            margin: 4px 0;
            color: #666;
            line-height: 1.6;
        }

        .footer {
            background-color: #f9f9f9;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #e5e5e5;
        }

        .footer p {
            margin: 5px 0;
        }

        .divider {
            height: 1px;
            background-color: #e5e5e5;
            margin: 20px 0;
        }

        .badge {
            display: inline-block;
            background-color: #667eea;
            color: white;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background-color: #f39c12;
        }

        .status-completed {
            background-color: #27ae60;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>✓ Order Received</h1>
            <p>Thank you for the order! Here are the details.</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                <p>Hello Admin,</p>
                <p style="margin-top: 10px;">A new order has been placed by a customer. Please find the details below.</p>
            </div>

            <!-- Order Information -->
            <div class="section-title">Order Details</div>
            <div class="order-info">
                <div class="order-info-row">
                    <span class="info-label">Order Number:</span>
                    <span class="info-value">#{{ $order->id }}</span>
                </div>
                <div class="order-info-row">
                    <span class="info-label">Order Date:</span>
                    <span class="info-value">{{ $order->created_at->format('F d, Y \a\t h:i A') }}</span>
                </div>
                <div class="order-info-row">
                    <span class="info-label">Status:</span>
                
                    <span class="info-value">
                        <span class="badge status-{{ strtolower($order->status->label()) }}">
                            {{ $order->status->label() }}
                        </span>
                    </span>

                </div>
            </div>

            <!-- Customer Information -->
            <div class="section-title">Customer Information</div>
            <div class="order-info">
                <div class="order-info-row">
                    <span class="info-label">Name:</span>
                    <span class="info-value">{{ $order->name }}</span>
                </div>
                <div class="order-info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value"><a href="mailto:{{ $order->email }}" style="color: #667eea; text-decoration: none;">{{ $order->email }}</a></span>
                </div>
                <div class="order-info-row">
                    <span class="info-label">Phone:</span>
                    <span class="info-value">{{ $order->phone }}</span>
                </div>
            </div>

            <!-- Order Items -->
            <div class="section-title">Order Items</div>
            <table class="products-table">
                <thead>
                    <tr>
                        <th style="width: 40%;">Product</th>
                        <th style="width: 15%;">Price</th>
                        <th style="width: 15%;">Quantity</th>
                        <th style="width: 20%; text-align: right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($order->items as $item)
                    <tr>
                        <td>
                            <div class="product-name">{{ $item->product->product_title ?? 'Product' }}</div>
                            <div class="product-sku">SKU: {{ $item->product->sku ?? 'N/A' }}</div>
                        </td>
                        <td class="text-right">${{ number_format($item->price, 2) }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right"><strong>${{ number_format($item->price * $item->quantity, 2) }}</strong></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center" style="padding: 20px;">No items in this order</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Order Totals -->
            <div class="totals-section">
                <div class="total-row">
                    <span class="total-label">Subtotal:</span>
                    <span class="total-value">${{ number_format($order->subtotal, 2) }}</span>
                </div>
                @if($order->discount > 0)
                <div class="total-row">
                    <span class="total-label discount">Discount Applied:</span>
                    <span class="total-value discount">-${{ number_format($order->discount, 2) }}</span>
                </div>
                @endif
                <div class="total-row final">
                    <span>Total Amount:</span>
                    <span>${{ number_format($order->total, 2) }}</span>
                </div>
            </div>

            <!-- Billing Address -->
            <div class="section-title">Billing & Shipping Address</div>
            <div class="billing-shipping">
                <div class="billing-col">
                    <div class="address-block">
                        <strong>Billing Address</strong>
                        <p>{{ $order->name }}</p>
                        <p>{{ $order->address }}</p>
                        <p>📧 {{ $order->email }}</p>
                        <p>📱 {{ $order->phone }}</p>
                    </div>
                </div>
                <div class="billing-col">
                    <div class="address-block">
                        <strong>Shipping Address</strong>
                        <p>{{ $order->name }}</p>
                        <p>{{ $order->address }}</p>
                        <p>📧 {{ $order->email }}</p>
                        <p>📱 {{ $order->phone }}</p>
                    </div>
                </div>
            </div>

            @if($order->coupon_id)
            <div class="section-title">Coupon Applied</div>
            <div style="background-color: #f0f7ff; padding: 12px; border-radius: 4px; border-left: 4px solid #667eea;">
                <p style="font-size: 13px; color: #333;">
                    <strong>Coupon Discount:</strong> ${{ number_format($order->discount, 2) }}
                </p>
            </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Action Required:</strong> Please review this order and proceed with processing.</p>
            <div class="divider" style="background-color: #ddd; margin: 15px 0;"></div>
            <p>This is an automated message from your e-commerce system. Please do not reply to this email.</p>
            <p style="margin-top: 10px; font-size: 11px; color: #bbb;">
                © {{ date('Y') }} {{ config('app.name', 'Bathroom E-Commerce') }}. All rights reserved.
            </p>
        </div>
    </div>
</body>

</html>