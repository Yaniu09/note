<?php

namespace App\Http\Controllers;

use App\Products;
use App\Type;
use App\ProductPhotos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Mohamedathik\PhotoUpload\Upload;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $colors = ProductPhotos::all();
        $types = Type::all();
        $products = Products::all();
        return view('admin.admin.products-add',compact('products', 'types', 'colors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(request(), [
            'name' => 'required',
            'rprice' => 'required'
        ]);
       

        $product = Products::create([
            'name' => request('name'),
            'rprice' => request('rprice'),
            'wprice' => request('wprice'),
            'type_id' => request('type_id'),
            'fproduct' => request('fproduct'),
            'toppicks' => request('toppicks'),
           // 'user_id' => auth()->id(),
        ]);


        $files = $request->image;
        $i = 0;
        foreach ($files as $file) {

            $i++;

            $m = ($i == '1') ? '1' : '0';

            $file_name = $file->getClientOriginalName();

            $location = "/images";

            $url_original = Upload::upload_original($file, $file_name, $location);

            $url_thumbnail = Upload::upload_thumbnail($file, $file_name, $location);
            
            ProductPhotos::create([
                'product_id' => $product->id,
                'main' => $m,
                'color' => request('color'),
                'url_original' => $url_original,
                'url_thumbnail' => $url_thumbnail,
            ]);

                
            }

        return redirect('admin/product')->with('alert-success', 'Successfully added a new Product');
            
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function show(Products $products)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function edit(Products $products)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Products $products)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function destroy(Products $products)
    {
        //
    }
}
