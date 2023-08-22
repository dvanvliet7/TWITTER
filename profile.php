<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: login.php');
	exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="twittericon.png" type="image/x-icon">
  <title>Edit Profile | Twitter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Sweet Alert -->
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <!-- end -->

    <script src="script.js"></script>

  <style>
    /* Custom CSS for additional styling */
    .profile-image {
      width: 110px;
      height: 110px;
      border: 3px solid #fff;
      border-radius: 50%;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }
    button {
        margin-right: 7px;
        margin-bottom: 7px;
    }
  </style>
</head>
<script>
    // fetchProfile();
</script>
<body class="bg-dark">

    <div class="container mt-5 text-white bg-dark">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="d-flex align-items-center">
                    <img class="bg-light profile-image" src="data:image/jpeg;base64,<?php print $_SESSION['pfp'] ?>" alt="profile_picture">
                    <h1>&nbsp;<?php echo $_SESSION['name'] . " " . $_SESSION['surname']; ?></h1>
                </div>
                
                <hr>
                <form id="change-form" enctype="multipart/form-data">
                    <div class="form-group mb-3">
                        <label for="profileImage" class="form-label">Change profile picture</label>
                        <input type="file" class="form-control" id="change-profileImage">
                    </div>
                    <div class="form-group mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="change-name" value="">
                    </div>
                    <div class="form-group mb-3">
                        <label for="surname" class="form-label">Surname</label>
                        <input type="text" class="form-control" id="change-surname">
                    </div>
                    <div class="form-group mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="text" class="form-control" id="change-email-address">
                    </div>
                    <div class="form-group mb-3">
                        <label for="Username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="change-username">
                    </div>
					<div class="form-group mb-3">
                        <label for="Password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="change-password">
						<div class="form-group">
                            <label class="col-form-label">Show Password</label>
                            <input type="checkbox" class="p-2" id="login-checkbox" onclick="showPassword('change-password')">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="Password" class="form-label">Type password again</label>
                        <input type="password" class="form-control" id="match-change-password">
                    </div>
					<hr>
					<button type="button" class="btn btn-outline-light" onclick="discardChange()">Discard Changes</button>
					<button type="button" class="btn btn-primary" onclick="saveChange()">Save Changes</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('change-name').value = "<?php echo $_SESSION['name'] ?>";
        document.getElementById('change-surname').value = "<?php echo $_SESSION['surname'] ?>";
        document.getElementById('change-email-address').value = "<?php echo $_SESSION['email'] ?>";
        document.getElementById('change-username').value = "<?php echo $_SESSION['user'] ?>";
        document.getElementById('change-password').value = "<?php echo $_SESSION['password'] ?>";
        document.getElementById('match-change-password').value = "<?php echo $_SESSION['password'] ?>";
    </script>
</body>
</html>