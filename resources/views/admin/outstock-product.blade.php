@extends('admin-layout')

@section('content')
<br><br><br>
@include('partials.side-menus')

<center>
    <div class="main-dashboard-ajax-wrapper">
        <div class="header-intro-ajax">
            <h1>OutStock Products</h1>
            <h2><i class="fas fa-calendar-alt"></i> <span class="currentDate"></span></h2>
            <br>
        </div><br>
        <div class="centered-before-ajax">
            <form action="/admin/outstock-product" method="GET" class="search-component">
                @csrf
                <input type="text" name="search" id="searchInput" onkeyup="searchProducts()" placeholder="Search outstock product...">
                <!-- <button type="submit"><span>Search</span></button> -->
            </form>
            <button class="add-product-button" onclick="PrintDoc()"><i class="fa fa-print"></i> <span>Print</span></button>
        </div><br><br>

        <style>
            @media print{
                body *{
                    visibility:hidden;
                }
                .mini-container,table,tr,th,td{
                    visibility:visible;
                }
                td img{
                    visibility:visible;
                }
            }
        </style>
        <div class="flex-wrapper-container">
            <div class="mini-container">
                <table>
                    <tr class="product-table-header">
                    <th>#</th>
                    <th>Image</th>
                    <th>Product Id</th>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Store Name</th>
                    <th>Date Created</th>
                    </tr>
                    @foreach($products as $product)
                    @if($product->product_quantity == '0')
                    <tr class="product-tr-td">
                        <td>{{$product->id}}</td>
                        <td><img src="{{asset('storage/' . $product->product_image)}}" alt=""></td>
                        <td>{{$product->product_id}}</td>
                        <td>{{$product->product_name}}</td>
                        <td>{{$product->product_quantity}}</td>
                        <td>Tsh {{number_format($product->product_price)}}</td>
                        <td>{{$product->description}}</td>
                        <td>{{$product->store_name}}</td>
                        <td>{{$product->created_at}}</td>
                    </tr>
                    @endif
                    @endforeach
                </table>
        </div>

        <script>
            function searchProducts() {
            const input = document.getElementById('searchInput').value.toLowerCase();
            const products = document.querySelectorAll('.product-tr-td');

            products.forEach(product => {
                const name = product.querySelector('td:nth-child(4)').textContent.toLowerCase();
                const id = product.querySelector('td:nth-child(3)').textContent.toLowerCase();
                
                const isVisible = name.includes(input) || id.includes(input);
                product.style.display = isVisible ? '' : 'none';
            });
        }
        </script>

        <form action="/products" method="POST" class="product-creator-ajax-wrapper" enctype="multipart/form-data">
            @csrf
            <h1>Add a Product</h1><br><br>
            <label for="">Product Id:</label>
            <input type="text" name="product_id" id="" placeholder="Product Id..."><br><br>
            <label for="">Product Name:</label>
            <input type="text" name="product_name" id="" placeholder="Product Name..."><br><br>
            <label for="">Quantity:</label>
            <input type="text" name="product_quantity" id="" placeholder="Product Quantity..."><br><br>
            <label for="">Product Price:</label>
            <input type="text" name="product_price" id="" placeholder="Product Price..."><br><br>
            <label for="">Store Name:</label>
            <select name="store_name" id="">
                <option value="empty">Select Store</option>
                <option value="Store1">Store1</option>
                <option value="Store2">Store2</option>
            </select><br><br>
            <label for="">Product Image:</label>
            <input type="file" name="product_image" id="" style="border:none;"><br><br>
            <button type="submit" class="button">Submit Product</button> 
            <button type="button" class="close-button" onclick="closePopUpForm()">Close</button>
            <br><br>
        </form>
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

        function PrintDoc(){
            window.print();
        }
    </script>
</center>
@endsection