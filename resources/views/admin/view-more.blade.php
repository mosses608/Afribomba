@extends('admin-layout')

@section('content')
<br><br><br>
@include('partials.side-menus')

<div class="black-screeen-view"></div>

<center>
    <div class="main-dashboard-ajax-wrapper">
        <div class="header-intro-ajax">
            <h1>View Order / <strong id="header-id-clowe">U{{ $order->id }}</strong></h1>
            <h2><i class="fas fa-calendar-alt"></i> <span class="currentDate"></span></h2>
            <br>
        </div><br>
        <button class="print-out-order-btn" onclick="printWindow(event)"><i class="fa fa-print"></i> <span>Print Order</span></button><br><br>
        <div class="centered-before-ajax-yz">
            <div class="head-componet">
                <h1><strong>AFRIBOMBA COMPANY LIMITED</strong></h1>
                <h3>P.O.Box 63105, Dar Es Salaam</h3>
                <h3>Ilala - Dar Es Salaam -Livingstone & Mchikichi</h3>
                <h3>No: +255 762 881 188</h3>
                <h3>Email: baracky2000@gmail.com</h3>
            </div>
            <div class="left-printed-panel">
                <h2><strong>Order Details</strong></h2><br><br>
                <h3><strong>Order Id: U{{ $order->id }}</strong></h3><br>

                @php
                $containerName = is_array($containerId) ? implode(',', $containerId) : $container->container_id;
                @endphp
                    @foreach($containers as $container)
                    @if($container->container_id == $containerName)
                    <h3>
                    <strong>Container Name: {{ $container->name }}</strong>
                    </h3><br>
                    <h3><strong>Coantiner Capacity: {{ $container->capacity }} CBM</strong></h3><br>
                    <h3><strong>Tare Weight: {{ number_format($container->tare_weight) }} KG</strong></h3><br>
                    <h3><strong>Gross Weight: {{ number_format($container->gross_weight) }} KG</strong></h3><br>
                    <h3><strong>Load Weight: {{ number_format($container->max_payload) }} KG</strong></h3><br>
                    @endif
                    @endforeach
                <h3><strong>Date Created: {{$order->created_at->format('Y-m-d')}}</strong></h3><br>
                <h3><strong>Issued By: {{ is_array($staffName) ? implode(',', $staffName) : $staffName }}</strong></h3>
                <br>
                <br>
                <div class="bottom-middle-printed-panel">
                    <table>
                        <tr class="tr-th">
                            <th>Container ID</th>
                            <th>Quantity Ordered</th>
                        </tr>

                        @foreach($productName as $index => $product)
                        <tr>
                            <td>{{ $product }}</td>
                            <td>{{ $quantity[$index] }}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td><strong>Total</strong></td>
                            <td><strong>{{ array_sum($quantity) }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>

            <style>
                @media print{
                    body *{
                        visibility:hidden;
                    }
                    .centered-before-ajax-yz{
                        visibility:visible;
                        position:absolute;
                        left:0%;
                        top:1%;
                        width:100%;
                    }
                    .centered-before-ajax-yz h1,h2,h3,strong{
                        visibility:visible;
                    }
                    .bottom-middle-printed-panel{
                        visibility: visible;
                    }

                    .bottom-middle-printed-panel table{
                        visibility:visible;
                    }

                    .bottom-middle-printed-panel table tr,th,td{
                        visibility:visible;
                    }
                    #header-id-clowe{
                        visibility:hidden;
                    }
                    .left-printed-panel h2 strong{
                        color: #000;
                        background-color: blue;
                    }
                }
            </style>
            
        </div>
        <br>

        <script>
            window.printWindow = function(event){
                event.preventDefault();
                window.print();
            }
        </script>
</div>
</center>
@endsection