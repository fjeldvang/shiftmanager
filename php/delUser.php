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
            $userID = $_POST["listbox"];
            if($userID == null){
                print("<h3>Velg bruker fra listeboksen</h3>");
            }		
            else {
                $sql ="DELETE FROM shifts WHERE userID = $userID";
                $result= mysqli_query($conn, $sql) or die("Databasetilkobling feilet");

                $sql ="SELECT * FROM isadmin WHERE userID = $userID";
                $result= mysqli_query($conn, $sql) or die("Databasetilkobling feilet");
                if(mysqli_num_rows($result)>0){
                    $sql ="DELETE FROM isadmin WHERE userID = $userID";
                    mysqli_query($conn, $sql);
                }

                $sql = "DELETE FROM user WHERE userID = $userID";
                if (mysqli_query($conn, $sql)) {
                    echo "<h3>Bruker slettet.</h3>";
                } 
                else {
                    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                }
            }
            mysqli_close($conn);
        }
?>

<a href="../index.php"><p>Tilbake til kalenderen</p></a>

</body>

</html>
