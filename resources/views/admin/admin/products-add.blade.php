@extends('admin.layout.admin-home')

@section('content')
<div class="container">
<h3>Add Products</h3>
<hr>
</div>

<div class="container">

<div class="flash-message">
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
            @if(Session::has('alert-' . $msg))

            <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
            
            @endif
        @endforeach
</div>

<form method="POST" enctype="multipart/form-data" action="/admin/product">
@csrf
  <div class="form-group">
    <label for="name">Product Name *</label>
    <input type="text" class="form-control" id="name" name="name"  required>
  </div>
  
  <div class="form-group">
      <label for="type_id">Choose a product catergory *</label>
      <select class="form-control" id="type_id" name="type_id" required >
      <option selected disabled hidden>Select a catergory</option>
      @foreach($types as $type)
          <option  value="{{ $type->id }}">{{ $type->name }}</option>
      @endforeach
      </select> 
  </div>

  <div class="form-group">
    <label for="rprice">Retail price *</label>
    <input type="text" class="form-control" id="rprice" name="rprice" placeholder="MVR " required>
  </div>
  
  <div class="form-group">
    <label for="wprice">Wholesale Price</label>
    <input type="text" class="form-control" id="wprice" name="wprice" placeholder="MVR ">
  </div>

  <div class="form-group">
    <label for="fproduct">Featured Product</label>
    <input type="checkbox" name="fproduct" value="1" > 
  </div>

  <div class="form-group">
    <label for="toppicks">Top picks</label>
    <input type="checkbox" name="toppicks" value="1" > 
  </div>

  <div class="form-group">
    <label for="color">Color</label>
    <input type="text" class="form-control" id="color" name="color" placeholder="#">
  </div>

  <div class="form-group">
    <label for="image">Upload Image</label>
    <input type="file" name="image[]" multiple>
   </div>

  <div class="form-group">
    <button type="submit" class="btn btn-primary">Submit</button>
  </div>
  </form>


 <hr>
<br>
 <div class="container">
<h3>Product List</h3>

</div>

  <table class="table">
  <thead>
    <tr>
      <th scope="col">Product Name</th>
      <th scope="col">Type</th>
      <th scope="col">Retail Price</th>
      <th scope="col">Wholesale Price</th>
      <th scope="col">Top Picks</th>
      <th scope="col">Featured</th>
      <th scope="col">Control</th>
    </tr>
  </thead>
  <tbody>
  @foreach($products as $product) 
    <tr>
      <td>{{ $product->name }}</td>
      <td>{{ $product->type_id }}</td>
      <td>{{ $product->rprice }}</td>
      <td>{{ $product->wprice }}</td>
      <td>{{ $product->toppicks}}</td>
      <td>{{ $product->fproduct}}</td>
      <td><button type="button" class="btn btn-warning">Edit</button>  <button type="submit"  class="btn btn-danger">Delete</button></td>
    </tr>
    @endforeach


  </tbody>
</table>

<div>
@endsection
