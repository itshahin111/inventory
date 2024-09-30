<!-- resources/views/pages/auth/reset-password-page.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <h1>Reset Password</h1>
    <form id="reset-password-form">
        <!-- Include CSRF Token -->
        @csrf
        <input type="password" name="password" id="password" placeholder="New Password" required><br>
        <button type="submit">Reset Password</button>
    </form>

    <script>
        $('#reset-password-form').on('submit', function(e) {
            e.preventDefault();

            let password = $('#password').val();

            // Basic client-side password validation
            if (password.length < 6) {
                alert('Password must be at least 6 characters long.');
                return;
            }

            $.ajax({
                url: '/reset-password',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    // Show success message and optionally redirect
                    alert(response.message);
                    window.location.href = '/login'; // Redirect to login page after success
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        alert(xhr.responseJSON.message); // Show error message from server
                    } else {
                        alert('An error occurred. Please try again.'); // Fallback error message
                    }
                }
            });
        });
    </script>
</body>

</html>
