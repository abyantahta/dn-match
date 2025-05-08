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
Route::post('/matching/reset-session', [MatchingController::class, 'resetSession'])->name('matching.reset');

Route::get('/transactions', [MatchingController::class, 'index'])->name('transactions.index');
Route::get('/transactions/data', [MatchingController::class, 'getTransactions'])->name('transactions.data');

Route::get('/export/transactions/{plant}', [MatchingController::class, 'exportTransactions']);
// Route::get('/', function () {
Route::get('/dashboard', [DashboardController::class, 'index']);
Route::get('/dashboard/data', [DashboardController::class, 'getData'])->name('dashboard.data');
//     return view('welcome');
// });

Route::post('/order-deliveries/import', [OrderDeliveryController::class, 'importOrderDelivery'])->name('import.order.delivery');
// Route::get('/order-deliveries', [OrderDeliveryController::class, 'index'])->name('order.deliveries');
Route::get('/dn-page', [OrderDeliveryController::class, 'index']);
// Route::post('/import-order-delivery', [OrderDeliveryController::class, 'import'])->name('import.order.delivery');
// Route::get('/order-deliveries/data', [OrderDeliveryController::class, 'getData'])->name('order.deliveries.data');
Route::get('/getdn', [OrderDeliveryController::class, 'getDn'])->name('getdn');

Route::get('/demos', [DemoController::class, 'index'])->name('demos.index');
Route::post('/demos-import', [DemoController::class, 'importDemo'])->name('demos.import');
Route::get('/demos/data', [DemoController::class, 'getData'])->name('demos.data');


Route::get('/dn/adm/sap', [DnController::class, 'sap'])->name('dn.adm.sap');
// Route::get('/dn/adm/kep', [DnController::class, 'kep'])->name('dn.adm.kep');
// Route::get('/dn/adm/kap', [DnController::class, 'kap'])->name('dn.adm.kap');
Route::get('/dn/adm/sap/data', [DnController::class, 'getDnADMSAPData'])->name('dn.adm.sap.data');
// Route::get('/dn/adm/kep/data', [DnController::class, 'getDnADMKEPData'])->name('dn.adm.kep.data');
// Route::get('/dn/adm/kap/data', [DnController::class, 'getDnADMKAPData'])->name('dn.adm.kap.data');
Route::post('/dn/adm/sap/import', [DnController::class, 'importDnADM'])->name('dn.adm.sap.import');
// Route::post('/dn/adm/kep/import', [DnController::class, 'importDnADMKEP'])->name('dn.adm.kep.import');
// Route::post('/dn/adm/kap/import', [DnController::class, 'importDnADMKAP'])->name('dn.adm.kap.import');
Route::post('/dn/adm/save', [DnController::class, 'saveDnADM'])->name('dn.adm.save');

Route::get('/pcc/upload', [PccController::class, 'showUploadForm'])->name('pcc.upload.form');
Route::post('/pcc/upload', [PccController::class, 'upload'])->name('pcc.upload');
Route::get('/pcc/download/{filename}', [PccController::class, 'download'])->name('pcc.download');