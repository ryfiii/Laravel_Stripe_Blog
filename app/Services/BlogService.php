<?php
namespace App\Services;

use App\Models\Purchase;

class BlogService 
{
    public function isBlogCollect($cart, $blogId)
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
}