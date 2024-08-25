@extends('staff-layout')

@section('content')
<br><br><br>
@include('partials.staff-side-menu')

<x-success_update />

<x-user_pass />

<x-error_code />

<center>
    <div class="main-dashboard-ajax-wrapper">
        <div class="header-intro-ajax">
            <h1>Staff Profile | {{Auth::guard('web')->user()->staff_name}}</h1>
            <h2><i class="fas fa-calendar-alt"></i> <span class="currentDate"></span></h2>
            <br>
        </div><br>
        <div class="profile-left-layer">
            <div class="image-viewer">
            <img src="{{Auth::guard('web')->user()->profile ? asset('storage/' . Auth::guard('web')->user()->profile) : asset('assets/images/background-logo.png')}}" alt="Profile">
            </div><hr><br>
            <p>Role: {{Auth::guard('web')->user()->staff_role}}</p><br><br>
            <p>Name: {{Auth::guard('web')->user()->staff_name}}</p><br><br>
            <p>Email: {{Auth::guard('web')->user()->staff_email}}</p><br><br>
            <p>Phone Number: {{Auth::guard('web')->user()->staff_phone}}</p><br><br>
            <p>Username: {{Auth::guard('web')->user()->username}}</p><br><br>
        </div>
        <div class="profile-right-layer">
            <button class="profile-pass-editor"><span class="profile-editor-viewer" onclick="showProfileEditor()">Edit Profile</span><span class="pass-user-editor" onclick="showPassUserEditor()">Edit Password</span></button><br><br>
            <form action="/users/edit-profile/{{Auth::guard('web')->user()->id}}" method="POST" class="edit-ad-profile" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <label for="">Staff Id</label>
                <input type="text" name="staff_id" id="" value="{{Auth::guard('web')->user()->staff_id}}"><br><br>
                <label for="">Name:</label>
                <input type="text" name="staff_name" id="" value="{{Auth::guard('web')->user()->staff_name}}"><br><br>
                <label for="">Email:</label>
                <input type="email" name="staff_email" id="" value="{{Auth::guard('web')->user()->staff_email}}"><br><br>
                <label for="">Phone Number</label>
                <input type="number" name="staff_phone" id="" value="{{Auth::guard('web')->user()->staff_phone}}"><br><br>
                <label for="">Profile Image:</label>
                <input type="file" name="profile" id="" style="border:none;" accept="image/*" value="{{asset('storage/' . Auth::guard('web')->user()->profile)}}"><br><br>
                <button type="submit">Update Profile</button><br><br>
            </form>

            <form action="/users/pass-user-edit/{{Auth::guard('web')->user()->id}}" method="POST" class="pass-user-data-editor">
                @csrf
                @method('PUT')
                <label for="">Username:</label>
                <input type="text" name="username" id="" value="{{Auth::guard('web')->user()->username}}"><br><br>
                <label for="">Password:</label>
                <input type="text" name="password" id="" value="{{Auth::guard('web')->user()->password}}"><br><br>
                <label for="">Confirm Password:</label>
                <input type="password" name="password_confirm" id=""><br><br>
                <button type="submit">Update</button><br><br>
            </form>
        </div>
    </div>

    <script>
        function showPassUserEditor(){
            document.querySelector('.pass-user-data-editor').classList.toggle('active');
            document.querySelector('.edit-ad-profile').classList.toggle('deactivate');
            document.querySelector('.profile-editor-viewer').style.backgroundColor="#FFFFFF";
            document.querySelector('.profile-editor-viewer').style.color="#000";
            document.querySelector('.pass-user-editor').style.backgroundColor="#0000FF";
            document.querySelector('.pass-user-editor').style.color="#FFFFFF";
            // document.querySelector('.profile-editor-viewer').classList.toggle('activeProfile');
        }

        function showProfileEditor(){
            location.reload();
        }
    </script>
</center>
@endsection