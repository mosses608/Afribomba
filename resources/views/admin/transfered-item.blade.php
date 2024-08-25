@extends('admin-layout')

@section('content')
<br><br><br>
@include('partials.side-menus')

<x-product_created />

<x-exist_flash_msg />

<x-export_message />

<center>
    <div class="main-dashboard-ajax-wrapper">
        <div class="header-intro-ajax">
            <h1>Transfer Details</h1>
            <h2><i class="fas fa-calendar-alt"></i> <span class="currentDate"></span></h2>
            <br>
        </div><br>
        <div class="centered-before-ajax">
            <!-- <form action="/admin/all-products" method="GET" class="search-component">
                @csrf
                <input type="text" name="search" id="" placeholder="Search product..."><button type="submit"><span>Search</span></button>
            </form> -->
            <!-- <button class="export-product-button" onclick="showAddProductFormy()"><i class="fa fa-plus"></i> <span>Export Product</span></button> -->
            <button class="add-product-button" onclick="printTransferDoc()"><i class="fa fa-print"></i> <span> Print</span></button>
        </div><br><br>
        <div class="flex-wrapper-container">
            
        <style>
            @media print{
                body *{
                    visibility:hidden;
                }
                head{
                    visibility:hidden;
                }
                footer{
                    visibility:hidden;
                }
                .printable-visible{
                    visibility:visible;
                    position:absolute;
                    top:1%;
                    left:0%;
                    width:100%;
                    overflow:none;
                }
                .mini-container{
                    visibility:hidden;
                }
                .sub-main-counter h1,span,p,strong{
                    visibility:visible;
                }
                .line-breaker-point br{
                    visibility:hidden;
                }
                .transfered-status{
                    font-weight:700;
                }
                .source-main-destina{
                    visibility:visible;
                }
                th,tr,td{
                    visibility:visible;
                }
                strong{
                    color:#000;
                }
                .header-tr th{
                    color:#000;
                }
                td,th{
                    padding:6px;
                    border:1px solid #999;
                }
                .header-tr td{
                    border:1px solid #999;
                }

            }
        </style>


            <div class="mini-container">
                <div class="printable-visible">
                <div class="sub-main-counter">
                    <h1 class="id-specifier"><strong>U{{$transfer->id}}</strong></h1><br><br> 
                    <span class="transfered-status"><strong>{{__('Transfered')}}</strong></span><br><br>
                    <p>Date: <strong>{{ is_array($createdAt) ? implode(', ', $createdAt) : $transfer->created_at }}</strong></p><br>
                    <p>Created By: <strong>{{ is_array($staff_name) ? implode(', ', $staff_name) : $staff_name }}</strong></p><br>
                    <p>Reason: <strong>{{ is_array($reason) ? implode(', ', $reason) : $reason }}</strong></p><br><br>
                    <p>Transfer order has been received by: <strong>{{ is_array($staff_recommended) ? implode(', ', $staff_recommended) : $transfer->staff_recommeded }}</strong></p><br>
                    <p>Date: <strong>{{ is_array($createdAt) ? implode(', ', $createdAt) : $transfer->created_at  }}</strong></p><br>

                </div><div class="line-breaker-point"><br><br><br><br><br><br><br><br><br><br><br></div>
                <table class="source-main-destina">
                    <tr>
                        <th>{{__('Source Store')}}</th>
                        <th>{{__('Destination Store')}}</th>
                    </tr>
                    <tr>
                        <td>{{__('AFRIBOMBA COMPANY LIMITED')}}</td>
                        <td>{{__('AFRIBOMBA COMPANY LIMITED')}}</td>
                    </tr>
                    <tr>
                        <td>@if(is_array($sourceStores))
                                {{ implode(', ', $sourceStores) }}
                            @else
                                {{ $sourceStores }}
                            @endif
                        </td>
                        <td> @if(is_array($destinationStores))
                                {{ implode(', ', $destinationStores) }}
                            @else
                                {{ $destinationStores }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>{{__('Dar Es Salaam')}}</td>
                        <td>{{__('Dar Es Salaam')}}</td>
                    </tr>
                </table>
                <br><br>
                <h1 class="header-specifier"><strong>{{__('Items')}}</strong></h1>

                @php
                $totalproductQuantity = 0;
                @endphp

                @foreach($productNames as $index => $productName)
                @php

                $quantity = $productQuantities[$index];

                $totalproductQuantity += $quantity;
                @endphp
                @endforeach
                
                <table class="items-loader-viewer">
        <tr class="header-tr">
            <th>Item</th>
            <th>Quantity Transferred</th>
        </tr>
        @foreach($productNames as $index => $productName)
        
        <tr class="tr-td">
            <td><p style="float:left;">{{ $productName }}</p></td>
            <td>{{ $productQuantities[$index] }}</td>
        </tr>
       
    @endforeach

    <tr>
        <td><strong>Total</strong></td>
        <td><strong>{{is_array($totalproductQuantity) ? implode(',', $totalproductQuantity): $totalproductQuantity}} Items</strong></td>
    </tr>

</table>
<br><br>

            </div>
        </div>
    </div>

    <script>
        function showAddProductForm(){
            document.querySelector('.product-creator-ajax-wrapper').classList.toggle('active');
            document.querySelector('.product-creator-ajax-wrapper').style.opacity='1';
            document.querySelector('.font-sans-antialiased-admin').style.backgroundColor='rgba(0,0,0,0.5)';
        }

        function closePopUpForm(){
            document.querySelector('.product-creator-ajax-wrapper').style.display='none';
            location.reload();
        }

        function showExportForm(){
            document.querySelector('.product-creator-ajax-wrapper').style.opacity='1';
            document.querySelector('.font-sans-antialiased-admin').style.backgroundColor='rgba(0,0,0,0.5)';
            document.getElementById("product-creator-ajax-wrapper").classList.toggle('active');
        }

        function printTransferDoc(){
            window.print();
        }
    </script>
</center>
@endsection