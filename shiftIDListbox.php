<?php
function listboxForShiftID()
{
    print("<select name='listboxShift' id='listboxShift'>");
    $servername = "localhost";
    $username ="225299";
    $password = "992522";
    $dbname = "225299";
    // Create connection
    $conn = mysqli_connect($servername, $username, $password,  $dbname);

    $sqlQuery = "SELECT * FROM shifts JOIN user ON shifts.userID=user.userID;";
    $sqlResult = mysqli_query($conn, $sqlQuery) or die("Ikke mulig å hente data fra databasen");
    $numRows = mysqli_num_rows($sqlResult);
    print ("<option value='' disabled selected> Velg skift </option>");
    for ($r = 1;$r <= $numRows;$r++)
    {
        $row = mysqli_fetch_array($sqlResult);
        $shiftID = $row["id"];
        $username = $row["username"];
        print ("<option value='$shiftID'>$shiftID |$username </option>");
    }
    print("</select>");
}