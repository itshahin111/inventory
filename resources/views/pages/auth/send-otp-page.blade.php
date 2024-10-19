<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send OTP</title>
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

        .otp-container {
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

        input[type="email"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="otp-container">
        <h1>Send OTP</h1>
        <form id="send-otp-form">
            <input type="email" id="email" name="email" placeholder="Email" required><br>
            <button type="button" onclick="VerifyEmail()">Send OTP</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
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
            console.log('Showing loader...'); // Replace with actual loader UI if needed
        }

        // Hide loading animation
        function hideLoader() {
            console.log('Hiding loader...'); // Replace with actual loader UI if needed
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
