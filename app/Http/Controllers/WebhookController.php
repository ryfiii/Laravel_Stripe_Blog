<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Webhook;

class WebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $event = null;

        // Stripeのシークレットキーとエンドポイントのシークレットを設定
        Stripe::setApiKey(config("services.stripe.secret_key"));
        $endpointSecret = config("services.stripe.webhook_key");

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\UnexpectedValueException $e) {
            // 無効なペイロード
            http_response_code(400);
            exit();
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // 無効な署名
            http_response_code(400);
            exit();
        }

        // payment_intent.succeededイベントを処理
        if ($event->type == 'payment_intent.succeeded') {
            $paymentIntent = $event->data->object;
            $this->handlePaymentSuccess($paymentIntent);
        }

        return response()->json(['received' => true]);
    }


    // 支払い(購入)成功時の処理
    protected function handlePaymentSuccess($paymentIntent) {

        Log::info($paymentIntent);

        $pi = $paymentIntent;

        $purchase = new Purchase();

        $purchase->user_id = $pi->metadata->user_id;
        $blogIds = json_decode($pi->metadata->blog_ids);

        foreach ($blogIds as $blogId) {
            $purchase = new Purchase();
            $purchase->user_id = $pi->metadata->user_id;
            $purchase->blog_id = $blogId;
            $purchase->stripe_payment_id = $pi->id;
            $purchase->purchased_at = now();
            $purchase->save();
        }
    }
}
