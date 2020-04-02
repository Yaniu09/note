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

    $topPicks = Products::where('toppicks', '1')->get();
    $featuredProducts = Products::where('featured_product', '1')->get();

    return view('welcome',compact('types', 'products', 'topPicks', 'featuredProducts'));
});

Route::get('/admin', function () {
    return view('admin.admin.home');
});


Route::get('/admin/type', 'TypeController@index');
Route::post('/admin/type', 'TypeController@store');

Route::get('/admin/sub-type', 'SubTypeController@index');
Route::post('/admin/sub-type', 'SubTypeController@store');

Route::get('/admin/product', 'ProductsController@index');
Route::post('/admin/product', 'ProductsController@store');

Route::get('/admin/product/{product}/images', 'ProductsController@showImages');
Route::post('/admin/product/{product}/images/upload-images', 'ProductsController@storeImages');
Route::post('/admin/product/{product}/images/update-color', 'ProductsController@updateImageColor');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/add-to-cart/{productId}', function($productId) {
    $Product = Products::find($productId);
    $userID = auth()->user()->id;
    $rowId = $userID . $productId;


    \Cart::session($userID)->add(array(
        'id' => $rowId,
        'name' => $Product->name,
        'price' => $Product->reatil_price,
        'quantity' => 1,
        'attributes' => array(),
        'associatedModel' => $Product
    ));

    return redirect()->back();
});
