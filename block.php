<?php
class Block {
    private $ConnectionObj;


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


    function isBlocked() { // is this user already blocked
        $ReturnStr = "";
        $this->createConn();

        // get all blocked users
        $UserIpStr = $_SERVER['REMOTE_ADDR'];
        $IsBlockedStr = "SELECT * FROM blocked where IP='".$UserIpStr."'";
        $ResultObj = $this->ConnectionObj->query($IsBlockedStr);

        if ($ResultObj->num_rows > 0) {
            $ReturnStr = "Blocked";
        } else {
            $ReturnStr = "Not found";
        }

        $this->endConn();

        return $ReturnStr;
    }

    function blockPerson() { // block the current user
        $this->createConn();

        // insert a blocked person
        $UserIpStr = $_SERVER['REMOTE_ADDR'];
        $InsertBlockedPersonStr = "INSERT INTO blocked (IP) VALUES ('".$UserIpStr."')";
        $this->ConnectionObj->query($InsertBlockedPersonStr);

        $this->endConn();
    }
}