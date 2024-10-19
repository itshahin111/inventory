<!-- resources/views/pages/admin/admin-dashboard.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('bootstrap-5.3.3/css/bootstrap.min.css') }}">
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('bootstrap-5.3.3/js/bootstrap.bundle.js') }}"></script>
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

        h1 {
            margin-bottom: 20px;
        }

        .btn {
            margin: 5px;
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
            <a class="nav-link active" href="{{ url('categoryList') }}">Categories</a>
            <a class="nav-link" href="{{ url('product') }}">Products</a>
            <a class="nav-link" href="{{ url('customers') }}">Customers</a>
            <a class="nav-link" href="{{ url('orders') }}">Orders</a>
            <a class="nav-link" href="{{ url('reports') }}">Reports</a>
            <a class="nav-link" href="{{ url('settings') }}">Settings</a>
        </nav>
    </div>
    <div>
        @yield('content')


    </div>


</body>

</html>
