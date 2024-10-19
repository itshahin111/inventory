<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
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

        .reset-password-container {
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

        input[type="password"] {
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
    <div class="reset-password-container">
        <h1>Reset Password</h1>
        <form id="reset-password-form">
            @csrf
            <input type="password" name="password" id="password" placeholder="New Password" required>
            <input type="password" name="cpassword" id="cpassword" placeholder="Confirm Password" required>
            <button type="button" onclick="ResetPass()">Reset Password</button>
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

        // Reset Password using jQuery Ajax
        function ResetPass() {
            let password = $('#password').val();
            let cpassword = $('#cpassword').val();

            if (password.length === 0) {
                errorToast('Password is required');
            } else if (cpassword.length === 0) {
                errorToast('Confirm Password is required');
            } else if (password !== cpassword) {
                errorToast('Password and Confirm Password must be the same');
            } else {
                showLoader();
                $.ajax({
                    url: '/reset-pass',
                    method: 'POST',
                    data: {
                        password: password,
                        _token: "{{ csrf_token() }}" // Laravel CSRF token
                    },
                    success: function(res) {
                        hideLoader();
                        if (res.status === true) {
                            successToast(res.message);
                            setTimeout(function() {
                                window.location.href = '/userLogin';
                            }, 1000);
                        } else {
                            errorToast(res.message);
                        }
                    },
                    error: function(xhr) {
                        hideLoader();
                        errorToast('Error resetting password');
                    }
                });
            }
        }
    </script>
</body>

</html>
