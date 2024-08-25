@extends('admin-layout')

@section('content')
<br><br><br>
@include('partials.side-menus')

<x-message_flash />

<x-user_exists />

<x-success_delete />

<center>
    <div class="main-dashboard-ajax-wrapper">
        <div class="header-intro-ajax">
            <h1>All Users</h1>
            <h2><i class="fas fa-calendar-alt"></i> <span class="currentDate"></span></h2>
            <br>
        </div><br>
        <div class="centered-before-ajax">
            <form action="/admin/users" method="GET" class="search-component">
                @csrf
                <input type="text" name="search" id="" placeholder="Search staff or user..."><button type="submit"><span>Search</span></button>
            </form>
            <button class="add-product-button" onclick="showAddStaffForm()"><i class="fa fa-plus"></i> <span>Add Staff</span></button>
        </div><br><br>
        <div class="flex-wrapper-container">
            @if(count($users) == "")
            <p>No user found!</p>
            @endif
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
                    @foreach($users as $user)
                    <tr>
                        <td>#</td>
                        <td>{{$user->staff_id}}</td>
                        <td>{{$user->staff_name}}</td>
                        <td>{{$user->staff_role}}</td>
                        <td>{{$user->staff_email}}</td>
                        <td>{{$user->staff_phone}}</td>
                        <td>{{$user->username}}</td>
                        <td><a href="{{asset('storage/' . $user->profile)}}"><img src="{{asset('storage/' . $user->profile)}}" alt="Image"></a></td>
                        <td><a href="/admin/single-user/{{$user->id}}"><i class="fa fa-eye"></i></a> 
                        @if($user->staff_role == 'SuperAdmin')
                            <span class="edit-form-viewer"><i class="fa fa-edit"></i></span>
                            <span class="delete-user-con"><i class="fas fa-trash-alt"></i></span>
                            @else
                            <span class="edit-form-viewer" onclick="showEditStaffForm(event, {{$user->id}})"><i class="fa fa-edit"></i></span>
                            <span class="delete-user-con" onclick="showDeletePop(event, {{$user->id}})"><i class="fas fa-trash-alt"></i></span>
                            @endif


        <form action="/users/deleteuser/{{$user->id}}" method="POST" class="delete-user-confirm" id="delete-user-confirm-{{$user->id}}" hidden>
            @csrf
            @method('DELETE')
            <p>Are you sure, you want to delete {{$user->staff_name}}</p>
            <button type="button" class="non-cancel" onclick="hidePopUp()">No</button> <button type="submit" class="yes-confirm">Yes</button> 
        </form>



        <form action="/users/edituser/{{$user->id}}" method="POST" class="user-creator-ajax-wrapper" id="user-creator-ajax-{{$user->id}}" enctype="multipart/form-data" hidden>
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
            <input type="number" name="staff_phone" id="" value="{{$user->staff_phone}}"><br><br>
            <label for="">Username:</label>
            <input type="text" name="username" id="" value="{{$user->username}}"><br><br>
            <label for="">Password:</label>
            <input type="password" name="password" id="" value="{{$user->password}}"><br><br>
            <label for="">Profile Picture:</label>
            <input type="file" name="profile" id="" style="border:none;" accept="image/*" value="{{$user->profile}}"><br><br>
            <button type="button" class="close-button" onclick="closePopUpForm()">Close</button>
            <button type="submit" class="button" style="float:right;">Update Staff</button> 
            <br><br>
        </form>
                    </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>


        <form action="/storeusers" method="POST" class="product-creator-ajax-wrapper" id="product-creator-ajax-wrapper" enctype="multipart/form-data">
            @csrf
            <h1>Add a Staff</h1><br><br>


            <label for="">Staff Id:</label>
            <input type="text" name="staff_id" id="" placeholder="Staff Id..."><br><br>


            <label for="">Staff Name:</label>
            <input type="text" name="staff_name" id="" placeholder="Staff Name..."><br><br> 


            <label for="">Role:</label>
            <select name="staff_role" id="">
                <option value="//">Select Role</option>
                <option value="Staff">Staff</option>
                <option value="Admin">Admin</option>
            </select><br><br>


            <label for="">Email:</label>
            <input type="email" name="staff_email" id="" placeholder="Staff Email..."><br><br>


            <label for="">Contact:</label>
            <input type="number" name="staff_phone" id="" placeholder="Phone Number..."><br><br>


            <label for="">Username:</label>
            <input type="text" name="username" id="" placeholder="Username..."><br><br>


            <label for="">Password:</label>
            <input type="password" name="password" id="" placeholder="Password..."><br><br>


            <label for="">Profile Picture:</label>
            <input type="file" name="profile" id="" style="border:none;" accept="image/*"><br><br>

            
            <button type="button" class="close-button" onclick="closePopUpForm()">Close</button>
            <button type="submit" class="button">Add Staff</button> 
            <br><br>
        </form>

        <script>
        function showAddStaffForm(){
            // document.querySelector('.product-creator-ajax-wrapper').foreach();
            document.getElementById('product-creator-ajax-wrapper').classList.toggle('active');
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


        document.addEventListener('DOMContentLoaded', function(){
            window.showEditStaffForm = function(event, userId){
                const useEditForm = document.getElementById(`user-creator-ajax-${userId}`);
                useEditForm.classList.add('active');
                document.querySelector('.font-sans-antialiased-admin').style.backgroundColor='rgba(0,0,0,0.5)';
            }
        });

        window.showDeletePop = function(event, userId){
            const deletePopUp = document.getElementById(`delete-user-confirm-${userId}`);
            deletePopUp.classList.add('active');
            document.querySelector('.font-sans-antialiased-admin').style.backgroundColor='rgba(0,0,0,0.5)';
        }
    </script>
    </div>
</center>
@endsection