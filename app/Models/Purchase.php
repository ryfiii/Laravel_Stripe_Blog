<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "blog_id",
        "stripe_checkout_id",
        "purchased_at",
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //Purchaseテーブルからログインユーザーが購入した履歴を取得
    public static function getPuchaseBlogs($blogs, $userId) {
        $matchedPurchases = [];

        foreach ($blogs["contents"] as $blog) {
            $blogId = $blog["id"];

            $purchase = Purchase::where("user_id", $userId)
                ->where("blog_id", $blogId)
                ->orderBy("purchased_at", "desc")
                ->first();

            if ($purchase) {
                $matchedPurchases[] = $purchase;
            }
        }
        return $matchedPurchases;
    }
}
