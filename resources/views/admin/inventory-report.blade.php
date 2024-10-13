@extends('admin-layout')

@section('content')
<br><br><br>
@include('partials.side-menus')

<center>
    <div class="main-dashboard-ajax-wrapper">
        <div class="header-intro-ajax">
            <h1>Inventory Reports</h1>
            <h2><i class="fas fa-calendar-alt"></i> <span class="currentDate"></span></h2>
            <br>
        </div><br>
        <div class="centered-before-ajax-yz">
        <form action="/admin/inventory-report" method="GET" class="form-filter-component">
            @csrf
            <div class="filter-input">
                <input type="date" name="start_date" id="start_date" placeholder="Start date...">
                <input type="date" name="end_date" id="end_date" placeholder="End date...">
                <button type="submit" class="submit-btn"><span>Filter</span></button>
            </div>
        </form>

        <form action="/admin/inventory-report" method="GET" class="search-by-name">
            @csrf
            <select name="search" id="">
                <option value="" selected disabled>--filter by store name--</option>
                <option value="">All</option>
                @foreach($stores as $store)
                <option value="{{$store->store_name}}">{{$store->store_name}}</option>
                @endforeach
            </select>
            <button type="submit" class="submit-filter-btn">Filter</button>
        </form>
        <div class="action-handler-btn">
            <button class="print-product-button" onclick="printDoc()"><i class="fa fa-print"></i> <span>Print</span></button>
            <button class="transfer-report-button" onclick="printDocyy()"><a href="/admin/transfer-report">Transfer Report</a></button>
        </div>
        </div>
        <br>

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
                table{
                    border-radius:10px;
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
                .all-prices p{
                    visibility:visible;
                }
                .all-quantity p{
                    visibility: visible;
                }
                .image-reporte a img{
                    visibility:visible;
                }
            }
        </style>
        
        <div class="flex-wrapper-container">
            <div class="mini-container">
            <table class="printable-media">
                <tr class="product-table-header">
                    <th>S/N</th>
                    <th>Product No</th>
                    <th>Product Name</th>
                    <!-- <th>Quantity Ordered</th> -->
                    <th>Quantity In</th>
                    <th>Quantity Out</th>
                    <th>Quantity On Hand</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>

                @php
                $totalQuantity = 0;
                $totalPrices = 0;
                @endphp

                @foreach($products as $product)
                <tr class="tr-sold-prod">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $product->product_id }}</td>
                    <td>{{ $product->product_name }}</td>
                    <!-- <td>{{ $product->quantity_ordered }}</td> -->
                    <td>{{ $product->product_quantity }}</td> <!-- Quantity In -->
                    <td>{{ $product->quantity_out }}</td> <!-- Calculated Quantity Out -->
                    <td>{{ $product->quantity_on_hand }}</td> <!-- Calculated Quantity On Hand -->
                    <td>{{ number_format($product->product_price, 2) }}</td>
                    <td>{{ number_format($product->quantity_on_hand * $product->product_price,2) }}</td>
                </tr>
                @endforeach

                <tr class="tr-sold-prod">
                    <td>Total</td>
                    <td></td>
                    <td></td>
                    <td>
                        {{number_format($ToatlQuantityIn)}}
                    </td>
                    <td>
                        {{number_format($TotalQuantityOut)}}
                    </td>
                    <td>
                        {{number_format($totalQuantityOnHand)}}
                    </td>
                    <td></td>
                    <td>
                        Tsh {{number_format($totalPrice,2)}}
                    </td>
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