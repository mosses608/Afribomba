<!-- resources/views/admin/single-export.blade.php -->
@extends('admin-layout')

@section('content')
<br><br><br>
@include('partials.side-menus')

<x-export_message />
<x-import_success />
<x-success_transfer />
<x-comment_sent />
<x-not_enough />

<center>
    <div class="main-dashboard-ajax-wrapper">
        <div class="header-intro-ajax">
            <h1>Single Product</h1>
            <h2><i class="fas fa-calendar-alt"></i> <span class="currentDate"></span></h2>
            <br>
        </div><br>
        <div class="centered-before-ajax">
        <form id="searchForm" method="GET" class="search-component">
            @csrf
            <input type="text" name="search" id="searchInput" onkeyup="searchProducts()" placeholder="Search product...">
        </form>
            <button class="transfer-product-button" onclick="showTransferProducty()"><a href="/admin/all-products"><i class="fa fa-plus"></i> <em>T</em>  <span>Transfer Product</span></a></button>
            <button class="add-product-button" onclick="showAddProductFormy()"><a href="/admin/all-products"><i class="fa fa-plus" style="padding:4px;"></i> <span>Add Product</span></a></button>
            <button class="export-product-wrapper" onclick="showExportFormy()"><a href="/admin/all-products"><i class="fa fa-upload"></i> <span>Sale Product</span></a></button>
        </div><br><br>
        <div class="flex-wrapper-container">
            <div class="mini-container">
                <br>
                <div class="image-holder-view">
                    <img src="{{$product->product_image ? asset('storage/' . $product->product_image) : asset('assets/images/profile.png')}}" alt="">
                </div>
                <div class="left-side-wrapper">
                    <p><strong>Product Id: {{$product->product_id}}</strong></p><br><br id="br-line">
                    <p><strong>Name: {{$product->product_name}}</strong></p><br><br id="br-line">
                    <p><strong>Quantity: {{$product->product_quantity}}</strong></p><br><br id="br-line">
                    @if($product->status == 'Good')
                        <p class="good-status-in"><strong>Status: {{$product->status}}</strong></p><br><br id="br-line">
                        @elseif($product->product_quantity <= 20 && $product->product_quantity >= 1)
                        <p class="less-status-in"><strong>Status: {{$product->status}}</strong></p><br><br id="br-line">
                        @elseif($product->product_quantity == 0)
                        <p class="out-stock-in"><strong>Status: Out Stock</strong></p><br><br id="br-line">
                    @endif
                    <p><strong>Price: Tsh {{number_format($product->product_price,2)}}</strong></p><br><br id="br-line">
                    <p><strong>Description: {{$product->description}}</strong></p><br><br id="br-line">
                    <p><strong>Store Name: {{$product->store_name}}</strong></p><br><br id="br-line">
                    <p><strong>Created On: {{$product->created_at}}</strong></p><br><br id="br-line">
                    @if($product->status == 'Less')
                        <button class="comment-button-loader" type="button" onclick="showCommentForm()"><i class="fa fa-comment"></i></button><br><br>
                        @elseif($product->status == 'Good')
                        <button class="comment-button-loader" id="hidder-component" type="button" onclick="showMessage()"><i class="fa fa-comment"></i></button><br><br>
                    @endif
                </div><br>
                
                <div class="minor-builder-product">
                    <div class="top-notch-containers">
                        <h1>Quantity On Hand</h1>
                        <span>{{ $product->product_quantity }}</span>
                    </div><br><br><br>
                    <hr>
                    <table>
                        <tr>
                            <th>Location</th>
                            <th>Quantity</th>
                        </tr>
                        @foreach($transfers as $transfer)
                        @endforeach
                            @php
                                $productName = is_array($transfer['product_name']) ? implode(',', $transfer['product_name']) : $transfer['product_name'];
                                $otherStoreQuantity = is_array($transfer['store_name']) ? implode(',', $transfer['store_name']) : $transfer['store_name'];
                                
                                $transferedQuantity = is_array($transfer['product_quantity']) ? implode(',', $transfer['product_quantity']) : $transfer['product_quantity'];
                                $transferedQuantity = floatval($transferedQuantity);  // Convert to a numeric value
                            @endphp
                            <tr>
                                <td>{{ $product->store_name }}</td>
                                <td>{{ floatval($product->product_quantity) - $transferedQuantity }}</td>
                            </tr>
                            <tr>
                                <td>{{ $otherStoreQuantity }}</td>
                                <td>{{ $transferedQuantity }}</td>
                            </tr>
                       

                    </table>
                </div>

                <div class="graph-analytical">
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <!-- <canvas id="productChart" width="400" height="200"></canvas> -->
                <style>
                    @media only screen and (max-width:768px){
                        .graph-analytical canvas{
                            display:block;
                            height:400px !important;
                            widt: 100% !important;
                        }
                    }
                </style>
                </div>
                <script>
    // Extract data from PHP to JavaScript
    var chartData = @json($chartData);

    var labels = chartData.map(function(item) {
        return item.created_at;
    });

    var quantities = chartData.map(function(item) {
        return item.quantity;
    });

    // Create the chart
    var ctx = document.getElementById('productChart').getContext('2d');
    var productChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Quantity Sold',
                data: quantities,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 2
            }]
        },
        options: {
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Date'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Quantity'
                    }
                }
            }
        }
    });
</script>
            </div>
        </div>

        <form action="/exports" method="POST" class="product-creator-ajax-wrapper" enctype="multipart/form-data">
            @csrf
            <h1>Export a Product</h1><br><br>
            <input type="hidden" name="product_image" value="{{$product->product_image}}" style="border:none;" accept="image/*">
            <!-- <label>Product Id:</label>
            <input type="text" name="product_id" value="{{$product->product_id}}"><br><br> -->
            <label>Product Name:</label>
            <input type="text" name="product_name" value="{{$product->product_name}}"><br><br>
            <label>Customer Name:</label>
            <input type="text" name="customer_name" placeholder="Customer Name..."><br><br>
            <input type="hidden" name="staff_name" value="{{Auth::guard('web')->user()->staff_name}}">
            <label>Quantity:</label>
            <input type="text" name="product_quantity" value="{{$product->product_quantity}}"><br><br>
            <label>Total Price:</label>
            <input type="text" name="product_price" value="{{$product->product_quantity * $product->product_price}}"><br><br>
            <button type="submit" class="button">Submit Product</button> 
            <button type="button" class="close-button" onclick="closePopUpForm()">Close</button>
            <br><br>
        </form>

        <form action="/products/edit/{{$product->id}}" method="POST" class="product-creator-ajax-wrapper" id="product-creator-ajax-wrapper" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <h1>Import a Product</h1><br><br>
            <label>Product Id:</label>
            <input type="text" name="product_id" value="{{$product->product_id}}"><br><br>
            <label>Product Name:</label>
            <input type="text" name="product_name" value="{{$product->product_name}}"><br><br>
            <label>Quantity:</label>
            <input type="text" name="product_quantity" value="{{$product->product_quantity}}"><br><br>
            <label>Product Price:</label>
            <input type="text" name="product_price" value="{{$product->product_price}}"><br><br>
            <label>Store Name:</label>
            <select name="store_name">
                <option value="">Select Store</option>
                @foreach($stores as $store)
                <option value="{{$store->store_name}}">{{$store->store_name}}</option>
                @endforeach
            </select><br>
            @error('store_name')
            <span>Store name is required!</span>
            @enderror
            <br>
            <label>Description:</label>
            <input type="text" name="description" value="{{$product->description}}"><br><br>
            <label>Product Image:</label>
            <input type="file" name="product_image" value="{{$product->product_image}}" style="border:none;" accept="image/*"><br><br>
            <button type="submit" class="button">Import Product</button> 
            <button type="button" class="close-button" onclick="closePopUpForm()">Close</button>
            <br><br>
        </form>

        <form action="/transfers" method="POST" class="product-creator-ajax-wrapper" id="transfer-complete-product-wr" enctype="multipart/form-data">
            @csrf
            <h1>Transfer a Product</h1><br><br>
            <!-- <label>Product Id:</label>
            <input type="text" name="product_id[]" value="{{$product->product_id}}"><br><br> -->
            <input type="hidden" name="source_store[]" id="" value="{{$product->store_name}}">
            <label>Product Name:</label>
            <input type="text" name="product_name[]" value="{{$product->product_name}}"><br><br>
            <input type="hidden" name="staff_name[]" value="{{Auth::guard('web')->user()->staff_name}}">
            <label>Store Name:</label>
            <select name="store_name[]">
                <option value="">Select Store</option>
                @foreach($stores as $store)
                @if($product->store_name != $store->store_name)
                <option value="{{$store->store_name}}">{{$store->store_name}}</option>
                @endif
                @endforeach
            </select><br>
            @error('store_name')
            <span>Store name is required!</span>
            @enderror
            <br>
            <label>Recommend:</label>
            <select name="staff_recommeded[]">
                <option value="">Select a Staff</option>
                @foreach($users as $user)
                @if($user->id != Auth::guard('web')->user()->id)
                <option value="{{$user->staff_name}}">{{$user->staff_name}}</option>
                @endif
                @endforeach
            </select><br>
            @error('staff_recommeded')
            <span>Recommend a staff please!</span>
            @enderror
            <br>
            <input type="hidden" name="product_image[]" value="{{$product->product_image}}" style="border:none;" accept="image/*">
            <label>Quantity:</label>
            <input type="text" name="product_quantity[]" placeholder="Product quantity..."><br><br>
            <label for="">Reason:</label>
            <input type="text" name="reason[]" id="" placeholder="Reason for transfer..."><br><br>
            <input type="hidden" name="product_price[]" value="{{$product->product_price}}">
            <button type="submit" class="button">Transfer Product</button> 
            <button type="button" class="close-button" onclick="closePopUpForm()">Close</button>
            <br><br>
        </form>

        <form action="/comments" method="POST" class="product-creator-ajax-wrapper" id="comment-less-product" enctype="multipart/form-data">
            @csrf
            <h1>Comment On This Product</h1><br><br>
            <label>Product Name:</label>
            <input type="text" value="{{$product->product_name}}" name="product_name"><br><br>
            <label>Comment:</label>
            <input type="text" name="comment" placeholder="Write a comment..."><br><br>
            <input type="hidden" name="store_name" value="{{$product->store_name}}">
            <input type="hidden" name="commented_staff" value="{{Auth::guard('web')->user()->staff_name}}">
            <button type="submit" class="button"><i class="fa fa-paper-plane"></i> Send</button> 
            <button type="button" class="close-button" onclick="closePopUpForm()">Close</button>
            <br><br>
        </form>

        <div class="hidden-class-component">
            <p>Can not comment on this product!...
                <span class="close-pop-up-msg" onclick="closePopUpMessage()">&times;</span>
            </p>
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

        function showImportForm(){
            document.getElementById("product-creator-ajax-wrapper").classList.toggle('active');
            document.querySelector('.font-sans-antialiased-admin').style.backgroundColor='rgba(0,0,0,0.5)';
        }

        function showTransferProduct(){
            document.getElementById("transfer-complete-product-wr").classList.toggle('active');
            document.querySelector('.font-sans-antialiased-admin').style.backgroundColor='rgba(0,0,0,0.5)';
        }

        function showCommentForm(){
            document.getElementById("comment-less-product").classList.toggle('active');
            document.querySelector('.font-sans-antialiased-admin').style.backgroundColor='rgba(0,0,0,0.5)';
        }

        function showMessage(){
            document.querySelector('.hidden-class-component').classList.toggle('active');
        }

        function closePopUpMessage(){
            location.reload();
        }
    </script>
</center>
@endsection
