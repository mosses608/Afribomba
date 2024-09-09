@extends('admin-layout')

@section('content')
<br><br><br>
@include('partials.side-menus')

<center>
    <div class="main-dashboard-ajax-wrapper">
        <div class="header-intro-ajax">
            <h1>Product Comments</h1>
            <h2><i class="fas fa-calendar-alt"></i> <span class="currentDate"></span></h2>
            <br>
        </div><br>
        <div class="centered-before-ajax">
            <form action="/admin/comments" method="GET" class="search-component">
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
                .printable-visible{
                    visibility:visible;
                }
                footer,header{
                    visibility:hidden;
                }
                table,td,th{
                    border:1px solid #999;
                    visibility:visible;
                }
            }
        </style>

        <div class="flex-wrapper-container">
        <div class="mini-container">
                <table class="printable-visible">
                    <tr class="product-table-header">
                    <!-- <th>#</th> -->
                    <th>Product Name</th>
                    <th>Comment</th>
                    <th>Store</th>
                    <th>Commented Staff</th>
                    <th>Date Created</th>
                    </tr>
                    @foreach($comments as $comment)
                    <tr class="product-tr-td">
                        <!-- <td>{{$comment->id}}</td> -->
                        <td>{{$comment->product_name}}</td>
                        <td>{{$comment->comment}}</td>
                        <td>{{$comment->store_name}}</td>
                        <td>{{$comment->commented_staff}}</td>
                        <td>{{$comment->created_at}}</td>
                    </tr>
                    @endforeach
                </table>

                @if(count($comments) == 0)
                <br>
                <p>No comments found!</p>
                <br>
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