<?php
session_start();
include('login_person.php');
include('block.php');
include('otp_on_signup.php');

$BlockObj = new Block();
if ($BlockObj->isBlocked() == "Blocked") {
    exit;
}

if (isset($_GET["type"])) {// Fetch posts from the database
    $UserObj = new LogIn();
    $ResultObj = $UserObj->loadPosts();
    echo json_encode($ResultObj);
}

if (isset($_POST["function"])) {
    $UserObj = new LogIn();
    switch($_POST["function"]) {
        case "login":
            $ResultObj = $UserObj->checkLogin($_POST["user"], $_POST["password"], $_POST["g-recaptcha-response"]);
            echo json_encode($ResultObj);
        break;
        case "signup":
            $ResultObj = $UserObj->checkSignUp($_POST["firstName"], $_POST["lastName"], $_POST["email"], $_POST["username"], $_POST["password"]);
            if ($ResultObj == 1) {
                $SignUpObj = new SignUp();
                $CurrentTimeStampStr = date('Y-m-d H:i:s');
                $SignUpObj->insertPerson($_SESSION['Email'], $UserObj->genPin(), 0, $CurrentTimeStampStr);
                echo json_encode($ResultObj);
            } else {
                echo json_encode($ResultObj);
            }
        break;
        case "SIgnUpPin":
            $SignUpObj = new SignUp();
            $ResultObj = $SignUpObj->usePin($_POST['pin']);
            if ($ResultObj == "block") {
                $BlockObj->blockPerson();
                echo json_encode("You are blocked");
            } elseif ($ResultObj == "Successful") {
                $UserObj->completeSignUp($_SESSION['Firstname'], $_SESSION['Lastname'], $_SESSION['Email'], $_SESSION['Username'], $_SESSION['HashedPassword'], $_SESSION['Userpfp']);
                echo json_encode("Successful");
            } else {
                echo json_encode($ResultObj);
            }
        break;
        case "submit":
            $CurrentDateStr = date('Y-m-d H:i:s');
            if (!isset($_FILES["image"])) {
                // error_log("submitAltPost");
                $ResultObj = $UserObj->submitAltPost($_SESSION['id'], $_POST["title"], $CurrentDateStr, $_POST["message"]);
                echo json_encode($ResultObj);
            } else {
                // error_log("submitPost");
                $ResultObj = $UserObj->submitPost($_SESSION['id'], $_POST["title"], $CurrentDateStr, $_POST["message"],$_FILES["image"]);
                echo json_encode($ResultObj);
            }
        break;
        case "getProfilePicture":
            echo json_encode($_SESSION['pfp']);
        break;
        case "changeProfile":
            if (!isset($_FILES['image'])) {
                $ResultObj = $UserObj->changeAltProfile($_SESSION['id'], $_POST["name"], $_POST["surname"], $_POST["email"], $_POST['username'], $_POST['password']);
                echo json_encode($ResultObj);
            } else {
                $ResultObj = $UserObj->changeProfile($_SESSION['id'], $_POST["name"], $_POST["surname"], $_POST["email"], $_POST["username"], $_POST["password"], $_FILES['image']);
                echo json_encode($ResultObj);
            }
        break;
        case "EmailPin":
            $ResultObj = $UserObj->forgotPass($_POST['email']);
            echo json_encode($ResultObj);
        break;
        case "pin":
            $ResultObj = $UserObj->validatePin($_POST['pin']);
            if ($ResultObj == "wrong pin") {
                $BlockObj->blockPerson();
            }
            echo json_encode($ResultObj);
        break;
    }
}