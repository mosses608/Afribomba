@extends('admin-layout')

@section('content')
<br><br><br>
@include('partials.side-menus')

<x-message_flash />

<x-user_exists />

<x-success_edit />

<center>
    <div class="main-dashboard-ajax-wrapper">
        <div class="header-intro-ajax">
            <h1>Single User / {{$user->staff_name}}</h1>
            <h2><i class="fas fa-calendar-alt"></i> <span class="currentDate"></span></h2>
            <br>
        </div><br>
        <div class="centered-before-ajax">
            <!-- <form action="/admin/users" method="GET" class="search-component">
                @csrf
                <input type="text" name="search" id="" placeholder="Search staff or user..."><button type="submit"><span>Search</span></button>
            </form> -->
            @if($user->staff_role == 'SuperAdmin')
            <button class="add-product-button"><i class="fa fa-edit"></i> <span>Update Staff</span></button>
            @else
            <button class="add-product-button" onclick="showAddStaffForm()"><i class="fa fa-edit"></i> <span>Update Staff</span></button>
            @endif
        </div><br><br>
        <div class="flex-wrapper-container">
            <div class="mini-container">
                <table>
                    <tr class="product-table-header">
                    <th>#</th>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Username</th>
                    <th>Picture</th>
                    <th>Action</th>
                    </tr>
                    <tr>
                        <td>{{$user->id}}</td>
                        <td>{{$user->staff_id}}</td>
                        <td>{{$user->staff_name}}</td>
                        <td>{{$user->staff_role}}</td>
                        <td>{{$user->staff_email}}</td>
                        <td>{{$user->staff_phone}}</td>
                        <td>{{$user->username}}</td>
                        <td><a href="{{asset('storage/' . $user->profile)}}"><img src="{{asset('storage/' . $user->profile)}}" alt="Image"></a></td>
                        <td style="text-align:center; color:red; cursor:pointer;">
                            @if($user->staff_role == 'SuperAdmin')
                            <span class="delete-user-con"><i class="fas fa-trash-alt"></i></span>
                            @else
                            <span class="delete-user-con" onclick="showDeletePop()"><i class="fas fa-trash-alt"></i></span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <form action="/users/deleteuser/{{$user->id}}" method="POST" class="pop-delete-user-confirm">
            @csrf
            @method('DELETE')
            <p>Are you sure, you want to delete {{$user->staff_name}}</p>
            <button type="submit" class="yes-confirm">Yes</button> <button type="button" class="non-cancel" onclick="hidePopUp()">No</button>
        </form>

        <form action="/users/edituser/{{$user->id}}" method="POST" class="product-creator-ajax-wrapper" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <h1>Update Staff</h1><br><br>
            <label for="">Staff Id:</label>
            <input type="text" name="staff_id" id="" value="{{$user->staff_id}}"><br><br>
            <label for="">Staff Name:</label>
            <input type="text" name="staff_name" id="" value="{{$user->staff_name}}"><br><br> 
            <label for="">Role:</label>
            <select name="staff_role" id="">
                <option value="{{$user->staff_role}}">Select Role</option>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select><br><br>
            <label for="">Email:</label>
            <input type="email" name="staff_email" id="" value="{{$user->staff_email}}"><br><br>
            <label for="">Contact:</label>
            <input type="number" name="staff_phone" id="" placeholder="Phone Number..."><br><br>
            <label for="">Username:</label>
            <input type="text" name="username" id="" value="{{$user->staff_phone}}"><br><br>
            <label for="">Password:</label>
            <input type="password" name="password" id="" value="{{$user->password}}"><br><br>
            <label for="">Profile Picture:</label>
            <input type="file" name="profile" id="" style="border:none;" accept="image/*" value="{{$user->profile}}"><br><br>
            <button type="submit" class="button">Update Staff</button> 
            <button type="button" class="close-button" onclick="closePopUpForm()">Close</button>
            <br><br>
        </form>

        <script>
        function showAddStaffForm(){
            document.querySelector('.product-creator-ajax-wrapper').classList.toggle('active');
            document.querySelector('.product-creator-ajax-wrapper').style.opacity='1';
            document.querySelector('.font-sans-antialiased-admin').style.backgroundColor='rgba(0,0,0,0.5)';
        }

        function closePopUpForm(){
            document.querySelector('.product-creator-ajax-wrapper').style.display='none';
            location.reload();
        }

        function showDeletePop(){
            document.querySelector('.product-creator-ajax-wrapper').style.opacity='1';
            document.querySelector('.font-sans-antialiased-admin').style.backgroundColor='rgba(0,0,0,0.5)';
            document.querySelector('.pop-delete-user-confirm').classList.toggle('active');
        }

        function hidePopUp(){
            location.reload();
        }
    </script>
    </div>
</center>
@endsection