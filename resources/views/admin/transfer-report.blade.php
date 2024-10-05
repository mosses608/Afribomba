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
        <div class="centered-before-ajax-yz">
        <form action="/admin/transfer-report" method="GET" class="form-filter-component">
            @csrf
            <div class="filter-input">
                <input type="date" name="start_date" id="start_date" placeholder="Start date...">
                <input type="date" name="end_date" id="end_date" placeholder="End date...">
                <button type="submit" class="submit-btn"><span>Filter</span></button>
            </div>
        </form>
        <div class="action-handler-btn" style="width:60%;">
            <button class="print-product-button" id="print-product-button" onclick="printDoc()"><i class="fa fa-print"></i> <span>Print</span></button>
        </div>
            <br>
        </div><br>

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
                .tr-sold-prod{
                    visibility:visible;
                }
                .tr-sold-prod td strong{
                    visibility:visible;
                }
                .tr-sold-prod p{
                    visibility: visible;
                }
            }
        </style>
        
        <div class="flex-wrapper-container">
            <div class="mini-container">
            <table class="printable-media">
                <tr class="product-table-header">
                    <th>S/N</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <!-- <th>Price</th> -->
                     <th>Source</th>
                     <th>Destination</th>
                     <th>Reason</th>
                     <th>Staff Recommended</th>
                     <th>Status</th>
                    <th>Date Created</th>
                </tr>

                @php
                    $totalQuantity = 0;
                    $totalPrice = 0;
                @endphp

                @foreach($transfers as $product)
                    <tr class="tr-sold-prod">
                        <td>{{ $product->id }}</td>
                        
                        <td class="td-prodt-name">
                            <button class="view-productName" onclick="showNames(event, {{ $product->id }})">
                                <i class="fa fa-eye"></i> View Names
                            </button>
                            <div class="all-product-names" id="all-product-names-{{ $product->id }}">
                                @if(is_array($product->productName))
                                    @foreach($product->productName as $name)
                                        <p>{{ $name }}</p>
                                    @endforeach
                                @else
                                    <p>No names available</p>
                                @endif
                            </div>
                        </td>

                        <td class="td-quantity">
                            <button class="view-quantity" onclick="showQuantity(event, {{ $product->id }})">
                                <i class="fa fa-eye"></i> View Quantities
                            </button>
                            <div class="all-quantity" id="all-quantity-{{ $product->id }}">
                                @if(is_array($product->quantity))
                                    @foreach($product->quantity as $qty)
                                        <p>{{ $qty }}</p>
                                        @php
                                            $totalQuantity += $qty; // Summing the total quantity
                                        @endphp
                                    @endforeach
                                @else
                                    <p>No quantities available</p>
                                @endif
                            </div>
                        </td>
                        <td>
                            <p>{{ is_array($sourceStore) ? implode(',', $sourceStore) : $product->source_store}}</p>
                        </td>
                        <td>
                            {{ is_array($destinationStore) ? implode(',', $destinationStore) : $product->store_name}}
                        </td>
                        <td>
                            {{ is_array($reason) ? implode(',', $reason) : $product->reason}}
                        </td>
                        <td>
                            {{ is_array($staffRec) ? implode(',', $staffRec) : $product->staff_recommeded}}
                        </td>
                        <td>
                            {{ is_array($status) ? implode(',', $status) : $product->status }}
                        </td>

                        <!-- <td class="all-prices">
                            @if(is_array($product->price))
                                @foreach($product->price as $p)
                                    <p>Tsh {{ number_format($p) }}</p>
                                    @php
                                        $totalPrice += $p;
                                    @endphp
                                @endforeach
                            @else
                                <p>No prices available</p>
                            @endif
                        </td> -->

                        <td>{{ $product->created_at }}</td>
                    </tr>
                @endforeach

                <tr class="tr-sold-prod">
                    <td><strong>Total:</strong></td>
                    <td></td>
                    <td><strong>{{ $totalQuantity }}</strong></td>
                    <!-- <td><strong>Tsh {{ number_format($totalPrice) }}</strong></td> -->
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
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