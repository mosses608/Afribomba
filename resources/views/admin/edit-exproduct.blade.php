@extends('admin-layout')

@section('content')
<br><br><br>
@include('partials.side-menus')

<center>
    <div class="main-dashboard-ajax-wrapper">
        <div class="header-intro-ajax">
            <h1>Edit {{$product->product_name}}</h1>
            <h2><i class="fas fa-calendar-alt"></i> <span class="currentDate"></span></h2>
            <br>
        </div>
        </div>
        <div class="flex-wrapper-container-xx">
            <form action="/exports/edit-product-exp/{{$product->id}}" method="POST" class="wraper-ajax-editro">
                @csrf
                @method('PUT')
                <div class="left-menuxx">
            <input type="hidden" name="product_image" value="{{$product->product_image}}" style="border:none;" accept="image/*">
            <label>Product Id:</label><br>
            <input type="text" name="product_id" value="{{$product->product_id}}"><br><br>

            <label>Product Name:</label><br>
            <input type="text" name="product_name" value="{{$product->product_name}}"><br><br>

            <label>Customer Name:</label><br>
            <input type="text" name="customer_name" value="{{$product->customer_name}}"><br><br>

           </div>
           <div class="right-menuxx">
            <input type="hidden" name="staff_name" value="{{Auth::guard('web')->user()->staff_name}}">
            <label>Quantity:</label><br>
            <input type="text" name="product_quantity" value="{{$product->product_quantity}}"><br><br>

            <label>Total Price:</label><br>
            <input type="text" name="product_price" value="{{$product->product_quantity * $product->product_price}}"><br><br>
<br>
            <button type="submit" class="button">Edit Sales</button> 
            </div>
            <br><br>
            </form>
        </div>
    </div>

    <script>
        function showAddProductForm(){
            document.querySelector('.product-creator-ajax-wrapper').classList.toggle('active');
            document.querySelector('.product-creator-ajax-wrapper').style.opacity='1';
            document.querySelector('.font-sans-antialiased-admin').style.backgroundColor='rgba(0,0,0,0.5)';
        }

        function closePopUpForm(){
            document.querySelector('.product-creator-ajax-wrapper').style.display='none';
            location.reload();
        }
    </script>
</center>
@endsection