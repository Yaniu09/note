<?php

use Illuminate\Http\Request;
use App\Products;
use App\Type;
use App\SubType;

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

Route::get('collections/{type}/{subType}/{product}', function (Type $type, SubType $subType, Products $product) {
    $types = Type::all();
    return view('product.single', compact('types', 'product'));
});

Route::post('/add-to-cart', function(Request $request) {
    $product = Products::where('id', $request->product_id)->with(['images'])->first();
    // return $product;

    \Cart::session(session()->getId())->add(array(
        'id' => $product->id . '0001',
        'name' => $product->name,
        'price' => $product->retail_price,
        'quantity' => $request->qty,
        'attributes' => array(),
        'associatedModel' => $product,
        'associatedModelWith' => ['images']
    ));

    return response()->json([
        'message' => 'Item added to cart successfully'
    ]);;
});

Route::get('cart', function() {
    $types = Type::all();
    $items = \Cart::session(session()->getId())->getContent();

    return view('cart', compact('types', 'items'));
});

Route::get('cart-items', function() {
    $items = \Cart::session(session()->getId())->getContent();
    return $items;
});
