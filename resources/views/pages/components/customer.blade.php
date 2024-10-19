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

    <style>
        body {
            background-color: #f8f9fa;
        }

        .sidebar {
            height: 100vh;
            position: fixed;
            top: 56px;
            /* Adjust for the navbar height */
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
            /* Adjust for sidebar width */
            padding: 20px;
        }

        .navbar {
            background-color: #007bff;
        }

        .navbar-brand {
            color: white !important;
        }

        .navbar-nav .nav-link {
            color: white !important;
        }

        .navbar-nav .nav-link:hover {
            background-color: rgba(28, 2, 255, 0.2);
        }

        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        #loading {
            display: none;
            font-weight: bold;
        }

        #error-message {
            color: red;
            margin: 10px 0;
        }

        table {
            width: 100%;
            margin-top: 20px;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
        }

        th,
        td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .modal {
            display: none;
            border: 1px solid #ccc;
            padding: 20px;
            margin-top: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            border-bottom: 1px solid #ddd;
            margin-bottom: 10px;
        }

        .modal-header h3 {
            margin: 0;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="ms-auto">
                    <a href="{{ url('profile') }}" class="btn btn-sm btn-outline-warning">Update Profile</a>
                    <a href="{{ url('logout') }}" class="btn btn-sm btn-outline-danger">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar Navigation -->
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
                <input type="text" id="customer-name" class="form-control d-inline-block w-auto"
                    placeholder="Customer Name" required>
                <input type="text" id="customer-email" class="form-control d-inline-block w-auto"
                    placeholder="Customer Email" required>
                <input type="text" id="customer-phone" class="form-control d-inline-block w-auto"
                    placeholder="Customer Phone" required>
                <button type="submit" class="btn btn-primary">Add Customer</button>
            </form>
            <div id="loading">Loading...</div>
            <div id="error-message"></div>

            <table id="customer-list">
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

        <div class="modal" id="update-customer-modal">
            <div class="modal-header">
                <h3>Update Customer</h3>
            </div>
            <form id="update-customer-form">
                <input type="hidden" id="update-customer-id">
                <input type="text" id="update-customer-name" class="form-control" placeholder="New Customer Name"
                    required>
                <input type="text" id="update-customer-email" class="form-control" placeholder="New Customer Email"
                    required>
                <input type="text" id="update-customer-phone" class="form-control" placeholder="New Customer Phone"
                    required>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" id="cancel-update" class="btn btn-secondary">Cancel</button>
                </div>
            </form>
        </div>

    </div>

    <script>
        $(document).ready(function() {
            const userId = 'USER_ID'; // Replace with the actual user ID

            // Fetch and render customers when the page loads
            fetchCustomers();

            function fetchCustomers() {
                $('#loading').show();
                $('#error-message').hide();

                $.ajax({
                    url: '/customerList',
                    type: 'GET',
                    headers: {
                        'id': userId
                    },
                    success: function(data) {
                        const tbody = $('#customer-list tbody');
                        tbody.empty();
                        data.forEach(function(customer, index) {
                            tbody.append(
                                `<tr>
                            <td>${index + 1}</td>
                            <td>${customer.name}</td>
                            <td>${customer.email}</td>
                            <td>${customer.phone}</td>
                            <td>
                                <button class="edit-customer btn btn-warning btn-sm" data-id="${customer.id}">Edit</button>
                                <button class="delete-customer btn btn-danger btn-sm" data-id="${customer.id}">Delete</button>
                            </td>
                        </tr>`
                            );
                        });
                    },
                    error: function() {
                        showToast("Error fetching customers. Please try again.", "error");
                    },
                    complete: function() {
                        $('#loading').hide();
                    }
                });
            }

            // Function to show a styled Toastify message
            function showToast(message, type) {
                const backgroundColors = {
                    success: "linear-gradient(to right, #28a745, #218838)",
                    error: "linear-gradient(to right, #dc3545, #c82333)",
                    info: "linear-gradient(to right, #007bff, #0056b3)"
                };

                Toastify({
                    text: message,
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    style: {
                        background: backgroundColors[type] || backgroundColors.info,
                        color: "#fff",
                        fontSize: "16px",
                        borderRadius: "8px",
                        boxShadow: "0 5px 15px rgba(0, 0, 0, 0.2)"
                    }
                }).showToast();
            }

            // Add customer
            $('#add-customer-form').on('submit', function(event) {
                event.preventDefault();
                $('#loading').show();
                $('#error-message').hide();

                const newCustomer = {
                    name: $('#customer-name').val(),
                    email: $('#customer-email').val(),
                    phone: $('#customer-phone').val(),
                };

                $.ajax({
                    url: '/addCustomer',
                    type: 'POST',
                    data: JSON.stringify(newCustomer),
                    contentType: 'application/json',
                    headers: {
                        'id': userId
                    },
                    success: function() {
                        showToast("Customer added successfully!", "success");
                        fetchCustomers();
                    },
                    error: function() {
                        showToast("Error adding customer. Please try again.", "error");
                    },
                    complete: function() {
                        $('#loading').hide();
                        $('#add-customer-form')[0].reset(); // Clear the form
                    }
                });
            });

            // Edit customer
            $(document).on('click', '.edit-customer', function() {
                const customerId = $(this).data('id');

                $.ajax({
                    url: `/customer/${customerId}`,
                    type: 'GET',
                    success: function(customer) {
                        $('#update-customer-id').val(customer.id);
                        $('#update-customer-name').val(customer.name);
                        $('#update-customer-email').val(customer.email);
                        $('#update-customer-phone').val(customer.phone);
                        $('#update-customer-modal').show();
                    },
                    error: function() {
                        showToast("Error fetching customer details.", "error");
                    }
                });
            });

            // Update customer
            $('#update-customer-form').on('submit', function(event) {
                event.preventDefault();
                $('#loading').show();
                $('#error-message').hide();

                const updatedCustomer = {
                    id: $('#update-customer-id').val(),
                    name: $('#update-customer-name').val(),
                    email: $('#update-customer-email').val(),
                    phone: $('#update-customer-phone').val(),
                };

                $.ajax({
                    url: '/updateCustomer',
                    type: 'PUT',
                    data: JSON.stringify(updatedCustomer),
                    contentType: 'application/json',
                    headers: {
                        'id': userId
                    },
                    success: function() {
                        showToast("Customer updated successfully!", "success");
                        fetchCustomers();
                        $('#update-customer-modal').hide();
                    },
                    error: function() {
                        showToast("Error updating customer. Please try again.", "error");
                    },
                    complete: function() {
                        $('#loading').hide();
                    }
                });
            });

            // Delete customer
            $(document).on('click', '.delete-customer', function() {
                const customerId = $(this).data('id');

                if (confirm("Are you sure you want to delete this customer?")) {
                    $('#loading').show();

                    $.ajax({
                        url: `/deleteCustomer/${customerId}`,
                        type: 'DELETE',
                        headers: {
                            'id': userId
                        },
                        success: function() {
                            showToast("Customer deleted successfully!", "success");
                            fetchCustomers();
                        },
                        error: function() {
                            showToast("Error deleting customer. Please try again.", "error");
                        },
                        complete: function() {
                            $('#loading').hide();
                        }
                    });
                }
            });

            // Cancel update
            $('#cancel-update').on('click', function() {
                $('#update-customer-modal').hide();
            });
        });
    </script>
</body>

</html>
