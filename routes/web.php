<?php

use App\Http\Controllers\BoxController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if(Auth::check()){ return redirect("/home"); }
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    //ホーム
    Route::get('/home', [MainController::class, 'index'])->name('index');

    //カート
    Route::get('/cart', [MainController::class, 'cart'])->name('cart');
    Route::post("/cartadd", [MainController::class, "cartAdd"])->name("cartadd");
    Route::post("/cartdelete", [MainController::class, "cartDelete"])->name("cartdelete");

    //注文
    Route::post("/create-checkout-session", [MainController::class, "create_checkout_session"])->name("create-checkout-session");
    Route::get("/stripe-redirect", [MainController::class, "stripeRedirect"])->name("stripe-redirect");
    Route::get("/success", [MainController::class, "success"])->name("success");
    Route::get("/cancel", [MainController::class, "cancel"])->name("cancel");
    
    //Box
    Route::get('/box', [BoxController::class, 'box'])->name('box');
    Route::post("/box/{blog}", [BoxController::class, "blog"])->name("blog");
});

//WebHook
Route::post("/stripe/webhook", [WebhookController::class, "handleWebhook"]);