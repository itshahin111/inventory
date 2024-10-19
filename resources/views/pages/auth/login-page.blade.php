<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 320px;
            text-align: center;
        }

        h1 {
            color: #333333;
            margin-bottom: 20px;
        }

        input[type="email"],
        input[type="password"],
        button {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            display: block;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        p {
            font-size: 14px;
            margin-top: 10px;
        }

        p a {
            color: #007bff;
            text-decoration: none;
        }

        p a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h1>Login</h1>
        <form id="login-form">
            <input id="email" type="email" name="email" placeholder="Email" required><br>
            <input id="password" type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Login</button><br>
            <p>
                If you don't have an account yet, <a href="{{ url('/registration') }}">sign up here</a><br>
                If you've forgotten your password, <a href="{{ url('/sendOtp') }}">click here</a> to get a password
                reset
                link.
            </p>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        // Success Toast with a clickable link
        function showSuccessToast(message) {
            Toastify({
                text: message,
                duration: 1000,
                close: true,
                gravity: "top", // Position: top or bottom
                position: "right", // Position: left, center, or right
                backgroundColor: "green",
                stopOnFocus: true, // Prevents dismissing of toast on hover
            }).showToast();
            // auto redirect to dashboard
            setTimeout(function() {
                window.location.href = '/dashboard'; // Redirect to the dashboard
            }, 800);
        }

        // Error Toast
        function showErrorToast(message) {
            Toastify({
                text: message,
                duration: 800,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: "red",
                stopOnFocus: true
            }).showToast();
        }

        // Handle login form submission
        $('#login-form').on('submit', function(e) {
            e.preventDefault(); // Prevent default form submission

            // Retrieve form data
            let email = $('#email').val();
            let password = $('#password').val();

            // Send AJAX request to login API
            axios.post('/login', {
                    email: email,
                    password: password
                })
                .then(function(response) {
                    // On successful login
                    if (response.data.status) {
                        // Show success toast and redirect to the dashboard
                        showSuccessToast(response.data.message);
                        // Store JWT token in localStorage
                        localStorage.setItem('token', response.data.token);
                    } else {
                        // Show error toast
                        showErrorToast('Login failed. Unauthorized.');
                    }
                })
                .catch(function(error) {
                    if (error.response) {
                        // Show error message from server
                        showErrorToast(error.response.data.message);
                    } else {
                        // Fallback error message
                        showErrorToast('Something went wrong, please try again.');
                    }
                });
        });
    </script>
</body>

</html>
