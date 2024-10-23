<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
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

        select,
        input[type="file"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
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
        <form id="productForm">
            <input type="text" id="name" name="name" placeholder="Product Name" required>
            <input type="number" id="price" name="price" placeholder="Price" required>
            <input type="text" id="unit" name="unit" placeholder="Unit" required>
            <select id="category_id" name="category_id" required>
                <option value="" disabled selected>Select Category</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            <input type="file" id="img" name="img" accept="image/*" required>
            <button type="submit">Add Product</button>
        </form>

        <div id="loading">Loading...</div>
        <table id="productTable">
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
    <!-- Update Product Modal -->
    <div class="modal fade" id="updateProductModal" tabindex="-1" aria-labelledby="updateModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Update Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateProductForm" enctype="multipart/form-data">
                        <input type="hidden" id="update_id">
                        <input type="text" id="update_name" name="name" placeholder="Product Name" required>
                        <input type="number" id="update_price" name="price" placeholder="Price" required>
                        <input type="text" id="update_unit" name="unit" placeholder="Unit" required>
                        <select id="update_category_id" name="category_id" required>
                            <option value="" disabled selected>Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <img id="update_img_preview" src="" alt="" width="100"
                            style="display: none; margin-top: 10px;">
                        <label for="update_img">Update Image</label>
                        <input type="file" id="update_img" name="img" accept="image/*">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="updateProductBtn" class="btn btn-primary">Update Product</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function() {
            // Fetch and display products
            function fetchProducts() {
                $.ajax({
                    url: '/productList',
                    type: 'GET',
                    success: function(products) {
                        let rows = '';
                        products.forEach(product => {
                            rows += `
                                <tr>
                                    <td>${product.id}</td>
                                    <td>${product.name}</td>
                                    <td>${product.price}</td>
                                    <td>${product.unit}</td>
                                    <td><img src="${product.img_url}" alt="${product.name}" width="100"></td>
                                    <td>
                                        <button class="btn btn-warning edit-btn" data-id="${product.id}" data-bs-toggle="modal" data-bs-target="#updateProductModal">Edit</button>
                                        <button class="btn btn-danger delete-btn" data-id="${product.id}">Delete</button>
                                    </td>
                                </tr>
                            `;
                        });
                        $('#productTable tbody').html(rows);
                    },
                    error: function() {
                        Toastify({
                            text: "Error fetching products",
                            duration: 3000,
                            gravity: "top",
                            position: "right",
                            backgroundColor: "#FF0000",
                        }).showToast();
                    }
                });
            }

            // Add Product
            $('#productForm').on('submit', function(event) {
                event.preventDefault();
                $('#loading').show();
                const formData = new FormData(this);

                $.ajax({
                    url: '/add-product',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#loading').hide();
                        $('#productForm')[0].reset();
                        fetchProducts();
                        Toastify({
                            text: response.message,
                            duration: 3000,
                            gravity: "top",
                            position: "right",
                            backgroundColor: "#28a745",
                        }).showToast();
                    },
                    error: function(xhr) {
                        $('#loading').hide();
                        Toastify({
                            text: xhr.responseJSON.message,
                            duration: 3000,
                            gravity: "top",
                            position: "right",
                            backgroundColor: "#FF0000",
                        }).showToast();
                    }
                });
            });

            // Populate modal for editing
            $(document).on('click', '.edit-btn', function() {
                const id = $(this).data('id');

                $.ajax({
                    url: `/product/${id}`,
                    type: 'GET',
                    success: function(product) {
                        $('#update_id').val(product.id);
                        $('#update_name').val(product.name);
                        $('#update_price').val(product.price);
                        $('#update_unit').val(product.unit);
                        $('#update_category_id').val(product.category_id);
                        $('#update_img_preview').attr('src', product.img_url)
                            .show(); // Set the existing image URL and show it
                    },
                    error: function() {
                        Toastify({
                            text: "Error fetching product data",
                            duration: 3000,
                            gravity: "top",
                            position: "right",
                            backgroundColor: "#FF0000",
                        }).showToast();
                    }
                });
            });


            // Update Product
            $('#updateProductBtn').on('click', function() {
                const id = $('#update_id').val();
                const formData = new FormData($('#updateProductForm')[0]);

                $.ajax({
                    url: `/update-product/${id}`,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#updateProductModal').modal('hide');
                        fetchProducts();
                        Toastify({
                            text: response.message,
                            duration: 3000,
                            gravity: "top",
                            position: "right",
                            backgroundColor: "#28a745",
                        }).showToast();
                    },
                    error: function(xhr) {
                        Toastify({
                            text: xhr.responseJSON.message,
                            duration: 3000,
                            gravity: "top",
                            position: "right",
                            backgroundColor: "#FF0000",
                        }).showToast();
                    }
                });
            });

            // Delete Product
            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id');

                if (confirm('Are you sure you want to delete this product?')) {
                    $.ajax({
                        url: `/delete-product/${id}`,
                        type: 'DELETE',
                        success: function(response) {
                            fetchProducts();
                            Toastify({
                                text: response.message,
                                duration: 3000,
                                gravity: "top",
                                position: "right",
                                backgroundColor: "#28a745",
                            }).showToast();
                        },
                        error: function(xhr) {
                            Toastify({
                                text: xhr.responseJSON.message,
                                duration: 3000,
                                gravity: "top",
                                position: "right",
                                backgroundColor: "#FF0000",
                            }).showToast();
                        }
                    });
                }
            });

            // Initial fetch
            fetchProducts();
        });
    </script>
</body>

</html>
