@extends('admin-layout')

@section('content')
<br><br><br>
@include('partials.side-menus')

<x-product_created />

<x-exist_flash_msg />

<x-export_message />

<div class="black-screeen-view"></div>

<center>
    <div class="main-dashboard-ajax-wrapper">
        <div class="header-intro-ajax">
            <h1>Transfered Products</h1>
            <h2><i class="fas fa-calendar-alt"></i> <span class="currentDate"></span></h2>
            <br>
        </div><br>
        <div class="centered-before-ajax">
            <form action="/admin/transfered-products" method="GET" class="search-component">
                @csrf
                <input type="date" name="search" id="" placeholder="Search product..."><button type="submit"><span>Search</span></button>
            </form>
            <button class="View-all-transfers-button"><i class="fa fa-eye"></i> <span><a href="/admin/all-transfers">All Transfers</a></span></button>
            <button class="add-product-button" onclick="ViewGraph(event)"><i class="fa fa-eye"></i> <span> View Graph</span></button>
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

        window.ViewGraph = function(event){
            event.preventDefault();
            const graphShow = document.querySelector('.graph-transfer');
            const hideScreenView = document.querySelector('.black-screeen-view');

            graphShow.style.display='block';
            hideScreenView.style.display='block';
        }
        window.closeBtn = function(event){
            event.preventDefault();
            const graphShow = document.querySelector('.graph-transfer');
            const hideScreenView = document.querySelector('.black-screeen-view');

            graphShow.style.display='none';
            hideScreenView.style.display='none';
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="graph-transfer">
        <div class="top-breaker-graph">
            <h1>Product Transfer Analysis Graph</h1>
            <button class="closeBtn" onclick="closeBtn(event)">&times;</button>
        </div>
        <br>
        <canvas id="exportChart" width="400" height="200"></canvas>
    </div>

    <!-- <style>
        @media only screen and (min-width: 769px) {
            .graph-analytics canvas {
                height: 400px !important;
            }
        }

        @media only screen and (max-width: 768px) {
            .graph-analytics canvas {
                width: 100% !important;
                height: 400px !important;
            }
        }
    </style> -->

    <script>
        // Decode the JSON data passed from the controller
        var chartDates = @json($dates); // Array of dates
        var chartData = @json($chartData); // Object with product names as keys and date quantities as values

        // Create the datasets for each product
        var datasets = Object.keys(chartData).map(function(productName) {
            return {
                label: productName,
                data: chartDates.map(function(date) {
                    return chartData[productName][date] || 0; // Use 0 if no data for the date
                }),
                borderWidth: 1,
                fill: false // Use false if you don't want to fill the area under the line
            };
        });

        // Create the chart
        var ctx = document.getElementById('exportChart').getContext('2d');
        var exportChart = new Chart(ctx, {
            type: 'bar',  // Use 'bar' for a bar chart
            data: {
                labels: chartDates, // Dates for the x-axis
                datasets: datasets  // Data for each product
            },
            options: {
                maintainAspectRatio: false,  // Disable maintain aspect ratio for better responsiveness
                aspectRatio: 2,  // Adjust this value to control the height (e.g., higher value = shorter height on larger screens)
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Quantity Transfered'
                        },
                        beginAtZero: true
                    }
                },
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.dataset.label + ': ' + tooltipItem.raw;
                            }
                        }
                    }
                }
            }
        });
    </script>
</center>
@endsection