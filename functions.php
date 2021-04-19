<?php

function listboxForUserID()
{
    print("<select name='listbox' id='listbox'>");
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
        $start_day = $row["start_day"];
        $start_time = sprintf("%02d:%02d", $row["start_time"]/60/60, ($row["start_time"]%(60*60)/60));
        $convertedDay = date("j-m-y", $start_day);
        // $convertedTime = date("H:i", $start_time);
        
        print ("<option value='$shiftID'>$convertedDay $start_time | $username </option>");
    }
    print("</select>");
}