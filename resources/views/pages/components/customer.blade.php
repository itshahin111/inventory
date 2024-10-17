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
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f8f9fa;
        }

        h2 {
            color: #333;
            text-align: center;
        }

        form {
            margin-bottom: 20px;
        }

        input[type="text"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 200px;
        }

        button {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
            margin-left: 5px;
        }

        button:hover {
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
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
        }

        th,
        td {
            padding: 10px;
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

        #update-customer-modal {
            display: none;
            border: 1px solid #ccc;
            padding: 20px;
            margin-top: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div>
        <h2>Customers</h2>
        <form id="add-customer-form" style="text-align: center">
            <input type="text" id="customer-name" placeholder="Customer Name" required>
            <input type="text" id="customer-email" placeholder="Customer Email" required>
            <input type="text" id="customer-phone" placeholder="Customer Phone" required>
            <button type="submit">Add Customer</button>
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

    <div id="update-customer-modal">
        <h3>Update Customer</h3>
        <form id="update-customer-form">
            <input type="hidden" id="update-customer-id">
            <input type="text" id="update-customer-name" placeholder="New Customer Name" required>
            <input type="text" id="update-customer-email" placeholder="New Customer Email" required>
            <input type="text" id="update-customer-phone" placeholder="New Customer Phone" required>
            <button type="submit">Update</button>
            <button type="button" id="cancel-update">Cancel</button>
        </form>
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
                        boxShadow: "0 5px 15px rgba(0, 0, 0, 0.3)",
                        padding: "12px 20px"
                    },
                    offset: {
                        y: 70 // Adjust this if the toast overlaps with other content
                    }
                }).showToast();
            }

            // Add new customer
            $('#add-customer-form').on('submit', function(e) {
                e.preventDefault();
                const customerName = $('#customer-name').val();
                const customerEmail = $('#customer-email').val();
                const customerPhone = $('#customer-phone').val();
                $('#loading').show();

                $.ajax({
                    url: '/add-customer',
                    type: 'POST',
                    headers: {
                        'id': userId
                    },
                    data: {
                        name: customerName,
                        email: customerEmail,
                        phone: customerPhone
                    },
                    success: function() {
                        $('#customer-name').val('');
                        $('#customer-email').val('');
                        $('#customer-phone').val('');
                        fetchCustomers(); // Refresh the list
                        showToast("Customer added successfully!", "success");
                    },
                    error: function() {
                        showToast("Error adding customer. Please try again.", "error");
                    },
                    complete: function() {
                        $('#loading').hide();
                    }
                });
            });

            // Delete a customer with confirmation
            $(document).on('click', '.delete-customer', function() {
                const customerId = $(this).data('id');
                if (confirm('Are you sure you want to delete this customer?')) {
                    $('#loading').show();

                    $.ajax({
                        url: '/delete-customer',
                        type: 'DELETE',
                        headers: {
                            'id': userId
                        },
                        data: {
                            id: customerId
                        },
                        success: function() {
                            fetchCustomers(); // Refresh the list
                            showToast("Customer deleted successfully!", "success");
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

            // Show update form
            $(document).on('click', '.edit-customer', function() {
                const customerId = $(this).data('id');
                $('#loading').show();

                $.ajax({
                    url: '/customer-id',
                    type: 'POST',
                    headers: {
                        'id': userId
                    },
                    data: {
                        id: customerId
                    },
                    success: function(data) {
                        $('#update-customer-id').val(data.id);
                        $('#update-customer-name').val(data.name);
                        $('#update-customer-email').val(data.email);
                        $('#update-customer-phone').val(data.phone);
                        $('#update-customer-modal').show();
                    },
                    error: function() {
                        showToast("Error fetching customer details. Please try again.",
                            "error");
                    },
                    complete: function() {
                        $('#loading').hide();
                    }
                });
            });

            // Update a customer
            $('#update-customer-form').on('submit', function(e) {
                e.preventDefault();
                const customerId = $('#update-customer-id').val();
                const newCustomerName = $('#update-customer-name').val();
                const newCustomerEmail = $('#update-customer-email').val();
                const newCustomerPhone = $('#update-customer-phone').val();
                $('#loading').show();

                $.ajax({
                    url: '/update-customer',
                    type: 'PUT',
                    headers: {
                        'id': userId
                    },
                    data: {
                        id: customerId,
                        name: newCustomerName,
                        email: newCustomerEmail,
                        phone: newCustomerPhone
                    },
                    success: function() {
                        $('#update-customer-modal').hide();
                        fetchCustomers(); // Refresh the list
                        showToast("Customer updated successfully!", "info");
                    },
                    error: function() {
                        showToast("Error updating customer. Please try again.", "error");
                    },
                    complete: function() {
                        $('#loading').hide();
                    }
                });
            });

            // Cancel updating customer
            $('#cancel-update').on('click', function() {
                $('#update-customer-modal').hide();
            });
        });
    </script>
</body>

</html>
