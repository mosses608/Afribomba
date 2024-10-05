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
        <div class="centered-before-ajax-yz">
        <form action="/admin/reports" method="GET" class="form-filter-component">
            @csrf
            <div class="filter-input">
                <input type="date" name="start_date" id="start_date" placeholder="Start date...">
                <input type="date" name="end_date" id="end_date" placeholder="End date...">
                <button type="submit" class="submit-btn"><span>Filter</span></button>
            </div>
        </form>

        <form action="/admin/reports" method="GET" class="search-by-name">
            @csrf
            <select name="search" id="">
                <option value="" selected disabled>--filter by customer name--</option>
                <option value="">All</option>
                @foreach($exports as $export)
                <option value="{{is_array('$customerName') ? implode(',', $customerName) : $export->tin}}">{{is_array('$customerName') ? implode(',', $customerName) : $export->tin}}</option>
                @endforeach
            </select>
            <button type="submit" class="submit-filter-btn">Filter</button>
        </form>
        <div class="action-handler-btn">
            <button class="print-product-button" onclick="printDoc()"><i class="fa fa-print"></i> <span>Print</span></button>
            <button class="transfer-report-button" onclick="printDocyy()"><a href="/admin/transfer-report">Transfer Report</a></button>
            <button class="inventory-report"><a href="/admin/inventory-report">Inventory Report</a></button>
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
            }
        </style>
        
        <div class="flex-wrapper-container">
            <div class="mini-container">
                <table class="printable-media">
                    <tr class="product-table-header">
                    <th>S/N</th>
                    <!-- <th>Product Id</th> -->
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Customer</th>
                    <th>Date Created</th>
                    </tr>

                    @if(count($exports) != "")

                    @foreach($exports as $export)
                    <tr class="tr-sold-prod">
                        <td>{{ $export->id }}</td>

                        <td class="td-prodt-name">
                            <button class="view-productName" onclick="showNames(event, {{ $export->id }})">
                                <i class="fa fa-eye"></i> View Names
                            </button>
                            <div class="all-product-names" id="all-product-names-{{ $export->id }}">
                                @if(is_array($export->productNames))
                                    @foreach($export->productNames as $name)
                                        <p style="font-size:14px;">{{ $name }}</p>
                                    @endforeach
                                @else
                                    <p style="font-size:14px;">{{ $export->productNames }}</p>
                                @endif
                            </div>
                        </td>

                        <td class="td-quantity">
                            <button class="view-quantity" onclick="showQuantity(event, {{ $export->id }})">
                                <i class="fa fa-eye"></i> View Quantities
                            </button>
                            <div class="all-quantity" id="all-quantity-{{ $export->id }}">
                                @if(is_array($export->quantities))
                                    @foreach($export->quantities as $qty)
                                        <p style="font-size:14px;">{{ $qty }}</p>
                                    @endforeach
                                @else
                                    <p style="font-size:14px;">{{ $export->quantities }}</p>
                                @endif
                            </div>
                        </td>

                        <td class="td-price-tg">
                            <button class="price-tag-btn" onclick="showPrices(event, {{ $export->id }})">
                                <i class="fa fa-eye"></i> View Prices
                            </button>
                            <div class="all-prices" id="all-prices-{{ $export->id }}">
                                @if(is_array($export->prices))
                                    @foreach($export->prices as $price)
                                        <p style="font-size:14px;">Tsh {{ number_format($price) }}</p>
                                    @endforeach
                                @else
                                    <p style="font-size:14px;">Tsh {{ number_format($export->prices) }}</p>
                                @endif
                            </div>
                        </td>

                        <td style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size:14px;">
                            @if(is_array($export->customerName))
                                @foreach($export->customerName as $cname)
                                    {{ $cname }}
                                @endforeach
                            @else
                                {{ $export->customerName }}
                            @endif
                        </td>

                        <td style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size:14px;">{{ $export->created_at }}</td>
                    </tr>
                @endforeach


                    <tr class="tr-sold-prod">
                        <td>Total</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td id="total_price_exported">Tsh {{number_format($datePrice, 2)}}</td>
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