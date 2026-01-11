<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\AuthController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [ItemController::class, 'index'])->name('index');
Route::get('/item/{id}', [ItemController::class, 'show'])->name('item.show');
// ログイン中のみコメントできるようにミドルウェアを設定
Route::post('/item/{item_id}/comment', [CommentController::class, 'store'])
    ->name('comment.store')
    ->middleware('auth');
// 商品購入画面の表示
Route::get('/purchase/{id}', [PurchaseController::class, 'index'])
    ->name('purchase')
    ->middleware('auth');
Route::get('/login', [AuthController::class, 'login'])->name('login');

