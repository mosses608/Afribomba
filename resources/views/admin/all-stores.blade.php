@extends('admin-layout')

@section('content')
<br><br><br>
@include('partials.side-menus')

<x-store_created_flash />

<x-warning_flash />

<x-delete_success />

<x-warning_flash />

<x-success_store_edit />


<center>
    <div class="main-dashboard-ajax-wrapper">
        <div class="header-intro-ajax">
            <h1>All Stores</h1>
            <h2><i class="fas fa-calendar-alt"></i> <span class="currentDate"></span></h2>
            <br>
        </div><br>
        <div class="centered-before-ajax">
            <form action="/admin/all-stores" method="GET" class="search-component">
                @csrf
                <input type="text" name="search" id="searchInput" onkeyup="searchProducts()" placeholder="Search store...">
                <!-- <button type="submit"><span>Search</span></button> -->
            </form>
            <button class="add-product-button" onclick="showAddStoreForm()"><i class="fa fa-plus"></i> <span>Add Store</span></button>
        </div><br><br>
        <div class="flex-wrapper-container">
            <div class="mini-container">
                <table>
                    <tr class="product-table-header">
                    <th>#</th>
                    <th>Store Id</th>
                    <th>Store Name</th>
                    <th>Location</th>
                    <th>Action</th>
                    </tr>
                    @foreach($stores as $store)
                    <tr class="product-tr-td">
                        <td>{{$store->id}}</td>
                        <td>{{$store->store_id}}</td>
                        <td>{{$store->store_name}}</td>
                        <td>{{$store->store_location}}</td>
                        <td style="text-align:center; padding:6px;" class="action-bar">
                        <a href="#" onclick="showDeleteDialog(event, {{$store->id}})" class="delete-dialog-sh" style="color:red;"><i class="fa fa-trash"></i></a>
                        <a href="#" class="edit-form-dialog" onclick="showUpdateForm(event, {{$store->id}})"><i class="fa fa-edit"></i></a>
                        <a href="/admin/single-store/{{$store->id}}"><i class="fa fa-eye"></i></a>

                            <form id="delete-form-{{$store->id}}" action="/stores/delete/{{$store->id}}" method="POST" class="delete-form-act hidden">
                            @csrf
                            @method('DELETE')
                            <p>Are you sure you want to delete this {{$store->store_name}}?</p>
                            <button type="button" class="no-confirm-btn">No</button>
                            <button type="submit" class="yes-confirm-btn">Yes</button>
                            </form>


                            <form action="/stores/editstore/{{$store->id}}" id="product-creator-ajax-wrapper-{{$store->id}}" method="POST" class="product-creator-ajax-wrapper-w hidden">
                            @method('PUT')
                            @csrf
                            <label for="">Store Id:</label>
                            <input type="text" name="store_id" value="{{$store->store_id}}"><br><br>
                            <label for="">Store Name:</label>
                            <select name="store_name">
                                <option value="{{$store->store_name}}">Choose Store</option>
                                <option value="Godown">Godown</option>
                                <option value="Store">Store</option>
                            </select><br><br>
                            <label for="">Store Location:</label>
                            <input type="text" name="store_location" value="{{$store->store_location}}"><br><br>
                            <button type="submit" class="button">Update Store</button> 
                            <button type="button" class="close-button" onclick="closePopUpForm({{$store->id}})">Close</button>
                            </form>

                        </td>
                    </tr>
                    @endforeach
                </table>

                <style>
        .hidden {
            display: none;
        }
    </style>
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

        <form action="/stores" method="POST" class="product-creator-ajax-wrapper" enctype="multipart/form-data">
            @csrf
            <h1>Add a Store</h1><br><br>
            <label for="">Store Id:</label>
            <input type="text" name="store_id" id="" placeholder="Store Id..."><br><br>
            <label for="">Store Name:</label>
            <select name="store_name" id="">
                <option value="//">Choose Store</option>
                <option value="Godown">Godown</option>
                <option value="Store">Store</option>
            </select><br><br>
            <label for="">Store Location:</label>
            <input type="text" name="store_location" id="" placeholder="Store Location..."><br><br>
            <button type="submit" class="button">Add Store</button> 
            <button type="button" class="close-button" onclick="closePopUpFormy()">Close</button>
            <br><br>
        </form>

        <script>
        function showAddStoreForm(){
            document.querySelector('.product-creator-ajax-wrapper').classList.toggle('active');
            document.querySelector('.product-creator-ajax-wrapper').style.opacity='1';
            document.querySelector('.font-sans-antialiased-admin').style.backgroundColor='rgba(0,0,0,0.5)';
        }

        function closePopUpFormy(){
            document.querySelector('.product-creator-ajax-wrapper').style.display='none';
            location.reload();
        }

        
    </script>

    <!-- <script>
      document.addEventListener("DOMContentLoaded", function() {
    window.showDeleteDialog = function(event, storeId) {
        event.preventDefault();
        const deleteForm = document.getElementById(`delete-form-${storeId}`);
        deleteForm.classList.remove('hidden');
        document.querySelector('.product-creator-ajax-wrapper').style.opacity='1';
        document.querySelector('.font-sans-antialiased-admin').style.backgroundColor='rgba(0,0,0,0.5)';
        // location.reload();
    };

    

    const noConfirmButtons = document.querySelectorAll('.no-confirm-btn');
    noConfirmButtons.forEach(button => {
        button.addEventListener('click', function() {
            const deleteForm = button.closest('.delete-form-act');
            deleteForm.classList.add('hidden');
            location.reload();
        });
    });
});


    </script> -->

<script>
    document.addEventListener("DOMContentLoaded", function() {
        window.showDeleteDialog = function(event, storeId) {
            event.preventDefault();
            const deleteForm = document.getElementById(`delete-form-${storeId}`);
            deleteForm.classList.remove('hidden');
        };

        window.showUpdateForm = function(event, storeId) {
            event.preventDefault();
            const updateForm = document.getElementById(`product-creator-ajax-wrapper-${storeId}`);
            updateForm.classList.remove('hidden');
        };

        window.closePopUpForm = function(storeId) {
            const updateForm = document.getElementById(`product-creator-ajax-wrapper-${storeId}`);
            updateForm.classList.add('hidden');
        };

        const noConfirmButtons = document.querySelectorAll('.no-confirm-btn');
        noConfirmButtons.forEach(button => {
            button.addEventListener('click', function() {
                const deleteForm = button.closest('.delete-form-act');
                deleteForm.classList.add('hidden');
            });
        });
    });
</script>

    </div>
</center>
@endsection