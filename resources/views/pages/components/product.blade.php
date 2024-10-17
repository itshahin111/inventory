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

        input[type="text"],
        input[type="number"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 200px;
            margin-right: 10px;
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

        #update-product-modal {
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
    <nav class="navbar navbar-light bg-primary">
        <a class="nav-link" href="{{ url('dashboard') }}">Dashboard</a>
        <a class="nav-link" href="#">Home</a>
    </nav>
    <div>
        <h2>Manage Products</h2>
        <form id="add-product-form" style="text-align: center">
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

    <div id="update-product-modal">
        <h3>Update Product</h3>
        <form id="update-product-form" enctype="multipart/form-data">
            <input type="hidden" id="update-product-id">
            <input type="text" id="update-product-name" placeholder="New Product Name" required>
            <input type="number" id="update-product-price" placeholder="New Price" required>
            <input type="text" id="update-product-unit" placeholder="New Unit" required>
            <input type="file" id="update-product-image">
            <button type="submit">Update Product</button>
            <button type="button" id="cancel-update">Cancel</button>
        </form>
    </div>

    <script>
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
                                        <button class="edit-product btn btn-warning btn-sm" data-id="${product.id}">Edit</button>
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
                        'id': userId
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

            // Show update form and update product
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
                        $('#update-product-modal').show();
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
                        $('#update-product-modal').hide();
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
                $('#update-product-modal').hide();
            });
        });
    </script>
</body>

</html>
