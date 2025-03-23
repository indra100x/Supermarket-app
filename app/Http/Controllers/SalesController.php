<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Stock;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{
    public function index()
    {
        return response()->json(Sale::with('saleItems.product')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'cash_register_id' => 'required|exists:cash_registers,id',
            'payment_method' => 'required|in:cash,card,mobile',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1'
        ]);

        $sale = Sale::create([
            'user_id' => Auth::id(),
            'cash_register_id' => $request->cash_register_id,
            'total_price' => 0,
            'payment_method' => $request->payment_method
        ]);

        $totalPrice = 0;
        foreach ($request->items as $item) {
            $productStock = Stock::where('product_id', $item['product_id'])->first();
            if ($productStock->quantity < $item['quantity']) {
                return response()->json(['error' => 'Not enough stock'], 400);
            }

            $price = $productStock->product->price * $item['quantity'];
            $totalPrice += $price;

            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $price
            ]);

            $productStock->decrement('quantity', $item['quantity']);
        }

        $sale->update(['total_price' => $totalPrice]);

        return response()->json(['message' => 'Sale recorded successfully', 'sale' => $sale]);
    }
}
