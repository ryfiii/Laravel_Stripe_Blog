<?php

namespace App\Http\Controllers;

use App\Services\BlogService;
use App\Services\MicroCmsService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $microCmsService;
    protected $blogService;

    //コンストラクタ
    public function __construct(MicroCmsService $microCmsService, BlogService $blogService)
    {
        $this->microCmsService = $microCmsService;
        $this->blogService = $blogService;
    }

    //ホーム画面
    public function index(Request $request)
    {
        $blogs = $this->microCmsService->getBlog();
        $cart = $request->session()->get("cart", []);

        // それぞれのブログについて、カート内に存在するかチェック
        foreach ($blogs['contents'] as &$blog) {
            $blog['isInCart'] = $this->blogService->isBlogCollect($cart, $blog['id']);
        }

        return view('home', compact('blogs'));
    }
}
