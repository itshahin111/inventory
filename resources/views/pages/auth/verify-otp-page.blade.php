<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
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

        input[type="text"] {
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
        <h1>Verify OTP</h1>
        <form id="verify-otp-form">
            @csrf
            <input id="otp" placeholder="Enter your OTP code" class="form-control" type="text" maxlength="4"
                required />
            <br />
            <button type="button" onclick="VerifyOtp()" class="btn w-100 float-end">Next</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        // Function to show success toast
        function successToast(message) {
            Toastify({
                text: message,
                duration: 3000,
                gravity: "top",
                position: "right",
                backgroundColor: "green",
            }).showToast();
        }

        // Function to show error toast
        function errorToast(message) {
            Toastify({
                text: message,
                duration: 3000,
                gravity: "top",
                position: "right",
                backgroundColor: "red",
            }).showToast();
        }

        // Show loader (example function)
        function showLoader() {
            console.log('Showing loader...'); // Replace with actual loader UI if needed
        }

        // Hide loader
        function hideLoader() {
            console.log('Hiding loader...'); // Replace with actual loader UI if needed
        }

        // Verify OTP using jQuery Ajax
        function VerifyOtp() {
            let otp = $('#otp').val();
            if (otp.length !== 4) {
                errorToast('Invalid OTP. Please enter a 4-digit code.');
            } else {
                showLoader();
                $.ajax({
                    url: '/verify-otp',
                    method: 'POST',
                    data: {
                        otp: otp,
                        email: sessionStorage.getItem('email'),
                        _token: "{{ csrf_token() }}" // CSRF Token
                    },
                    success: function(res) {
                        hideLoader();
                        if (res.status === 'success') {
                            successToast(res.message);
                            sessionStorage.clear();
                            setTimeout(function() {
                                window.location.href = '/resetPassword';
                            }, 1000);
                        } else {
                            errorToast(res.message);
                        }
                    },
                    error: function(xhr) {
                        hideLoader();
                        errorToast('Error verifying OTP');
                    }
                });
            }
        }
    </script>
</body>

</html>
