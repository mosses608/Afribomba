@extends('admin-layout')

@section('content')
<br><br><br>
@include('partials.side-menus')

<x-success_flash_msg />

<center>
    <div class="main-dashboard-ajax-wrapper">
        <div class="header-intro-ajax">
            <h1>Admin Dashboard | {{Auth::guard('web')->user()->staff_name}}</h1>
            <h2><i class="fas fa-calendar-alt"></i> <span class="currentDate"></span></h2>
            <br>
        </div>
        <section class="product-catalog-loader">
            <div class="left-panel-sider">
                <h1>{{(int)count($products)}}</h1><br>
                <h2>
                    @if((int)count($products)<2)
                    <a href="/admin/all-products" style="text-decoration:none;">All Product</a>
                    @else
                    <a href="/admin/all-products" style="text-decoration:none;">All Products</a>
                    @endif
                </h2>
            </div>
            <div class="right-panel-sider">
                <i class="fa fa-lock"></i>
            </div>
            <!-- <br><br><br><br><hr><hr><br>
            <a href="/admin/all-products">More Info <i class="fas fa-arrow-right"></i></a><br><br> -->
        </section>

        <!-- <section class="instock-catalog-loader">
            <div class="left-panel-sider">
                <h1>{{$instockCount}}</h1><br>
                <h2>In Stock</h2>
            </div>
            <div class="right-panel-sider">
                <i class="fa fa-lock"></i>
            </div>
            <br><br><br><br><hr><hr><br>
            <a href="/admin/instock-products">More Info <i class="fas fa-arrow-right"></i></a><br><br>
        </section> -->


        <section class="exported-catalog-loader">
            <div class="left-panel-sider">
                <h1>{{(int)count($exports)}}</h1><br>
                <h2><a href="/admin/exported-products" style="text-decoration:none;">Sold Stocks</a></h2>
            </div>
            <div class="right-panel-sider">
                <i class="fa fa-lock"></i>
            </div>
            <!-- <br><br><br><br><hr><hr><br>
            <a href="/admin/exported-products">More Info <i class="fas fa-arrow-right"></i></a><br><br> -->
        </section>


        <!-- <section class="users-catalog-loader">
            <div class="left-panel-sider">
                <h1>{{$lessProductCount}}</h1><br>
                <h2>Less In Stocks</h2>
            </div>
            <div class="right-panel-sider">
                <i class="fa fa-lock"></i>
            </div>
            <br><br><br><br><hr><hr><br>
            <a href="/admin/less-product">More Info <i class="fas fa-arrow-right"></i></a><br><br>
        </section> -->

        <!-- <section class="comments-catalog-loader">
            <div class="left-panel-sider">
                <h1>
                {{count($comments)}}
                </h1><br>
                <h2>
                    @if(count($comments) <2 )
                    Comment
                    @else
                    Comments
                    @endif
                </h2>
            </div>
            <div class="right-panel-sider">
                <i class="fa fa-comments"></i>
            </div>
            <br><br><br><br><hr><hr><br>
            <a href="/admin/comments">More Info <i class="fas fa-arrow-right"></i></a><br><br>
        </section> -->

        <!-- <section class="recommended-catalog-loader">
            <div class="left-panel-sider">
                <h1>{{$staff_recommednCounter}}</h1><br>
                <h2>Recommended</h2>
            </div>
            <div class="right-panel-sider">
                <i class="fa fa-paper-plane"></i>
            </div>
            <br><br><br><br><hr><hr><br>
            <a href="/admin/recommended">More Info <i class="fas fa-arrow-right"></i></a><br><br>
        </section> -->


        <section class="stores-catalog-loader">
            <div class="left-panel-sider">
                <h1>{{(int)count($stores)}}</h1><br>
                <h2>
                    @if((int)count($stores)<2)
                    <a href="/admin/all-stores" style="text-decoration:none;">All Store</a>
                    @else
                    <a href="/admin/all-stores" style="text-decoration:none;">All Stores</a>
                    @endif

                </h2>
            </div>
            <div class="right-panel-sider">
                <i class="fas fa-store-alt"></i>
            </div>
            <!-- <br><br><br><br><hr><hr><br>
            <a href="/admin/all-stores">More Info <i class="fas fa-arrow-right"></i></a><br><br> -->
        </section>

        <!-- <section class="less-in-catalog-loader">
            <div class="left-panel-sider">
                <h1>{{count($users)}}</h1><br>
                <h2>Users</h2>
            </div>
            <div class="right-panel-sider">
                <i class="fa fa-users"></i>
            </div>
            <br><br><br><br><hr><hr><br>
            <a href="/admin/users">More Info <i class="fas fa-arrow-right"></i></a><br><br>
        </section> -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <div class="graph-analytics">
            <div class="line-break"><br><br><br><br></div>
            <canvas id="exportChart" width="400" height="200"></canvas>
        </div>

        <style>
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
        </style>

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