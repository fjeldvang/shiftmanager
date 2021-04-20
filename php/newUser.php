<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>Lag bruker</title>
</head>

<body>

<?php

function getUserID($userID, $usn){
    $servername = "localhost";
    $username ="225299";
    $password = "992522";
    $dbname = "225299";
    // Create connection
    $conn = mysqli_connect($servername, $username, $password,  $dbname);

	$sqlQuery = "SELECT * FROM user WHERE username LIKE '$usn'";
    $sqlResult = mysqli_query($conn, $sqlQuery) or die("Funker ikke");
        $row = mysqli_fetch_array($sqlResult);
		$userID = $row["userID"];
        print($userID);
		setAsAdmin($userID);
}

function setAsAdmin($userID){
	
	$servername = "localhost";
    $username ="225299";
    $password = "992522";
    $dbname = "225299";
    // Create connection
    $conn = mysqli_connect($servername, $username, $password,  $dbname);

	$sql = "INSERT INTO isadmin(userID)
			VALUES ($userID)";

        if (mysqli_query($conn, $sql)) 
		{
            echo "<h3>Registrert som admin.</h3>";
        }
}

		include 'config.php';

		// Create connection
		$conn = mysqli_connect($servername, $username, $password, $dbname);

		// Check connection
		if (!$conn) {
			die("Connection failed: " . mysqli_connect_error());
		}
        else {
            $userID = null;
            $name = $_POST["fname"];
            $usn = $_POST["username"];
            $pw = $_POST["password"];
            $phone = $_POST["phone"];
            $sql = "INSERT INTO user(username, name, phone, password) VALUES('$usn', '$name', '$phone', '$pw')";

            if (mysqli_query($conn, $sql)) 
            {
                echo "<h3>Bruker laget</h3>";

                    if(isset($_POST["admin"])){
                        getUserID($userID, $usn);
                    }
            } 
            else 
            {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        }
		mysqli_close($conn);
?>

<a href="../index.php"><p>Tilbake til kalenderen</p></a>

</body>

</html>
