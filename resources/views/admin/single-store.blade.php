@extends('admin-layout')

@section('content')
<br><br><br>
@include('partials.side-menus')

<x-store_created_flash />

<x-warning_flash />

<x-success_store_edit />

<center>
    <div class="main-dashboard-ajax-wrapper">
        <div class="header-intro-ajax">
            <h1>{{$store->store_name}} </h1>
            <h2><i class="fas fa-calendar-alt"></i> <span class="currentDate"></span></h2>
            <br>
        </div><br>
        <div class="centered-before-ajax">
            <button class="sale-product-btn" onclick="showExportForm()"><i class="fa fa-upload"></i> <span>Sale Product</span></button> <button class="transfer-product-btn" onclick="showTransferProduct()">Transfer Product</button>
            <form action="/admin/all-stores" method="GET" class="search-component">
                @csrf   
                <input type="text" name="search" id="searchInput" onkeyup="searchProducts()" placeholder="Search...">
                <!-- <button type="submit"><span>Search</span></button> -->
            </form>
            <button class="add-product-button" onclick="showAddStoreForm()"><i class="fa fa-edit"></i> <span>Edit Store</span></button>
        </div><br><br>
        <div class="flex-wrapper-container">
            <div class="mini-container">
                <table>
                    <tr class="product-table-header">
                        <th>#</th>
                        <th>Image</th>
                        <th>Product Name</th>
                        <th>Description</th>
                        <th>Quantity Available</th>
                        <th>Action</th>
                    </tr>

                    @foreach($products as $product)
                    <tr class="product-tr-td">                    
                        @if($product->store_name == $store->store_name)
                        <td>{{$product->id}}</td>
                        <td style="text-align:center; padding:6px;"><img src="{{$product->product_image ? asset('storage/' . $product->product_image): asset('assets/images/background-logo.png')}}" alt="Image"></td>
                        <td style="text-align:center; padding:6px;">
                        {{$product->product_name}}
                        </td>
                        <td>{{$product->description}}</td>
                        <td style="text-align:center; padding:6px;">
                            {{$product->product_quantity}}
                        </td>
                        <td>
                            <!-- <button onclick="editBar(event, $product->id)"><i class="fas fa-edit"></i></button> -->
                            <button onclick="showEditProForm(event, $product->id)"><i class="fa fa-trash"> </i></button>
                            <form action="/products/edit/{{$product->id}}" method="POST" class="delete-store-dt hidden" id="delete-store-{{$product->id}}">
                                @csrf
                                @method('DELETE')
                                <p>You are about to delete {{$product->product_name}}</p>
                                <button type="submit" class="Confirm-delete">Confirm</button>
                                <button type="button" class="close-form">&times;</button>
                            </form>
                        </td>
                        @endif
                    </tr>
                    @endforeach

                    @foreach($transfers as $transfer)
                        <tr>
                            @php
                                $transferStoreName = is_array($transfer->store_name) ? implode(',', $transfer->store_name) : $transfer->store_name;
                                $transferProductName = is_array($transfer->product_name) ? implode(',', $transfer->product_name) : $transfer->product_name;
                            @endphp

                            @if(is_array($storeName) && in_array($transferStoreName, $storeName) || $transferStoreName == $storeName)
                                @if(is_array($productName) && in_array($transferProductName, $productName) || $transferProductName == $productName)
                                    <td>{{ $transfer->id }}</td>
                                    <td>
                                        <img src="{{ $product->product_image ? asset('storage/' . $product->product_image) : asset('assets/images/background-logo.png') }}" alt="Image">
                                    </td>
                                    <td>{{ is_array($productName) ? implode(',', $productName) : $transfer->product_name }}</td>
                                    <td>{{ __('Fine') }}</td>
                                    <td>{{ is_array($productQuantity) ? implode(',', $productQuantity) : $transfer->product_quantity }}</td>
                                    <td>
                                        <button onclick="showEditTransForm(event, {{ $transfer->id }})"><i class="fas fa-trash-alt"></i></button>
                                        <form action="/transfer/edit/{{ $transfer->id }}" method="POST" class="delete-store-dt hidden" id="delete-store-dt-{{ $transfer->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <p>You are about to delete {{ is_array($productName) ? implode(',', $productName) : $transfer->product_name }}</p>
                                            <button type="submit" class="Confirm-delete">Confirm</button>
                                            <button type="button" class="close-form">&times;</button>
                                        </form>
                                    </td>
                                @endif
                            @endif
                        </tr>
                    @endforeach
                   
                </table>
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
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function(){
                window.showEditProForm = function(event, productId){
                    event.preventDefault();
                    const productForm = document.getElementById(`delete-store-${productId}`);
                    productForm.style.display='block';
                }

                window.showEditTransForm = function(event, transferId){
                    event.preventDefault();
                    const transferDialog = document.getElemntById(`delete-store-dt-${transferId}`);
                    transferDialog.style.display='block';
                }
            });
        </script>


        
        <form action="/exports" method="POST" class="ajax-wrapper-creator" enctype="multipart/form-data" id="product-creator-ajax-wrapper">
    @csrf
    <span class="closeItem" onclick="closeItem()">&times;</span>

    <div class="added-component">
        <div class="appendable-min-comp">
        <div class="inp-select-opta">
            <label>Product Name:</label><br>
            <select name="product_name[]" id="product-name-select" class="product-name-select">
                <option value="">Select Product Name</option>
                @foreach($products as $product)
                <option value="{{$product->product_name}}"
                        data-price="{{$product->product_price}}"
                        data-quantity="{{$product->product_quantity}}">
                    {{$product->product_name}}
                </option>
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
            <input type="text" name="product_quantity[]" class="product-quantity">
        </div>

        <div class="inp-select-optc">
            <label>Unit Price:</label><br>
            <input type="text" name="product_price[]" class="product-price">
        </div>

        <div class="client-phone">
            <label for="">Client Phone: (Optional)</label><br>
            <input type="text" name="phone[]" id="" placeholder="Client phone number">
        </div>

        </div>
        <br><br><br><br>
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
            placeholder: 'Select Product Name',
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
                            <option value="">Select Product Name</option>
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
                placeholder: 'Select Product Name',
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
                <option value="">Choose Store</option>
               
                    <option value="{{$store->store_name}}">
                        {{$store->store_name}}
                    </option>
               
               
            </select>
        </div>
        <div class="select-md2">
            <label>Product Name:</label><br>
            <select name="product_name[]" class="product-select-md2">
                <option value="">Choose Product</option>
                @foreach($products as $product)
                    <option value="{{$product->product_name}}"
                            data-quantity="{{$product->product_quantity}}">
                        {{$product->product_name}}, {{$product->product_quantity}}
                    </option>
                @endforeach
            </select>
        </div>

        <input type="hidden" name="staff_name[]" value="{{Auth::guard('web')->user()->staff_name}}">
        <div class="select-md3">
            <label>Destination Store:</label><br>
            <select name="store_name[]">
                <option value="">Select Store</option>
                
                    <option value="{{$store->store_name}}">{{$store->store_name}}</option>
                
            </select>
            @error('store_name')
            <span>Store name is required!</span>
            @enderror
        </div>
        <div class="select-md-4">
            <label>Recommend:</label><br>
            <select name="staff_recommeded[]">
                <option value="">Select a Staff</option>
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
                            <option value="">Choose Product</option>
                            @foreach($products as $product)
                                <option value="{{$product->product_name}}"
                                        data-quantity="{{$product->product_quantity}}">
                                    {{$product->product_name}}, {{$product->product_quantity}}
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



        <form action="/stores/delete/{{$store->id}}" method="POST" class="delete-confirm-cancel">
            @csrf
            @method('DELETE')
            <p>Are sureyou want to delete {{$store->store_name}} ?</p>
            <button type="submit" class="yes-option">Yes</button> <button type="button" class="no-option" onclick="closeDialog()">No</button>
        </form>

        <form action="/stores/editstore/{{$store->id}}" method="POST" class="product-creator-ajax-wrapper" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <h1>Edit Store</h1><br><br>
            <label for="">Store Id:</label>
            <input type="text" name="store_id" id="" value="{{$store->store_id}}"><br><br>
            <label for="">Store Name:</label>
            <select name="store_name" id="">
                <option value="{{$store->store_name}}">Choose Store</option>
                <option value="Godown">Godown</option>
                <option value="Store">Store</option>
            </select><br><br>
            <label for="">Store Location:</label>
            <input type="text" name="store_location" id="" value="{{$store->store_location}}"><br><br>
            <button type="submit" class="button">Edit Store</button> 
            <button type="button" class="close-button" onclick="closePopUpForm()">Close</button>
            <br><br>
        </form>

        <script>
        function showAddStoreForm(){
            document.querySelector('.product-creator-ajax-wrapper').classList.toggle('active');
            document.querySelector('.product-creator-ajax-wrapper').style.opacity='1';
            document.querySelector('.font-sans-antialiased-admin').style.backgroundColor='rgba(0,0,0,0.5)';
        }

        function closePopUpForm(){
            document.querySelector('.product-creator-ajax-wrapper').style.display='none';
            location.reload();
        }

        function showDeleteDialog(){
            document.querySelector('.delete-confirm-cancel').classList.toggle('active');
            document.querySelector('.product-creator-ajax-wrapper').style.opacity='1';
            document.querySelector('.font-sans-antialiased-admin').style.backgroundColor='rgba(0,0,0,0.5)';
        }

        function closeDialog(){
            location.reload();
        }
    </script>

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
    </div>
</center>
@endsection