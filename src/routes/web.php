<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisteredController;
use App\Http\Controllers\MypageController;


Route::get('/', [ItemController::class, 'index'])->name('index');
Route::get('/item/{id}', [ItemController::class, 'show'])->name('item.show');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [RegisteredController::class, 'create'])->name('register');
Route::post('/register', [RegisteredController::class, 'store'])->name('register.store');


Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/email/verify', function () {
        return view('auth.verify_email');
    })->name('verification.notice');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', '認証メールを送信しました！');
    })->middleware('throttle:6,1')->name('verification.send');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('profile.edit');
    })->middleware('signed')->name('verification.verify');

    Route::middleware('verified')->group(function () {

        Route::get('/mypage', [MypageController::class, 'index'])->name('mypage');

        Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::post('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');

        Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'editAddress'])->name('address.edit');
        Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'updateAddress'])->name('address.update');

        Route::get('/sell', [ItemController::class, 'create'])->name('item.create');
        Route::post('/sell', [ItemController::class, 'store'])->name('item.store');
        Route::get('/purchase/{item_id}', [PurchaseController::class, 'index'])->name('purchase');
        Route::post('/purchase/{item_id}', [PurchaseController::class, 'store'])->name('purchase.store');
        Route::get('/purchase/success/{item_id}', [PurchaseController::class, 'success'])->name('purchase.success');
        Route::post('/item/{item_id}/comment', [CommentController::class, 'store'])->name('comment.store');
        Route::put('/comment/{comment_id}', [CommentController::class, 'update'])->name('comment.update');
        Route::delete('/comment/{comment_id}', [CommentController::class, 'destroy'])->name('comment.destroy');
        Route::post('/favorite/{item_id}', [ItemController::class, 'favorite'])->name('favorite.store');
        Route::delete('/favorite/{item_id}', [ItemController::class, 'unfavorite'])->name('favorite.destroy');
    });
});
