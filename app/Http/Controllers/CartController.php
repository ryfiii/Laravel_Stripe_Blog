<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    //カート画面
    public function cart(Request $request)
    {

        $cart = $request->session()->get("cart", []);
        return view("cart", compact("cart"));
    }

    //カート追加処理
    public function cartAdd(Request $request)
    {
        $blog = json_decode($request->blogData, true);

        $cart = $request->session()->get("cart", []);

        $cart[] = [
            "id" => $blog["id"],
            "stripe_id" => $blog["stripe_id"],
            "title" => $blog["title"],
            "details" => $blog["details"],
            "price" => $blog["price"],
            "image" => $blog["eyecatch"]["url"],
        ];

        $request->session()->put("cart", $cart);

        return redirect()->route("index")->with(["msg" => "カートに追加しました！"]);
    }

    //カート削除処理
    public function cartDelete(Request $request)
    {
        $itemId = $request->input('item_id');
        $cart = $request->session()->get('cart', []);

        $updatedCart = array_filter($cart, function ($item) use ($itemId) {
            return $item['id'] !== $itemId;
        });

        $request->session()->put('cart', array_values($updatedCart));

        return redirect()->route("cart");
    }
}
