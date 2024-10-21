<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers</title>
    <link rel="stylesheet" href="{{ asset('bootstrap-5.3.3/css/bootstrap.min.css') }}">
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('bootstrap-5.3.3/js/bootstrap.bundle.js') }}"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        /* Styling remains unchanged */
        body {
            background-color: #f8f9fa;
        }

        .sidebar {
            height: 100vh;
            position: fixed;
            top: 56px;
            left: 0;
            width: 220px;
            background-color: #343a40;
            padding-top: 20px;
        }

        .sidebar a {
            color: #ffffff;
        }

        .sidebar a:hover {
            background-color: #00c030;
            text-decoration: none;
        }

        .content {
            margin-left: 240px;
            padding: 20px;
        }

        .navbar {
            background-color: #007bff;
        }

        .navbar-brand {
            color: white !important;
        }

        h2 {
            text-align: center;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin Dashboard</a>
            <div class="ms-auto">
                <a href="{{ url('profile') }}" class="btn btn-sm btn-outline-warning">Update Profile</a>
                <a href="{{ url('logout') }}" class="btn btn-sm btn-outline-danger">Logout</a>
            </div>
        </div>
    </nav>

    <div class="sidebar">
        <nav class="nav flex-column">
            <a class="nav-link" href="{{ url('categoryList') }}">Categories</a>
            <a class="nav-link" href="{{ url('product') }}">Products</a>
            <a class="nav-link active" href="{{ url('customers') }}">Customers</a>
            <a class="nav-link" href="{{ url('orders') }}">Orders</a>
            <a class="nav-link" href="{{ url('reports') }}">Reports</a>
            <a class="nav-link" href="{{ url('settings') }}">Settings</a>
        </nav>
    </div>

    <div class="content">
        <div class="container">
            <h2>Customers</h2>
            <form id="add-customer-form" class="text-center mb-4">
                @csrf
                <div class="input-group">
                    <input type="text" id="customer-name" class="form-control" placeholder="Customer Name" required>
                    <input type="email" id="customer-email" class="form-control" placeholder="Customer Email"
                        required>
                    <input type="text" id="customer-phone" class="form-control" placeholder="Customer Phone"
                        required>
                    <button type="submit" class="btn btn-primary">Add Customer</button>
                </div>
            </form>

            <div id="loading" class="text-center">Loading...</div>
            <div id="error-message"></div>

            <table id="customer-list" class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Customer Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <!-- Update Customer Modal -->
        <div class="modal fade" id="update-customer-modal" tabindex="-1" aria-labelledby="updateCustomerModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateCustomerModalLabel">Update Customer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="update-customer-form">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" id="update-customer-id">
                            <div class="mb-3">
                                <label for="update-customer-name" class="form-label">Customer Name</label>
                                <input type="text" id="update-customer-name" class="form-control"
                                    placeholder="New Customer Name" required>
                            </div>
                            <div class="mb-3">
                                <label for="update-customer-email" class="form-label">Customer Email</label>
                                <input type="email" id="update-customer-email" class="form-control"
                                    placeholder="New Customer Email" required>
                            </div>
                            <div class="mb-3">
                                <label for="update-customer-phone" class="form-label">Customer Phone</label>
                                <input type="text" id="update-customer-phone" class="form-control"
                                    placeholder="New Customer Phone" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            const userId = 'USER_ID';

            fetchCustomers();

            function fetchCustomers() {
                $('#loading').show();
                $.ajax({
                    url: '/customerList',
                    type: 'GET',
                    headers: {
                        'id': userId
                    },
                    success: function(data) {
                        const tbody = $('#customer-list tbody');
                        tbody.empty();
                        data.forEach((customer, index) => {
                            tbody.append(`
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${customer.name}</td>
                                    <td>${customer.email}</td>
                                    <td>${customer.phone}</td>
                                    <td>
                                        <button class="edit-customer btn btn-warning btn-sm" data-id="${customer.id}">Edit</button>
                                        <button class="delete-customer btn btn-danger btn-sm" data-id="${customer.id}">Delete</button>
                                    </td>
                                </tr>
                            `);
                        });
                    },
                    error: function() {
                        showToast("Error fetching customers.", "error");
                    },
                    complete: function() {
                        $('#loading').hide();
                    }
                });
            }

            $('#add-customer-form').on('submit', function(event) {
                event.preventDefault();
                const newCustomer = {
                    name: $('#customer-name').val(),
                    email: $('#customer-email').val(),
                    phone: $('#customer-phone').val()
                };

                $.ajax({
                    url: '/add-customer',
                    type: 'POST',
                    headers: {
                        'id': userId,
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: JSON.stringify(newCustomer),
                    contentType: 'application/json',
                    success: function() {
                        showToast("Customer added successfully!", "success");
                        fetchCustomers();
                        $('#add-customer-form')[0].reset();
                    },
                    error: function() {
                        showToast("Error adding customer.", "error");
                    }
                });
            });

            $(document).on('click', '.delete-customer', function() {
                const customerId = $(this).data('id');

                $.ajax({
                    url: '/delete-customer',
                    type: 'DELETE',
                    headers: {
                        'id': userId,
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: JSON.stringify({
                        id: customerId
                    }),
                    contentType: 'application/json',
                    success: function() {
                        showToast("Customer deleted successfully!", "success");
                        fetchCustomers();
                    },
                    error: function() {
                        showToast("Error deleting customer.", "error");
                    }
                });
            });

            $(document).on('click', '.edit-customer', function() {
                const customerId = $(this).data('id');

                $.ajax({
                    url: '/customer-id',
                    type: 'POST',
                    headers: {
                        'id': userId,
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: JSON.stringify({
                        id: customerId
                    }),
                    contentType: 'application/json',
                    success: function(customer) {
                        $('#update-customer-id').val(customer.id);
                        $('#update-customer-name').val(customer.name);
                        $('#update-customer-email').val(customer.email);
                        $('#update-customer-phone').val(customer.phone);
                        $('#update-customer-modal').modal('show');
                    },
                    error: function() {
                        showToast("Error fetching customer details.", "error");
                    }
                });
            });

            $('#update-customer-form').on('submit', function(event) {
                event.preventDefault();

                const customerId = $('#update-customer-id').val();
                const updatedCustomer = {
                    id: customerId,
                    name: $('#update-customer-name').val(),
                    email: $('#update-customer-email').val(),
                    phone: $('#update-customer-phone').val()
                };

                $.ajax({
                    url: '/update-customer',
                    type: 'PUT',
                    headers: {
                        'id': userId,
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: JSON.stringify(updatedCustomer),
                    contentType: 'application/json',
                    success: function() {
                        showToast("Customer updated successfully!", "success");
                        $('#update-customer-modal').modal('hide');
                        fetchCustomers();
                    },
                    error: function() {
                        showToast("Error updating customer.", "error");
                    }
                });
            });

            function showToast(message, type) {
                Toastify({
                    text: message,
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    backgroundColor: type === "success" ? "#4CAF50" : "#FF0000"
                }).showToast();
            }
        });
    </script>
</body>

</html>
