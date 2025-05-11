<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DnController;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\MatchingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderDeliveryController;
use App\Http\Controllers\PccController;

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


Route::get('/', [MatchingController::class, 'index'])->name('matching.index');
Route::get('/matching', [MatchingController::class, 'index'])->name('matching.index');
Route::post('/matching', [MatchingController::class, 'store'])->name('matching.store');
Route::post('/matching/store', [MatchingController::class, 'store'])->name('matching.store');
Route::post('/matching/unlock', [MatchingController::class, 'unlock'])->name('matching.unlock');
Route::post('/matching/reset-session', [MatchingController::class, 'resetSession'])->name('matching.reset');

Route::get('/transactions', [MatchingController::class, 'index'])->name('transactions.index');
Route::get('/transactions/data', [MatchingController::class, 'getTransactions'])->name('transactions.data');

Route::get('/export/transactions', [MatchingController::class, 'exportTransactions']);

Route::get('/pcc/upload', [PccController::class, 'index'])->name('pcc.upload');
Route::get('/pcc/data', [PccController::class, 'getPCCData'])->name('pcc.data');
Route::post('/pcc/upload', [PccController::class, 'upload'])->name('pcc.upload');
Route::get('/pcc/download/{filename}', [PccController::class, 'download'])->name('pcc.download');
