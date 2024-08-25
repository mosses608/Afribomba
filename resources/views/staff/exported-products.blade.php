@extends('staff-layout')

@section('content')
<br><br><br>
@include('partials.staff-side-menu')

<center>
    <div class="main-dashboard-ajax-wrapper">
        <div class="header-intro-ajax">
            <h1>Exported Products</h1>
            <h2><i class="fas fa-calendar-alt"></i> <span class="currentDate"></span></h2>
            <br>
        </div><br>
        <div class="centered-before-ajax">
            <form action="/staff/exported-products" method="GET" class="search-component">
                @csrf
                <input type="date" name="search" id="" placeholder="Search exported product..."><button type="submit"><span>Search</span></button>
            </form>
            <button class="printDocs" onclick="printDocs()"><i class="fa fa-print"></i> <span>Print</span></button>
            <button class="add-product-button" style="background-color:orange;"> <span class="spanned-profit">Tsh {{$dateProfit}}</span></button>
        </div><br><br>

        <style>
            @media print{
                body * {
                    visibility:hidden;
                }
                .visible-table{
                    visibility:visible;
                }
                footer,header{
                    visibility:hidden;
                }
                table,td,th{
                    border:1px solid #999;
                    visibility:visible;
                }
                .add-product-button{
                    visibility:visible;
                }
                .spanned-profit span strong{
                    visibility:visible;
                }
            }
        </style>
        <div class="flex-wrapper-container">
            <div class="mini-container">
                <!-- <table class="visible-table">
                    <tr class="product-table-header">
                    <th>#</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Date Created</th>
                    <th>Action</th>
                    </tr> -->
                    @foreach($exports as $export)
                    <div class="single-loop-wrapper-bor">
                    <span><strong>S{{$export->id}}</strong> <br> <p style="font-size:14px;">{{$export->created_at}}</p></span>
                                <span><a href="/staff/print/{{ $export->id }}" style="color:#0000FF; cursor:pointer;"><i class="fa fa-print"></i></a> </span>
                                <span onclick="showEditSaleForm(event, {{ $export->id }})"><i class="fa fa-edit"></i></span> 
        </div>
                    @endforeach
                   

                @if(count($exports) == 0)
                <br>
                <p>No exported products today!</p>
                <br>
                @endif
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
            </select><br>
            <br>
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

        function printDocs(){
            window.print();
        }
    </script>
</center>
@endsection