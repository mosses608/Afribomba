@extends('staff-layout')

@section('content')
<br><br><br>

@include('partials.staff-side-menu')

<x-product_created />

<x-exist_flash_msg />

<x-export_message />

<x-not_enough />

<center>
    <div class="main-dashboard-ajax-wrapper">
        <div class="header-intro-ajax">
            <h1>All Products</h1>
            <h2><i class="fas fa-calendar-alt"></i> <span class="currentDate"></span></h2>
            <br>
        </div><br>
        <div class="centered-before-ajax">
            <form action="/staff/all-products" method="GET" class="search-component">
                @csrf
                <input type="text" name="search" id="" placeholder="Search product..."><button type="submit"><span>Search</span></button>
            </form>
            <button class="sale-product-button" id="sale-product-button" onclick="showExportForm()"><i class="fa fa-upload"></i> <span>Sale Product</span></button>
            <button class="add-product-button" id="add-product-button" onclick="showAddProductForm()"><i class="fa fa-plus"></i> <span>Add Product</span></button>
        </div><br><br>
        <div class="flex-wrapper-container">
            <div class="mini-container">
                <table>
                    <tr class="product-table-header">
                    <th>#</th>
                    <th>Image</th>
                    <th>Product Id</th>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Store Name</th>
                    <!-- <th>Date Created</th> -->
                    <th>Action</th>
                    </tr>
                    @foreach($products as $product)
                    <tr>
                        <td>{{$product->id}}</td>
                        <td><a href="{{asset('storage/'. $product->product_image)}}"><img src="{{asset('storage/'. $product->product_image)}}" alt=""></a></td>
                        <td>{{$product->product_id}}</td>
                        <td>{{$product->product_name}}</td>
                        <td>{{$product->product_quantity}}</td>
                        <td>
                            @if($product->status == 'Good')
                                <p class="good-status">{{$product->status}}</p>
                            @else
                                <p class="less-status">{{$product->status}}</p>
                            @endif
                        </td>
                        <td>Tsh {{number_format($product->product_price)}}</td>
                        <td>{{$product->description}}</td>
                        <td>{{$product->store_name}}</td>
                        <!-- <td>{{$product->created_at}}</td> -->
                        <td style="text-align:center;"><a href="/staff/single-export/{{$product->id}}" style="text-align:center;"><i class="fa fa-eye"></i></a></td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>


        
        <form action="/exports" method="POST" class="ajax-wrapper-creator" enctype="multipart/form-data" id="product-creator-ajax-wrapper">
            @csrf
            <span class="closeItem" onclick="closeItem()">&times;</span>

            <div class="added-component">
            @foreach($products as $product)
            <input type="hidden" name="product_image[]" value="{{$product->product_image}}" style="border:none;" accept="image/*">
            @endforeach

            <div class="inp-select-opta">
            <label>Product Name:</label><br>
            <select name="product_name[]">
                <option value="">Select Product Name</option>
                @foreach($products as $product)
                <option value="{{$product->product_name}}">{{$product->product_name}}</option>
                @endforeach
            </select>
            </div>

            <div class="inp-select-optb">
            <label>Customer Name:</label><br>
            <input type="text" name="customer_name[]" placeholder="Customer Name...">
            </div>

            <div class="inp-select-optc">
            <input type="hidden" name="staff_name[]" value="{{Auth::guard('web')->user()->staff_name}}">
            <label>Quantity:</label><br>
            <input type="text" name="product_quantity[]">
            </div>

            <div class="inp-select-optc">
            <label>Unit Price:</label><br>
            <input type="text" name="product_price[]">
            </div>
            <br><br><br><br>

            </div>
            <button type="submit" class="button-sale-add">Submit Product</button> 
            <button type="button" class="add-button" onclick="addForm()"><i class="fa fa-plus"></i></button>

            <br><br>
        </form>

        <script>

            document.addEventListener('DOMContentLoaded', function(){
                document.querySelector('.add-button').addEventListener('click', function(){
                    var appendableChild = document.createElement('div');
                    appendableChild.className = 'added-component';
                    appendableChild.innerHTML =   `
                    @foreach($products as $product)
            <input type="hidden" name="product_image[]" value="{{$product->product_image}}" style="border:none;" accept="image/*">
            @endforeach


            <div class="inp-select-opta">
            <label>Product Name:</label><br>
            <select name="product_name[]">
                <option value="">Select Product Name</option>
                @foreach($products as $product)
                <option value="{{$product->product_name}}">{{$product->product_name}}</option>
                @endforeach
            </select>
            </div>

            <div class="inp-select-optc">
            <input type="hidden" name="staff_name[]" value="{{Auth::guard('web')->user()->staff_name}}">
            <label>Quantity:</label><br>
            <input type="text" name="product_quantity[]">
            </div>

            <div class="inp-select-optc">
            <label>Unit Price:</label><br>
            <input type="text" name="product_price[]">
            </div>
            <br><br><br><br>

                    `;

                    document.querySelector('.ajax-wrapper-creator').appendChild(appendableChild);

                });
            });

    // document.addEventListener('DOMContentLoaded', function(){
    //     const products = @json($products);

    //     document.querySelector('.add-button').addEventListener('click', function(){
    //         var childClass = document.createElement('div');
    //         childClass.className = 'added-component';
    //         childClass.innerHTML = `
    //         <div class="inp-select-opta">
    //         <label>Product Name:</label><br>
    //         <select name="product_name[]">
    //             <option value="">Select Product Name</option>
    //             ${products.map(product => `<option value="${product.product_name}">${product.product_name}</option>`).join('')}
    //         </select>
    //         </div>

    //         <div class="inp-select-optb">
    //         <label>Customer Name:</label><br>
    //         <input type="text" name="customer_name[]" placeholder="Customer Name...">
    //         </div>

    //         <div class="inp-select-optc">
    //         <input type="hidden" name="staff_name[]" value="{{Auth::guard('web')->user()->staff_name}}">
    //         <label>Quantity:</label><br>
    //         <input type="text" name="product_quantity[]">
    //         </div>

    //         <div class="inp-select-optc">
    //         <label>Unit Price:</label><br>
    //         <input type="text" name="product_price[]">
    //         </div>
    //         <br><br><br><br>
    //         `;

    //         document.querySelector('.ajax-wrapper-creator').appendChild(childClass);
    //     });
    // });
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
                @foreach($stores as $store)
                <option value="{{$store->store_name}}">{{$store->store_name}}</option>
                @endforeach
            </select><br><br>
            <label for="">Description:</label>
            <input type="text" name="description" id="" placeholder="Product description..."><br><br>
            <label for="">Product Image:</label>
            <input type="file" name="product_image" id="" style="border:none;" accept="image/*"><br><br>
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

        function showExportForm(){
            document.querySelector('.product-creator-ajax-wrapper').style.opacity='1';
            document.querySelector('.font-sans-antialiased-admin').style.backgroundColor='rgba(0,0,0,0.5)';
            document.getElementById("product-creator-ajax-wrapper").classList.toggle('active');
        }

        function closeItem(){
            location.reload();
        }
    </script>
</center>
@endsection