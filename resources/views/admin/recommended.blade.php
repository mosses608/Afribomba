@extends('admin-layout')

@section('content')
<br><br><br>
@include('partials.side-menus')

<center>
    <div class="main-dashboard-ajax-wrapper">
        <div class="header-intro-ajax">
            <h1>Recommended Products</h1>
            <h2><i class="fas fa-calendar-alt"></i> <span class="currentDate"></span></h2>
            <br>
        </div><br>
        <div class="centered-before-ajax">
            <form action="/admin/recommended" method="GET" class="search-component">
                @csrf
                <input type="text" name="search" id="searchInput" onkeyup="searchProducts()" placeholder="Filter items...">
                <!-- <button type="submit"><span>Filter</span></button> -->
            </form>
            <button class="add-product-button" onclick="printDoc()"><i class="fa fa-print"></i> <span>Print</span></button>
        </div><br><br>
        <style>
            @media print{
                body * {
                    visibility:hidden;
                }
                .visible-table{
                    visibility:visible;
                }
                footer,header{
                    visibility:hidden;
                }
                table,td,th{
                    color:#000;
                    border:1px solid #999;
                    visibility:visible;
                }
                .status-check,.fa,.image-print{
                    visibility:visible;
                }
                .mini-container{
                    visibility:visible;
                    position:absolute;
                    left:0%;
                    top:1%;
                    width:100%;
                }
                .fa{
                    visibility:hidden;
                }
            }
        </style>
        <div class="flex-wrapper-container">
            <div class="mini-container">
                <table class="visible-table">
                    <tr class="product-table-header">
                    <th>#</th>
                    <!-- <th>Image</th> -->
                    <!-- <th>Product Id</th> -->
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Recommended</th>
                    <th>Source</th>
                    <th>Destination</th>
                    <th>Status</th>
                    <th>Date Created</th>
                    <!-- <th>Action</th> -->
                    </tr>
                    @foreach($transfers as $transfer)
                    <tr class="product-tr-td">
                       
                        <td>{{is_array($productId) ? implode(',', $productId) : $transfer->id}}</td>
                        <!-- <th><img src="{{asset('storage/' . $transfer->product_image)}}" alt="Image" class="image-print"></th> -->
                        <!-- <td>{{$transfer->product_id}}</td> -->
                        <td>
                            {{is_array($productName) ? implode(',', $productName): $transfer->product_name}}
                        </td>
                        <td>
                            {{is_array($productQuantity) ? implode(',', $productQuantity): $transfer->product_quantity}}
                        </td>
                        <td>
                            {{is_array($staffRecommended) ? implode(',', $staffRecommended): $transfer->staff_recommeded}}
                        </td>
                        <td>
                            {{is_array($sourceStore) ? implode(',', $sourceStore): $transfer->source_store}}
                        </td>
                        <td>
                            {{is_array($destinationStore) ? implode(',', $destinationStore): $transfer->store_name}}
                        </td>
                        <td>
                            @if($transfer->status !='')
                            <span class="status-check"><i class="fa fa-check"></i>{{$transfer->status}}</span>
                            @else
                            <span class="status-check">Unchecked</span>
                            @endif
                        </td>
                        <td>
                            {{ is_array($createdAt) ? implode(',', $createdAt): $transfer->created_at}}
                        </td>
                        <!-- <td><a href="/admin/single-export/{{$transfer->id}}"><i class="fa fa-eye"></i></a></td> -->
                    </tr>
                    @endforeach
                </table>

                @if(count($transfers) == 0)
                <p>No product found!</p>
                @endif
            </div>
        </div>

        <script>
            function printDoc(){
                window.print();
            }
        </script>

        <script>
            function searchProducts() {
            const input = document.getElementById('searchInput').value.toLowerCase();
            const products = document.querySelectorAll('.product-tr-td');

            products.forEach(product => {
                const name = product.querySelector('td:nth-child(4)').textContent.toLowerCase();
                const id = product.querySelector('td:nth-child(3)').textContent.toLowerCase();
                
                const isVisible = name.includes(input) || id.includes(input);
                product.style.display = isVisible ? '' : 'none';
            });
        }
        </script>
    </div>
</center>
@endsection