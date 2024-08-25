@extends('staff-layout')

@section('content')
<br><br><br>
@include('partials.staff-side-menu')

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
            <!-- <form action="/admin/exported-products" method="GET" class="search-component">
                @csrf
                <input type="text" name="search" placeholder="Search exported product...">
                <button type="submit"><span>Search</span></button>
            </form> -->
            <!-- <button class="add-product-button" onclick="showAddProductForm()"><i class="fa fa-plus"></i> <span>Sale Product</span></button> -->
        </div>
        <div class="flex-wrapper-container">
            <div class="mini-container">
                <!-- <table>
                    <tr class="product-table-header">
                        <th>Product Id</th>
                        <th>Name</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th>Price</th>
                        <th>Description</th>
                        <th>Store Name</th>
                        <th>Date Created</th>
                        <th>Action</th>
                    </tr>
                    <tr class="single-viewer-holder">
                        <td>{{$product->product_id}}</td>
                        <td>{{$product->product_name}}</td>
                        <td>{{$product->product_quantity}}</td>
                        <td>
                            @if($product->status == 'Good')
                            <p class="good-status">{{$product->status}}</p>
                            @elseif($product->product_quantity <= 20 && $product->product_quantity >= 1)
                            <p class="less-status">{{$product->status}}</p>
                            @elseif($product->product_quantity == 0)
                            <p class="out-stock">Out Stock</p>
                            @endif
                        </td>
                        <td>{{$product->product_price}}</td>
                        <td>{{$product->description}}</td>
                        <td>{{$product->store_name}}</td>
                        <td>{{$product->created_at}}</td>
                        <td>
                            @if($product->status == 'Less')
                            <button class="comment-button-loader" type="button" onclick="showCommentForm()"><i class="fa fa-comment"></i></button>
                            @elseif($product->status == 'Good')
                            <button class="comment-button-loader" id="hidder-component" type="button" onclick="showMessage()"><i class="fa fa-comment"></i></button>
                            @endif
                        </td>
                    </tr>
                </table> -->
                <br>
                <div class="image-holder-view">
                    <img src="{{$product->product_image ? asset('storage/' . $product->product_image) : asset('assets/images/profile.png')}}" alt="">
                </div>
                <div class="left-side-wrapper">
                    <p><strong>Product Id: {{$product->product_id}}</strong></p><br><br>
                    <p><strong>Name: {{$product->product_name}}</strong></p><br><br>
                    <p><strong>Quantity: {{$product->product_quantity}}</strong></p><br><br>
                    @if($product->status == 'Good')
                            <p class="good-status-in"><strong>Status: {{$product->status}}</strong></p><br><br>
                            @elseif($product->product_quantity <= 20 && $product->product_quantity >= 1)
                            <p class="less-status-in"><strong>Status: {{$product->status}}</strong></p><br><br>
                            @elseif($product->product_quantity == 0)
                            <p class="out-stock-in"><strong>Status: Out Stock</strong></p><br><br>
                    @endif
                    <p><strong>Price: Tsh {{number_format($product->product_price)}}</strong></p><br><br>
                    <p><strong>Description: {{$product->description}}</strong></p><br><br>
                    <p><strong>Store Name: {{$product->store_name}}</strong></p><br><br>
                    <p><strong>Created On: {{$product->created_at}}</strong></p><br><br>
                    @if($product->status == 'Less')
                            <button class="comment-button-loader" type="button" onclick="showCommentForm()"><i class="fa fa-comment"></i></button><br><br>
                            @elseif($product->status == 'Good')
                            <button class="comment-button-loader" id="hidder-component" type="button" onclick="showMessage()"><i class="fa fa-comment"></i></button><br><br>
                    @endif
                </div>
                <div class="graph-analytical">
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <canvas id="productChart" width="400" height="200"></canvas>
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
            <input type="hidden" name="product_image[]" value="{{$product->product_image}}" style="border:none;" accept="image/*">
            <label>Product Name:</label>
            <input type="text" name="product_name[]" value="{{$product->product_name}}"><br><br>
            <label>Customer Name:</label>
            <input type="text" name="customer_name[]" placeholder="Customer Name..."><br><br>
            <input type="hidden" name="staff_name[]" value="{{Auth::guard('web')->user()->staff_name}}">
            <label>Quantity:</label>
            <input type="text" name="product_quantity[]" value="{{$product->product_quantity}}"><br><br>
            <label>Total Price:</label>
            <input type="text" name="product_price[]" value="{{$product->product_quantity * $product->product_price}}"><br><br>
            <button type="submit" class="button">Submit Product</button> 
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
