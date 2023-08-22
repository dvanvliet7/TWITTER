<?php
require_once 'config.php';
// session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="twittericon.png" type="image/x-icon">
    <title>Log in | Twitter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Sweet Alert -->
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <!-- end -->

    <!--reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <!-- end -->

    <script src="script.js"></script>

    <!-- css -->
    <style>
        .material-symbols-outlined {
            text-align: center;
            vertical-align: middle;
        }
        button {
            margin: 2%;
        }
        .modal-backdrop {
            background-color: transparent;
        }
    </style>

</head>
<body class="bg-info">
    <script>
        showModal();
    </script>

    <!-- Login form -->
    <div id="myModal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-white bg-dark">
                <div class="modal-header">
                    <h4 class="modal-title p-1" id="headermodal"><i class="fab fa-twitter"></i>&nbsp;Login</h4>
                    <div class="clearfix" id="mySpinner" hidden>
                        <div class="spinner-border float-end" role="status">
                          <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-body" id="bodymodal">
                    <div class="form-group">
                        <label for="user-username" class="col-form-label">Username or e-mail</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1"><i class="material-symbols-outlined">person</i></span>
                            <input type="text" class="form-control" id="login-user">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="user-password" class="col-form-label">Password</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1"><i class="material-symbols-outlined">password</i></span>
                            <input type="password" class="form-control" id="login-password" name="pass">
                        </div>
                        <div class="col">
                            <div class="collapse" id="pass-collapse"  aria-expanded="false">
                              <div class="card card-body text-white bg-danger">
                              Invalid username or password.
                              </div>
                            </div>
                        </div>
                        <div class="container">
                            <label class="col-form-label">Show Password</label>
                            <input type="checkbox" class="p-2" id="login-checkbox" onclick="showPassword('login-password')">
                        </div>
                    </div>
                    <div class="form-group d-flex justify-content-center">
                        <div class="g-recaptcha" data-sitekey="6LcmMJUnAAAAAEllZLmgV77ZkKWTO0DkbHRtAjZv"></div>
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary rounded-pill" id="btn-login" onclick="logIn()">Login</button>
                    </div>
                    <div class="d-grid p-2" >
                        <a class="" href="#" onclick="forgotPass()">forgot password?</a>
                    </div>
                </div>
                <div class="modal-footer" id="modalFoot">Don't have an account?&nbsp;<a class="my-class" id="signup-link" onclick="signUp()">Sign up</a></div>
            </div>
        </div>
    </div>
    <!-- end -->

    <!-- Sign up form -->
    <div id="myModal2" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-white bg-dark">
                <div class="modal-header">
                    <h4 class="modal-title p-1" id="headermodal2"><i class="fab fa-twitter"></i>&nbsp;Sign up</h4>
                    <div class="clearfix" id="mySpinner2" hidden>
                        <div class="spinner-border float-end" role="status">
                          <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-body" id="bodymodal">
                    <div class="form-group">
                        <label for="fname" class="col-form-label">First name</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon2"><i class="material-symbols-outlined">person</i></span>
                            <input type="text" class="form-control" id="signup-firstName">
                        </div>
                        <div class="col">
                            <div class="collapse" id="fname-collapse">
                              <div class="card card-body text-white bg-danger">
                                Invalid name.
                              </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Last name</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon2"><i class="material-symbols-outlined">person</i></span>
                            <input type="text" class="form-control" id="signup-lastName">
                        </div>
                        <div class="col">
                            <div class="collapse" id="lname-collapse">
                                <div class="card card-body text-white bg-danger">
                                    Invalid last name.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">E-mail address</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon2"><i class="material-symbols-outlined">alternate_email</i></span>
                            <input type="email" class="form-control" id="signup-mail">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Username</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon2"><i class="material-symbols-outlined">remember_me</i></span>
                            <input type="text" class="form-control" id="signup-username">
                        </div>
                        <div class="col">
                            <div class="collapse" id="username-collapse">
                              <div class="card card-body text-white bg-danger">
                                There is a problem with the username or email.
                              </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="user-password" class="col-form-label">Password</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon2"><i class="material-symbols-outlined">password</i></span>
                            <input type="password" class="form-control" id="signup-password" name="pass">
                        </div>
                        <div class="col">
                            <div class="collapse" id="pass2-collapse"  aria-expanded="false">
                                <div class="card card-body text-white bg-danger">
                                    Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.
                                </div>
                            </div>
                        </div>
                        <div class="container">
                            <label class="col-form-label">Show Password</label>
                            <input type="checkbox" class="p-2" id="signup-checkbox" onclick="showPassword('signup-password')">
                        </div>
                    </div>
                    <div class="form-group">
                    <label class="col-form-label">Type password again</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon2"><i class="material-symbols-outlined">password</i></span>
                            <input type="password" class="form-control" id="retype-password">
                        </div>
                    </div>
                    <!-- reCAPTCHA -->
                    <!-- <div class="form-group d-flex justify-content-center">
                        <div class="g-recaptcha" data-sitekey="6LcmMJUnAAAAAEllZLmgV77ZkKWTO0DkbHRtAjZv"></div>
                    </div> -->
                    <!-- Sign up -->
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary rounded-pill" id="btn-signup" onclick="signUpForm()">Sign up</button>
                    </div>
                </div>
                <div class="modal-footer" id="modalFoot">
                    <a href="" class="btn btn-outline-light" id="btn-back">back</a>
                </div>
            </div>
        </div>
    </div>
    <!-- end -->

    <!-- listen to enter-key using javascript -->
    <script>
        // login
        $("#login-user").keypress(function (event) {
            if (event.keyCode === 13) {
                $("#btn-login").click();
            }
        });
        $("#login-password").keypress(function (event) {
            if (event.keyCode === 13) {
                $("#btn-login").click();
            }
        });
        $("#login-checkbox").keypress(function (event) {
            if (event.keyCode === 13) {
                $("#btn-login").click();
            }
        });

        // sign up
        $("#signup-firstName").keypress(function (event) {
            if (event.keyCode === 13) {
                $("#btn-signup").click();
            }
        });
        $("#signup-lastName").keypress(function (event) {
            if (event.keyCode === 13) {
                $("#btn-signup").click();
            }
        });
        $("#signup-mail").keypress(function (event) {
            if (event.keyCode === 13) {
                $("#btn-signup").click();
            }
        });
        $("#signup-username").keypress(function (event) {
            if (event.keyCode === 13) {
                $("#btn-signup").click();
            }
        });
        $("#signup-password").keypress(function (event) {
            if (event.keyCode === 13) {
                $("#btn-signup").click();
            }
        });
        $("#signup-checkbox").keypress(function (event) {
            if (event.keyCode === 13) {
                $("#btn-signup").click();
            }
        });
    </script>
    <!-- end -->

</body>
</html>