<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script> <!-- Toastify for toast notifications -->
</head>

<body>
    <h1>Login Page</h1>
    <form id="login-form">
        <input id="email" type="email" name="email" placeholder="Email" required><br>
        <input id="password" type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Login</button>
    </form>

    <script>
        // Success Toast with a clickable link
        function showSuccessToast(message) {
            Toastify({
                text: message,
                duration: 3000,
                close: true,
                gravity: "top", // Position: top or bottom
                position: "right", // Position: left, center, or right
                backgroundColor: "green",
                stopOnFocus: true, // Prevents dismissing of toast on hover
                onClick: function() { // On click, redirect to dashboard
                    window.location.href = '/dashboard';
                }
            }).showToast();
        }

        // Error Toast
        function showErrorToast(message) {
            Toastify({
                text: message,
                duration: 3000,
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
