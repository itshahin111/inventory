<!-- resources/views/pages/auth/send-otp-page.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send OTP</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
</head>

<body>
    <h1>Send OTP</h1>
    <form id="send-otp-form">
        <!-- Added id="email" for jQuery selector -->
        <input type="email" id="email" name="email" placeholder="Email" required><br>
        <button type="submit">Send OTP</button>
    </form>

    <script>
        // Success Toast with a clickable link
        function showSuccessToast(message) {
            Toastify({
                text: message,
                duration: 1000,
                close: true,
                gravity: "top", // Position: top or bottom
                position: "right", // Position: left, center, or right
                backgroundColor: "green",
                stopOnFocus: true, // Prevents dismissing of toast on hover
            }).showToast();
            // Auto redirect to dashboard
            setTimeout(function() {
                window.location.href = '/dashboard'; // Redirect to the dashboard page
            }, 800);
        }

        // Error Toast
        function showErrorToast(message) {
            Toastify({
                text: message,
                duration: 800,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: "red",
                stopOnFocus: true
            }).showToast();
        }

        $('#send-otp-form').on('submit', function(e) {
            e.preventDefault();

            let email = $('#email').val(); // Corrected email input selection

            // Send AJAX request to OTP send API
            axios.post('/otp-send', {
                    email: email // Pass the email directly
                })
                .then(function(response) {
                    // On successful response
                    if (response.data.status === 'success') {
                        // Show success toast and redirect to the dashboard
                        showSuccessToast(response.status.message);
                        sessionStorage.setItem('email', email);
                        setTimeout(() => {
                            window.location.href = '/verifyOtp'
                        }, 3000);
                    } else {
                        // Show error toast
                        showErrorToast();
                    }
                })
                .catch(function(error) {
                    if (error.response) {
                        // Show error message from server
                        showErrorToast(error.response.data.message);
                    } else {
                        // Fallback error message
                        showErrorToast();
                    }
                });
        });
    </script>
</body>

</html>
