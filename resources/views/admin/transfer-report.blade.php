@extends('admin-layout')

@section('content')
<br><br><br>
@include('partials.side-menus')

<center>
    <div class="main-dashboard-ajax-wrapper">
        <div class="header-intro-ajax">
            <h1>Transfer Reports</h1>
            <h2><i class="fas fa-calendar-alt"></i> <span class="currentDate"></span></h2>
            <br>
        </div><br>
        <div class="centered-before-ajax">
        <form action="/admin/transfer-report" method="GET" class="search-component-inp">
            @csrf
            <div class="breaker-liner0">
                <label for="From">From</label><br>
                <input type="date" name="start_date" id="start_date" placeholder="Start date...">
            </div>
            <div class="line-breaker-01">
                <label for="">To</label><br>
                <input type="date" name="end_date" id="end_date" placeholder="End date...">
            </div>
            <button type="submit"><span>Filter</span></button>
        </form><br>
        <button class="print-product-button" onclick="printDoc()"><i class="fa fa-print"></i> <span>Print</span></button>
        </div><br><br>

        <style>
            @media print{
                body * {
                    visibility:hidden;
                }
                .printable-media,table,td,th{
                    visibility:visible;
                }
                td,th,table{
                    border:1px solid #999;
                }
                .printable-media{
                    position:absolute;
                    left:0%;
                    top:1%;
                    width:100%;
                }
                .tr-sold-prod{
                    visibility:visible;
                }
                .td-prodt-name p{
                    visibility:visible;
                }
                .all-quantity p{
                    visibility:visible;
                }
                .all-prices p{
                    visibility: visible;
                }
            }
        </style>
        
        <div class="flex-wrapper-container">
            <div class="mini-container">
                <table class="printable-media">
                    <tr class="product-table-header">
                    <th>#</th>
                    <!-- <th>Product Id</th> -->
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Date Created</th>
                    </tr>

                    @php
                    $myquantities = 0;
                    @endphp

                    @foreach($transfers as $product)
                    <tr class="tr-sold-prod">
                    <td>{{$product->id}}</td>
                    <!-- <td>
                        @foreach($productIds as $id)
                            <div>{{ $id }}</div>
                        @endforeach
                    </td> -->
                    <td class="td-prodt-name">
                        <button class="view-productName" onclick="showNames(event, {{$product->id}})"><i class="fa fa-eye"></i> View Names</button>
                        <div class="all-product-names" id="all-product-names-{{$product->id}}">
                        @foreach($productNames as $name)
                            <p>{{ $name }}</p>
                        @endforeach
                        </div>
                    </td>
                    <td class="td-quantity">
                        <button class="view-quantity" onclick="showQuantity(event, {{$product->id}})"><i class="fa fa-eye"></i> View Quantities</button>
                        <div class="all-quantity" id="all-quantity-{{$product->id}}">
                        @foreach($quantities as $qty)
                            <p>{{ $qty }}</p>
                        @endforeach
                        </div>
                    </td>
                    <td class="all-prices">
                        @foreach($prices as $p)
                            <p>Tsh {{ number_format($p) }}</p>
                        @endforeach
                    </td>
                    <td>
                        {{$product->created_at}}
                    </td>
                </tr>


                    @endforeach
                    <tr>
                        <td></td>
                        <td></td>
                        <td>
                        @php
                            $totalQuantity = 0;
                            @endphp
                            @foreach($quantities as $quantity)
                                @php
                                    $totalQuantity += $quantity;
                                @endphp
                            @endforeach
                            <!-- {{ $totalQuantity }} -->
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
            </div>
        </div>

        <script>
            function printDoc(){
                window.print();
            }

            document.addEventListener('DOMContentLoaded', function(){
                window.showNames = function(event, nameId){
                    const namesClass = document.getElementById(`all-product-names-${nameId}`);
                    namesClass.style.display='block';
                }
            });
        </script>
    </div>
</center>
@endsection