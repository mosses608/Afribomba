@extends('admin-layout')

@section('content')
<br><br><br>
@include('partials.side-menus')

<center>
    <div class="main-dashboard-ajax-wrapper">
        <div class="header-intro-ajax">
            <h1>Sales Reports</h1>
            <h2><i class="fas fa-calendar-alt"></i> <span class="currentDate"></span></h2>
            <br>
        </div><br>
        <div class="centered-before-ajax">
        <form action="/admin/reports" method="GET" class="search-component-inp">
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
        <button class="add-product-button" onclick="printDoc()"><i class="fa fa-print"></i> <span>Print</span></button>
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

                    @if(count($exports) != "")
                    @foreach($exports as $product)
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
                    <td class="td-price-tg">
                        <button class="price-tag-btn" onclick="showPrices(event, {{$product->id}})"><i class="fa fa-eye"></i> View Prices</button>
                        <div class="all-prices" id="all-prices-$product->id">
                        @foreach($prices as $p)
                            <p>Tsh {{ number_format($p) }}</p>
                        @endforeach
                        </div>
                    </td>
                    <td>
                        {{$product->created_at}}
                    </td>
                </tr>


                    @endforeach
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <!-- <td id="total_price_exported">Tsh {{number_format($datePrice, 2)}}</td> -->
                        <td></td>
                    </tr>
                    @endif
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