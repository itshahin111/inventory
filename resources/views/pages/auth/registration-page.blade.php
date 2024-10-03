<!-- resources/views/pages/auth/registration-page.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script> <!-- Toastify for toast notifications -->
</head>

<body>
    <h1>Registration Page</h1>
    <form id="registration-form">
        <input id="firstName" type="text" name="firstName" placeholder="First Name" required><br>
        <input id="lastName" type="text" name="lastName" placeholder="Last Name" required><br>
        <input id="email" type="email" name="email" placeholder="Email" required><br>
        <input id="phone" type="text" name="phone" placeholder="Phone" required><br>
        <input id="password" type="password" name="password" placeholder="Password" required><br>
        <button type="button" onclick="onRegistration()">Register</button>
    </form>
    {{-- login page --}}
    <p>
        Already have an account?<br>
        <a href="{{ url('/userLogin') }}">Login here</a>
    </p>

    <script>
        // Success Toast with clickable link
        function showSuccessToast(message) {
            Toastify({
                text: message,
                duration: 1000,
                close: true,
                gravity: "top", // top or bottom
                position: "right", // left, center or right
                backgroundColor: "green",
                stopOnFocus: true,
            }).showToast();
            // auto redirect to dashboard
            setTimeout(function() {
                window.location.href = '/userLogin'; // Redirect to the login page
            }, 800);
        }

        // Error Toast
        function showErrorToast(message) {
            Toastify({
                text: message,
                duration: 1000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: "red",
                stopOnFocus: true
            }).showToast();
        }

        async function onRegistration() {
            // Retrieving input values
            let email = document.getElementById('email').value;
            let firstName = document.getElementById('firstName').value;
            let lastName = document.getElementById('lastName').value;
            let phone = document.getElementById('phone').value;
            let password = document.getElementById('password').value;

            // Validation for empty fields
            if (!email) {
                showErrorToast('Email is required');
            } else if (!firstName) {
                showErrorToast('First Name is required');
            } else if (!lastName) {
                showErrorToast('Last Name is required');
            } else if (!phone) {
                showErrorToast('Phone is required');
            } else if (!password) {
                showErrorToast('Password is required');
            } else {
                try {
                    // Making POST request to server using axios
                    let res = await axios.post('/register', {
                        email: email,
                        firstName: firstName,
                        lastName: lastName,
                        phone: phone,
                        password: password
                    });

                    // If registration is successful
                    if (res.status === 200 && res.data.status) {
                        showSuccessToast('Registration Successful! Click to go to login');
                    } else {
                        showErrorToast(res.data.message);
                    }
                } catch (error) {
                    // Error handling in case of server or validation errors
                    if (error.response) {
                        showErrorToast(error.response.data.message); // Show server error
                    } else {
                        showErrorToast('Something went wrong, please try again.');
                    }
                }
            }
        }
    </script>
</body>

</html>
