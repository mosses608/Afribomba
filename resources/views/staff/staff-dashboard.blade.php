@extends('staff-layout')

@section('content')

<x-staff_login_success />

@include('partials.staff-side-menu')

<br><br><br>
<center>
    <div class="main-dashboard-ajax-wrapper">
        <div class="header-intro-ajax">
            <h1>Staff Dashboard | {{Auth::guard('web')->user()->staff_name}}</h1>
            <h2><i class="fas fa-calendar-alt"></i> <span class="currentDate"></span></h2>
            <br>
        </div><br>
        <section class="product-catalog-loader">
            <div class="left-panel-sider">
                <h1>{{count($products)}}</h1><br>
                <h2>
                    @if(count($products)<2)
                    <a href="/staff/all-products">All Product</a>
                    @else
                    <a href="/staff/all-products">All Products</a>
                    @endif
                </h2>
            </div>
            <div class="right-panel-sider">
                <i class="fa fa-lock"></i>
            </div>
            <!-- <br><br><br><br><hr><hr><br>
            <a href="/staff/all-products">More Info <i class="fas fa-arrow-right"></i></a><br><br> -->
        </section>
        <section class="exported-catalog-loader">
            <div class="left-panel-sider">
                <h1>{{count($exports)}}</h1><br>
                <h2><a href="/staff/exported-products">Exported Stocks</a></h2>
            </div>
            <div class="right-panel-sider">
                <i class="fa fa-lock"></i>
            </div>
            <!-- <br><br><br><br><hr><hr><br>
            <a href="/staff/exported-products">More Info <i class="fas fa-arrow-right"></i></a><br><br> -->
        </section>
        <section class="recommended-catalog-loader">
            <div class="left-panel-sider">
                <h1>{{$staff_recommednCounter}}</h1>
                <br>
                <h2><a href="/staff/recommended">Recommended</a></h2>
            </div>
            <div class="right-panel-sider">
                <i class="fa fa-paper-plane"></i>
            </div>
            <!-- <br><br><br><br><hr><hr><br>
            <a href="/staff/recommended">More Info <i class="fas fa-arrow-right"></i></a><br><br> -->
        </section>
        <!-- <section class="stores-catalog-loader">
            <div class="left-panel-sider">
                <h1>{{count($stores)}}</h1><br>
                <h2>
                    @if(count($stores)<2)
                    All Store
                    @else
                    All Stores
                    @endif

                </h2>
            </div>
            <div class="right-panel-sider">
                <i class="fas fa-store-alt"></i>
            </div>
            <br><br><br><br><hr><hr><br>
            <a href="/staff/all-stores">More Info <i class="fas fa-arrow-right"></i></a><br><br>
        </section> -->

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="graph-analytics">
    <div class="line-break"><br><br><br><br></div>
    <canvas id="exportChart" width="400" height="200"></canvas>
</div>

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
            // backgroundColor: getRandomColor(),
            // borderColor: getRandomColor(),
            borderWidth: 1,
            fill: false // Use false if you don't want to fill the area under the line
        };
    });

    // Helper function to generate random colors for the bars
    function getRandomColor() {
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    // Create the chart
    var ctx = document.getElementById('exportChart').getContext('2d');
    var exportChart = new Chart(ctx, {
        type: 'bar',  // Use 'bar' for a bar chart
        data: {
            labels: chartDates, // Dates for the x-axis
            datasets: datasets  // Data for each product
        },
        options: {
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
                        text: 'Quantity Sold'
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


    </div>
</center>

@endsection