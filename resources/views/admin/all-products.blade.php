@extends('admin-layout')

@section('content')
<br><br><br>
@include('partials.side-menus')

<x-product_created />

<x-exist_flash_msg />

<x-export_message />

<x-success_transfer />

<x-success_delete_product />

<x-not_enough />

<x-import_success />

<center>
    <div class="main-dashboard-ajax-wrapper">
        <div class="header-intro-ajax">
            <h1>All Products</h1>
            <h2><i class="fas fa-calendar-alt"></i> <span class="currentDate"></span></h2>
            <br>
        </div><br>
        <div class="centered-before-ajax">
        <form id="searchForm" method="GET" class="search-component">
            @csrf
            <input type="text" name="search" id="searchInput" onkeyup="searchProducts()" placeholder="Search product...">
        </form>
            <button class="transfer-product-button" onclick="showTransferProduct()"><i class="fa fa-plus"></i> <em>T</em>  <span>Transfer Product</span></button>
            <button class="add-product-button" onclick="showAddProductForm()"><i class="fa fa-plus" style="padding:4px;"></i> <span>Add Product</span></button>
            <button class="export-product-wrapper" onclick="showExportForm()"><i class="fa fa-upload"></i> <span>Sale Product</span></button>
        </div><br><br>
        @php
        $totalQuantity = 0;
        @endphp
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
                    <!-- <th>Date Created</th> -->
                    <th>Action</th>
                    </tr>
                    @foreach($products as $product)
                    <tr class="product-tr-td">
                        <td>{{$product->id}}</td>
                        <td><a href="{{asset('storage/'. $product->product_image)}}"><img src="{{asset('storage/'. $product->product_image)}}" alt=""></a></td>
                        <td>{{$product->product_id}}</td>
                        <td>{{$product->product_name}}</td>
                        
                        <td>
                            {{$product->product_quantity}}
                        </td>
                        <td>
                            @if($product->level <= $product->product_quantity)
                                <p class="good-status">Instock</p>
                            @elseif($product->level >= $product->product_quantity && $product->product_quantity != '0')
                                <p class="less-status">Less</p>
                            @elseif($product->quantity == '0')
                                <p class="out-stock">OutStock</p>
                            @endif
                        </td>
                        <td>Tsh {{number_format($product->product_price)}}</td>
                        <td>{{$product->description}}</td>

                        <!-- <td>{{$product->created_at}}</td> -->
                        <td style="text-align:center;">
                            <a href="/admin/single-export/{{$product->id}}"><i class="fa fa-eye"></i></a>
                            <a href="#" style="color:green;" onclick="showEditDialog(event, {{$product->id}})"><i class="fa fa-edit"></i></a>
                            <a href="#" style="color:red;" onclick="showDeleteDialog(event, {{$product->id}})"><i class="fa fa-trash"></i></a>


                            <form action="/products/delete/{{$product->id}}" id="delete-dialog-{{$product->id}}" method="POST" class="delete-dialog hidden">
                            @csrf
                            @method('DELETE')
                            <p>Deleting {{$product->product_name}} can not be reversed!</p>
                            <button class="close-dialog" type="button">&times;</button>
                            <button type="submit" class="confirm-delete">Confirm</button>
                            </form>



                            <form action="/products/edit/{{$product->id}}" id="product-creator-ajax-wrapper-w-{{$product->id}}" method="POST" class="product-creator-ajax-wrapper-w hidden" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <h1>Edit Product</h1><br><br>
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
                                    <option value="{{$product->store_name}}">--select--</option>
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
                                <button type="button" class="close-button" onclick="closePopUpForm()" style="float:left;">Close</button>
                                <button type="submit" class="button" style="float:right;">Import Product</button> 
                                <br><br>
                            </form>

                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
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
            <input type="number" name="product_quantity" id="" placeholder="Product Quantity..."><br><br>
            <label for="">Product Price:</label>
            <input type="number" name="product_price" id="" placeholder="Product Price..."><br><br>
            <label for="">Store Name:</label>
            <select name="store_name" id="">
                <option value="empty">--select--</option>
                @foreach($stores as $store)
                <option value="{{$store->store_name}}">{{$store->store_name}}</option>
                @endforeach
            </select><br><br>
            <label for="">Description:</label>
            <input type="text" name="description" id="" placeholder="Product description..."><br><br>
            <label for="">Status Level</label>
            <input type="number" name="level" id="" placeholder="Product status level"><br><br>
            <label for="">Product Image:</label>
            <input type="file" name="product_image" id="" style="border:none;" accept="image/*"><br><br>
            <button type="button" class="close-button" onclick="closePopUpForm()">Close</button>
            <button type="submit" class="button">Submit Product</button>
            <br><br>
        </form>



        <form action="/exports" method="POST" class="ajax-wrapper-creator" enctype="multipart/form-data" id="product-creator-ajax-wrapper">
            @csrf
            <span class="closeItem" onclick="closeItem()">&times;</span>

            <div class="added-component">
                <div class="appendable-min-comp">
                <div class="inp-select-opta">
                    <label>Customer Name:</label><br>
                    <input type="text" name="tin[]" placeholder="Customer name">
                </div>
                <div class="inp-select-opta">
                    <label>TIN:</label><br>
                    <input type="text" name="customer_name[]" placeholder="TIN">
                </div>
                <div class="client-phone">
                    <label for="">Client Phone</label><br>
                    <input type="text" name="phone[]" id="" placeholder="Phone number">
                </div>

                <div class="inp-select-optb"> 
                <label>Product Name:</label><br>
                    <select name="product_name[]" id="product-name-select" class="product-name-select">
                        <option value="">--select--</option>
                        @foreach($products as $product)
                        <option value="{{$product->product_name}}"
                                data-price="{{$product->product_price}}"
                                data-quantity="{{$product->product_quantity}}">
                            {{$product->product_name}}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="inp-select-optc">
                    <input type="hidden" name="staff_name[]" value="{{Auth::guard('web')->user()->staff_name}}">
                    <label>Quantity:</label><br>
                    <input type="text" name="product_quantity[]" class="product-quantity">
                </div>

                <div class="inp-select-optc">
                    <label>Unit Price:</label><br>
                    <input type="text" name="product_price[]" class="product-price">
                </div>

                <div class="inp-select-optc">
                <label>Sale Mode:</label><br>
                <select name="sale_mode[]" id="">
                    <option value="" selected disabled>--select sale mode--</option>
                    <option value="Paid">{{__('Full Paid')}}</option>
                    <option value="Loan">By Loan</option>
                </select>
                </div>
                <div class="inp-select-optc">
                    <label>Payment Date:</label><br>
                    <input type="date" name="payment_date[]" class="product-price">
                </div>

                </div>
                <br>
                <!-- <br><br><br><br> -->
            </div>

            <button type="submit" class="button-sale-add">Submit Product</button>
            <button type="button" class="add-button" onclick="addForm()"><i class="fa fa-plus"></i></button>

            <br><br>
        </form>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2 for dynamic dropdown
        $('#product-name-select').select2({
            placeholder: '--select--',
            allowClear: true
        });

        // Update fields on product selection
        $('#product-name-select').on('change', function() {
            var selectedOption = $(this).find('option:selected');
            var price = selectedOption.data('price');
            var quantity = selectedOption.data('quantity');
            
            $(this).closest('.appendable-min-comp').find('.product-price').val(price);
            $(this).closest('.appendable-min-comp').find('.product-quantity').val(quantity);
        });

        // Add new product form dynamically
        $('.add-button').on('click', function() {
            var appendableChild = `
                <div class="appendable-min-comp">
                    <div class="inp-select-opta">
                        <label>Product Name:</label><br>
                        <select name="product_name[]" class="product-name-select">
                            <option value="">--select--</option>
                            @foreach($products as $product)
                            <option value="{{$product->product_name}}"
                                    data-price="{{$product->product_price}}"
                                    data-quantity="{{$product->product_quantity}}">
                                {{$product->product_name}}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="inp-select-optc">
                        <input type="hidden" name="staff_name[]" value="{{Auth::guard('web')->user()->staff_name}}">
                        <label>Quantity:</label><br>
                        <input type="text" name="product_quantity[]" class="product-quantity">
                    </div>

                    <div class="inp-select-optc">
                        <label>Unit Price:</label><br>
                        <input type="text" name="product_price[]" class="product-price">
                    </div>
                    <br><br><br><br>
                </div>
            `;

            $('.added-component').append(appendableChild);
            
            // Reinitialize Select2 for the new dropdown
            $('.product-name-select').select2({
                placeholder: '--select--',
                allowClear: true
            });

            // Attach change event to the new dropdown
            $('.product-name-select').last().on('change', function() {
                var selectedOption = $(this).find('option:selected');
                var price = selectedOption.data('price');
                var quantity = selectedOption.data('quantity');
                
                $(this).closest('.appendable-min-comp').find('.product-price').val(price);
                $(this).closest('.appendable-min-comp').find('.product-quantity').val(quantity);
            });
        });
    });
</script>




<form action="/transfers" method="POST" class="product-transfer-ajax-wrapper" id="transfer-complete-product-wr" enctype="multipart/form-data">
    @csrf
    <h1>Transfer a Product</h1>
    <span class="closeItem" onclick="closeItem()">&times;</span><br>
    <br><br>

    <div class="appendable-child">
        <div class="all-append-class">
        <div class="select-md1">
            <label for="">Source Store: </label><br>
            <select name="source_store[]">
                <option value="">--select--</option>
                @foreach($stores as $store)
                
                    <option value="{{$store->store_name}}">
                        {{$store->store_name}}
                    </option>
               
                @endforeach
            </select>
        </div>

        <div class="select-md3">
            <label>Destination Store:</label><br>
            <select name="store_name[]">
                <option value="">--select--</option>
                @foreach($stores as $store)
                    <option value="{{$store->store_name}}">{{$store->store_name}}</option>
                @endforeach
            </select>
            @error('store_name')
            <span>Store name is required!</span>
            @enderror
        </div>
        
        <div class="select-md2">
            <label>Product Name:</label><br>
            <select name="product_name[]" class="product-select-md2">
                <option value="">--select--</option>
                @foreach($products as $product)
                    <option value="{{$product->product_name}}"
                            data-quantity="{{$product->product_quantity}}">
                        {{$product->product_name}}
                    </option>
                @endforeach
            </select>
        </div>

        <input type="hidden" name="staff_name[]" value="{{Auth::guard('web')->user()->staff_name}}">
        
        <div class="select-md-4">
            <label>Recommend:</label><br>
            <select name="staff_recommeded[]">
                <option value="">--select--</option>
                @foreach($users as $user)
                    @if($user->id != Auth::guard('web')->user()->id)
                        <option value="{{$user->staff_name}}">{{$user->staff_name}}</option>
                    @endif
                @endforeach
            </select>
            @error('staff_recommeded')
            <span>Recommend a staff please!</span>
            @enderror
        </div>
        <div class="select-md-5">
            <label>Quantity:</label><br>
            <input type="text" name="product_quantity[]" class="product-quantity" placeholder="Product quantity...">
        </div>
        <div class="select-md6">
            <label for="">Reason:</label><br>
            <input type="text" name="reason[]" placeholder="Reason for transfer...">
        </div>
        </div>
    </div>
    
    <br><br><br><br><br>
    <button type="submit" class="button-submit">Submit</button> 
    <button type="button" class="append-component"><i class="fa fa-plus"></i></button>
    <br><br><br>
</form>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2 for dynamic dropdown
        $('.product-select-md2').select2({
            placeholder: 'Choose Product',
            allowClear: true
        });

        // Update quantity field on product selection
        $('.product-select-md2').on('change', function() {
            var selectedOption = $(this).find('option:selected');
            var quantity = selectedOption.data('quantity');
            
            $(this).closest('.all-append-class').find('.product-quantity').val(quantity);
        });

        // Add new product form dynamically
        $('.append-component').on('click', function() {
            var appendableChild = `
                <div class="all-append-class">
                    <div class="select-md2">
                        <label>Product Name:</label><br>
                        <select name="product_name[]" class="product-select-md2">
                            <option value="">--select--</option>
                            @foreach($products as $product)
                                <option value="{{$product->product_name}}"
                                        data-quantity="{{$product->product_quantity}}">
                                    {{$product->product_name}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="select-md-5">
                        <label>Quantity:</label><br>
                        <input type="text" name="product_quantity[]" class="product-quantity" placeholder="Product quantity...">
                    </div>
                </div><br><br>
            `;

            $('.appendable-child').append(appendableChild);
            
            // Reinitialize Select2 for the new dropdown
            $('.product-select-md2').select2({
                placeholder: 'Choose Product',
                allowClear: true
            });

            // Attach change event to the new dropdown
            $('.product-select-md2').last().on('change', function() {
                var selectedOption = $(this).find('option:selected');
                var quantity = selectedOption.data('quantity');
                
                $(this).closest('.all-append-class').find('.product-quantity').val(quantity);
            });
        });
    });
</script>



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
        function showTransferProduct(){
            document.querySelector('.product-transfer-ajax-wrapper').classList.toggle('active');
            document.querySelector('.product-creator-ajax-wrapper').style.opacity='1';
            document.querySelector('.font-sans-antialiased-admin').style.backgroundColor='rgba(0,0,0,0.5)';
        }

        function closeItem(){
            location.reload();
        }
    </script>



<!-- <script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('.append-component').addEventListener('click', function() {
        var appendableChild = document.createElement('div');
        appendableChild.className = 'appendable-child';
        appendableChild.innerHTML = `
            
            <div class="select-md2">
    <label>Product Name:</label><br>
    <select name="product_name[]" id="product-select-md2">
        <option value="">Choose Product</option>
        @foreach($products as $product)
            <option value="{{$product->product_name}}">
                {{$product->product_name}}, {{$product->product_quantity}}
            </option>
        @endforeach
    </select>
</div>
        

            <div class="select-md-5">
                <label>Quantity:</label><br>
                <input type="text" name="product_quantity[]" placeholder="Product quantity...">
            </div>
            
        `;

        document.querySelector('.product-transfer-ajax-wrapper').appendChild(appendableChild);
    });


});

$(document).ready(function() {
    $('#product-select-md2').select2({
        placeholder: 'Choose Product',
        allowClear: true
    });
});


</script> -->



<script>
    document.addEventListener("DOMContentLoaded", function() {
    window.showDeleteDialog = function(event, productId){
        event.preventDefault();
        const deleteForm = document.getElementById(`delete-dialog-${productId}`);
        deleteForm.classList.remove('hidden');
        document.querySelector('.product-creator-ajax-wrapper').style.opacity='1';
        document.querySelector('.font-sans-antialiased-admin').style.backgroundColor='rgba(0,0,0,0.5)';
    }

    window.showEditDialog = function(event, productId){
        const editDialog = document.getElementById(`product-creator-ajax-wrapper-w-${productId}`);
        editDialog.classList.remove('hidden');
        document.querySelector('.product-creator-ajax-wrapper').style.opacity='1';
        document.querySelector('.font-sans-antialiased-admin').style.backgroundColor='rgba(0,0,0,0.5)';
    }
});

const closeEditDialog = document.querySelectorAll('.close-button');

closeEditDialog.forEach(button=>{
    button.addEventListener('click', function(){
        const ediForm = button.closest('.product-creator-ajax-wrapper-w');
        editForm.classList.add('hidden');
    });
});



const closeDialog = document.querySelectorAll('.close-dialog');
closeDialog.forEach(button=>{
    button.addEventListener('click', function(){
        const formDialog = button.closest('.delete-dialog');
        formDialog.classList.add('hidden');
        location.reload();
    });
});
</script>


</center>
@endsection