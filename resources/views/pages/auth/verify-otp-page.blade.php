<!-- resources/views/pages/auth/verify-otp-page.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <h1>Verify OTP</h1>
    <form id="verify-otp-form">
        <!-- Include CSRF Token for Laravel POST requests -->
        @csrf
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="text" name="otp" placeholder="Enter OTP" required><br>
        <button type="submit">Verify OTP</button>
    </form>

    <script>
        $('#verify-otp-form').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: '/verify-otp',
                method: 'POST',
                data: $(this).serialize(), // Serialize form data
                success: function(response) {
                    // Show a success message from the response
                    alert(response.message);
                    // Optionally redirect to another page after successful verification
                    window.location.href = '/dashboard'; // Redirect to dashboard
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        alert(xhr.responseJSON.message); // Show error from server
                    } else {
                        alert('An error occurred. Please try again.'); // Fallback error
                    }
                }
            });
        });
    </script>
</body>

</html>
