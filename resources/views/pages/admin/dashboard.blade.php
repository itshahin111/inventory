<!-- resources/views/pages/admin/admin-dashboard.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="container mt-4">
        <h1 class="text-center">Inventory Management Dashboard</h1>
        <a href="{{ url('userProfile') }}" class="btn btn-sm btn-outline-warning">Update Profile</a>

        <!-- Navigation -->

        <nav class="nav justify-content-center mb-4">
            <a class="nav-link active" href="#inventory">Inventory</a>
            <a class="nav-link" href="#add-item">Add Item</a>
            <a class="nav-link" href="#manage-items">Manage Items</a>
        </nav>
        {{-- Logout --}}
        <a href="{{ url('logout') }}" class="btn btn-sm btn-outline-danger">Logout</a>

        <!-- Inventory Section -->
        <div id="inventory" class="mb-5">
            <h2>Current Inventory</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="inventory-table-body">
                    <!-- Inventory items will be dynamically populated here -->
                </tbody>
            </table>
        </div>

        <!-- Add Item Section -->
        <div id="add-item" class="mb-5">
            <h2>Add New Item</h2>
            <form id="add-item-form">
                <div class="mb-3">
                    <label for="item-name" class="form-label">Item Name</label>
                    <input type="text" class="form-control" id="item-name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="item-quantity" class="form-label">Quantity</label>
                    <input type="number" class="form-control" id="item-quantity" name="quantity" required>
                </div>
                <div class="mb-3">
                    <label for="item-price" class="form-label">Price</label>
                    <input type="number" step="0.01" class="form-control" id="item-price" name="price" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Item</button>
            </form>
        </div>

        <!-- Manage Items Section -->
        <div id="manage-items">
            <h2>Manage Existing Items</h2>
            <form id="manage-items-form">
                <div class="mb-3">
                    <label for="manage-item-id" class="form-label">Item ID</label>
                    <input type="number" class="form-control" id="manage-item-id" name="id" required>
                </div>
                <div class="mb-3">
                    <label for="manage-item-quantity" class="form-label">New Quantity</label>
                    <input type="number" class="form-control" id="manage-item-quantity" name="new_quantity" required>
                </div>
                <button type="submit" class="btn btn-warning">Update Quantity</button>
            </form>
        </div>
    </div>

    <script>
        // Example AJAX request to fetch inventory items
        $(document).ready(function() {
            loadInventory();

            $('#add-item-form').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: '/admin/add-item',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        alert(response.message);
                        loadInventory(); // Reload inventory after adding
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON.message);
                    }
                });
            });

            $('#manage-items-form').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: '/admin/manage-item',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        alert(response.message);
                        loadInventory(); // Reload inventory after updating
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON.message);
                    }
                });
            });
        });

        function loadInventory() {
            $.ajax({
                url: '/admin/inventory',
                method: 'GET',
                success: function(data) {
                    const inventoryTableBody = $('#inventory-table-body');
                    inventoryTableBody.empty();
                    data.forEach(item => {
                        inventoryTableBody.append(`
                            <tr>
                                <td>${item.id}</td>
                                <td>${item.name}</td>
                                <td>${item.quantity}</td>
                                <td>${item.price}</td>
                                <td>
                                    <button class="btn btn-danger" onclick="deleteItem(${item.id})">Delete</button>
                                </td>
                            </tr>
                        `);
                    });
                },
                error: function(xhr) {
                    alert('Failed to load inventory.');
                }
            });
        }

        function deleteItem(itemId) {
            $.ajax({
                url: `/admin/delete-item/${itemId}`,
                method: 'DELETE',
                success: function(response) {
                    alert(response.message);
                    loadInventory(); // Reload inventory after deletion
                },
                error: function(xhr) {
                    alert(xhr.responseJSON.message);
                }
            });
        }
    </script>
</body>

</html>
