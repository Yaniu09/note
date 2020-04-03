<?php

use Illuminate\Http\Request;
use App\Products;
use App\Type;
use App\SubType;
use App\Order;
use App\OrderProduct;

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

Route::get('/contact-us', function () {
    $types = Type::all();
    return view('contact', compact('types'));
});

Route::post('/contact-us', 'Contact@store');

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

    $sub_total = \Cart::session(session()->getId())->getSubTotal();
    $total = \Cart::session(session()->getId())->getTotal();

    return view('cart', compact('types', 'items', 'sub_total', 'total'));
});

Route::get('cart-items', function() {
    $items = \Cart::session(session()->getId())->getContent();
    return $items;
});

Route::get('checkout', function() {
    $types = Type::all();
    $items = \Cart::session(session()->getId())->getContent();

    $sub_total = \Cart::session(session()->getId())->getSubTotal();
    $total = \Cart::session(session()->getId())->getTotal();

    return view('checkout', compact('types', 'items', 'sub_total', 'total'));
});

Route::post('checkout', function(Request $request) {
    $types = Type::all();
    $items = \Cart::session(session()->getId())->getContent();

    $sub_total = \Cart::session(session()->getId())->getSubTotal();
    $total = \Cart::session(session()->getId())->getTotal();

    $order = new Order;
    $order->user_id = (auth()->user() !== null ? auth()->user()->id : '0');
    $order->first_name = $request->first_name;
    $order->last_name = $request->last_name;
    $order->company_name = $request->company_name;
    $order->address1 = $request->address1;
    $order->address2 = $request->address2;
    $order->island = $request->island;
    $order->zip = $request->zip;
    $order->email = $request->email;
    $order->phone = $request->phone;
    $order->ordernotes = $request->ordernotes;

    $order->payment_status = '1';
    $order->payment_method = 'BML';

    $order->tax = '0';
    $order->discount = '0';
    $order->subtotal = $sub_total;
    $order->total = $total;

    $order->save();

    foreach ($items as $item) {
        $orderProduct = new OrderProduct;
        $orderProduct->order_id = $order->id;
        $orderProduct->product_id = $item->associatedModel->id;
        $orderProduct->price = $item->price;
        $orderProduct->qty = $item->quantity;
        $orderProduct->total = $item->price * $item->quantity;
        $orderProduct->save();
    }

    return view('thankYou', compact('types'));
});
