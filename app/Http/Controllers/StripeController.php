<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;

class StripeController extends Controller
{
    //StripeCheckoutSession発行
    public function create_checkout_session(Request $request)
    {
        Stripe::setApiKey(config("services.stripe.secret_key"));

        $cart = $request->session()->get("cart", []);
        $userId = auth()->id();

        $line_items = [];
        $blogIds = [];

        foreach ($cart as $item) {
            $line_items[] = [
                "price" => $item["stripe_id"],
                "quantity" => 1,
            ];
            $blogIds[] = $item["id"];
        }

        try {
            $checkout_session = \Stripe\Checkout\Session::create([
                "payment_method_types" => ["card"],
                "line_items" => $line_items,
                "mode" => "payment",
                "success_url" => route("success"),
                "cancel_url" => route("cancel"),
                'payment_intent_data' => [
                    'metadata' => [
                        "user_id" => $userId,
                        "blog_ids" => json_encode($blogIds),
                    ],
                ],
            ]);

            return redirect()->route("stripe-redirect")->with(["checkout_id" => $checkout_session->id]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage(), 500]);
        }
    }

    //決済サイトリダイレクト
    public function stripeRedirect()
    {
        return view("stripe_redirect");
    }

    //注文成功処理
    public function success(Request $request)
    {
        //カート空にする
        $request->session()->put("cart", []);
        return view("success");
    }

    //注文失敗処理
    public function cancel()
    {
        return view("cancel");
    }
}
