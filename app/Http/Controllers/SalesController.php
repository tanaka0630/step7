<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Sales;

class SalesController extends Controller
{
    public function purchase(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);

        $product = Products::find($productId);
        //ここに関しては後でリレーションができてるか確認する


        //商品が存在しない、または在庫が不足している場合のバリデーションを行う。
        if (!$product) {
            return response()->json(['message' => '商品が存在しません'], 404);
        }
        if ($product->stock < $quantity) {
            return response()->json(['message' => '在庫が足りません'], 400);
        }

        //在庫を減少させる
        $product->stock -= $quantity;
        $product->save();

        //Sales tableに商品idと購入日時を記録する(created_at,updated_atなどは自動入力なので不要)
        $sale = new Sales([
            'product_id' => $productId,
        ]);

        $sale->save();
    

        return response()->json(['message' => '購入成功']);
    }
}
