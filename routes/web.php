<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiswaController;
// use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangKeluarController;
// use App\Http\Controllers\HomeController;

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

// Route::get('/', function () {
//     return view('welcome');
//     //return phpinfo();
// });


Route::get('/stembayo', function () {
    return "welcome to SIJA";
});

Route::get('/', function () {
    return view('welcome');
});
Route::resource('barang', BarangController::class);
Route::resource('barangmasuk', BarangMasukController::class);
Route::resource('barangkeluar', BarangKeluarController::class);
Route::resource('kategori', KategoriController::class);
// Route::resource('siswa',SiswaController::class