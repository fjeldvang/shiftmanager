<?php

// Inneholder en funksjon for å sjekke om brukernavn og passord er korrekt

error_reporting(0);
ini_set('display_errors', 0);

function checkUserPass($username, $password){
    $servername = "localhost";
    $us ="225299";
    $pw = "992522";
    $dbname = "225299";
    // Create connection
    $conn = mysqli_connect($servername, $us, $pw, $dbname);

    $legalUser = true;
    $sqlQuery = "SELECT * FROM user WHERE username='$username' AND password='$password';";
    $sqlResult = mysqli_query($conn, $sqlQuery);

    if (!$sqlResult)
    {
        $legalUser = false;
    }
    else
    {
        $row = mysqli_fetch_array($sqlResult);
        $savedUsername = $row["username"];
        $savedPassword = $row["password"];
        setUserID($row["userID"]);
        checkIfAdmin($row["userID"], $conn);

        if ($username != $savedUsername || $password != $savedPassword)
        {
            $legalUser = false;
        }
    }
    end:
    return $legalUser;
}

function setUserID($userID){
    $_SESSION["userID"] = $userID;
}

function checkIfAdmin($userID, $conn){

    $sqlQuery = "SELECT * FROM isadmin WHERE userID='$userID';";
    $sqlResult = mysqli_query($conn, $sqlQuery);
    if($row = mysqli_fetch_array($sqlResult)){
        $_SESSION["admin"] = "true";
    }
    else{
        $_SESSION["admin"] = "false";
    }
}

?>