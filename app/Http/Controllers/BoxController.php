<?php

namespace App\Http\Controllers;

use App\Services\MicroCmsService;
use App\Models\Purchase;
use Illuminate\Http\Request;

class BoxController extends Controller
{
    protected $microCmsService;

    //コンストラクタ
    public function __construct(MicroCmsService $microCmsService) {
        $this->microCmsService = $microCmsService;
    }

    //Box閲覧処理
    public function box(){
        $blog = $this->microCmsService->getBlog();
        $userId = auth()->id();
        $matchedPurchases = Purchase::getPuchaseBlogs($blog, $userId);

        $blogs = [];
        foreach ($matchedPurchases as $item) {
            $blog = $this->microCmsService->getBlogById($item["blog_id"]);
            $blogs[] = $blog;
        }

        return view("box", compact("blogs"));
    }

    //ブログ閲覧処理
    public function blog($blog){
        $blog = $this->microCmsService->getBlogById($blog);
        return view("blog", compact("blog"));
    }
}
