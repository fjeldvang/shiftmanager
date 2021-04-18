<?php

function listboxForUserID2()
{
    print("<select name='listbox2' id='listbox2' onchange='this.createCookie()'>");
    $servername = "localhost";
    $username ="225299";
    $password = "992522";
    $dbname = "225299";
    // Create connection
    $conn = mysqli_connect($servername, $username, $password,  $dbname);

    $sqlQuery = "SELECT * FROM user order by userID;";
    $sqlResult = mysqli_query($conn, $sqlQuery) or die("Ikke mulig å hente data fra databasen");
    $numRows = mysqli_num_rows($sqlResult);
    print ("<option value='' disabled selected> Velg bruker </option>");
    for ($r = 1;$r <= $numRows;$r++)
    {
        $row = mysqli_fetch_array($sqlResult);
        $userID = $row["userID"];
        $name= $row["name"];
        $username = $row["username"];
        print ("<option value='$userID'>$name | $username </option>");
    }
    print("</select>");
}
