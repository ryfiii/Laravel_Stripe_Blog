<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Services\MicroCmsService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;

class MainController extends Controller
{
    protected $microCmsService;

    public function __construct(MicroCmsService $microCmsService)
    {
        $this->microCmsService = $microCmsService;
    }

    //ホーム画面
    public function index(Request $request)
    {
        $blogs = $this->microCmsService->getBlog();
        $cart = $request->session()->get("cart", []);

        // それぞれのブログについて、カート内に存在するかチェック
        foreach ($blogs['contents'] as &$blog) {
            $blog['isInCart'] = $this->isCollect($cart, $blog['id']);
        }

        return view('home', compact('blogs'));
    }

    //カートかDBに追加されてないかチェック
    public function isCollect($cart, $blogId)
    {
        $userId = auth()->id();

        // DBのチェック
        $purchase = Purchase::where('user_id', $userId)
            ->where('blog_id', $blogId)
            ->first();
        if ($purchase) {
            return 1;  // DBに追加されていた場合
        }

        // カートのチェック
        foreach ($cart as $item) {
            if ($item["id"] === $blogId) {
                return 2;  // セッションカートに追加されていた場合
            }
        }

        return 0;  // どちらにも追加されていなかった場合
    }


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

        Log::info('User ID: ' . $userId);
        Log::info('Blog IDs: ' . json_encode($blogIds));

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
        } catch (Exception $e) {
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
