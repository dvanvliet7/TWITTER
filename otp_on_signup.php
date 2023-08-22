<?php

class SignUp {
    private $ConnectionObj;

    public $HASHOPTIONARR = array('cost'=>11);
    public string $FirstnameStr;
    public string $SurnameStr;
    public string $PinStr;


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


    function emailUser($ToMailStr, $NameStr, $PinStr) {
        $BodyStr = '<br><br>Your OTP is: ' . $PinStr . '<br>Please do not share your OTP.';

        $SubjectStr = "Twitter Sign-up";

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

    function ifBlock() {
        $UserIpStr = $_SERVER['REMOTE_ADDR'];
        $CheckIpStr = "SELECT * FROM signup WHERE IP='".$UserIpStr."'";
        $ResultObj = $this->ConnectionObj->query($CheckIpStr);
        if ($ResultObj->num_rows > 2) {
            return true;
        } else {
            return false;
        }
    }

    function insertPerson($EmailStr, $PinStr, $UsedInt, $TimeStampStr) { // Insert the person trying to create an account
        $HashedPasswordStr = password_hash($PinStr, PASSWORD_BCRYPT, $this->HASHOPTIONARR);
        $this->createConn();

        $InsertToTableStr =  "INSERT INTO signup (EmailAddress, Pin, Used, UsedTimeStamp, IP) VALUES (?, ?, ?, ?, ?)";
        $PrepObj = $this->ConnectionObj->prepare($InsertToTableStr);
        $PrepObj->bind_param("ssiss", $EmailStr, $HashedPasswordStr, $UsedInt, $TimeStampStr, $_SERVER['REMOTE_ADDR']);
        $PrepObj->execute();
        $PrepObj->close();

        $this->endConn();

        $this->emailUser($EmailStr, $_SESSION['Firstname'], $PinStr);
    }

    function usePin($PinStr) {
        $PinDBStr = "";
        $UsedInt = 0;

        $this->createConn();

        if ($this->ifBlock()) {
            $ReturnStr = "block";
        } else {
            $SelectPinStr =  "SELECT Pin, Used FROM signup WHERE EmailAddress=? ORDER BY UsedTimeStamp DESC LIMIT 1";
            $PrepObj = $this->ConnectionObj->prepare($SelectPinStr);
            $PrepObj->bind_param("s", $_SESSION['Email']);
            $PrepObj->execute();
            $PrepObj->store_result();

            
            if ($PrepObj->num_rows > 0) { // check if exists
                $PrepObj->bind_result($PinDBStr, $UsedInt);
                $PrepObj->fetch();
                $PrepObj->close();
    
                if ($UsedInt == 0) { // check if used
                    if (password_verify($PinStr, $PinDBStr)) { // check if pin is correct
                        $ReturnStr = "Successful";
                    } else {
                        $ReturnStr = "wrong pin";
                    }
                } else {
                    $ReturnStr = "Pin already used";
                }
            } else {
                // email address NOT found
                $ReturnStr = "email address not found";
                $PrepObj->close();
            }
        }

        $this->endConn();

        error_log($ReturnStr);
        return $ReturnStr;
    }

}