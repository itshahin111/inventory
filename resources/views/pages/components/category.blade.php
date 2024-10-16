<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
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

        #update-category-modal {
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
        <h2>Categories</h2>
        <form id="add-category-form" style="text-align: center">
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

    <div id="update-category-modal">
        <h3>Update Category</h3>
        <form id="update-category-form">
            <input type="hidden" id="update-category-id">
            <input type="text" id="update-category-name" placeholder="New Category Name" required>
            <button type="submit">Update Category</button>
            <button type="button" id="cancel-update">Cancel</button>
        </form>
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
                                        <button class="edit-category btn btn-warning btn-sm" data-id="${category.id}">Edit</button>
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

            // Add new category
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
                        $('#category-name').val('');
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

            // Delete a category with confirmation
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
                        $('#update-category-modal').hide();
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
                $('#update-category-modal').hide();
            });
        });
    </script>
</body>

</html>
