<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
    <link rel="stylesheet" href="{{ asset('bootstrap-5.3.3/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
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

        input[type="text"] {
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
        <h2>Categories</h2>
        <form id="add-category-form">
            <input type="text" id="category-name" placeholder="Category Name" required>
            <button type="submit">Add Category</button>
        </form>
        <div id="loading">Loading...</div>

        <table id="category-list">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Category Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <!-- Update Category Modal -->
    <div id="update-category-modal" class="modal fade" tabindex="-1" aria-labelledby="updateCategoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateCategoryModalLabel">Update Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="update-category-form">
                        <input type="hidden" id="update-category-id">
                        <div class="mb-3">
                            <label for="update-category-name" class="form-label">New Category Name</label>
                            <input type="text" id="update-category-name" class="form-control"
                                placeholder="New Category Name" required>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">Update Category</button>
                            <button type="button" id="cancel-update" class="btn btn-secondary"
                                data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            const userId = 'USER_ID'; // Replace with the actual user ID

            // Fetch and render categories when the page loads
            fetchCategories();

            function fetchCategories() {
                $('#loading').show();
                $.ajax({
                    url: '/category',
                    type: 'GET',
                    headers: {
                        'id': userId
                    },
                    success: function(data) {
                        const tbody = $('#category-list tbody');
                        tbody.empty();
                        data.forEach(function(category, index) {
                            tbody.append(
                                `<tr>
                                    <td>${index + 1}</td>
                                    <td>${category.name}</td>
                                    <td>
                                        <button class="edit-category btn btn-warning btn-sm" data-id="${category.id}" data-bs-toggle="modal" data-bs-target="#update-category-modal">Edit</button>
                                        <button class="delete-category btn btn-danger btn-sm" data-id="${category.id}">Delete</button>
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

            // Add category
            $('#add-category-form').on('submit', function(e) {
                e.preventDefault();
                const categoryName = $('#category-name').val();
                $('#loading').show();

                $.ajax({
                    url: '/add-category',
                    type: 'POST',
                    headers: {
                        'id': userId
                    },
                    data: {
                        name: categoryName
                    },
                    success: function() {
                        fetchCategories();
                        showToast("Category added successfully!", "success");
                    },
                    error: function() {
                        showToast("Error adding category. Please try again.", "error");
                    },
                    complete: function() {
                        $('#loading').hide();
                    }
                });
            });

            // Delete category
            $(document).on('click', '.delete-category', function() {
                const categoryId = $(this).data('id');
                if (confirm("Are you sure you want to delete this category?")) {
                    $('#loading').show();
                    $.ajax({
                        url: '/delete-category',
                        type: 'DELETE',
                        headers: {
                            'id': userId
                        },
                        data: {
                            id: categoryId
                        },
                        success: function() {
                            fetchCategories();
                            showToast("Category deleted successfully!", "success");
                        },
                        error: function() {
                            showToast("Error deleting category. Please try again.", "error");
                        },
                        complete: function() {
                            $('#loading').hide();
                        }
                    });
                }
            });

            // Show update form and update category
            $(document).on('click', '.edit-category', function() {
                const categoryId = $(this).data('id');
                $('#loading').show();

                $.ajax({
                    url: '/category-id',
                    type: 'GET',
                    headers: {
                        'id': userId
                    },
                    data: {
                        id: categoryId
                    },
                    success: function(data) {
                        $('#update-category-id').val(data.id);
                        $('#update-category-name').val(data.name);
                        $('#update-category-modal').show();
                    },
                    complete: function() {
                        $('#loading').hide();
                    }
                });
            });

            // Update a category
            $('#update-category-form').on('submit', function(e) {
                e.preventDefault();
                const categoryId = $('#update-category-id').val();
                const newCategoryName = $('#update-category-name').val();
                $('#loading').show();

                $.ajax({
                    url: '/update-category',
                    type: 'PUT',
                    headers: {
                        'id': userId
                    },
                    data: {
                        id: categoryId,
                        name: newCategoryName
                    },
                    success: function() {
                        $('#update-category-modal').modal(
                            'hide'); // Updated to use Bootstrap's modal method
                        fetchCategories();
                        showToast("Category updated successfully!", "success");
                    },
                    error: function() {
                        showToast("Error updating category. Please try again.", "error");
                    },
                    complete: function() {
                        $('#loading').hide();
                    }
                });
            });

            // Cancel updating category
            $('#cancel-update').on('click', function() {
                $('#update-category-modal').modal('hide'); // Updated to use Bootstrap's modal method
            });

            function showToast(message, type) {
                Toastify({
                    text: message,
                    duration: 3000,
                    gravity: "top", // top or bottom
                    position: 'right', // left, center or right
                    backgroundColor: type === "success" ? "green" : "red",
                    stopOnFocus: true // Prevents dismissing of toast on hover
                }).showToast();
            }
        });
    </script>
</body>

</html>
