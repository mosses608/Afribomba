@extends('staff-layout')

@section('content')
<br><br><br>
@include('partials.staff-side-menu')

<x-store_created_flash />

<x-warning_flash />

<x-delete_success />

<center>
    <div class="main-dashboard-ajax-wrapper">
        <div class="header-intro-ajax">
            <h1>All Stores</h1>
            <h2><i class="fas fa-calendar-alt"></i> <span class="currentDate"></span></h2>
            <br>
        </div><br>
        <div class="centered-before-ajax">
            <form action="/staff/all-stores" method="GET" class="search-component">
                @csrf
                <input type="text" name="search" id="" placeholder="Search store..."><button type="submit"><span>Search</span></button>
            </form>
            <!-- <button class="add-product-button" onclick="showAddStoreForm()"><i class="fa fa-plus"></i> <span>Add Store</span></button> -->
        </div><br><br>
        <div class="flex-wrapper-container">
            <div class="mini-container">
                <table>
                    <tr class="product-table-header">
                    <th>#</th>
                    <th>Store Id</th>
                    <th>Store Name</th>
                    <th>Location</th>
                    </tr>
                    @foreach($stores as $store)
                    <tr>
                        <td>#</td>
                        <td>{{$store->store_id}}</td>
                        <td>{{$store->store_name}}</td>
                        <td>{{$store->store_location}}</td>
                    </tr>
                    @endforeach
                </table>

                @if(count($stores) == 0)
                <p>No store found!</p>
                @endif
                
            </div>
        </div>

        <!-- <form action="/stores" method="POST" class="product-creator-ajax-wrapper" enctype="multipart/form-data">
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
            <button type="button" class="close-button" onclick="closePopUpForm()">Close</button>
            <br><br>
        </form> -->

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
    </script>
    </div>
</center>
@endsection