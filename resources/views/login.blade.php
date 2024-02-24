@extends('layouts.app')
@section('content')
<div class="vh-100 d-flex align-items-center justify-content-center">
    <form action="{{ route('login') }}" method="post">
        @csrf
        <h4>Login to Your Account</h4>
        <input type="hidden" name="type" value="web">
        <div class="form-group mt-5 mb-3">
            <input type="text" class="form-control" placeholder="email" aria-label="email" name="email" v-model="email" required autofocus>
        </div>
        <div class="form-group my-3 ">
            <input type="password" class="form-control" placeholder="Password" aria-label="Password" name="password" v-model="password" required>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-primary">Login</button>
        </div>
    </form>
</div>
@endsection