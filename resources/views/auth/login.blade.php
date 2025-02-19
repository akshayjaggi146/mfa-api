@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="card p-4">
    <h3 class="text-center">Login</h3>
    <form id="loginForm">
        @csrf
        <div class="mb-3">
            <label for="email">Email</label>
            <input type="email" id="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password">Password</label>
            <input type="password" id="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
</div>

<!-- MFA Section -->
<div class="card p-4 mt-4" id="mfaSection" style="display: none;">
    <h4 class="text-center">MFA Verification</h4>
    <form id="mfaForm">
        <input type="hidden" id="userId">
        <div class="mb-3">
            <label for="mfaToken">MFA Token</label>
            <input type="text" id="mfaToken" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success w-100">Verify & Login</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    $('#loginForm').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: '/api/login',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                email: $('#email').val(),
                password: $('#password').val()
            }),
            success: function(response) {
                $('#mfaSection').show();
                $('#userId').val(response.user_id);
            },
            error: function(xhr) {
                alert(xhr.responseJSON.message);
            }
        });
    });

    $('#mfaForm').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: '/api/verify-mfa',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                user_id: $('#userId').val(),
                mfa_token: $('#mfaToken').val()
            }),
            success: function(response) {
                alert(response.message);
                localStorage.setItem('token', response.token);
                window.location.href = '/customers';
            },
            error: function(xhr) {
                alert(xhr.responseJSON.message);
            }
        });
    });
</script>
@endsection
