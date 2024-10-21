<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" href="{{ asset('bootstrap-5.3.3/css/bootstrap.min.css') }}">
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('bootstrap-5.3.3/js/bootstrap.bundle.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <style>
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

        form {
            margin-bottom: 20px;
            text-align: center;
        }

        input[type="text"],
        input[type="number"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 200px;
            margin-right: 5px;
        }

        button {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        #loading {
            display: none;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
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
            <a class="nav-link" href="{{ url('customers') }}">Customers</a>
            <a class="nav-link" href="{{ url('orders') }}">Orders</a>
            <a class="nav-link" href="{{ url('reports') }}">Reports</a>
            <a class="nav-link" href="{{ url('settings') }}">Settings</a>
        </nav>
    </div>

    <div class="content">
        <h2>Manage Products</h2>
        <form id="add-product-form">
            <input type="text" id="product-name" placeholder="Product Name" required>
            <input type="number" id="product-price" placeholder="Price" required>
            <input type="text" id="product-unit" placeholder="Unit" required>
            <input type="file" id="product-image" required>
            <button type="submit">Add Product</button>
        </form>
        <div id="loading">Loading...</div>

        <table id="product-list">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Unit</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <!-- Update Product Modal -->
    <div id="update-product-modal" class="modal fade" tabindex="-1" aria-labelledby="updateProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateProductModalLabel">Update Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="update-product-form" enctype="multipart/form-data">
                        <input type="hidden" id="update-product-id">
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select type="text" class="form-control form-select" id="productCategory">
                        </div>
                        <div class="mb-3">

                            <label for="update-product-name" class="form-label">New Product Name</label>
                            <input type="text" id="update-product-name" class="form-control"
                                placeholder="New Product Name" required>
                        </div>
                        <div class="mb-3">
                            <label for="update-product-price" class="form-label">New Price</label>
                            <input type="number" id="update-product-price" class="form-control" placeholder="New Price"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="update-product-unit" class="form-label">New Unit</label>
                            <input type="text" id="update-product-unit" class="form-control"
                                placeholder="New Unit" required>
                        </div>
                        <div class="mb-3">
                            <label for="update-product-image" class="form-label">New Image</label>
                            <input type="file" id="update-product-image" class="form-control">
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">Update Product</button>
                            <button type="button" id="cancel-update" class="btn btn-secondary"
                                data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let productCategory = document.getElementById('productCategory').value;
        $(document).ready(function() {
            const userId = 'USER_ID'; // Replace with the actual user ID

            // Fetch and render products when the page loads
            fetchProducts();

            function fetchProducts() {
                $('#loading').show();
                $.ajax({
                    url: '/productList',
                    type: 'GET',
                    headers: {
                        'id': userId
                    },
                    success: function(data) {
                        const tbody = $('#product-list tbody');
                        tbody.empty();
                        data.forEach(function(product, index) {
                            tbody.append(
                                `<tr>
                                    <td>${index + 1}</td>
                                    <td>${product.name}</td>
                                    <td>${product.price}</td>
                                    <td>${product.unit}</td>
                                    <td><img src="${product.img_url}" alt="${product.name}" width="100"></td>
                                    <td>
                                        <button class="edit-product btn btn-warning btn-sm" data-id="${product.id}" data-bs-toggle="modal" data-bs-target="#update-product-modal">Edit</button>
                                        <button class="delete-product btn btn-danger btn-sm" data-id="${product.id}">Delete</button>
                                    </td>
                                </tr>`
                            );
                        });
                    },
                    complete: function() {
                        $('#loading').hide();
                    }
                });
            }

            // Show toast notification
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
                        borderRadius: "8px",
                        padding: "10px 20px"
                    }
                }).showToast();
            }

            // Add new product
            $('#add-product-form').on('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                $('#loading').show();

                $.ajax({
                    url: '/add-product',
                    type: 'POST',
                    headers: {
                        'id': userId,
                        'category_id': category_id

                    },
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function() {
                        $('#add-product-form')[0].reset();
                        fetchProducts();
                        showToast("Product added successfully!", "success");
                    },
                    error: function() {
                        showToast("Error adding product. Please try again.", "error");
                    },
                    complete: function() {
                        $('#loading').hide();
                    }
                });
            });

            // Delete a product with confirmation
            $(document).on('click', '.delete-product', function() {
                const productId = $(this).data('id');
                if (confirm("Are you sure you want to delete this product?")) {
                    $('#loading').show();

                    $.ajax({
                        url: '/delete-product',
                        type: 'DELETE',
                        headers: {
                            'id': userId
                        },
                        data: {
                            id: productId
                        },
                        success: function() {
                            fetchProducts();
                            showToast("Product deleted successfully!", "success");
                        },
                        error: function() {
                            showToast("Error deleting product. Please try again.", "error");
                        },
                        complete: function() {
                            $('#loading').hide();
                        }
                    });
                }
            });

            // Show update form and populate with product data
            $(document).on('click', '.edit-product', function() {
                const productId = $(this).data('id');
                $('#loading').show();

                $.ajax({
                    url: '/product-id',
                    type: 'POST',
                    headers: {
                        'id': userId
                    },
                    data: {
                        id: productId
                    },
                    success: function(data) {
                        $('#update-product-id').val(data.id);
                        $('#update-product-name').val(data.name);
                        $('#update-product-price').val(data.price);
                        $('#update-product-unit').val(data.unit);
                        $('#update-product-image').val(''); // Clear the image input field
                    },
                    complete: function() {
                        $('#loading').hide();
                    }
                });
            });

            // Update product
            $('#update-product-form').on('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                $('#loading').show();

                $.ajax({
                    url: '/update-product',
                    type: 'POST',
                    headers: {
                        'id': userId
                    },
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function() {
                        fetchProducts();
                        showToast("Product updated successfully!", "success");
                        $('#update-product-modal').modal('hide');
                    },
                    error: function() {
                        showToast("Error updating product. Please try again.", "error");
                    },
                    complete: function() {
                        $('#loading').hide();
                    }
                });
            });

            // Cancel update
            $('#cancel-update').on('click', function() {
                $('#update-product-modal').modal('hide');
            });
        });
    </script>
</body>

</html>
