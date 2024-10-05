<!-- resources/views/pages/auth/reset-password-page.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
</head>

<body>
    <h1>Reset Password</h1>
    <form id="reset-password-form">
        <!-- Include CSRF Token -->
        @csrf
        <input type="password" name="password" id="password" placeholder="New Password" required><br>
        <input type="password" name="cpassword" id="cpassword" placeholder="Confirm Password" required><br>
        <button type="button" onclick="ResetPass()">Reset Password</button>
    </form>

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
            console.log('Showing loader...');
        }

        // Hide loader
        function hideLoader() {
            console.log('Hiding loader...');
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
