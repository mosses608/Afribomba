@extends('admin-layout')

@section('content')
<br><br><br>
@include('partials.side-menus')

<x-edit_success_sale />

<div class="black-screeen-view"></div>

<center>
    <div class="main-dashboard-ajax-wrapper">
        <div class="header-intro-ajax">
            <h1>Loans Manager</h1>
            <h2><i class="fas fa-calendar-alt"></i> <span class="currentDate"></span></h2>
            <br>
        </div><br>
        <div class="centered-before-ajax-yz">
        <form action="/admin/loans" method="GET" class="form-filter-component">
            @csrf
            <div class="filter-input">
                <input type="date" name="start_date" id="start_date" placeholder="Start date...">
                <input type="date" name="end_date" id="end_date" placeholder="End date...">
                <button type="submit" class="submit-btn"><span><i class="fa fa-search"></i></span></button>
            </div>
        </form>
        <form action="/admin/loans" method="GET" class="search-by-name" id="customer-search">
            @csrf
            <input type="text" name="search" id="" placeholder="Search customer name">
            <button type="submit" class="submit-filter-btn"><i class="fa fa-search"></i></button>
        </form>
            <button class="printLoanData" onclick="printLoanData()"><i class="fa fa-print"></i> <span>Print</span></button>
            <button class="view-all-sold" onclick="loanManager(event)"><i class="fa fa-plus"></i> <span>Add Loan</span></button>
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
        </div>

        <div class="mini-container">
            <table class="printable-meta-data">
                <tr class="product-table-header">
                    <th>Id</th>
                    <th>Product Name</th>
                    <th>Unit Price</th>
                    <th>Total Quantity</th>
                    <th>Total Amount</th>
                    <th>Amount Paid</th>
                    <th>Amount Remained</th>
                    <th>Staff Name</th>
                    <th>Date Created</th>
                    <th>Payment Date</th>
                    <th>Customer Name</th>
                    <th>Loan Status</th>
                    <th class="action-th">Action</th>
                </tr>

                @foreach($loans as $loan)
                <tr class="tr-sold-prod">
                    <td>{{ $loan->id }}</td>
                    <td>
                        @foreach($loan->productNames as $key => $name)
                        <span>{{ is_array($name) ? implode(',', $name) : $name }}</span><br>
                        @endforeach
                    </td>
                    <td>
                        @foreach($loan->unitPrice as $key => $price)
                        <span>{{ number_format(is_array($price) ? implode(',', $price) : $price) }}</span> <br>
                        @endforeach
                    </td>
                    <td>
                        @foreach($loan->quantities as $key => $quantity)
                        <span>{{ number_format( is_array($quantity) ? implode(',', $quantity) : $quantity ) }}</span> <br>
                        @endforeach
                    </td>
                    @php
                        $grandTotal = 0; // Initialize a variable to hold the cumulative total price
                    @endphp
                    <td>
                        @foreach($loan->unitPrice as $key => $price)
                            @php
                                // Ensure the price is a numeric value
                                if (is_array($price)) {
                                    $price = reset($price);
                                }
                                $price = (float) $price;

                                // Retrieve the quantity and ensure it's numeric
                                $quantity = is_array($loan->quantities) && isset($loan->quantities[$key]) 
                                            ? (float) $loan->quantities[$key] 
                                            : 0;

                                // Calculate the total price for this item
                                $totalPrice = $price * $quantity;

                                // Add to the cumulative total
                                $grandTotal += $totalPrice;
                            @endphp
                            <!-- Display individual total price for this item -->
                            <span>{{ number_format($totalPrice, 2) }}</span><br>
                        @endforeach

                        <!-- Display the grand total after the loop -->
                         <hr><hr><hr>
                        <strong>Total Price: {{ number_format($grandTotal, 2) }}</strong>
                    </td>


                    <td>{{ number_format($loan->amount_paid, 2) }}</td>
                    <td>{{ number_format($grandTotal - $loan->amount_paid, 2) }}</td>
                    <td>{{ $loan->staff_name }}</td>
                    <td>{{ $loan->created_at->format('Y-m-d') }}</td>
                    <td>
                        {{ $loan->payment_date }}
                    </td>

                    <td>
                        {{ $loan->customer_name }}
                    </td>
                    <td>
                        @if($loan->payment_date == $todayDate)
                        <span class="pay-alert">{{ $loan->status }}</span>
                        @else
                        <span class="not-reach-time">{{ $loan->status }}</span>
                        @endif
                    </td>
                    <td>
                        <button class="edit-loan-stat" onclick="editStatusLoan(event, {{ $loan->id }})"><i class="fas fa-edit"></i></button>

                        <form action="/edit-loan/{{ $loan->id }}" method="POST" class="order-tbl-wrapper" id="order-tbl-wrapper-{{ $loan->id }}">
                            @csrf
                            @method('PUT')
                            <div class="top-notch-form">
                                <h1>Edit This Loan</h1>
                                <button type="button" onclick="hideEditLoanForm(event, {{ $loan->id }})" class="close-btn-order-wrapper">&times;</button>
                            </div>
                            <br><br>
                            <div class="child-component-loan">
                                <label for="">Payment Date</label><br>
                                <input type="date" name="payment_date" id="" value="{{ $loan->payment_date }}">
                            </div><br>
                            <div class="child-component-loan">
                                <label for="">Amount Paid</label><br>
                                <input type="number" name="amount_paid" id="" value="{{ $loan->amount_paid }}">
                            </div><br>
                            <div class="child-component-loan">
                                <label for="">Loan Status</label><br>
                                <select name="status" id="">
                                    <option value="{{ $loan->status }}" selected disabled>--select status--</option>
                                    <option value="Partial">Partial</option>
                                    <option value="Full Paid">Full Paid</option>
                                </select>
                            </div><br>
                            <button type="submit" class="submit-order-btn">Update Loan</button>
                        </form>
                    </td>

                </tr>
                @endforeach
            </table>
            @if(count($loans) == 0)
            <br>
            <p>No loan history found here!</p>
            <br>
            @endif
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function(){
                window.editStatusLoan = function(event, loanId){
                    const loanEditForm = document.getElementById(`order-tbl-wrapper-${loanId}`);
                    const screenView = document.querySelector('.black-screeen-view');
                    loanEditForm.style.display='block';
                    screenView.style.display='block';
                }

                window.hideEditLoanForm = function(event, loanId){
                    const loanEditForm = document.getElementById(`order-tbl-wrapper-${loanId}`);
                    const screenView = document.querySelector('.black-screeen-view');
                    loanEditForm.style.display='none';
                    screenView.style.display='none';
                }
            });
        </script>

        <form action="{{ route('create.loans') }}" method="POST" class="ajax-wrapper-creator" enctype="multipart/form-data" id="product-creator-ajax-wrapper">
            @csrf
            <span class="closeItem" onclick="closeItem(event)">&times;</span>

            <div class="added-component">
                <div class="appendable-min-comp">
                <div class="inp-select-opta">
                    <label>Customer Name:</label><br>
                    <input type="text" name="customer_name" placeholder="Customer name">
                </div>
                <div class="inp-select-opta">
                    <label>TIN:</label><br>
                    <input type="text" name="tin" placeholder="TIN">
                </div>
                <div class="client-phone">
                    <label for="">Client Phone</label><br>
                    <input type="text" name="phone" id="" placeholder="Phone number">
                </div>

                <div class="inp-select-optb"> 
                <label>Product Name:</label><br>
                    <select name="product_name[]" id="product-name-select" class="product-name-select">
                        <option value="">--select--</option>
                        @foreach($products as $product)
                        <option value="{{$product->product_name}}"
                                data-price="{{$product->product_price}}"
                                data-quantity="{{$product->product_quantity}}">
                            {{$product->product_name}}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="inp-select-optc">
                    <input type="hidden" name="staff_name" value="{{Auth::guard('web')->user()->staff_name}}">
                    <label>Quantity:</label><br>
                    <input type="text" name="product_quantity[]" class="product-quantity">
                </div>

                <div class="inp-select-optc">
                    <label>Unit Price:</label><br>
                    <input type="text" name="product_price[]" class="product-price">
                </div>

                <div class="inp-select-optc">
                    <label>Payment Date:</label><br>
                    <input type="date" name="payment_date" class="product-price">
                </div>

                </div>
            </div>
<br>
            <button type="submit" class="button-sale-add">Submit Product</button>
            <button type="button" class="add-button" onclick="addForm()"><i class="fa fa-plus"></i></button>

            <br><br>
        </form>

        
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(function() {
                // Initialize Select2 for dynamic dropdown
                $('#product-name-select').select2({
                    placeholder: '--select--',
                    allowClear: true
                });

                // Update fields on product selection
                $('#product-name-select').on('change', function() {
                    var selectedOption = $(this).find('option:selected');
                    var price = selectedOption.data('price');
                    var quantity = selectedOption.data('quantity');
                    
                    $(this).closest('.appendable-min-comp').find('.product-price').val(price);
                    $(this).closest('.appendable-min-comp').find('.product-quantity').val(quantity);
                });

                // Add new product form dynamically
                $('.add-button').on('click', function() {
                    var appendableChild = `
                        <div class="appendable-min-comp">
                            <div class="inp-select-opta">
                                <label>Product Name:</label><br>
                                <select name="product_name[]" class="product-name-select">
                                    <option value="">--select--</option>
                                    @foreach($products as $product)
                                    <option value="{{$product->product_name}}"
                                            data-price="{{$product->product_price}}"
                                            data-quantity="{{$product->product_quantity}}">
                                        {{$product->product_name}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="inp-select-optc">
                                <label>Quantity:</label><br>
                                <input type="text" name="product_quantity[]" class="product-quantity">
                            </div>

                            <div class="inp-select-optc">
                                <label>Unit Price:</label><br>
                                <input type="text" name="product_price[]" class="product-price">
                            </div>
                            
                        </div>
                    `;

                    $('.added-component').append(appendableChild);
                    
                    // Reinitialize Select2 for the new dropdown
                    $('.product-name-select').select2({
                        placeholder: '--select--',
                        allowClear: true
                    });

                    // Attach change event to the new dropdown
                    $('.product-name-select').last().on('change', function() {
                        var selectedOption = $(this).find('option:selected');
                        var price = selectedOption.data('price');
                        var quantity = selectedOption.data('quantity');
                        
                        $(this).closest('.appendable-min-comp').find('.product-price').val(price);
                        $(this).closest('.appendable-min-comp').find('.product-quantity').val(quantity);
                    });
                });
            });

            function loanManager(event){
                event.preventDefault();
                const screenView = document.querySelector('.black-screeen-view');
                const loanLoader = document.querySelector('.ajax-wrapper-creator');
                loanLoader.style.display='block';
                screenView.style.display='block';
            }

            function closeItem(event){
                event.preventDefault();
                const screenView = document.querySelector('.black-screeen-view');
                const loanLoader = document.querySelector('.ajax-wrapper-creator');
                loanLoader.style.display='none';
                screenView.style.display='none';
            }
        </script>
</center>
@endsection
