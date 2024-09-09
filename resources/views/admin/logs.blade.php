@extends('admin-layout')

@section('content')
<br><br><br>
@include('partials.side-menus')

<center>
    <div class="main-dashboard-ajax-wrapper">
        <div class="header-intro-ajax">
            <h1>Logs</h1>
            <h2><i class="fas fa-calendar-alt"></i> <span class="currentDate"></span></h2>
            <br>
        </div><br>
        <div class="centered-before-ajax">
        <form action="/admin/reports" method="GET" class="search-component">
            @csrf
                <input type="text" name="search" id="start_date">
            <button type="submit"><span>Search</span></button>
        </form><br>
        <br>
        <div class="flex-wrapper-container">
            <div class="mini-container">
            <table>
                <thead>
                    <tr class="product-table-header">
                        <!-- <th>ID</th> -->
                        <th>User ID</th>
                        <th>IP Address</th>
                        <th>User Agent</th>
                        <th>Last Activity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sessions as $session)
                        <tr style="font-size:14px; padding:4px;">
                            <!-- <td>{{ $session->id }}</td> -->
                            <td>{{ $session->user_id }}</td>
                            <td>{{ $session->ip_address }}</td>
                            <td>{{ $session->user_agent }}</td>
                            <td>{{ $session->last_activity }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
    </div>
</center>
@endsection