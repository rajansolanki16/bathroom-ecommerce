<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\ProductReview;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating'     => 'required|integer|min:1|max:5',
            'review'     => 'nullable|string|max:1000',
        ]);

        // ✅ Only purchased users
        if (!OrderItem::hasPurchased($request->product_id)) {
            abort(403, 'You have not purchased this product.');
        }

        // ✅ Prevent duplicate review for same order
        $orderItem = OrderItem::where('product_id', $request->product_id)
            ->whereHas('order', function ($q) {
                $q->where('user_id', auth()->id())
                  ->where('status', 'completed');
            })
            ->latest()
            ->first();

        ProductReview::updateOrCreate(
            [
                'user_id'    => auth()->id(),
                'product_id' => $request->product_id,
                'order_id'   => $orderItem->order_id,
            ],
            [
                'rating' => $request->rating,
                'review' => $request->review,
            ]
        );

        return back()->with('success', 'Thank you for your review!');
    }
}
