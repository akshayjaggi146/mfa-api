@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="card p-4">
    <h3 class="text-center">Register</h3>
    <form id="registerForm">
        @csrf
        <div class="mb-3">
            <label for="name">Full Name</label>
            <input type="text" id="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="email">Email</label>
            <input type="email" id="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password">Password</label>
            <input type="password" id="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Register</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    $('#registerForm').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: '/api/register',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                name: $('#name').val(),
                email: $('#email').val(),
                password: $('#password').val()
            }),
            success: function(response) {
                alert(response.message);
                window.location.href = '/login';
            },
            error: function(xhr) {
                alert(xhr.responseJSON.message);
            }
        });
    });
</script>
@endsection
