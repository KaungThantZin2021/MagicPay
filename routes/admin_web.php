<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\PageController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\WalletController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Backend\AdminUserController;

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

// Route::prefix('admin')->namespace('Backend')->middleware('auth:admin_user')->group(function(){
//     Route::get('/',[PageController::class,'home'])->name('admin.home');
// });

// Admin                             ->namespace('Backend')
Route::prefix('admin')->name('admin.')->middleware('auth:admin_user')->group(function(){

    Route::get('/',[PageController::class,'home'])->name('home');

    // create destroy update index
    #admin-user
    Route::resource('admin-user', AdminUserController::class);// ....p 10
    Route::get('admin-user/datatable/ssd',[AdminUserController::class,'ssd']);

    #user
    Route::resource('user', UserController::class);
    Route::get('user/datatable/ssd',[UserController::class,'ssd']);

    #wallet
    Route::get('wallet',[WalletController::class,'index'])->name('wallet.index');
    Route::get('wallet/datatable/ssd',[WalletController::class,'ssd']);

    Route::get('/wallet/add/amount' ,[WalletController::class, 'addAmount']);
    Route::post('/wallet/add/amount/store' ,[WalletController::class, 'addAmountStore']);

    Route::get('/wallet/reduce/amount' ,[WalletController::class, 'reduceAmount']);
    Route::post('/wallet/reduce/amount/store' ,[WalletController::class, 'reduceAmountStore']);
});
