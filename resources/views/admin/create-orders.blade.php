@extends('admin-layout')

@section('content')
<br><br><br>
@include('partials.side-menus')

<div class="black-screeen-view"></div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 <!-- Include jQuery and Select2 -->
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<x-prod_created_flash_msg />

<x-success_created />

<x-order_deleted_flash_msg />

<x-order_updated_msg />

<style>
    @media print{
        body *{
            visibility: hidden;
        }
        .mini-container{
            visibility: hidden;
        }
        table{
            position:absolute;
            left:0%;
            top:1%;
            border: 1.2px solid #ddd;
            width:100%;
            visibility: visible;
        }
        table tr,th,td{
            visibility: visible;
        }
        .product-table-header th{
            font-size:11px;
            color:#000;
            border: 1.2px solid #ddd;
        }
        table td{
            font-size:11px;
        }
        #action-th{
            display:none;
        }
        .tr-td-class td span{
            visibility: visible;
        }
    }
</style>

<center>
    <div class="main-dashboard-ajax-wrapper">
        <!-- <div class="header-intro-ajax">
            <h1>Create Orders</h1>
            <h2><i class="fas fa-calendar-alt"></i> <span class="currentDate"></span></h2>
            <br>
        </div><br> -->
        
        <div class="centered-before-ajax-yz">
            <form action="/admin/create-orders" method="GET" class="form-filter-component">
                @csrf
                <div class="filter-input">
                    <input type="date" name="start_date" id="start_date" placeholder="Start date...">
                    <input type="date" name="end_date" id="end_date" placeholder="End date...">
                    <button type="submit" class="submit-btn"><span><i class="fa fa-search"> </i></span></button>
                </div>
            </form>

            <form action="/admin/create-orders" method="GET" class="get-name-adapter">
                @csrf
                <select name="search" id="">
                    <option value="" selected disabled>--search by order name--</option>
                    @foreach($orders as $order)
                    <option value="{{ $order->order_name }}">{{ $order->order_name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="button"><i class="fa fa-search"></i></button>
            </form>

            <button class="order-btn-show" onclick="showPlaceOrderForm(event)"><i class="fa fa-shopping-cart"></i> <span>Order</span></button>
            <button class="order-product-print"><a href="/admin/view-products"><i class="fa fa-eye"></i> <span>View Product</span></a></button>
            <button class="add-order-product-class" onclick="createOrderedProduct(event)"><i class="fa fa-plus"></i> <span> Product</span></button>
        </div><br>
        <style>
            .view-product-link{
                background-color: #444;
                color: #FFF;
                padding: 6px;
                font-family: Verdana, Geneva, Tahoma, sans-serif;
                font-size: 13px;
                border-radius: 4px;
                cursor: pointer;
                width: 120px;
                margin-left: 14%;
            }

            .view-product-link a{
                text-decoration: none;
                color: #FFF;
            }
        </style>

        <div class="flexer-wrapper-container">
            @foreach($orders as $order)
                <div class="order-loop-single">
                    <h1>{{$order->order_name}}</h1>
                    <a href="/admin/view-order/{{ $order->id }}"><i class="fa fa-eye"></i></a>
                </div>
            @endforeach        
            @if(count($orders) == 0)
            <br>
            <p id="non-order-found">No order found!</p>
            <br>
            @endif
        </div>

        <form action="{{ route('post.orders') }}" method="POST" class="order-tbl-wrapper" id="order-tbl-wrapper">
            @csrf
            <div class="top-notch-form">
                <h1>Create Your Order From Here!</h1>
                <button type="button" onclick="hideOrderForm(event)" class="close-btn-order-wrapper">&times;</button>
            </div><br><br>

            <input type="hidden" name="staff_name" value="{{ Auth::guard('web')->user()->staff_name }}">
            <div class="container-selector">
                <div class="min-order-name">
                    <label for="">Order Name</label><br>
                    <input type="text" name="order_name" id="" placeholder="Order name">
                </div>
                <div class="container-name">
                    <label for="">Select Container</label><br>
                    <select name="container_id" id="containerSelect" onchange="updateRemainingCapacity()" style="width:98%; padding:10px;">
                        <option value="" selected disabled>--select container--</option>
                        @foreach($containers as $container)
                            <option value="{{$container->id}}" data-capacity="{{$container->total_capacity}}">{{$container->name}}</option>
                        @endforeach
                    </select>
                </div>
                <!-- <div id="remainingCapacity"></div> -->
            </div>

            <!-- <div class="mini-parent-class">
                <div class="child-component-x">
                    <label for="">Product Name</label><br>
                    <select name="product_name" class="product-select" id="productSelect" onchange="updatePayloadWeight()" style="width:100%;">
                        <option value="" selected disabled>--select product--</option>
                        @foreach($posts as $post)
                            <option value="{{$post->product_id}}">{{$post->product_name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="child-component-y">
                    <label for="">Box Quantity</label><br>
                    <select name="quantity">
                        <option value="" selected disabled>--select quantity--</option>
                        @for($i=1; $i<=100; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div> -->
            <br>

            <button type="submit" class="submit-order-btn">Create Order</button>
            <!-- <button type="button" class="append-container-child"><i class="fa fa-plus"></i></button> -->
        </form>

        <script>
            window.showPlaceOrderForm = function(event){
                event.preventDefault();
                const orderForm = document.getElementById("order-tbl-wrapper");
                const hideScreenView = document.querySelector('.black-screeen-view');

                orderForm.style.display='block';
                hideScreenView.style.display='block';
            }

            window.hideOrderForm = function(event){
                event.preventDefault();
                const orderForm = document.getElementById("order-tbl-wrapper");
                const hideScreenView = document.querySelector('.black-screeen-view');

                orderForm.style.display='none';
                hideScreenView.style.display='none';
            }

            window.showEditOrderForm = function(event, orderId){
                event.preventDefault();
                const editOrderForm = document.getElementById(`order-tbl-wrapper-${orderId}`);
                const hideScreenView = document.querySelector('.black-screeen-view');
                editOrderForm.style.display='block';
                hideScreenView.style.display='block';
            }

            window.hideEditOrderForm = function(event, orderId){
                event.preventDefault();
                const editOrderForm = document.getElementById(`order-tbl-wrapper-${orderId}`);
                const hideScreenView = document.querySelector('.black-screeen-view');
                editOrderForm.style.display='none';
                hideScreenView.style.display='none';
            }

            window.showDeleteDialog = function(event, orderId){
                event.preventDefault();
                const deleteDialog = document.getElementById(`delete-order-dialog-${orderId}`);
                const hideScreenView = document.querySelector('.black-screeen-view');

                deleteDialog.style.display='block';
                hideScreenView.style.display='block';
            }

            window.hideDeleteOrderForm = function(event, orderId){
                event.preventDefault();
                const deleteDialog = document.getElementById(`delete-order-dialog-${orderId}`);
                const hideScreenView = document.querySelector('.black-screeen-view');

                deleteDialog.style.display='none';
                hideScreenView.style.display='none';
            }

            window.printOrders = function(event){
                event.preventDefault();
                window.print();
            }

            $(document).ready(function() {
                // Initialize Select2 for container_id field
                $('select[name="container_id[]"]').select2({
                    placeholder: '--select container--',
                    allowClear: true
                });

                // Initialize Select2 for the first product_name[] field
                $('select[name="product_name[]"]').select2({
                    placeholder: '--select product--',
                    allowClear: true
                });

                // Add new product and quantity fields on button click
                $('.append-container-child').click(function(e) {
                    e.preventDefault();

                    var productField = `
                        <br><br>
                        <div class="child-component-x">
                            <label for="">Product Name</label><br>
                            <select name="product_name[]" class="product-select" id="">
                                <option value="" selected disabled>--select product--</option>
                                @foreach($posts as $post)
                                    <option value="{{$post->id}}" data-weight="{{$post->payload_weight}}">{{$post->product_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="child-component-y">
                            <label for="">Box Quantity</label><br>
                            <select name="quantity[]" id="">
                                <option value="" selected disabled>--select quantity--</option>
                                @for($i=1; $i<=100; $i++)
                                    <option value="{{$i}}">{{$i}}</option>
                                @endfor
                            </select>
                        </div>`;

                    // Append the new fields
                    $('.mini-parent-class').append(productField);

                    // Re-initialize Select2 for dynamically added product select
                    $('.product-select').select2({
                        placeholder: '--select product--',
                        allowClear: true
                    });
                });
            });
        </script>

        <form action="{{ route('create.product') }}" method="POST" class="post-product-ordered">
            @csrf
            <div class="top-notch-form">
                <h1>Register Your Products Here!</h1>
                <button type="button" onclick="closeForm(event)" class="close-btn-order-wrapper">&times;</button>
            </div><br><br>
            <div class="main-product-id">
                <label for="">Product ID</label><br>
                <input type="text" name="product_id" id="" placeholder="Product ID">
                <br>
                @error('product_id')
                <span style="float:left; font-size:10px; color:red; font-style:italic;">{{ $message }}</span>
                @enderror
            </div>
            <div class="main-product-name">
                <label for="">Product Name</label><br>
                <input type="text" name="product_name" id="" placeholder="Product Name">
            </div>
            <div class="unit-measurement-categ">
                <div class="minor01">
                    <label for="">Price / Box (TZS)</label><br>
                    <input type="text" name="price" id="" placeholder="Price">
                </div>
                <div class="minor02">
                    <label for="">Weight (KG)</label><br>
                    <input type="text" name="weight" id="" placeholder="Weight in KG">
                </div>
            </div>
            <div class="cbm-class">
                <label for="">Cubic Metres</label><br>
                <input type="text" name="cbm" id="" placeholder="CBM">
            </div><br>
            <button type="submit" class="submit-btn-product">Submit</button>
        </form>
    </div>
    
    <script>
        window.createOrderedProduct = function(event){
            event.preventDefault();
            const hiddenForm = document.querySelector('.post-product-ordered');
            const blackScreenView = document.querySelector('.black-screeen-view');
            hiddenForm.style.display='block';
            blackScreenView.style.display='block';
        }

        window.closeForm = function(event){
            event.preventDefault();
            const hiddenForm = document.querySelector('.post-product-ordered');
            const blackScreenView = document.querySelector('.black-screeen-view');
            hiddenForm.style.display='none';
            blackScreenView.style.display='none';
        }
    </script>

<style>
    @media(max-width:768px){
        .form-filter-component input{
            padding:10px !important;
            width:130px !important;
        }
        .submit-btn{
            font-size:10px !important;
            float:right !important;
        }
        .search-by-name select{
            padding:20px !important;
            font-size:16px !important;
        }
        .submit-filter-btn{
            font-size:12px !important;
        }
        #container-details-adapter{
            font-size:10px !important;
        }
        .order-tbl-wrapper{
            left:2% !important;
            width:96% !important;
        }
        .post-product-ordered{
            left:2% !important;
            width:96% !important;
        }
    }
</style>

<style>
        .main-container-class{
        width: 100%;
        border: 1.2px solid rgb(199, 224, 247);
    }
    
    #container-details-adapter{
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-size: 12.5px;
        display: inline-block;
        padding: 6px;
        box-shadow: 0 0 4px rgba(0,0,0,0.2);
        border-radius: 6px;
        width: 49%;
        margin-top: 0.5%;
        margin-bottom: 0.5%;
    }
    
    #container-details-adapter strong{
        color: green;
        font-weight: bolder;
    }
    .order-tbl-wrapper label{
        float: left;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-size: 13px;
        margin-left: 1%;
    }
    
    .yes-action-btn{
        width: 100%;
        background-color: blue;
        padding: 6px;
        text-transform: uppercase;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-size: 13px;
        color: #FFF;
        border-radius: 4px;
        cursor: pointer;
    }
    
    .order-tbl-wrapper select{
        width: 98%;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-size: 13px;
    }
    
    .order-tbl-wrapper input{
        width: 98%;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-size: 13px;
    }
    
    .order-tbl-wrapper select:focus{
        outline: none;
        border: 1.5px solid rgb(188, 219, 247);
        box-shadow: none;
    }
    
    .order-tbl-wrapper input:focus{
        outline: none;
        border: 1.5px solid rgb(188, 219, 247);
        box-shadow: none;
    }
    
    .main-product-id input{
        width: 100%;
        border: 1.2px solid rgb(188, 219, 247);
        padding: 6px;
        border-radius: 4px;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-size: 13px;
    }
    
    .main-product-name input{
        width: 100%;
        border: 1.2px solid rgb(188, 219, 247);
        padding: 6px;
        border-radius: 4px;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-size: 13px;
    }
    
    .unit-measurement-categ div input{
        display: inline-block;
        border: 1.2px solid rgb(188, 219, 247);
        padding: 6px;
        font-size: 13px;
        border-radius: 4px;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        width: 100%;
    }
    
    .unit-measurement-categ div input:focus{
        box-shadow: none;
        outline: none;
        border: 1.2px solid rgb(188, 219, 247);
    }
    
    .unit-measurement-categ div label{
        float: left;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-size: 13px;
    }
    
    .container-selector .container-name select{
        width: 100%;
        border: 1.2px solid rgb(188, 219, 247);
        padding: 6px;
        border-radius: 4px;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-size: 13px;
    }
    
    .container-selector input{
        width: 100%;
        border: 1.2px solid rgb(188, 219, 247);
        padding: 6px;
        border-radius: 4px;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-size: 13px;
    }
    
    .min-order-name,.container-name{
        display: inline-block;
        width: 48%;
    }
    
    .unit-measurement-categ div{
        display: inline-block;
        width: 47%;
        padding: 10px;
    }
    
    .cbm-class{
        width: 90%;
    }
    
    .cbm-class label{
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-size: 13px;
        float: left;
    }
    
    .cbm-class input{
        border: 1.2px solid rgb(188, 219, 247);
        padding: 6px;
        font-size: 13px;
        border-radius: 4px;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        width: 100%;
    }
    
    .cbm-class input:focus{
        outline: none;
        box-shadow: none;
        border: 1.2px solid rgb(188, 219, 247);
    }
</style>
</center>
@endsection
