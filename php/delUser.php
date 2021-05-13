<?php
session_start();
error_reporting(0);
ini_set('display_errors', 0);
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
include 'functions.php'; // kan fjernes?

// Lager og sjekker tilkobling
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    // Henter info fra og validerer listeboks innhold
    $userID = $_POST["listbox"];
    if ($userID == null) {
        print("<h3>Velg bruker fra listeboksen</h3>");
    } else {
        // sletter admin om brukeren har det
        $sql = "SELECT * FROM isadmin WHERE userID = $userID";
        $result = mysqli_query($conn, $sql) or die("Databasetilkobling feilet");
        if (mysqli_num_rows($result) > 0 && $_SESSION["userID"] == 1) {
            $sql = "DELETE FROM isadmin WHERE userID = $userID";
            mysqli_query($conn, $sql);
            // fortsetter fra del:
            goto del;
        } elseif (mysqli_num_rows($result) > 0 && $_SESSION["userID"] != 1) {
            echo 'Du kan ikke slette en admin bruker uten å være rotbrukeren';
            echo '<br><br>';
            echo 'Login med userID 1 for å slette admin bruker';
        } else {
            //herifra
del:
            // sletter skift knyttet til bruker
            $sql = "DELETE FROM shifts WHERE userID = $userID";
            $result = mysqli_query($conn, $sql) or die("Databasetilkobling feilet");
            
            // sletter endelig skift knyttet til bruker
            $sql = "DELETE FROM user WHERE userID = $userID";
            if (mysqli_query($conn, $sql)) {
                echo "<h3>Bruker slettet.</h3>";
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        }
    }
    mysqli_close($conn);
}
?>

<a href="../index.php"><p>Tilbake til kalenderen</p></a>

</body>

</html>