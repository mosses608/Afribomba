@if($order)

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

<x-success_order_msg />

<x-order_deleted_flash_msg />

<x-order_updated_msg />

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
                    <button type="submit" class="submit-btn"><span><i class="fa fa-search"></i></span></button>
                </div>
            </form>

            <a href="/admin/view-products" class="view-product-link" id="view-product-link"><i class="fa fa-eye"></i> <span>View Product</span></a>
            <button class="order-btn-show" onclick="showPlaceOrderForm(event)"><i class="fa fa-shopping-cart"></i> <span>Order</span></button>
            <button class="order-product-print" onclick="printOrders(event)"><i class="fa fa-print"></i> <span>Print</span></button>
            <button class="add-order-product-class" onclick="createOrderedProduct(event)"><i class="fa fa-plus"></i> <span> Product</span></button>
        </div><br>

        @if($productName)
        @php
        $CBMCovered = 0;
        $KGCovered = 0;
        @endphp
        <div class="main-container-class" style="width:100%;">
        @foreach($productName as $key => $name)
            @foreach($posts as $post)
            @if($name == $post->product_id)
                @php
                    $CBMCovered += $post->cbm;
                @endphp
            @endif
            @endforeach
        @endforeach

        @foreach($productName as $key => $name)
            @foreach($posts as $post)
            @if($name == $post->product_id)
                @php
                    $KGCovered += $post->weight;
                @endphp
            @endif
            @endforeach
        @endforeach


            @foreach($containers as $container)
            @if($container->id == $order->container_id)
                @php
                    $totalCBM = 0;
                    $totalKG = 0;
                @endphp

                    @foreach($posts as $post)
                    @if($container->id == $order->container_id && $post->product_id == $order->product_name)
                        @php
                            $totalCBM += $CBMCovered;
                            $totalKG += $KGCovered;
                        @endphp
                    @endif
                    @endforeach

                    <h2 id="container-details-adapter" style="width:100%;">
                        Container: {{ $container->name }} - <span>
                            Covered CBM = <strong>{{ $CBMCovered }}</strong> | KG = <strong>{{ number_format($KGCovered) }}</strong>
                        </span> <br>
                        <span>Available CBM = <strong>{{ $container->capacity - $CBMCovered }}</strong> | Available KG = <strong>{{ number_format($container->max_payload - $KGCovered) }}</strong></span>
                    </h2>
                    @endif
            @endforeach
        </div>
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

            @media print{
                body * {
                    visibility: hidden;
                }
                table,tr,th,td,p{
                    visibility: visible;
                }
                table{
                    position: absolute;
                    left:0%;
                    top:1%;
                    width:100%;
                }
                .hidden-print{
                    display:none;
                }
                table tr th{
                    color:#000;
                }
            }
        </style>
        
        <div class="flex-wrapper-container">
            <div class="mini-container">
                <table>
                    <tr class="product-table-header">
                        <th>Product Name</th>
                        <th>KG</th>
                        <th>CBM</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total KG</th>
                        <th>Total CBM</th>
                        <th>Total Price</th>
                        <th class="hidden-print">Date</th>
                        <th class="hidden-print">Action</th>
                    </tr>

                   <tr class="tr-td-class">
                    <td>
                    @foreach($productName as $key => $name)
                       @foreach($posts as $post)
                        @if($name == $post->product_id)
                        <p>{{ $post->product_name  }}</p> <br>
                        @endif
                        @endforeach
                    @endforeach
                    </td>
                    <td>
                       @foreach($productName as $key => $name)
                       @foreach($posts as $post)
                            @if($name == $post->product_id)
                            <p>{{ number_format( $post->weight ) }}</p> <br>
                            @endif
                            @endforeach
                        @endforeach
                    </td>
                    <td>
                    @foreach($productName as $key => $name)
                       @foreach($posts as $post)
                        @if($name == $post->product_id)
                        <p>{{ $post->cbm  }}</p> <br>
                        @endif
                        @endforeach
                    @endforeach
                    </td>
                    <td>
                    @foreach($productName as $key => $name)
                       @foreach($posts as $post)
                        @if($name == $post->product_id)
                        <p>{{ number_format($post->price,2)  }}</p> <br>
                        @endif
                        @endforeach
                    @endforeach
                    </td>

                    <td>
                        @foreach($quantity as $key => $qty)
                            <p>{{ $qty }}</p> <br>
                        @endforeach
                    </td>
                    @php
                        $totalWeightKG = 0;
                        $totalCubicMetresCBM = 0;
                        $totalOrderPrice = 0;
                        $totalQuantity = 0;
                    @endphp
                    <td>
                    
                    @foreach($productName as $key => $name)
                       @foreach($posts as $post)
                        @if($name == $post->product_id)
                          
                            <p>{{ number_format($post->weight * $quantity[$key]) }}</p> <br>
                           
                        @endif
                        @endforeach
                    @endforeach
                    </td>
                    <td>
                    @foreach($productName as $key => $name)
                       @foreach($posts as $post)
                        @if($name == $post->product_id)
                           
                            <p>{{ $post->cbm * $quantity[$key] }}</p> <br>
                           
                        @endif
                        @endforeach
                    @endforeach
                   
                    </td>
                    <td>
                    @foreach($productName as $key => $name)
                       @foreach($posts as $post)
                        @if($name == $post->product_id)
                            
                            <p>{{ number_format($post->price * $quantity[$key], 2) }}</p> <br>
                            
                        @endif
                        @endforeach
                    @endforeach
                  
                    </td>
                    <td class="hidden-print">
                        {{ $order->created_at->format('Y-m-d') }}
                    </td>
                    <td class="hidden-print">
                        <button class="edit-order-btn" onclick="showEditOrderForm(event)"><i class="fa fa-pencil"></i></button><br><br><br>
                        <button class="delete-order-btn" onclick="showDeleteDialog(event)"><i class="fas fa-trash-alt"></i></button>
                    </td>
                   </tr>
                   <tr class="tr-td-class">
                    <td>Totals: </td>
                    <td>
                       @foreach($productName as $key => $name)
                       @foreach($posts as $post)
                            @if($name == $post->product_id)
                            @php
                            $totalWeightKG += $post->weight;
                            @endphp
                            @endif
                            @endforeach
                        @endforeach
                        {{ number_format($totalWeightKG) }}
                    </td>
                    <td>
                       @foreach($productName as $key => $name)
                       @foreach($posts as $post)
                            @if($name == $post->product_id)
                            @php
                            $totalCubicMetresCBM += $post->cbm;
                            @endphp
                            @endif
                            @endforeach
                        @endforeach
                        {{ $totalCubicMetresCBM }}
                    </td>
                    <td>
                       @foreach($productName as $key => $name)
                       @foreach($posts as $post)
                            @if($name == $post->product_id)
                            @php
                            $totalOrderPrice += $post->price;
                            @endphp
                            @endif
                            @endforeach
                        @endforeach
                        TZS {{ number_format($totalOrderPrice, 2) }}
                    </td>
                    <td>
                        @foreach($quantity as $key => $qty)
                            @php
                            $totalQuantity += $qty;
                            @endphp
                        @endforeach
                        {{ number_format($totalQuantity) }}
                    </td>
                   </tr>
                   
                </table>
            </div>
        </div>

        <!--DELETE THIS ORDER -->

        <form action="/delete-order/{{ $order->id }}" method="POST" class="order-tbl-wrapper" id="delete-order-dialog" style="top:30%;">
            @csrf
            @method('DELETE')
            <div class="top-notch-form">
                <h1>Delete This Order (<strong>U</strong>{{ $order->id }})</h1>
                <button type="button" onclick="hideDeleteOrderForm(event)" class="close-btn-order-wrapper">&times;</button>
            </div><br><br>
            <button type="submit" class="yes-action-btn">Confirm Action</button>
        </form>

        <!-- EDIT THIS ORDER -->

            <form action="/edit-order/{{ $order->id }}" method="POST" class="order-tbl-wrapper" id="order-tbl-wrapper">
                @csrf
                @method('PUT')
                <div class="top-notch-form">
                    <h1>Edit This Order</h1>
                    <button type="button" onclick="hideEditOrderForm(event)" class="close-btn-order-wrapper">&times;</button>
                </div><br><br>
                <div class="container-selector">
                    <div class="min-order-name">
                        <label for="">Order Name</label><br>
                        <input type="text" name="order_name" id="" value="{{ $order->order_name }}">
                    </div>
                    <div class="container-name">
                        <label for="">Select Container</label><br>
                        <select name="container_id" id="containerSelect" onchange="updateRemainingCapacity()" style="width:98%; padding:10px;">
                        @foreach($containers as $container)
                        @if($container->id == $order->container_id)
                            <option value="{{ $order->container_id }}" selected disabled>{{ $container->name }}</option>
                            @endif
                                <option value="{{$container->id}}" data-capacity="{{$container->total_capacity}}">{{$container->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mini-parent-class">
                    <div class="child-component-x">
                        <label for="">Product Ids</label><br>
                        @foreach($productName as $key => $name)
                        <input type="text" name="product_name[]" class="product-select" id="productSelect" onchange="updatePayloadWeight()" style="width:100%;" value="{{ $name }}"><br><br>
                        @endforeach
                    </div>

                    <div class="child-component-y">
                        <label for="">Box Quantity</label><br>
                        @foreach($quantity as $key => $qty)
                        <input type="text" value="{{ $qty }}" name="quantity[]"><br><br>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="submit-order-btn">Edit Order</button>
            </form>

            @else
            <p class="alert-message-dialog">
            @foreach($containers as $container)
            @if($container->id == $order->container_id)
            <span class="infinite-blink">Order Placed in <em style="color:green;">"{{ $container->name }}"</em> is empty!</span>
            @endif
            @endforeach
            </p>
            @endif

            <!-- ADD NEW PRODUCT TO THIS ORDER FORM -->

        <form action="/add-order/{{ $order->id }}" method="POST" class="order-tbl-wrapper" id="order-tbl-wrapper">
            @csrf
            @method('PUT')
            <div class="top-notch-form">
                <h1>Add Products In This Order <strong>"{{ $order->order_name }}"</strong></h1>
                <button type="button" onclick="hideOrderForm(event)" class="close-btn-order-wrapper">&times;</button>
            </div><br><br>

            <input type="hidden" name="staff_name" value="{{ Auth::guard('web')->user()->staff_name }}">
            <div class="container-selector">
                <div class="min-order-name">
                    <label for="">Order Name</label><br>
                    <input type="text" name="order_name" id="" value="{{ $order->order_name }}">
                </div>
                <div class="container-name">
                    <label for="">Select Container</label><br>
                    <select name="container_id" id="containerSelect" onchange="updateRemainingCapacity()" style="width:98%; padding:10px;">
                        @foreach($containers as $container)
                        @if($container->id == $order->container_id)
                            <option value="{{ $order->container_id }}">{{$container->name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <!-- <div id="remainingCapacity"></div> -->
            </div>

            <div class="mini-parent-class">
                <div class="child-component-x">
                    <label for="">Product Name</label><br>
                    <select name="product_name[]" class="product-select" id="productSelect" onchange="updatePayloadWeight()" style="width:100%;">
                        <option value="" selected disabled>--select product--</option>
                        @foreach($posts as $post)
                            <option value="{{ $post->product_id }}">{{ $post->product_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="child-component-y">
                    <label for="">Box Quantity</label><br>
                    <select name="quantity[]">
                        <option value="" selected disabled>--select quantity--</option>
                        @for($i=1; $i<=100; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <button type="submit" class="submit-order-btn">Add Products</button>
            <button type="button" class="append-container-child"><i class="fa fa-plus"></i></button>
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

            window.showEditOrderForm = function(event){
                event.preventDefault();
                const editOrderForm = document.getElementById("order-tbl-wrapper");
                const hideScreenView = document.querySelector('.black-screeen-view');
                editOrderForm.style.display='block';
                hideScreenView.style.display='block';
            }

            window.hideEditOrderForm = function(event){
                event.preventDefault();
                const editOrderForm = document.getElementById("order-tbl-wrapper");
                const hideScreenView = document.querySelector('.black-screeen-view');
                editOrderForm.style.display='none';
                hideScreenView.style.display='none';
            }

            window.showDeleteDialog = function(event){
                event.preventDefault();
                const deleteDialog = document.getElementById("delete-order-dialog");
                const hideScreenView = document.querySelector('.black-screeen-view');

                deleteDialog.style.display='block';
                hideScreenView.style.display='block';
            }

            window.hideDeleteOrderForm = function(event){
                event.preventDefault();
                const deleteDialog = document.getElementById("delete-order-dialog");
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
                                    <option value="{{$post->product_id}}">{{$post->product_name}}</option>
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

@else

<center>
    <p>Page | Empty</p>
</center>

@endif