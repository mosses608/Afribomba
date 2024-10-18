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

<x-order_deleted_flash_msg />

<x-order_updated_msg />

<center>
    <div class="main-dashboard-ajax-wrapper">
        <div class="header-intro-ajax">
            <h1>View Products</h1>
            <h2><i class="fas fa-calendar-alt"></i> <span class="currentDate"></span></h2>
            <br>
        </div><br>
        
        <div class="centered-before-ajax-yz">
            <form action="/admin/view-products" method="GET" class="form-filter-component">
                @csrf
                <div class="filter-input">
                    <input type="date" name="start_date" id="start_date" placeholder="Start date...">
                    <input type="date" name="end_date" id="end_date" placeholder="End date...">
                    <button type="submit" class="submit-btn"><span>Filter</span></button>
                </div>
            </form>

            <button class="view-product-link">
                <a href="/admin/create-orders">&#8592; <span>Back</span></a>
            </button>
            <!-- <button class="order-btn-show" onclick="showPlaceOrderForm(event)"><i class="fa fa-shopping-cart"></i> <span>Order</span></button>
            <button class="order-product-print" onclick="printOrders(event)"><i class="fa fa-print"></i> <span>Print</span></button>
            <button class="add-order-product-class" onclick="createOrderedProduct(event)"><i class="fa fa-plus"></i> <span> Product</span></button> -->
        </div><br>

        <div class="flex-wrapper-container">
            <div class="mini-container">
                <table>
                    <tr class="product-table-header">
                        <th>S/N</th>
                        <th>Product Id</th>
                        <th>Product Name</th>
                        <th>CBM</th>
                        <th>Price</th>
                        <th>Weight</th>
                        <th>Date Created</th>
                        <th>Action</th>
                    </tr>

                    @foreach($posts as $post)
                        <tr class="tr-td-class">
                          <td>{{ $post->id }}</td>
                          <td>{{ $post->product_id }}</td>
                          <td>{{ $post->product_name }}</td>
                          <td>{{ $post->cbm }}</td>
                          <td>TZS {{ number_format($post->price )}}</td>
                          <td>{{ $post->weight }}</td>
                          <td>{{ $post->created_at }}</td>
                          <td>
                            <button class="edit-order-btn" onclick="showEditOrderForm(event, {{ $post->id }})"><i class="fa fa-pencil"></i></button>
                            <button class="delete-order-btn" onclick="showDeleteDialog(event, {{ $post->id }})"><i class="fas fa-trash-alt"></i></button>

                            <form action="/delete-product/{{ $post->id }}" method="POST" class="order-tbl-wrapper" id="delete-product-dialog-{{ $post->id }}" style="top:30%;">
                                @csrf
                                @method('DELETE')
                                <div class="top-notch-form">
                                    <h1>Delete Product ({{ $post->product_name }})</h1>
                                    <button type="button" onclick="hideDeleteOrderForm(event, {{ $post->id }})" class="close-btn-order-wrapper">&times;</button>
                                </div><br><br>
                                <button type="submit" class="yes-action-btn">Confirm Action</button>
                            </form>

                            <form action="/edit-product/{{ $post->id }}" method="POST" class="post-product-ordered" id="order-tbl-wrapper-{{ $post->id }}">
                                @csrf
                                @method('PUT')
                                <div class="top-notch-form">
                                    <h1>Edit {{ $post->product_name }} Here!</h1>
                                    <button type="button" onclick="hideOrderForm(event, {{ $post->id }})" class="close-btn-order-wrapper">&times;</button>
                                </div><br><br>
                                <div class="main-product-id">
                                    <label for="">Product ID</label><br>
                                    <input type="text" name="product_id" id="" value="{{ $post->product_id }}">
                                    <br>
                                    @error('product_id')
                                    <span style="float:left; font-size:10px; color:red; font-style:italic;">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="main-product-name">
                                    <label for="">Product Name</label><br>
                                    <input type="text" name="product_name" id="" value="{{ $post->product_name }}">
                                </div>
                                <div class="unit-measurement-categ">
                                    <div class="minor01">
                                        <label for="">Price / Box (TZS)</label><br>
                                        <input type="text" name="price" id="" value="{{ $post->price }}">
                                    </div>
                                    <div class="minor02">
                                        <label for="">Weight (KG)</label><br>
                                        <input type="text" name="weight" id="" value="{{ $post->weight }}">
                                    </div>
                                </div>
                                <div class="cbm-class" style="margin-left:5%;">
                                    <label for="">Cubic Metres</label><br>
                                    <input type="text" name="cbm" id="" value="{{ $post->cbm }}">
                                </div><br>
                                <button type="submit" class="submit-btn-product">Submit</button>
                            </form>
                          </td>
                        </tr>
                    @endforeach
                </table>
               @if(count($posts) == 0)
               <br>
               <p id="non-order-found">No product found!</p>
               <br>
               @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function(){
            window.showEditOrderForm = function(event, postId){
                const editPostForm = document.getElementById(`order-tbl-wrapper-${postId}`);
                const blackScreenView = document.querySelector('.black-screeen-view');

                editPostForm.style.display='block';
                blackScreenView.style.display='block';
            }

            window.hideOrderForm = function(event, postId){
                const editPostForm = document.getElementById(`order-tbl-wrapper-${postId}`);
                const blackScreenView = document.querySelector('.black-screeen-view');

                editPostForm.style.display='none';
                blackScreenView.style.display='none';
            }

            window.showDeleteDialog = function(event, postId){
                const deleteDialog = document.getElementById(`delete-product-dialog-${postId}`);
                const blackScreenView = document.querySelector('.black-screeen-view');

                deleteDialog.style.display='block';
                blackScreenView.style.display='block';
            }

            window.hideDeleteOrderForm = function(event, postId){
                const deleteDialog = document.getElementById(`delete-product-dialog-${postId}`);
                const blackScreenView = document.querySelector('.black-screeen-view');

                deleteDialog.style.display='none';
                blackScreenView.style.display='none';
            }
        });
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
</center>
@endsection
