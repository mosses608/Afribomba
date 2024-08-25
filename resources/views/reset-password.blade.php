@extends('layout')

@section('content')
<center>
    <form action="/reset-password" method="POST" class="authenticate-action-lgx">
        @csrf
        <h1>Reset Password</h1>
        <!-- <label for="">Username:</label><br><br>
        <input type="text" name="username" id="" placeholder="Username...." class="uname-input"><br><br> -->
        <label for="">Password:</label><br><br>
        <input type="email" name="email" id="" placeholder="Enyer Your Email...." class="pass-input"><br><br>
        <button type="submit">Next</button> <a href="/">Back To Login</a><br><br>
    </form>
</center>
@endsection