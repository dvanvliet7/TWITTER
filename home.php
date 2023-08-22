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
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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

    <title>Home Page | Twitter</title>

    <style>
        .profile-image {
            width: 50px;
            height: 50px;
            border: 3px solid #fff;
            border-radius: 50%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
        button {
            margin: 7px;
        }
        .fa-twitter {
            font-size: 30px;
        }
        .material-symbols-outlined {
            vertical-align: bottom;
            font-size: 30px;
        }
        .btn-circle-icon {
            border-radius: 50%;
            width: 54px;
            height: 54px;
            font-size: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        :root {
            --dark-color: #212529;
        }
        .bg-secondary-dark {
            background-color: var(--dark-color);
            color: #ffffff;
        }
        .center-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: auto;
        }
    </style>
</head>
<body>
    <script>
        loadAllPosts();
    </script>
<!-- Left Side Panel -->
    <div class="container-fluid bg-dark">
        <div class="row justify-content-md-center">

            <div class="col-auto py-3 col-md-3 col-xl-2 px-sm-2 text-light bg-dark border-end">
                <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100 sticky-xl-top">
                    <a href="#" class="h-25 d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                        <span class="fs-5 d-none d-lg-block p-3"><i class="fab fa-twitter"></i></span>
                    </a>
                    
                    <h2 class="p-2 text-white">Menu</h2>

                    <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start text-light" id="menu">
                        <li class="nav-item">
                            <a href="#" class="fs-4 nav-link align-middle px-0">
                                <span class="material-symbols-outlined">home</span> <span class="ms-1 d-none d-sm-inline">Home</span>
                            </a>
                        </li>
                        <li>
                            <a href="profile.php" class="fs-4 nav-link px-0 align-middle">
                                <i class="material-symbols-outlined">person</i> <span class="ms-1 d-none d-sm-inline">Profile</span></a>
                        </li>
                        <li>
                            <a href="#" onclick="signOutSwal()" class="fs-4 nav-link px-0 align-middle">
                                <i class="material-symbols-outlined">edit</i> <span class="ms-1 d-none d-sm-inline">Sign out</span></a>
                        </li>
                    </ul>  
                </div>
            </div>

<!-- Center Panel -->
            <div class="col col-auto">
                <div class="sticky-top bg-secondary-dark">
                    <button class="btn btn-circle-icon btn-primary float-end py-2" id="btn-posts" data-bs-toggle="modal" data-bs-target="#postModal"><i class="material-symbols-outlined">add</i></button>
                    <h2 class="p-3 text-light border-bottom">Posts</h2>
                </div>
                <div id="content"></div>
            </div>

<!-- Right Side Panel -->
            <div class="d-flex flex-column align-items-stretch flex-shrink-0 text-light bg-dark border-start" style="width: 200px;">

                <div class="fs-4 p-4 pb-4 sticky-top">
                    <a href="edit_profile.php" class="d-flex align-items-center text-outline-primary text-decoration-none" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                        <img id="img-loggedin" src="data:image/jpeg;base64,<?php print $_SESSION['pfp'] ?>" alt="profile_picture" width="50" height="50" class="rounded-circle profile-image">
                        <span class="d-none d-sm-inline mx-1">&nbsp;You</span>
                    </a>
                </div>
                <!-- <button class="btn btn-primary" onclick="fetchProfile()">Click this</button> -->

            </div>

            <!-- Post form -->
            <div class="modal fade" id="postModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content text-white bg-primary">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel"><i class="material-symbols-outlined">ios_share</i> New post</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="uploadform" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="recipient-name" class="col-form-label">Post:</label>
                                    <div class="fs-3 input-group">
                                        <input type="file" class="fs-5 form-control" name="image" id="post-img">
                                        <label class="input-group-text" for="post-img"><i class="material-symbols-outlined">folder_open</i></label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="post-msg" class="col-form-label">Title:</label>
                                    <input type="text" class="form-control" id="title-input">
                                </div>
                                <div class="mb-3">
                                    <label for="post-msg" class="col-form-label">Message:</label>
                                    <textarea class="form-control" id="msg-input" name="message" rows="5"></textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Close</button>
                                    <button type="button" name="submit" class="btn btn-light" onclick="sendPost()">Send post</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end -->

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // fetchProfilePicture();
    </script>
</body>
</html>