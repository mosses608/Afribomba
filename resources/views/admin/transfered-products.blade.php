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
            <h1>Transfered Products</h1>
            <h2><i class="fas fa-calendar-alt"></i> <span class="currentDate"></span></h2>
            <br>
        </div><br>
        <div class="centered-before-ajax">
            <form action="/admin/all-products" method="GET" class="search-component">
                @csrf
                <input type="date" name="search" id="" placeholder="Search product..."><button type="submit"><span>Search</span></button>
            </form>
            <!-- <button class="export-product-button" onclick="showAddProductFormy()"><i class="fa fa-plus"></i> <span>Export Product</span></button> -->
            <button class="add-product-button" onclick="showAddProductForm()"><i class="fa fa-print"></i> <span> Print</span></button>
        </div><br><br>
        <div class="flex-wrapper-container">
            <div class="mini-container">
                
                @if(count($transfers) == 0)
                <p>No product transfered!</p>
                @endif

                @foreach($transfers as $transfer)
                @if($transfer->created_at->format('Y-m-d') == $nowDate)
               <div class="single-loop-wrapper">
                <a href="/admin/transfered-item/{{$transfer->id}}"><p><strong>U{{$transfer->id}}</strong>, {{$transfer->created_at}}</p></a><br>
               </div>
               @endif
               @endforeach
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
    </script>
</center>
@endsection