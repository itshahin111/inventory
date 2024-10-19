<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h4 {
            color: #333;
            margin-bottom: 20px;
        }

        .form-control {
            margin-bottom: 10px;
            padding: 8px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            width: 300px;
            padding: 10px;
            margin-top: 10px;
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
    <h4>User Profile</h4>

    <form id="profile-form">
        @csrf
        <label>Email Address</label>
        <input readonly id="email" placeholder="User Email" class="form-control" type="email" />

        <label>First Name</label>
        <input id="firstName" placeholder="First Name" class="form-control" type="text" />

        <label>Last Name</label>
        <input id="lastName" placeholder="Last Name" class="form-control" type="text" />

        <label>Phone Number</label>
        <input id="phone" placeholder="Phone" class="form-control" type="text" />

        <label>Password</label>
        <input id="password" placeholder="Enter new password" class="form-control" type="password" />

        <button type="button" onclick="onUpdate()">Update</button>
    </form>

    <script>
        // Success Toast
        function showSuccessToast(message) {
            Toastify({
                text: message,
                duration: 1000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: "green",
                stopOnFocus: true
            }).showToast();

            // Auto redirect after success
            setTimeout(function() {
                window.location.href = '/dashboard';
            }, 1000);
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

        // Fetch profile data on page load
        function getProfile() {
            axios.get("/userProfile")
                .then((response) => {
                    if (response.status === 200 && response.data.status === true) {
                        let data = response.data.data;
                        document.getElementById('email').value = data.email;
                        document.getElementById('firstName').value = data.firstName;
                        document.getElementById('lastName').value = data.lastName;
                        document.getElementById('phone').value = data.phone;
                    } else {
                        showErrorToast(response.data.message);
                    }
                })
                .catch(() => {
                    showErrorToast('Failed to load profile');
                });
        }

        // Update profile via AJAX request
        function onUpdate() {
            let firstName = document.getElementById('firstName').value;
            let lastName = document.getElementById('lastName').value;
            let phone = document.getElementById('phone').value;
            let password = document.getElementById('password').value;

            if (!firstName || !lastName || !phone || !password) {
                showErrorToast('All fields are required');
                return;
            }

            axios.post("/update-profile", {
                    firstName: firstName,
                    lastName: lastName,
                    phone: phone,
                    password: password
                })
                .then((response) => {
                    if (response.status === 200 && response.data.status === true) {
                        showSuccessToast(response.data.message);
                        getProfile(); // Reload profile data after update
                    } else {
                        showErrorToast(response.data.message);
                    }
                })
                .catch(() => {
                    showErrorToast('Failed to update profile');
                });
        }

        // Call getProfile on page load
        getProfile();
    </script>
</body>

</html>
