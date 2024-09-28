<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\loginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Auth;

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

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('index');
    } else {
        return redirect()->route('login.form');
    }
});

Auth::routes();

Route::get('/login', function () {
    return view('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::get('/register', [RegisterController::class, 'showRegister'])->name('showRegister');
Route::post('/register', [RegisterController::class, 'postRegister'])->name('postRegister');
Route::group(['middleware' => 'auth'], function () {
    Route::resource('products', ProductController::class);
});

//GET /products → ProductController@index → products.index
//GET /products/create → ProductController@create → products.create
//POST /products → ProductController@store → products.store
//GET /products/{product} → ProductController@show → products.show
//GET /products/{product}/edit → ProductController@edit → products.edit
//PUT/PATCH /products/{product} → ProductController@update → products.update
//DELETE /products/{product} → ProductController@destroy → products.destroy

//Route::get('/product', [ProductController::class, 'product'])->name('product');
//Route::post('/productlist', [ProductlistController::class, 'productlist'])->name('postRegister');

