@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="card p-4">
    <h3 class="text-center">Welcome to Dashboard</h3>
    <button class="btn btn-danger w-100" id="logoutBtn">Logout</button>
</div>
@endsection

@section('scripts')
<script>
    $('#logoutBtn').click(function() {
        $.ajax({
            url: '/api/logout',
            type: 'POST',
            headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') },
            success: function(response) {
                alert(response.message);
                localStorage.removeItem('token');
                window.location.href = '/login';
            },
            error: function(xhr) {
                alert('Logout failed');
            }
        });
    });
</script>
@endsection
