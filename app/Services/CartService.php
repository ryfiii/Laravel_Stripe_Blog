<?php

namespace App\Services;

use Illuminate\Http\Request;

class CartService {

    //カート取得処理
    public function getCart(Request $request)
    {
        return $request->session()->get("cart", []);
    }

    //カート追加処理
    public function addToCart(Request $request, $blog)
    {

        $cart = $this->getCart($request);

        $cart[] = [
            "id" => $blog["id"],
            "stripe_id" => $blog["stripe_id"],
            "title" => $blog["title"],
            "details" => $blog["details"],
            "price" => $blog["price"],
            "image" => $blog["eyecatch"]["url"],
        ];

        $request->session()->put("cart", $cart);
    }

    //カート削除処理
    public function removeFromCart(Request $request, $itemId)
    {
        $cart = $this->getCart($request);

        $updatedCart = array_filter($cart, function ($item) use ($itemId) {
            return $item['id'] !== $itemId;
        });

        $request->session()->put('cart', array_values($updatedCart));
    }
}