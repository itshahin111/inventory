<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
</head>

<body>
    <h1>Verify OTP</h1>
    <form id="verify-otp-form">
        @csrf
        <input id="otp" placeholder="Code" class="form-control" type="text" />
        <br />
        <button type="button" onclick="VerifyOtp()" class="btn w-100 float-end bg-gradient-primary">Next</button>
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

        // Verify OTP using jQuery Ajax
        function VerifyOtp() {
            let otp = $('#otp').val();
            if (otp.length !== 4) {
                errorToast('Invalid OTP');
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
