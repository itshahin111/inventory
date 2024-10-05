<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send OTP</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
</head>

<body>
    <h1>Send OTP</h1>
    <form id="send-otp-form">
        <input type="email" id="email" name="email" placeholder="Email" required><br>
        <button type="button" onclick="VerifyEmail()">Send OTP</button>
    </form>

    <script>
        // Function to show toast notifications for success
        function successToast(message) {
            Toastify({
                text: message,
                duration: 3000,
                gravity: "top",
                position: "right",
                backgroundColor: "green",
            }).showToast();
        }

        // Function to show toast notifications for errors
        function errorToast(message) {
            Toastify({
                text: message,
                duration: 3000,
                gravity: "top",
                position: "right",
                backgroundColor: "red",
            }).showToast();
        }

        // Show loading animation (example loader function)
        function showLoader() {
            console.log('Showing loader...');
        }

        // Hide loading animation
        function hideLoader() {
            console.log('Hiding loader...');
        }

        // Function to verify email and send OTP using jQuery ajax
        function VerifyEmail() {
            let email = $('#email').val();
            if (email.length === 0) {
                errorToast('Please enter your email address');
            } else {
                showLoader();

                $.ajax({
                    url: '/otp-send',
                    method: 'POST',
                    data: {
                        email: email,
                        _token: "{{ csrf_token() }}" // Include CSRF token if needed
                    },
                    success: function(res) {
                        hideLoader();
                        if (res.status === true) {
                            successToast(res.message);
                            sessionStorage.setItem('email', email);
                            setTimeout(function() {
                                window.location.href = '/verifyOtp';
                            }, 1000);
                        } else {
                            errorToast(res.message);
                        }
                    },
                    error: function(xhr) {
                        hideLoader();
                        errorToast('Error sending OTP');
                    }
                });
            }
        }
    </script>
</body>

</html>
