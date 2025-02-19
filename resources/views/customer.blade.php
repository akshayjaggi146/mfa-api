@extends('layouts.app')

@section('content')

    
<div class="d-flex justify-content-end mb-3">
        <button class="btn btn-danger" id="logoutBtn">Logout</button>
    </div>

<div class="container">
<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#customerModal">Add Customer</button>

    <h3>Customer List</h3>
    <table id="customerTable">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
<div class="modal fade" id="customerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="customerForm">
                    <input type="hidden" id="customer_id">
                    <div>
                        <label>Name:</label>
                        <input type="text" id="name" required>
                    </div>
                    <div>
                        <label>Email:</label>
                        <input type="email" id="email" required>
                    </div>
                    <div>
                        <label>Phone:</label>
                        <input type="text" id="phone" required>
                    </div>
                    <div>
                        <label>Address:</label>
                        <textarea id="address"></textarea>
                    </div>
                    <button type="submit">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let token = localStorage.getItem('token');
        if (!token) {
            window.location.href = "/login"; // Redirect if no token
        }
    });
$(document).ajaxError(function(event, jqxhr, settings, thrownError) {
    if (jqxhr.status === 401) { 
        window.location.href = "/login"; // Redirect to login
    }
});
$(document).ready(function() {
    let token = localStorage.getItem('token'); 
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
    function fetchCustomers() {
        $.ajax({
            url: "/api/customers",
            type: "GET",
            headers: { "Authorization": "Bearer " + token },
            success: function(data) {
                let rows = '';
                $.each(data, function(index, customer) {
                    rows += `<tr>
                        <td>${customer.name}</td>
                        <td>${customer.email}</td>
                        <td>${customer.phone}</td>
                        <td>${customer.address}</td>
                        <td>
                            <button onclick="editCustomer(${customer.id})">Edit</button>
                            <button onclick="deleteCustomer(${customer.id})">Delete</button>
                        </td>
                    </tr>`;
                });
                $("#customerTable tbody").html(rows);
            }
        });
    }

    $("#customerForm").submit(function(e) {
        e.preventDefault();
        let id = $("#customer_id").val();
        let url = id ? `/api/customers/${id}` : "/api/customers";
        let method = id ? "PUT" : "POST";

        $.ajax({
            url: url,
            type: method,
            headers: { "Authorization": "Bearer " + token },
            contentType: "application/json",
            data: JSON.stringify({
                name: $("#name").val(),
                email: $("#email").val(),
                phone: $("#phone").val(),
                address: $("#address").val()
            }),
            success: function() {
                $("#customerModal").modal("hide");
                $("#customerForm")[0].reset();
                $("#customer_id").val("");

                fetchCustomers();
            }
        });

    });

    window.editCustomer = function(id) {
        $.ajax({
            url: `/api/customers/${id}`,
            type: "GET",
            headers: { "Authorization": "Bearer " + token },
            success: function(customer) {
                $("#customer_id").val(customer.id);
                $("#name").val(customer.name);
                $("#email").val(customer.email);
                $("#phone").val(customer.phone);
                $("#address").val(customer.address);
            }
        });
    };

    window.deleteCustomer = function(id) {
        $.ajax({
            url: `/api/customers/${id}`,
            type: "DELETE",
            headers: { "Authorization": "Bearer " + token },
            success: fetchCustomers
        });
    };

    fetchCustomers();
});
</script>
@endsection
