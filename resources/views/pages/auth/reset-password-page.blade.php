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
        <input type="password" name="password" placeholder="New Password" required><br>
        <button type="submit">Reset Password</button>
    </form>

    <script>
        $('#reset-password-form').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: '/reset-password',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    alert(response.message);
                },
                error: function(xhr) {
                    alert(xhr.responseJSON.message);
                }
            });
        });
    </script>
</body>

</html>
