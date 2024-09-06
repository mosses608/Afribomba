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
                    <th>Product Id</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Date Created</th>
                    </tr>

                    @if(count($exports) != "")
                    @foreach($exports as $product)
                    <tr>
                    <td>{{$product->id}}</td>
                    <td>
                        @foreach($productIds as $id)
                            <div>{{ $id }}</div>
                        @endforeach
                    </td>
                    <td>
                        @foreach($productNames as $name)
                            <div>{{ $name }}</div>
                        @endforeach
                    </td>
                    <td>
                        @foreach($quantities as $qty)
                            <div>{{ $qty }}</div>
                        @endforeach
                    </td>
                    <td>
                        @foreach($prices as $p)
                            <div>Tsh {{ number_format($p) }}</div>
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
        </script>
    </div>
</center>
@endsection