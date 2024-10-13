@extends('admin-layout')

@section('content')
<br><br><br>
@include('partials.side-menus')

<x-edit_success_sale />

<center>
    <div class="main-dashboard-ajax-wrapper">
        <div class="header-intro-ajax">
            <h1>Exported Products</h1>
            <h2><i class="fas fa-calendar-alt"></i> <span class="currentDate"></span></h2>
            <br>
        </div><br>
        <div class="centered-before-ajax">
            <button class="print-docs" onclick="printDoc()"><i class="fa fa-print"></i></button>
        </div><br><br>

        <div class="flex-wrapper-container">
            
            <div class="printable-meta-data">
                <h1><strong>AFRIBOMBA COMPANY LIMITED</strong></h1>
                <h3>P.O.Box 63105, Dar Es Salaam</h3>
                <h3>Ilala - Dar Es Salaam -Livingstone & Mchikichi</h3>
                <h3>No +255 762 881 188</h3>
                <h3>Email baracky2000@gmail.com</h3>
                <br><br>
                
                <div class="left-side-metadata">
                    <h3>Invoice Number: <strong>S{{$product->id}}</strong></h3><br>
                    <h3>Invoice Date: <strong>{{ \Carbon\Carbon::parse($product->created_at) }}</strong></h3><br>
                    <!-- <h3>Due Date: <strong>{{ \Carbon\Carbon::parse($product->created_at)->format('Y-m-d') }}</strong></h3><br> -->
                    <h3>Issued By: <strong>{{ Auth::guard('web')->user()->staff_name }}</strong></h3><br>
                    <h3>Issued Email: <strong>{{ Auth::guard('web')->user()->staff_email }}</strong></h3><br>
                </div><br><br><br><br><br>
                <div class="down-metadata">
                    <h1><strong>Bill To: </strong></h1><br><br>
                    @if(is_array($customer_name) ? implode(',', $customer_name) : $product->customer_name != '')
                    <h3 style="margin-left:-10%;">TIN: <strong>{{is_array($customer_name) ? implode(',', $customer_name) : $product->customer_name}}</strong></h3>
                    @endif
                    @if(is_array($tin) ? implode(',', $tin) : $product->tin !='')
                    <h3 style="margin-left:-16%;">Name: <strong>{{is_array($tin) ? implode(',', $tin) : $product->tin}}</strong></h3>
                    @endif
                    @if(is_array($customerPhone) ? implode(', ', $customerPhone) : $product->phone != '')
                    <h3 style="margin-left:0%;">Phone: <strong>{{ is_array($customerPhone) ? implode(', ', $customerPhone) : $product->phone }}</strong></h3>
                    @endif
                    <!-- <h3>Due Date: <strong>{{ \Carbon\Carbon::parse($product->created_at)->addDays(30)->format('Y-m-d') }}</strong></h3><br> -->
                </div><br><br><br><br><br>
                <br>
                
                @php
                    // Decode JSON data
                    $quantities = json_decode($product->product_quantity, true);
                    $prices = json_decode($product->product_price, true);
                    $names = json_decode($product->product_name, true); // Assuming product_name might also be JSON
                    
                    // Initialize totals
                    $totalQuantity = 0;
                    $totalPrice = 0;
                @endphp

                <table class="printable-tr-table" style="width:100%; border-collapse:collapse; border:1px solid #000;">
                    <tr>
                        <th style="border:1px solid #000; padding:8px;">Item</th>
                        <th style="border:1px solid #000; padding:8px;">Quantity</th>
                        <th style="border:1px solid #000; padding:8px;">Unit Price</th>
                        <th style="border:1px solid #000; padding:8px;">Total Price</th>
                    </tr>
                    
                    @foreach($names as $index => $name)
                        @php
                            $quantity = (float)$quantities[$index];
                            $price = (float)$prices[$index];
                            $totalPriceForItem = $quantity * $price;
                            
                            // Update totals
                            $totalQuantity += $quantity;
                            $totalPrice += $totalPriceForItem;
                        @endphp
                        <tr>
                            <td style="border:1px solid #000; padding:8px;">{{ $name }}</td>
                            <td style="border:1px solid #000; padding:8px;">{{ $quantity }}</td>
                            <td style="border:1px solid #000; padding:8px;">{{ number_format($price, 2) }}</td>
                            <td style="border:1px solid #000; padding:8px;">{{ number_format($totalPriceForItem, 2) }}</td>
                        </tr>
                    @endforeach
                    
                    <!-- Summary Row -->
                    <tr style="font-weight: bold;">
                        <td style="border:1px solid #000; padding:8px;">Total</td>
                        <td style="border:1px solid #000; padding:8px;">{{ number_format($totalQuantity) }}</td>
                        <td></td>
                        <td style="border:1px solid #000; padding:8px;">Tsh {{ number_format($totalPrice) }}/=</td>
                    </tr>
                </table>
                <br>
                <p id="parag">Thank you for your business!</p>
                <br><br>
            </div>

            <style>
                @media print{
                    
                    body{
                        visibility:hidden;
                    }

                    .printable-meta-data{
                        position:absolute;
                        left:0%;
                        top:1%;
                        width:100%;
                    }
                    
                    .printable-meta-data h1,h3{
                        visibility:visible;
                    }

                    .down-metadata h1,h3{
                        visibility:visible;
                    }

                    .printable-tr-table{
                        visibility:visible;
                    }
                    #parag{
                        visibility:visible;
                    }
                }
            </style>
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

        function printDoc(){
            window.print();
        }
    </script>
</center>
@stop
