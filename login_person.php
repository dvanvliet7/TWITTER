<?php
// Security: 
// 1) Strong password  ✅
// 2) Limit login attempts ✅ --not completely functional
// 3) use reCAPTCHA ✅ --not completely functional
// 4) Multi-factor authentication ❌ --N/A
// 5) firewall ❌ 

// Functionality:
// 1) Login and validate details ✅
// 2) Signup and validate details ✅
// 3) Successfully load all posts from DB ✅
// 4) Successfully post something as user ❌
// 5) Edit profile ❌ --busy

class LogIn {
    public $HASHOPTIONARR = array('cost'=>11);
    public string $FirstNameStr;
    public string $LastNameStr;
    public string $UserNameStr;
    public string $EmailAddressStr;
    public string $PasswordStr;

    public string $EnteredPassStr;

    private $ConnectionObj;

    /**
     * Checks if query to DB was success/error
     */
    function checkQuery($QueryStr) { 
        if ($this->ConnectionObj->query($QueryStr) === TRUE) {
            echo "Success<br>";
            } else {
            echo "Error: " . "<br>" . $this->ConnectionObj->error;
            }
    }

    /**
     * Create connection to DB 'php_exercise'
     */
    function createConn() {
        $this->ConnectionObj = new mysqli("localhost", "root", "", "stratusolve_php_exercise");
        if ($this->ConnectionObj->connect_error) {
            die("Connection failed: " . $this->ConnectionObj->connect_error);
        }
    }

    /** 
     * End the connection to DB 'php_exercise'
     */
    function endConn() {
        $this->ConnectionObj->close();
    }



    /////////////////// validation //////////////////
    function checkEmailExists($EmailStr) {
        $IDInt = 0;
        $ReturnStr = "";
        $this->createConn();

        // prepare and bind
        $PrepObj = $this->ConnectionObj->prepare("SELECT ID FROM user WHERE EmailAddress=?");
        $PrepObj->bind_param("s", $EmailStr);
        // execute
        $PrepObj->execute();
        // Store the result so we can check if the account exists in the database.
        $PrepObj->store_result();

        if ($PrepObj->num_rows > 0) {
            $PrepObj->bind_result($IDInt);
            $PrepObj->fetch();
            $ReturnStr = "userErr";
        } else {
            // Incorrect username
            $ReturnStr = "Success";
        }

        $PrepObj->close();
        $this->endConn();

        return $ReturnStr;
    }

    function checkUserExists($UserStr) {
        $IDInt = 0;
        $ReturnStr = "";
        $this->createConn();

        // prepare and bind
        $PrepObj = $this->ConnectionObj->prepare("SELECT ID FROM user WHERE User=?");
        $PrepObj->bind_param("s", $UserStr);
        // execute
        $PrepObj->execute();
        // Store the result so we can check if the account exists in the database.
        $PrepObj->store_result();

        if ($PrepObj->num_rows > 0) {
            $PrepObj->bind_result($IDInt);
            $PrepObj->fetch();
            $ReturnStr = "userErr";
        } else {
            // Incorrect username
            $ReturnStr = "Success";
        }

        $this->endConn();

        return $ReturnStr;
    }

    function validateUsername($UserStr) {
        if ($UserStr == " " || $UserStr == "") {
            return true;
        } else {
            $UserStringArr = str_split($UserStr);
            if (count($UserStringArr) > 100) {
                return true;
            } else {
                return false;
            }
        }
    }

    function testPassword($PasswordStr) {
        // Validate password strength
        $uppercase = preg_match('@[A-Z]@', $PasswordStr);
        $lowercase = preg_match('@[a-z]@', $PasswordStr);
        $number    = preg_match('@[0-9]@', $PasswordStr);
        $specialChars = preg_match('/[!@#$%^&*()_+{}\[\]:;<>,.?~]/', $PasswordStr);

        if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($PasswordStr) < 8) {
            return true;
        } else {
            return false;
        }
    }

    function validateEmail($EmailStr) {
        if (filter_var($EmailStr, FILTER_VALIDATE_EMAIL)) {
            $atPos = mb_strpos($EmailStr, '@');
            // Select the domain
            $DomainStr = mb_substr($EmailStr, $atPos + 1);
            if (checkdnsrr($DomainStr . '.', 'MX')) {
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }



    /////////////////// mail ///////////////////
    function emailUser($ToMailStr, $NameStr, $PinStr) {
        $BodyStr = '<br><br>Your OTP is: ' . $PinStr . '<br>Please do not share your OTP.';

        $SubjectStr = "Twitter Sign-in";

        $DataArr = array(
            "personalizations" => array(
                array(
                    "to" => array(
                        array(
                            "email" => $ToMailStr,
                            "name" => $NameStr,
                        )
                    )
                )
                    ),
                    "from" => array(
                        "email" => "derek.vanvliet@stratusolve.com"
                    ),
                    "subject" => $SubjectStr,
                    "content" => array(
                        array(
                            "type" => "text/html",
                            "value" => $BodyStr
                        )
                    )
        );

        $HeadersArr = array('Authorization: Bearer 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', 'Content-Type: application/json');

        $CurlHandeler = curl_init();
        curl_setopt($CurlHandeler, CURLOPT_URL, "https://api.sendgrid.com/v3/mail/send");
        curl_setopt($CurlHandeler, CURLOPT_POST, 1);
        curl_setopt($CurlHandeler, CURLOPT_POSTFIELDS, json_encode($DataArr));
        curl_setopt($CurlHandeler, CURLOPT_HTTPHEADER, $HeadersArr);
        curl_setopt($CurlHandeler, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($CurlHandeler, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($CurlHandeler);
        curl_close($CurlHandeler);
    }



    ////////////////// check password attempt in DB ///////////////
    function isDayPassed($TimeStampStr) { // check if day has passed
        $BlockedObj = new DateTime($TimeStampStr);
        $CurrentTimeStampObj = new DateTime(date('Y-m-d H:i:s'));
        $DiffObj = $CurrentTimeStampObj->diff($BlockedObj);

        return $DiffObj->days;
    }

    function genPin() { // random 5 digit pin
        $CodeArr = array();
        for ($Counter = 0; $Counter < 5; $Counter++) {
            $RandNumStr = strval(rand(0, 11));
            array_push($CodeArr, $RandNumStr);
        }
        $CodeStr = $CodeArr[0] . $CodeArr[1] . $CodeArr[2]. $CodeArr[3] . $CodeArr[4];
        return $CodeStr;
    }

    function genCodeLogin($IdInt) { // generate a code for the user who forgot their password
        $CodeStr = $this->genPin();
        $HashedCodeStr = password_hash($CodeStr, PASSWORD_BCRYPT);
        
        // let's insert a (unique) code for user to sign in
        $CurrentTimeStampStr = date('Y-m-d H:i:s');

        $SelectUserStr = "SELECT * FROM attempt WHERE User=$IdInt";
        $ResultObj = $this->ConnectionObj->query($SelectUserStr);
        if ($ResultObj->num_rows > 0) {
            $UpdateUserStr = "UPDATE attempt SET Pin='".$HashedCodeStr."', disabled=1, BlockTimeStamp='".$CurrentTimeStampStr."' WHERE User=$IdInt";
            $ResultObj = $this->ConnectionObj->query($UpdateUserStr);
        } else {
            $InsertUserStr = "INSERT INTO attempt (User, LoginAttempt, Pin, disabled, BlockTimeStamp) VALUES ($IdInt, 0, '".$HashedCodeStr."', 1, '".$CurrentTimeStampStr."')";
            $ResultObj = $this->ConnectionObj->query($InsertUserStr);
        }

        return $CodeStr;
    }

    function incrementAttempt($IdInt) {
        //if password was a fail then increment to DB
        $this->ConnectionObj->query("UPDATE attempt SET LoginAttempt=LoginAttempt+1 WHERE User=$IdInt");
    }

    function resetAttempt($IdInt) {
        $this->ConnectionObj->query("UPDATE attempt SET LoginAttempt=0, disabled=0 WHERE User=$IdInt");
    }

    function checkAttempts($UserIdInt) {
        //checks if attempting again is valid
        $ReturnStr = "";

        $ResultObj = $this->ConnectionObj->query("SELECT LoginAttempt, disabled FROM attempt WHERE User=$UserIdInt");
        $RowArr = $ResultObj->fetch_assoc();
        if ($ResultObj->num_rows > 0) {
            // comparison
            if ($RowArr['LoginAttempt'] < 5 && intval($RowArr['disabled']) == 0) {
                $ReturnStr = "valid attempt";
            } else {
                $ReturnStr = "invalid attempt";
            }
        } else {
            // create row
            $InsertAttemptStr = "INSERT INTO attempt (User, LoginAttempt, Pin, disabled) VALUES ($UserIdInt, 0, 'none', 0)";
            $ResultObj = $this->ConnectionObj->query($InsertAttemptStr);
            $ReturnStr = "first attempt";
        }

        return $ReturnStr;
    }

    function validatePin($PinStr) { // validate OTP when user types it onto swal
        if (!isset($_SESSION['temp_id'])) {
            return "No id exists";
        }
        $IdInt = $_SESSION['temp_id'];

        $LoginAttemptsInt = 0;
        $HashedPinStr = "";
        $DisabledInt = 0;

        $this->createConn();

        $PrepObj = $this->ConnectionObj->prepare("SELECT LoginAttempt, Pin, disabled FROM attempt WHERE User=?");
        $PrepObj->bind_param("i", $IdInt);
        $PrepObj->execute();
        $PrepObj->store_result();
        
        if ($PrepObj->num_rows > 0) {
            //found id
            $PrepObj->bind_result($LoginAttemptsInt, $HashedPinStr, $DisabledInt); // require hashed pin from DB!
            $PrepObj->fetch();
            error_log($PinStr . "|" . $HashedPinStr);
            if (password_verify($PinStr, $HashedPinStr)) {
                // correct Pin 
                $this->resetAttempt($IdInt);
                $SelectUserStr = "SELECT * FROM User WHERE ID=$IdInt";
                $ResultObj = $this->ConnectionObj->query($SelectUserStr);
                $RowArr = $ResultObj->fetch_assoc();
                $this->createSession($RowArr['User'], $RowArr['EmailAddress'], $RowArr['FirstName'], $RowArr['LastName'], $RowArr['ID'], $RowArr['Password'], $RowArr['Userpfp']);
                $ReturnStr = "Successful";
            } else {
                // incorrect Pin
                $ResultObj = $this->ConnectionObj->query("UPDATE attempt SET disabled=2 WHERE User=$IdInt");
                $ReturnStr = "wrong pin";
            }
        } else {
            // no id found
            $ReturnStr = "no id";
        }

        $PrepObj->close();
        $this->endConn();

        return $ReturnStr;
    }



    /////////////////// login ///////////////////
    function createSession($UserStr, $EmailAddStr, $FNameStr, $LNameStr, $IDInt, $PassStr, $MyProfilePictureStr) { // creating a session
        session_regenerate_id(true);
        $_SESSION['loggedin'] = true;
        $_SESSION['user'] = $UserStr;
        $_SESSION['email'] = $EmailAddStr;
        $_SESSION['name'] = $FNameStr;
        $_SESSION['surname'] = $LNameStr;
        $_SESSION['id'] = $IDInt;
        $_SESSION['password'] = $PassStr;
        $_SESSION['pfp'] = $MyProfilePictureStr;
    }

    function saveUser($UserStr) {
        $IDInt = 0;
        $FNameStr = "";
        $LNameStr = "";
        $EmailAddStr = "";
        $MyPasswordStr = "";
        $ReturnStr = "";
        $MyProfilePictureStr = "";

        $this->createConn();

        // prepare and bind
        $PrepObj = $this->ConnectionObj->prepare("SELECT ID, FirstName, LastName, EmailAddress, Password, Userpfp FROM user WHERE User=?");
        $PrepObj->bind_param("s", $UserStr);
        $PrepObj->execute();
        // Store the result so we can check if the account exists in the database.
        $PrepObj->store_result();

        if ($PrepObj->num_rows > 0) {
            // Correct username
            $PrepObj->bind_result($IDInt, $FNameStr, $LNameStr, $EmailAddStr, $MyPasswordStr, $MyProfilePictureStr); // require hashed password from DB!
            $PrepObj->fetch();
            //check available attempts
            if ($this->checkAttempts($IDInt) != "invalid attempt") {
                // Now we verify the password.
                if (password_verify($this->EnteredPassStr, $MyPasswordStr)) {
                    // let's Create a session
                    $this->createSession($UserStr, $EmailAddStr, $FNameStr, $LNameStr, $IDInt, $this->EnteredPassStr, base64_encode($MyProfilePictureStr));
                    // let's reset the login attempts
                    $this->resetAttempt($IDInt);

                    $ReturnStr = "Successful";
                } else {
                    // Incorrect password (increment attempts)
                    $this->incrementAttempt($IDInt);
                    $ReturnStr = "passErr";
                }
            } else {
                // no more password attempts
                $ReturnStr = "no more attempts";
            }
        } else {
            // Incorrect username
            $ReturnStr = "userErr";
        }

        $PrepObj->close(); // close prepared statement
        $this->endConn(); // close DB connection

        return $ReturnStr;
    }

    function saveEmail($EmailStr) {
        $IDInt = 0;
        $FNameStr = "";
        $LNameStr = "";
        $UserStr = "";
        $MyPasswordStr = "";
        $ReturnStr = "";
        $MyProfilePictureStr = "";
        $this->createConn();

        // prepare and bind
        $PrepObj = $this->ConnectionObj->prepare("SELECT ID, FirstName, LastName, User, Password, Userpfp FROM user WHERE EmailAddress=?");
        $PrepObj->bind_param("s", $EmailStr);
        // execute
        $PrepObj->execute();
        // Store the result so we can check if the account exists in the database.
        $PrepObj->store_result();

        
        if ($PrepObj->num_rows > 0) {
            $PrepObj->bind_result($IDInt, $FNameStr, $LNameStr, $UserStr, $MyPasswordStr, $MyProfilePictureStr);
            $PrepObj->fetch();
            // Account exists, now we verify.
            if ($this->checkAttempts($IDInt) != "invald attempt") {
                if (password_verify($this->EnteredPassStr, $MyPasswordStr)) {
                    // Verification success! User has logged-in!
                    // Create sessions, so we know the user is logged in, they basically act like cookies but remember the data on the server.
                    $this->createSession($UserStr, $EmailStr, $FNameStr, $LNameStr, $IDInt, $this->EnteredPassStr, $MyProfilePictureStr);
                    // let's reset the login attempts
                    $this->resetAttempt($IDInt);
                    // Success
                    $ReturnStr = "Successful";
                } else {
                    // Incorrect password
                    $this->incrementAttempt($IDInt);
                    $ReturnStr = "passErr";
                }
            } else {
                $ReturnStr = "no more attempts";
            }
        } else {
            // Incorrect username
            $ReturnStr = "userErr";
        }

        $PrepObj->close();
        $this->endConn();

        return $ReturnStr;
    }

    function checkLogin($UsernameStr, $PassStr, $RecaptchaResponseStr) { // main
        $RecaptchaSecretKeyStr = '6LcmMJUnAAAAAAMhUMLluavMS0CMcl78UxQrDq7K';

        $ResponseStr = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$RecaptchaSecretKeyStr&response=$RecaptchaResponseStr");
        $ResponseKeysArr = json_decode($ResponseStr, true);

        if (intval($ResponseKeysArr["success"]) !== 1) {
            // reCAPTCHA verification failed
            return "reCAPTCHA verification failed. Try again.";
        } else {
            // Process the form data
            $UserStr = trim($UsernameStr);
            $this->EnteredPassStr = trim($PassStr);

            $ResultStr = $this->saveUser($UserStr);
            if ($ResultStr != "userErr") {
                return $ResultStr;
            } else {
                $ResultStr = $this->saveEmail($UserStr);
                if ($ResultStr != "userErr") {
                    return $ResultStr;
                } else {
                    return "userErr";
                }
            }
        }
    }

    function forgotPass($EmailStr) { // checks the user's entered email address
        if (!$this->validateEmail($EmailStr)) {
            if ($this->checkEmailExists($EmailStr) != "Success") {
                // This email exists
                $this->createConn();
                $ResultObj = $this->ConnectionObj->query("SELECT ID, User FROM user WHERE EmailAddress='".$EmailStr."'");
                $RowArr = $ResultObj->fetch_assoc();
                $IdInt = $RowArr['ID'];
                // set a temporary session id
                $_SESSION['temp_id'] = $IdInt;
                // return that a pin is sent to the email address
                $this->emailUser($EmailStr, $RowArr['User'], $this->genCodeLogin($IdInt));
                $this->endConn();
                return "an email has been sent to: " . $EmailStr;
            } else {
                // This email does not exist
                // We want to conceal if email is in the DB or not
                return "an email has been sent to: " . $EmailStr;
            }
        } else {
            return "Please enter a valid email address";
        }
    }



    ///////////////// sign up /////////////////////
    function correctName($NameStr) {
        $NameStr = strtolower($NameStr);
        $NameStr = ucfirst($NameStr);
        return $NameStr;
    }

    function checkSignUpFields() {
        if (!preg_match("/^[a-zA-Z-' ]*$/", $this->FirstNameStr) || $this->FirstNameStr == "" || $this->FirstNameStr == " ") {
            return "nameErr";
        } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $this->LastNameStr) || $this->LastNameStr == "" || $this->LastNameStr == " ") {
            return "surnameErr";
        } elseif ($this->validateEmail($this->EmailAddressStr)) {
            return "emailErr";
        } elseif ($this->checkEmailExists($this->EmailAddressStr) != "Success") {
            return "This E-mail is already in use.";
        } elseif ($this->checkUserExists($this->UserNameStr) != "Success") {
            return "This username already exists.";
        } elseif ($this->validateUsername($this->UserNameStr)) {
            return "userErr";
        } elseif ($this->testPassword($this->PasswordStr)) {
            return "passErr";
        } else {
            $this->FirstNameStr = $this->correctName($this->FirstNameStr);
            $this->LastNameStr = $this->correctName($this->LastNameStr);
            return "none";
        }
    }

    function checkSignUp($FNameStr, $LNameStr, $EmailStr, $UserStr, $PassStr) { // main
        $this->FirstNameStr = trim($FNameStr);
        $this->LastNameStr = trim($LNameStr);
        $this->EmailAddressStr = strtolower($EmailStr);
        $this->UserNameStr = trim($UserStr);
        $this->PasswordStr = trim($PassStr);

        $ResultStr = $this->checkSignUpFields();

        if ($ResultStr != "none") {
            return $ResultStr;
        } else {
            // get placeholder pfp
            // $ImagePathStr = 'C:\xampp\htdocs\stratuSolve_training\Final';
            // $ImageFilesArr = glob($ImagePathStr . '*.png');
            $ImageDataStr = file_get_contents('C:\xampp\htdocs\stratuSolve_training\Final\personpfp.png');
            // $OptionsArr = array('cost' => 11);
            $HashedPasswordStr = password_hash($this->PasswordStr, PASSWORD_BCRYPT, $this->HASHOPTIONARR);
            
            $_SESSION['Firstname'] = $this->FirstNameStr;
            $_SESSION['Lastname'] = $this->LastNameStr;
            $_SESSION['Email'] = $this->EmailAddressStr;
            $_SESSION['Username'] = $this->UserNameStr;
            $_SESSION['HashedPassword'] = $HashedPasswordStr;
            $_SESSION['Userpfp'] = $ImageDataStr;

            $_SESSION['temp_session'] = true;
            
            return 1;
        }

        // $RecaptchaSecretKeyStr = '6LcmMJUnAAAAAAMhUMLluavMS0CMcl78UxQrDq7K';

        // $ResponseStr = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$RecaptchaSecretKeyStr&response=$RecaptchaResponseStr");
        // $ResponseKeysArr = json_decode($ResponseStr, true);

        // if (intval($ResponseKeysArr['success'] !== 1)) {
        //     // reCAPTCHA verification failed
        //     return "reCAPTCHA verification failed. Try again.";
        // } else {
        //     $this->FirstNameStr = trim($FNameStr);
        //     $this->LastNameStr = trim($LNameStr);
        //     $this->EmailAddressStr = strtolower($EmailStr);
        //     $this->UserNameStr = trim($UserStr);
        //     $this->PasswordStr = trim($PassStr);

        //     $ResultStr = $this->checkSignUpFields();

        //     if ($ResultStr != "none") {
        //         return $ResultStr;
        //     } else {
        //         // get placeholder pfp
        //         // $ImagePathStr = 'C:\xampp\htdocs\stratuSolve_training\Final';
        //         // $ImageFilesArr = glob($ImagePathStr . '*.png');
        //         $ImageDataStr = file_get_contents('C:\xampp\htdocs\stratuSolve_training\Final\person_google.png');
        //         // $OptionsArr = array('cost' => 11);
        //         $HashedPasswordStr = password_hash($this->PasswordStr, PASSWORD_BCRYPT, $this->HASHOPTIONARR);
                
        //         $_SESSION['Firstname'] = $this->FirstNameStr;
        //         $_SESSION['Lastname'] = $this->LastNameStr;
        //         $_SESSION['Email'] = $this->EmailAddressStr;
        //         $_SESSION['Username'] = $this->UserNameStr;
        //         $_SESSION['HashedPassword'] = $HashedPasswordStr;
        //         $_SESSION['Userpfp'] = $ImageDataStr;

        //         $_SESSION['temp_session'] = true;
                
        //         return 1;
        //     }
        // }
    }

    function completeSignUp($FNameStr, $LNameStr, $EmailStr, $UserStr, $PassStr, $UserpfpStr) {
        $this->createConn();

        // insert
        $SignUpStr = "INSERT INTO user (FirstName, LastName, EmailAddress, User, Password, Userpfp) VALUES (?, ?, ?, ?, ?, ?)";
        $PrepObj = $this->ConnectionObj->prepare($SignUpStr);
        $PrepObj->bind_param("ssssss", $FNameStr, $LNameStr, $EmailStr, $UserStr, $PassStr, $UserpfpStr);
        $PrepObj->execute();
        $PrepObj->close();

        $this->endConn();

        session_unset();
    }



    /////////////////////// Posts //////////////////
    function loadPosts() { // load all the posts
        $this->createConn();

        $SelectAllStr = "SELECT * FROM post LEFT JOIN user ON post.UserId=user.ID ORDER BY PostTimeStamp DESC";
        $ResultObj = $this->ConnectionObj->query($SelectAllStr);

        $this->endConn();

        // Check if any posts are found
        if ($ResultObj->num_rows > 0) {
            $ReturnArr = array();

            while ($RowArr = $ResultObj->fetch_assoc()) {
                $PostsArr = array(); 
                // preparing to use image
                $ImageData = base64_encode($RowArr["PostMedia"]);
                // Reformat date
                $PostTimeStampStr = $RowArr["PostTimeStamp"]; // saving PostTimeStamp from SQL to variable
                $DateInSec = strtotime($PostTimeStampStr); // convert date to seconds
                $DateStr = date('D M Y', $DateInSec); // save reformated date to variable
                // save the rest in variables
                $PostTextStr = $RowArr["PostText"];
                $PostTitleStr = $RowArr["PostTitle"];
                $UserIdStr = $RowArr["User"];
                // Profile picture
                $Userpfp = base64_encode($RowArr["Userpfp"]);

                array_push($PostsArr, $ImageData, $PostTitleStr, $PostTextStr, $DateStr, $UserIdStr, $Userpfp);
                array_push($ReturnArr, $PostsArr);
            }
            return $ReturnArr;
        } else {
            return null;
        }
    }

    function submitAltPost($UserIdInt, $PostTitleStr, $PostTimeStampStr, $PostTextStr) { // if user submitted a post that does not contain an image
        $this->createConn();

        $InsertStr = "INSERT INTO post (UserId, PostTitle, PostTimeStamp, PostText) VALUES (?, ?, ?, ?)";
        $PrepObj = $this->ConnectionObj->prepare($InsertStr);
        $PrepObj->bind_param("isss", $UserIdInt, $PostTitleStr, $PostTimeStampStr, $PostTextStr);

        // Execute the query
        if ($PrepObj->execute()) {
            $ReturnStateStr = "text post uploaded successfully.";
        } else {
            $ReturnStateStr = "Error uploading post: " . $PrepObj->error;
        }

        // Close the statement
        $PrepObj->close();

        $this->endConn();
        return $ReturnStateStr;
    }

    function submitPost($UserIdInt, $PostTitleStr, $PostTimeStampStr, $PostTextStr, $ReceivedFileArr) { // user submits post
        $ReturnStateStr = "";
        $AllowedTypesArr = array("jpg", "png", "jpeg", "gif", "heic", "webp");
        $Filename = basename($ReceivedFileArr["name"]);
        $FileType = pathinfo($Filename, PATHINFO_EXTENSION);

        $this->createConn();

        // Check if a file was uploaded without errors
        if (isset($ReceivedFileArr) && $ReceivedFileArr["error"] === 0) {
            // checks if allowed filetype is given
            if (in_array($FileType, $AllowedTypesArr)) {
                $ImageStr = $ReceivedFileArr["tmp_name"]; // temporary file name in which the uploaded file was stored on the server

                // Read the image data
                $ImageContentStr = file_get_contents($ImageStr); // The file_get_contents() reads a file into a string

                // Prepare the SQL statement
                $InsertStr = "INSERT INTO post (UserId, PostTitle, PostTimeStamp, PostText, PostMedia) VALUES (?, ?, ?, ?, ?)";
                $PrepObj = $this->ConnectionObj->prepare($InsertStr);
                $PrepObj->bind_param("issss", $UserIdInt, $PostTitleStr, $PostTimeStampStr, $PostTextStr, $ImageContentStr);

                // Execute the query
                if ($PrepObj->execute()) {
                    $ReturnStateStr = "Image uploaded successfully.";
                } else {
                    $ReturnStateStr = "Error uploading image: " . $PrepObj->error;
                }

                // Close the statement
                $PrepObj->close();
            } else {
                $ReturnStateStr = "incorrect filetype.";
            }
        } else {
            $ReturnStateStr = "Error uploading image: " . $_FILES["image"]["error"];
        }
        $this->endConn();

        return $ReturnStateStr;
    }



    ////////////////////// Profile ///////////////////
    function checkChangeFields() {
        if (!preg_match("/^[a-zA-Z-' ]*$/", $this->FirstNameStr) || $this->FirstNameStr == "" || $this->FirstNameStr == " ") {
            return "nameErr";
        } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $this->LastNameStr) || $this->LastNameStr == "" || $this->LastNameStr == " ") {
            return "surnameErr";
        } elseif ($this->validateEmail($this->EmailAddressStr)) {
            return "emailErr";
        } elseif ($_SESSION['email'] != $this->EmailAddressStr) { // $_SESSION['email'] != $this->EmailAddressStr
            if ($this->checkEmailExists($this->EmailAddressStr) != "Success") { // $this->checkEmailExists($this->EmailAddressStr) != "Success"
                return "This E-mail is already in use.";
            }
        } elseif ($_SESSION['user'] != $this->UserNameStr) { // $_SESSION['username'] != $this->UserNameStr
            if ($this->checkUserExists($this->UserNameStr) != "Success") { // $this->checkUserExists($this->UserNameStr) != "Success"
                return "This username already exists.";
            }
        } elseif ($this->validateUsername($this->UserNameStr)) {
            return "userErr";
        } elseif ($this->testPassword($this->PasswordStr)) {
            return "passErr";
        }
        $this->FirstNameStr = $this->correctName($this->FirstNameStr);
        $this->LastNameStr = $this->correctName($this->LastNameStr);
        return "none";
    }

    function getProfileImg() {
        // get the current user's pfp and return the correct format for the front-end
        return "profile picture";
    }

    function getProfile($IdInt) { // gets user's profile
        // show all information about user
        $ReturnArr = array();
        $this->createConn();

        $ProfileInfoStr = "SELECT FirstName, LastName, EmailAddress, User, Password, Userpfp FROM user WHERE ID=$IdInt";
        $ResultObj = $this->ConnectionObj->query($ProfileInfoStr);
        $RowArr = $ResultObj->fetch_assoc();

        $this->endConn();

        array_push($ReturnArr, $RowArr["FirstName"], $RowArr["LastName"], $RowArr["EmailAddress"], $RowArr["User"], $RowArr["Password"], base64_encode($RowArr["Userpfp"]));
        return $ReturnArr;
    }

    function changeAltProfile($IdInt, $FNameStr, $LNameStr, $EmailStr, $UserStr, $PasswordStr) {
        $this->FirstNameStr = $FNameStr;
        $this->LastNameStr = $LNameStr;
        $this->EmailAddressStr = $EmailStr;
        $this->UserNameStr = $UserStr;
        $this->PasswordStr = $PasswordStr;

        $ResultStr = $this->checkChangeFields();
        if ($ResultStr == "none") {
            $HashedPasswordStr = password_hash($this->PasswordStr, PASSWORD_BCRYPT, $this->HASHOPTIONARR);
            $this->createConn();

            // Update
            $SignUpStr = "UPDATE user SET FirstName=?, LastName=?, EmailAddress=?, User=?, Password=? WHERE ID=?";
            $PrepObj = $this->ConnectionObj->prepare($SignUpStr);
            $PrepObj->bind_param('sssssi',$this->FirstNameStr, $this->LastNameStr, $this->EmailAddressStr, $this->UserNameStr, $HashedPasswordStr, $IdInt);
            $PrepObj->execute();
            $PrepObj->close();

            $this->endConn();

            $_SESSION['name'] = $this->FirstNameStr;
            $_SESSION['surname'] = $this->LastNameStr;
            $_SESSION['email'] = $this->EmailAddressStr;
            $_SESSION['user'] = $this->UserNameStr;
            $_SESSION['password'] = $this->PasswordStr;

            return "success";
        } else {
            return "something went wrong.";
        }
    }

    function changeProfile($IdInt, $FNameStr, $LNameStr, $EmailStr, $UserStr, $PasswordStr, $ReceivedFileArr) { // any change to the user's information will be changed here
        $this->FirstNameStr = $FNameStr;
        $this->LastNameStr = $LNameStr;
        $this->EmailAddressStr = $EmailStr;
        $this->UserNameStr = $UserStr;
        $this->PasswordStr = $PasswordStr;

        $ResultStr = $this->checkChangeFields();
        if ($ResultStr == "none") {
            $AllowedTypesArr = array("jpg", "png", "jpeg", "gif", "heic", "webp");
            $Filename = basename($ReceivedFileArr["name"]);
            $FileType = pathinfo($Filename, PATHINFO_EXTENSION);

            if (isset($ReceivedFileArr)) {
                if (is_array($ReceivedFileArr)) {
                    if (in_array($FileType, $AllowedTypesArr)) {
                        $ImageStr = $ReceivedFileArr["tmp_name"];
                        $ImageContentStr = file_get_contents($ImageStr);
        
                        $HashedPasswordStr = password_hash($this->PasswordStr, PASSWORD_BCRYPT, $this->HASHOPTIONARR);
                        $this->createConn();
        
                        // Update
                        $SignUpStr = "UPDATE user SET FirstName=?, LastName=?, EmailAddress=?, User=?, Password=?, Userpfp=? WHERE ID=?";
                        $PrepObj = $this->ConnectionObj->prepare($SignUpStr);
                        $PrepObj->bind_param('ssssssi',$this->FirstNameStr, $this->LastNameStr, $this->EmailAddressStr, $this->UserNameStr, $HashedPasswordStr, $ImageContentStr, $IdInt);
                        $PrepObj->execute();
                        $PrepObj->close();
        
                        $this->endConn();

                        $_SESSION['name'] = $this->FirstNameStr;
                        $_SESSION['surname'] = $this->LastNameStr;
                        $_SESSION['email'] = $this->EmailAddressStr;
                        $_SESSION['user'] = $this->UserNameStr;
                        $_SESSION['password'] = $this->PasswordStr;
                        $_SESSION['pfp'] = $ImageContentStr;
    
                        return "success";
                    } else {
                        return "Incorrect filetype";
                    }
                } else {
                    //
                }
            } else {
                return "Error uploading image: " . $_FILES["image"]["error"];
            }
        } else {
            return "something went wrong.";
        }
    }

}

?>