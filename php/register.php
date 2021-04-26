<?php
session_start();
error_reporting(0);
ini_set('display_errors', 0);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>Lag skift</title>
</head>

<body>

<?php
// for eventuell 5 dagers faste skift registrering og datetime konvertering
// https://www.php.net/manual/en/dateinterval.construct.php
// https://stackoverflow.com/questions/2515047/how-do-i-add-24-hours-to-a-unix-timestamp-in-php


		include 'config.php';
		
		// sjekk og lag tilkobling
		$conn = mysqli_connect($servername, $username, $password,  $dbname);
		if (!$conn) {
			die("Connection failed: " . mysqli_connect_error());
		}
		
		$start_day = intval(strtotime(htmlspecialchars($_POST["start_day"])));
		$start_time = (60*60*intval(htmlspecialchars($_POST["start_hour"]))) + (60*intval(htmlspecialchars($_POST["start_minute"])));
		$end_day = intval(strtotime(htmlspecialchars($_POST["end_day"])));
		$end_time = (60*60*intval(htmlspecialchars($_POST["end_hour"]))) + (60*intval(htmlspecialchars($_POST["end_minute"])));
		$userID = htmlspecialchars($_POST["listbox"]);
		
		$start_epoch = $start_day + $start_time;
		$end_epoch = $end_day + $end_time;

        if($userID == null){
        print("<h3>Velg bruker fra listeboksen</h3>");
        }
        else {
            $sql = "SELECT * FROM $tablename WHERE userID='$userID' AND (start_day>=$start_day OR end_day>=$start_day) AND canceled=0";
            $result = mysqli_query($conn, $sql);
            // sjekker etter relevante dager i db
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    // sjekker på 10 min intervaller om det er registrert skift
                    for ($i = $start_epoch; $i <= $end_epoch; $i = $i + 600) {
                        if ($i > ($row["start_day"] + $row["start_time"]) && $i < ($row["end_day"] + $row["end_time"])) {
                            echo '<h3><font color="red">Et skift er allerede registrert på denne dagen for valgt bruker</font></h3>';
                            goto end;
                        }
                    }
                }
            }

            $sql = "INSERT INTO $tablename (start_day, start_time, end_day, end_time, canceled, userID)
			VALUES ($start_day, $start_time, $end_day, $end_time, 0, $userID)";
            if (mysqli_query($conn, $sql)) {
                echo "<h3>Skift registrert.</h3>";
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        }
		end:
		mysqli_close($conn);
?>

<a href="../index.php"><p>Tilbake til kalenderen</p></a>

</body>

</html>
