@extends('staff-layout')

@section('content')
<br><br><br>
@include('partials.staff-side-menu')

<center>
    <div class="main-dashboard-ajax-wrapper">
        <div class="header-intro-ajax">
            <h1>OutStock Products</h1>
            <h2><i class="fas fa-calendar-alt"></i> <span class="currentDate"></span></h2>
            <br>
        </div><br>
        <div class="centered-before-ajax">
            <form action="/staff/instock-products" method="GET" class="search-component">
                @csrf
                <input type="text" name="search" id="" placeholder="Search instock product..."><button type="submit"><span>Search</span></button>
            </form>
            <button class="add-product-button" onclick="showPrintDialog()"><i class="fa fa-print"></i> <span>Print</span></button>
        </div><br><br>
        <div class="flex-wrapper-container">
        <style>
                @media print{
                    body * {
                        visibility:hidden;
                    }
                    .mini-container{
                        visibility:visible;
                        overflow-x:none;
                        position:absolute;
                        left:0%;
                        width:100%;
                        top:1%;
                    }
                    table, th,tr{
                        visibility:visible;
                    }
                    th,td{
                        border:1.2px solid #999;
                    }
                    td,#header-viewer{
                        visibility:visible;
                    }
                }
                #header-viewer{
                    float:left;
                    padding:6px;
                }
            </style>
            <div class="mini-container">
            <h1 id="header-viewer">LIST OF ALL OUTSTOCK PRODUCTS</h1>
                <table>
                    <tr class="product-table-header">
                    <th>#</th>
                    <th>Product Id</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Store Name</th>
                    <th>Date Created</th>
                    <!-- <th>Action</th> -->
                    </tr>
                    @foreach($products as $product)
                    @if($product->product_quantity == 0)
                        <tr>
                            <td>#</td>
                            <td>{{$product->product_id}}</td>
                            <td>{{$product->product_name}}</td>
                            <td>{{$product->product_quantity}}</td>
                            <td>Tsh {{number_format($product->product_price)}}</td>
                            <td>{{$product->description}}</td>
                            <td>{{$product->store_name}}</td>
                            <td>{{$product->created_at}}</td>
                        </tr>
                        @else
                        <tr>No outstock product</tr>
                    @endif
                    @endforeach
                </table>
            </div>
        </div>

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

        function showPrintDialog(){
            window.print();
            // document.getElementById("header-viewer").innerHTML ='LIST OF ALL INSTOCK PRODUCTS';
        }
    </script>
</center>
@endsection