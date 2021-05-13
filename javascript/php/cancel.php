<?php
session_start();
error_reporting(0);
ini_set('display_errors', 0);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>Kanseller Skift</title>
</head>

<body>

<?php
		include 'config.php';

		// Lager tilkobling og sjekker
		$conn = mysqli_connect($servername, $username, $password, $dbname);
		if (!$conn) {
			die("Connection failed: " . mysqli_connect_error());
		}

        // sjekker at ikke listeboks er null
		$id = intval(htmlspecialchars($_POST["listboxShift"]));
        if($id == null){
        print("<h3>Velg skift fra listeboksen</h3>");
        }
        else {
            $sql = "UPDATE $tablename SET canceled=1 WHERE id = $id";
            if (mysqli_query($conn, $sql)) {
                echo "<h3>Skift kansellert.</h3>";
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        }
		mysqli_close($conn);
?>

<a href="../index.php"><p>Tilbake til kalenderen</p></a>

</body>

</html>
