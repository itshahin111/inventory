<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
</head>
<style>
    .form-control {
        margin-bottom: 10px;
        padding: 8px;
        width: 300px;
    }
</style>

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

        <label>Mobile Number</label>
        <input id="phone" placeholder="phone" class="form-control" type="text" />

        <label>Password</label>
        <input id="password" placeholder="User Password" class="form-control" type="password" />

        <button type="button" onclick="onUpdate()">Update</button>
    </form>

    <script>
        // Fetch profile on page load
        getProfile();
        async function getProfile() {
            showLoader();
            let res = await axios.get("/userProfile")
            hideLoader();
            if (res.status === 200 && res.data['status'] === 'success') {
                let data = res.data['data'];
                document.getElementById('email').value = data['email'];
                document.getElementById('firstName').value = data['firstName'];
                document.getElementById('lastName').value = data['lastName'];
                document.getElementById('mobile').value = data['mobile'];
                document.getElementById('password').value = data['password'];
            } else {
                errorToast(res.data['message'])
            }

        }

        async function onUpdate() {


            let firstName = document.getElementById('firstName').value;
            let lastName = document.getElementById('lastName').value;
            let mobile = document.getElementById('mobile').value;
            let password = document.getElementById('password').value;

            if (firstName.length === 0) {
                errorToast('First Name is required')
            } else if (lastName.length === 0) {
                errorToast('Last Name is required')
            } else if (mobile.length === 0) {
                errorToast('Mobile is required')
            } else if (password.length === 0) {
                errorToast('Password is required')
            } else {
                showLoader();
                let res = await axios.post("/update-profile", {
                    firstName: firstName,
                    lastName: lastName,
                    mobile: mobile,
                    password: password
                })
                hideLoader();
                if (res.status === 200 && res.data['status'] === 'success') {
                    successToast(res.data['message']);
                    await getProfile();
                } else {
                    errorToast(res.data['message'])
                }
            }
        }
    </script>

</body>

</html>
