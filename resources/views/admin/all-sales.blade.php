@extends('admin-layout')

@section('content')
<br><br><br>
@include('partials.side-menus')

<x-edit_success_sale />

<center>
    <div class="main-dashboard-ajax-wrapper">
        <div class="header-intro-ajax">
            <h1>Exported Products</h1>
            <h2><i class="fas fa-calendar-alt"></i> <span class="currentDate"></span></h2>
            <br>
        </div><br>
        <div class="centered-before-ajax">
            <form action="/admin/exported-products" method="GET" class="search-component">
                @csrf
                <input type="date" name="search" id="" placeholder="Search exported product..."><button type="submit"><span>Search</span></button>
            </form>
            <button class="add-product-button" id="product-button" style="background-color:orange; margin-right:1%;"> Tsh {{number_format($datePrice)}}</button>
            <button class="totla-component">{{ $totalComponents }} Items</button>
            <button class="view-all-sold"><a href="/admin/exported-products">&#8592; <span>Back</span></a></button>
        </div><br><br>
        <div class="flex-wrapper-container">
            <style>
                @media print{
                    .printable-meta-data{
                        display:block;
                    }
                    h1,h3{
                        visibility: visible;
                    }
                    body * {
                        visibility: hidden;
                    }
                    table{
                        visibility: visible;
                    }
                    .printable-meta-data{
                        visibility: visible;
                    }
                }
            </style>
            <div class="mini-container">
                <!-- <table>
                    <tr class="product-table-header">
                    <th>#</th>
                    <th>Sale Id</th>
                    <th>Product Name</th>
                    <th>Unit Price</th>
                    <th>Total Price</th>
                    <th>Staff Name</th>
                    <th>Date Created</th>
                    <th>Action</th>
                    </tr> -->
                    @if(count($exports) == 0)
                    <p>No product exported today!</p>
                    @endif
                    @foreach($exports as $export)
                    <div class="single-loop-wrapper-bor">
                                <span><strong>{{ is_array($customerName) ? implode(',', $customerName) : $export->tin }}</strong> <br> <p style="font-size:14px;">{{$export->created_at}}</p></span>
                                <span><a href="/admin/print/{{ $export->id }}" style="color:#0000FF; cursor:pointer;"><i class="fa fa-print"></i></a> </span>
                                <!-- <span onclick="showEditSalesForm(event, {{ $export->id }})"><i class="fa fa-edit"></i></span>  -->
            </div>
                                @foreach(json_decode($export->product_quantity, true) as $index => $quantity)
                                @endforeach

                                <form action="/exports/edit-product-exp/{{ $export->id }}" method="POST" class="product-creator-ajax-wrapper" id="product-editor-ajax-{{ $export->id }}" hidden enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <h1>Edit Sales</h1><br><br>
                                    <label>Product Name:</label>
                                    <input type="text" name="product_name" value="{{ $export->product_name }}"><br><br>
                                    <label>Customer Name:</label>
                                    <input type="text" name="customer_name" value="{{ $export->customer_name }}"><br><br>
                                    <input type="hidden" name="staff_name" value="{{ Auth::guard('web')->user()->staff_name }}">
                                    <label>Quantity:</label>
                                    <input type="text" name="product_quantity" value="{{ $quantity }}"><br><br>
                                    <label>Unit Price:</label>
                                    <input type="text" name="product_price" value="{{ json_decode($export->product_price, true)[$index] }}"><br><br>
                                    <button type="submit" class="button">Edit Sales</button> 
                                    <button type="button" class="close-button" onclick="closePopUpForm()">Close</button>
                                    <br><br>
                                </form>
                       
                    @endforeach
                <!-- </table> -->
            </div>
        </div>

        <style>
            .hidden{
                display:none;
            }
        </style>

        <form action="/products" method="POST" class="product-creator-ajax-wrapper" enctype="multipart/form-data" hidden>
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

        function printDoc(){
            window.print();
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function(){
            window.showEditSaleForm = function(event, saleId){
                const editForm = document.getElementById(`product-editor-ajax-${saleId}`);
                editForm.classList.add('active');
            }
        });
    </script>
</center>
@endsection
