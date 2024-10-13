@extends('admin-layout')

@section('content')
<br><br><br>
@include('partials.side-menus')

<div class="black-screeen-view"></div>

<center>
    <div class="main-dashboard-ajax-wrapper">
        <div class="header-intro-ajax">
            <h1>Create Orders</h1>
            <h2><i class="fas fa-calendar-alt"></i> <span class="currentDate"></span></h2>
            <br>
        </div><br>
        <div class="centered-before-ajax-yz">
            <form action="/admin/inventory-report" method="GET" class="form-filter-component">
                @csrf
                <div class="filter-input">
                    <input type="date" name="start_date" id="start_date" placeholder="Start date...">
                    <input type="date" name="end_date" id="end_date" placeholder="End date...">
                    <button type="submit" class="submit-btn"><span>Filter</span></button>
                </div>
            </form>

            <form action="/admin/reports" method="GET" class="search-by-name">
                @csrf
                <select name="search" id="">
                    <option value="" selected disabled>--filter by order ID--</option>
                    <option value="">All</option>
                   
                </select>
                <button type="submit" class="submit-filter-btn">Filter</button>
            </form>
            <button class="order-btn-show" onclick="showPlaceOrderForm(event)"><i class="fa fa-shopping-cart"></i> <span>Order</span></button>
            <button class="order-product-print" onclick="printOrders(event)"><i class="fa fa-print"></i> <span>Print</span></button>
            <button class="add-order-product-class" onclick="createOrderedProduct(event)"><i class="fa fa-plus"></i> <span> Product</span></button>
        </div>
        <br>

        <div class="flex-wrapper-container">
            <div class="mini-container">
                <table>
                    <tr class="product-table-header">
                    <th>S/N</th>
                    <th>Order ID</th>
                    <th>Staff Name</th>
                    <th>Date Created</th>
                    <th>Items Number</th>
                    <th>Action</th>
                    </tr>

                    @foreach($orders as $order)
                    <tr class="tr-td-class">
                        <td>{{$order->id}}</td>
                        <td>U{{$order->id}}</td>
                        <td>{{is_array($staffName) ? implode(',', $staffName) : $order->staff_name}}</td>
                        <td>{{$order->created_at->format('Y-m-d')}}</td>
                        <td>
                        @if (is_array($productNames))
                            {{ count($productNames) }} 
                        @else
                            0
                        @endif
                        </td>
                        <td>
                            <a href="/admin/view-more/{{$order->id}}"><i class="fa fa-eye"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <form action="{{ route('post.orders') }}" method="POST" class="order-tbl-wrapper">
            @csrf
            <div class="top-notch-form">
                <h1>Create Your Order From Here!</h1>
                <button type="button" onclick="hideOrderForm(event)" class="close-btn-order-wrapper">&times;</button>
            </div><br><br>

            <input type="hidden" name="staff_name[]" id="" value="{{ Auth::guard('web')->user()->staff_name }}">
            <div class="container-selector">
                <label for="">Select Container</label><br>
                <select name="container_id[]" id="" style="width:98%; padding:10px;">
                    <option value="" selected disabled>--select container--</option>
                    @foreach($containers as $container)
                    <option value="{{$container->container_id}}">{{$container->name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="mini-parent-class">
                <div class="child-component-x">
                    <label for="">Product Name</label><br>
                    <select name="product_name[]" class="product-select" id="" style="width:100%;">
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
                </div>
            </div>

            <button type="submit" class="submit-order-btn">Place Order</button>
            <button type="button" class="append-container-child"><i class="fa fa-plus"></i></button>
        </form>

        <script>
            window.showPlaceOrderForm = function(event){
                event.preventDefault();
                const orderForm = document.querySelector('.order-tbl-wrapper');
                const hideScreenView = document.querySelector('.black-screeen-view');

                orderForm.style.display='block';
                hideScreenView.style.display='block';
            }

            window.hideOrderForm = function(event){
                event.preventDefault();
                const orderForm = document.querySelector('.order-tbl-wrapper');
                const hideScreenView = document.querySelector('.black-screeen-view');

                orderForm.style.display='none';
                hideScreenView.style.display='none';
            }
        </script>

        <!-- Include jQuery and Select2 -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

        <script>
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
                    <label for="">Length (cm)</label><br>
                    <input type="number" name="length" id="" placeholder="Length">
                </div>
                <div class="minor02">
                    <label for="">Width (cm)</label><br>
                    <input type="number" name="width" id="" placeholder="Width">
                </div>
                <div class="minor03">
                    <label for="">Height (cm)</label><br>
                    <input type="number" name="height" id="" placeholder="Height">
                </div>
                <div class="minor04">
                    <label for="">Weight (kg)</label><br>
                    <input type="number" name="weight" id="" placeholder="Weight">
                </div>
            </div>
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

    <script>
        <script>
            $(document).ready(function() {
                $('.append-container-child').click(function(e) {
                    e.preventDefault();

                    // Clone the product name and quantity divs and append to the form
                    var productField = `
                        <div class="child-component-x">
                            <label for="">Product Name</label><br>
                            <select name="product_name[]" id="">
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

                    // Append the cloned fields
                    $('.mini-parent-class').append(productField);
                });
            });
    </script>
</center>
@endsection