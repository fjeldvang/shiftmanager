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
		include 'config.php';
        include 'functions.php';
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

            $sql = "SELECT * FROM user WHERE name ='$name' OR username ='$usn'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                echo "Bruker med navn $name eller brukernavn $usn er allerede registrert fra før";
                goto end;
            }
            else
            {
                $sql = "INSERT INTO user(username, name, phone, password) VALUES('$usn', '$name', '$phone', '$pw')";

                if (mysqli_query($conn, $sql)) 
                {
                    echo "<h3>Bruker laget</h3>";

                        if(isset($_POST["admin"]) && $_SESSION["userID"]==1){
                            getUserID($userID, $usn);    
                        }
                        elseif(isset($_POST["admin"]) && $_SESSION["userID"]!=1){
                            echo "Du må være logget inn som rotbrukeren for å kunne legge til admin brukere<br><br>";
                            echo "(for DB admin) <br> Lag administrator konto via phpmyadmin med userID 1";
                            goto end;  
                        }
                } 
                else 
                {
                    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                }
            }
        }
        end:
		mysqli_close($conn);
?>

<a href="../index.php"><p>Tilbake til kalenderen</p></a>

</body>

</html>
