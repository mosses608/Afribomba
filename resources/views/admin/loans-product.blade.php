@extends('admin-layout')

@section('content')
<br><br><br>
@include('partials.side-menus')

<x-edit_success_sale />

<div class="black-screeen-view"></div>

<center>
    <div class="main-dashboard-ajax-wrapper">
        <div class="header-intro-ajax">
            <h1>Product Loans</h1>
            <h2><i class="fas fa-calendar-alt"></i> <span class="currentDate"></span></h2>
            <br>
        </div><br>
        <div class="centered-before-ajax-yz">
        <form action="/admin/loans-product" method="GET" class="form-filter-component">
            @csrf
            <div class="filter-input">
                <input type="date" name="start_date" id="start_date" placeholder="Start date...">
                <input type="date" name="end_date" id="end_date" placeholder="End date...">
                <button type="submit" class="submit-btn"><span>Filter</span></button>
            </div>
        </form>
        <form action="/admin/loans-product" method="GET" class="search-by-name">
            @csrf
            <select name="search" id="">
                <option value="" selected disabled>--filter by customer name--</option>
                <option value="">All</option>
                @foreach($exports as $export)
                @php
                    $saleStatus = is_array($export['status']) ? implode(',', $export['status']) : $export['status'];
                    $saleMode = is_array($export['saleMode']) ? implode(',', $export['saleMode']) : $export['saleMode'];
                @endphp

                @if($saleMode == "Loan" && $saleStatus != "Paid")
                <option value="["{{is_array($export['customerNames']) ? implode(',', $export['customerNames']) : $export['customerNames']}}"]">{{ is_array($export['customerNames']) ? implode(',', $export['customerNames']) : $export['customerNames'] }}</option>
                @endif
                @endforeach
            </select>
            <button type="submit" class="submit-filter-btn">Filter</button>
        </form>
            <button class="printLoanData" onclick="printLoanData()"><i class="fa fa-print"></i> <span>Print</span></button>
            <button class="view-all-sold"><a href="/admin/all-sales"><i class="fa fa-eye"></i> <span>All Sales</span></a></button>
            <style>
                .view-all-sold{
                    float: right;
                    margin-right: 2%;
                    padding: 6px;
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    background-color: green;
                    font-size: 14px;
                    border-radius: 4px;
                    border: none;
                    color: #FFF;
                }
            </style>
            <br>
        </div><br>
        <div class="flex-wrapper-container">
            <style>
                @media print{
                    h1,h3{
                        visibility: visible;
                    }
                    body * {
                        visibility: hidden;
                    }
                    table{
                        visibility: visible;
                    }
                    .printable-meta-data{
                        position:absolute;
                        left:0%;
                        top:1%;
                        width:100%;
                        visibility: visible;
                    }
                    .product-table-header{
                        visibility: visible;
                    }
                    td,th,table{
                        border:1px solid #999;
                    }
                    .tr-sold-prod{
                        visibility:visible;
                    }
                    tr,th,td{
                        visibility:visible;
                    }
                    th{
                        font-size:13px;
                        font-family: Verdana, Geneva, Tahoma, sans-serif;
                    }
                    .action-td,.action-th{
                        visibility:hidden;
                    }
                    .non-blintxt,.fadeBlink{
                        visibility:visible;
                        font-weight:800;
                        background-color:inherit;
                    }
                }
            </style>
            <div class="mini-container">
                <table class="printable-meta-data">
                    <tr class="product-table-header">
                    <th>Id</th>
                    <th>Product</th>
                    <th>Unit</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Staff</th>
                    <th>Date Created</th>
                    <th>Payment Date</th>
                    <th>Customer</th>
                    <th>Status</th>
                    <th class="action-th">Action</th>
                    </tr>
                    @foreach($exports as $export)
                        @php
                            $saleStatus = is_array($export['status']) ? implode(',', $export['status']) : $export['status'];
                            $saleMode = is_array($export['saleMode']) ? implode(',', $export['saleMode']) : $export['saleMode'];
                        @endphp

                        @if($saleMode == "Loan" && $saleStatus != "Paid")
                            <tr class="tr-sold-prod">
                                <td>S{{$export['id']}}</td>
                                <td>{{ is_array($export['productName']) ? implode(',', $export['productName']) : $export['productName'] }}</td>
                                <td>{{ number_format(is_array($export['unitPrice']) ? implode(',', $export['unitPrice']) : $export['unitPrice'], 2) }}</td>
                                <td>{{ is_array($export['product_quantity']) ? implode(',', $export['product_quantity']) : $export['product_quantity'] }}</td>

                                @if(is_array($export['unitPrice']) && is_array($export['product_quantity']))
                                    <td>
                                        Tsh
                                        @php
                                            $result = [];
                                            foreach($export['unitPrice'] as $index => $price) {
                                                if (isset($export['product_quantity'][$index])) {
                                                    $result[] = number_format($price * $export['product_quantity'][$index], 2);
                                                }
                                            }
                                            echo implode(',', $result);
                                        @endphp
                                    </td>
                                @else
                                    <td>{{ $export['unitPrice'] * $export['product_quantity'] }}</td>
                                @endif

                                <td>{{ is_array($export['staffName']) ? implode(',', $export['staffName']) : $export['staffName'] }}</td>
                                <td>{{ $export['created_at']->format('Y-m-d') }}</td>

                                @php
                                    $paymentDate = is_array($export['payment_date']) ? implode(',', $export['payment_date']) : $export['payment_date'];
                                @endphp

                                @if($todayDate == $paymentDate)
                                    <td><span class="fadeBlink">{{ $paymentDate }}</span></td>
                                @else
                                    <td><span class="non-blintxt">{{ $paymentDate }}</span></td>
                                @endif

                                <td>{{ is_array($export['customerNames']) ? implode(',', $export['customerNames']) : $export['customerNames'] }}</td>
                                <td>
                                    @if(is_array($export['status']) ? implode(',', $export['status']) : $export['status'] != "")
                                    {{ is_array($export['status']) ? implode(',', $export['status']) : $export['status']  }}
                                    @else
                                    {{__('____')}}
                                    @endif
                                </td>
                                <td class="action-td">
                                    <button class="edit-btn-export" onclick="showStatusEditor(event, {{$export['id']}})"><i class="fas fa-edit"></i></button>

                                    <form action="/edit-status/{{$export['id']}}" method="POST" class="edit-status-checker" id="edit-status-checker-{{$export['id']}}">
                                        @csrf
                                        @method('PUT')
                                        <div class="centered-ajx-minor">
                                            <h1>Edit Loan Status</h1>
                                            <button class="close-form-pop" type="button" onclick="closeEditForm(event, {{ $export['id'] }})">&times;</button>
                                        </div><br><br>
                                        <label for="">Loan Status:</label><br>
                                        <select name="status[]" id="">
                                            <option value="{{is_array($export['status']) ? implode(',', $export['status']) : $export['status']}}" selected disabled>--select status--</option>
                                            <option value="Paid">Paid</option>
                                        </select>
                                        <br><br>
                                        <label for="">Payment Date:</label><br>
                                        <input type="date" name="payment_date[]" id="" value="{{is_array($export['payment_date']) ? implode(',', $export['payment_date']) : $export['payment_date']}}">
                                        <br><br>
                                        <button type="submit" class="edit-btn-status-jx">Edit</button>
                                    </form>
                                </td>
                            </tr>
                        @endif
                    @endforeach

                </table>
            </div>
        </div>

        <style>
            .hidden{
                display:none;
            }
        </style>
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

        function printDoc(){
            window.print();
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function(){
            window.showEditSaleForm = function(event, saleId){
                const editForm = document.getElementById(`product-editor-ajax-${saleId}`);
                editForm.classList.add('active');
            }

            window.closeEditForm = function(event, exportId){
                event.preventDefault();
                const blackScreenView = document.querySelector('.black-screeen-view');
                const statusEditor = document.getElementById(`edit-status-checker-${exportId}`);
                statusEditor.style.display='none';
                blackScreenView.style.display='none';
            }

            window.showStatusEditor = function(event, exportId){
                event.preventDefault();
                const blackScreenView = document.querySelector('.black-screeen-view');
                const statusEditor = document.getElementById(`edit-status-checker-${exportId}`);
                statusEditor.style.display='block';
                blackScreenView.style.display='block';
            }
        });

        function printLoanData(){
            window.print();
        }
    </script>
</center>
@endsection
