<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;

class ApiController extends Controller
{
    public function purchase(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);
        $product = Product::find($productId);

        if (!$product || $product->stock < $quantity) {
            return response()->json(['error' => '在庫が不足しています'], 400);
        }

        \DB::transaction(function () use ($product, $quantity) {
            $product->decrement('stock', $quantity);

            Sale::create([
                'product_id' => $product->id,
            ]);
        });

        return response()->json(['message' => '購入が成功しました'], 200);
    }
}
