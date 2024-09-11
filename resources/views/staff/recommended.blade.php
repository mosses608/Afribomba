@extends('staff-layout')

@section('content')
<br><br><br>
@include('partials.staff-side-menu')

<x-status_updated_success />

<center>
    <div class="main-dashboard-ajax-wrapper">
        <div class="header-intro-ajax">
            <h1>Recommended Products</h1>
            <h2><i class="fas fa-calendar-alt"></i> <span class="currentDate"></span></h2>
            <br>
        </div><br>
        <div class="centered-before-ajax">
            <form action="/staff/recommended" method="GET" class="search-component">
                @csrf
                <input type="date" name="search" id="" placeholder="Filter items..."><button type="submit"><span>Filter</span></button>
            </form>
            <button class="add-product-button" onclick="printDoc()"><i class="fa fa-print"></i> <span>Print</span></button>
        </div><br><br>

        <style>
            @media print{
            title{
                visibility:hidden;
            }
                body * {
                    visibility:hidden;
                }
                .visible-table,#product-img-print{
                    visibility:visible;
                }
                footer,header{
                    visibility:hidden;
                }
                table,td,th{
                    border:1px solid #999;
                    visibility:visible;
                }
                th,td{
                    text-align:center;
                }
            }
        </style>

        <div class="flex-wrapper-container">
            <div class="mini-container">
                <table>
                    <tr class="product-table-header">
                    <th>#</th>
                    <!-- <th>Image</th>
                    <th>Product Id</th> -->
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Staff</th>
                    <th>Store</th>
                    <th>Status</th>
                    <th>Date Created</th>
                    <!-- <th>Action</th> -->
                    </tr>
                    @foreach($transfers as $transfer)
                    @if(is_array($staffRecommended) ? implode(',', $staffRecommended): $transfer->staff_recommeded == Auth::guard('web')->user()->staff_name)
                    <tr>
                        <td>{{$transfer->id}}</td>
                        <td>{{is_array($productName) ? implode(',', $productName) : $transfer->product_name}}</td>
                        <td>{{is_array($quantity) ? implode(',', $quantity) : $transfer->product_quantity}}</td>
                        <td>{{is_array($staffRecommended) ? implode(',', $staffRecommended) : $transfer->staff_recommeded}}</td>
                        <td>{{is_array($storeName) ? implode(',', $storeName) : $transfer->store_name}}</td>
                        <td>
                            @if($transfer->status == '')
                            <form action="/transfers/editstatus/{{$transfer->id}}" method="POST" class="edit-status-recommend">
                                @method('PUT')
                                @csrf
                                <input type="hidden" name="status" id="" value="Checked">
                                <button type="submit">Check</button>
                            </form>
                            @else
                            <span><i class="fa fa-check"></i></span>
                            @endif
                        </td>
                        <td>{{$transfer->created_at}}</td>
                        <!-- <td><a href="/admin/single-export/{{$transfer->id}}"><i class="fa fa-eye"></i></a></td> -->
                    </tr>
                    @endif
                    @endforeach
                </table>

                @if(count($transfers) == 0)
                <br>
                <p>No recommendations todat!</p>
                <br>
                @endif
                
            </div>
        </div>
    </div>

    <script>
        function printDoc(){
            window.print();
        }
    </script>
</center>
@endsection