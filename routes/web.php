<?php

use App\Type;
use App\Products;

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
    $products = Products::all();
    $types = Type::all();
    return view('welcome',compact('types', 'products'));
});

Route::get('/admin', function () {
    return view('admin.admin.home');
});


Route::get('/admin/type', 'TypeController@index');
Route::post('/admin/type', 'TypeController@store');

Route::get('/admin/product', 'ProductsController@index');
Route::post('/admin/product', 'ProductsController@store');