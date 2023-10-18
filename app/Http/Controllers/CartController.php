<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    //カート画面
    public function cart(Request $request)
    {
        $cart = $this->cartService->getCart($request);
        return view("cart", compact("cart"));
    }

    //カート追加処理
    public function cartAdd(Request $request)
    {
        $blog = json_decode($request->blogData, true);
        $this->cartService->addToCart($request, $blog);
        return redirect()->route("index")->with(["msg" => "カートに追加しました！"]);
    }

    //カート削除処理
    public function cartDelete(Request $request)
    {
        $itemId = $request->input('item_id');
        $this->cartService->removeFromCart($request, $itemId);
        return redirect()->route("cart");
    }
}
