<!-- resources/views/pages/auth/send-otp-page.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send OTP</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <h1>Send OTP</h1>
    <form id="send-otp-form">
        <input type="email" name="email" placeholder="Email" required><br>
        <button type="submit">Send OTP</button>
    </form>

    <script>
        $('#send-otp-form').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: '/otp-send',
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
