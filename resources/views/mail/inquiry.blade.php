<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>New Product Inquiry</title>

<style>
body { font-family: Arial, sans-serif; background:#f5f5f5; margin:0; padding:0; color:#444; }
.container { max-width:600px; margin:0 auto; background:#fff; border:1px solid #e5e5e5; }
.header { background:#0d6efd; color:#fff; padding:25px; text-align:center; }
.header h1 { margin:0; font-size:22px; }
.content { padding:25px; }
.section-title { font-size:13px; font-weight:bold; margin-top:25px; margin-bottom:10px; color:#333; border-bottom:1px solid #e5e5e5; padding-bottom:6px; text-transform:uppercase; }
.info-row { margin-bottom:6px; font-size:13px; }
.label { font-weight:bold; color:#666; }
.products-table { width:100%; border-collapse:collapse; margin-top:10px; font-size:13px; }
.products-table th { text-align:left; padding:10px; border-bottom:2px solid #e5e5e5; background:#f9f9f9; }
.products-table td { padding:10px; border-bottom:1px solid #e5e5e5; }
.footer { background:#f9f9f9; padding:20px; text-align:center; font-size:12px; color:#999; border-top:1px solid #e5e5e5; }
.badge { display:inline-block; background:#ffc107; color:#000; padding:3px 8px; font-size:11px; border-radius:10px; }
</style>
</head>

<body>
<div class="container">

    <!-- Header -->
    <div class="header">
        <h1>📩 New Product Inquiry</h1>
        <p>A customer has submitted an inquiry</p>
    </div>

    <div class="content">

        <p>Hello Admin,</p>
        <p>A new product inquiry has been received. Please review the details below and contact the customer.</p>

        <!-- Inquiry Info -->
        <div class="section-title">Inquiry Details</div>

        <div class="info-row">
            <span class="label">Inquiry ID:</span> #{{ $order->id }}
        </div>

        <div class="info-row">
            <span class="label">Date:</span>
            {{ $order->created_at->format('F d, Y h:i A') }}
        </div>

        <div class="info-row">
            <span class="label">Status:</span>
            <span class="badge">New Inquiry</span>
        </div>

        <!-- Customer Info -->
        <div class="section-title">Customer Information</div>

        <div class="info-row"><span class="label">Name:</span> {{ $order->name }}</div>
        <div class="info-row">
            <span class="label">Email:</span>
            <a href="mailto:{{ $order->email }}">{{ $order->email }}</a>
        </div>
        <div class="info-row"><span class="label">Phone:</span> {{ $order->phone }}</div>

        <!-- Message -->
        <div class="section-title">Customer Message</div>
        <div style="background:#f9f9f9; padding:12px; border-radius:4px; font-size:13px; color:#333;">
            {{ $order->address }}
        </div>

        <!-- Products -->
        <div class="section-title">Interested Products</div>

        <table class="products-table">
            <thead>
                <tr>
                    <th style="width:60%">Product</th>
                    <th style="width:20%">SKU</th>
                    <th style="width:20%">Qty</th>
                </tr>
            </thead>
            <tbody>
                @forelse($order->items as $item)
                    <tr>
                        <td>{{ $item->product->product_title ?? 'Product' }}</td>
                        <td>{{ $item->product->sku ?? 'N/A' }}</td>
                        <td>{{ $item->quantity }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align:center; padding:15px;">
                            No products found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Action Note -->
        <div class="section-title">Action Required</div>
        <p style="font-size:13px; color:#333;">
            Please contact the customer with pricing, availability, and further details regarding this inquiry.
        </p>

    </div>

    <!-- Footer -->
    <div class="footer">
        <p>This is an automated inquiry notification from your website.</p>
        <p>© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>

</div>
</body>
</html>
