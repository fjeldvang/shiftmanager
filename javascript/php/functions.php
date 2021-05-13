<?php
session_start();
error_reporting(0);
ini_set('display_errors', 0);
// https://www.linkedin.com/pulse/why-should-you-switch-pdo-from-mysql-mysqli-diwaker-mishra/

function getUserID($usn){
	/* Henter userID fra database med valgt brukernavn og setter som admin */
    include 'config.php';
    $conn = mysqli_connect($servername, $username, $password, $dbname);

	$sqlQuery = "SELECT * FROM user WHERE username LIKE '$usn'";
    $sqlResult = mysqli_query($conn, $sqlQuery) or die("Funker ikke");
        $row = mysqli_fetch_array($sqlResult);
		$userID = $row["userID"];
		setAsAdmin($userID);
		return $userID;
}

function getLocation($userID){
    /* Henter lokasjon fra database med userID */
    include 'config.php';
    $conn = mysqli_connect($servername, $username, $password,  $dbname);

    $sqlQuery = "SELECT * FROM user WHERE userID LIKE '$userID'";
    $sqlResult = mysqli_query($conn, $sqlQuery) or die("Funker ikke");
    $row = mysqli_fetch_array($sqlResult);
    $location = $row["location"];
    return $location;
}

function setAsAdmin($userID){
	/* Setter som admin */
	include 'config.php';
    $conn = mysqli_connect($servername, $username, $password,  $dbname);

	$sql = "INSERT INTO isadmin(userID)
			VALUES ($userID)";

        if (mysqli_query($conn, $sql)) 
		{
            echo "<h3>Bruker også registrert som admin.</h3>";
        }
}

function listboxForUserID()
{
    /* Listeboks for brukere */
    print("<select name='listbox' id='listbox'>");
    include 'config.php';
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

        // skip admin bruker
        if($userID==1) continue;
        print ("<option value='$userID'>$name | $username </option>");
    }
    print("</select>");
}

function listboxForUserIDByLocation()
{
    /* Listeboks for brukere */
    $location = $_POST["listboxLocation"];
    print("<select name='listbox' id='listbox'>");
    include 'config.php';
    $conn = mysqli_connect($servername, $username, $password,  $dbname);

    $sqlQuery = "SELECT * FROM user where location LIKE '$location' order by userID;";
    $sqlResult = mysqli_query($conn, $sqlQuery) or die("Ikke mulig å hente data fra databasen");
    $numRows = mysqli_num_rows($sqlResult);

    print ("<option value='' disabled selected> Velg bruker </option>");

    for ($r = 1;$r <= $numRows;$r++)
    {
        $row = mysqli_fetch_array($sqlResult);
        $userID = $row["userID"];
        $name= $row["name"];
        $username = $row["username"];

        // skip admin bruker
        if($userID==1) continue;
        print ("<option value='$userID'>$name | $username </option>");
    }
    print("</select>");
}

function listboxForLocation()
{
    /* Listeboks for brukere */
    print("<select name='listboxLocation' id='listboxLocation'>");
    include 'config.php';
    $conn = mysqli_connect($servername, $username, $password,  $dbname);

    $sqlQuery = "SELECT * FROM location ORDER BY location;";
    $sqlResult = mysqli_query($conn, $sqlQuery) or die("Ikke mulig å hente data fra databasen");
    $numRows = mysqli_num_rows($sqlResult);

    print ("<option value='' disabled selected> Velg lokasjon</option>");

    for ($r = 1;$r <= $numRows;$r++)
    {
        $row = mysqli_fetch_array($sqlResult);
        $location = $row["location"];
        print ("<option value='$location'>$location </option>");
    }
    print("</select>");
}

function listboxForShiftID()
{
	/* Listeboks for skift */
    print("<select name='listboxShift' id='listboxShift'>");
    include 'config.php';
    $conn = mysqli_connect($servername, $username, $password,  $dbname);

    $sqlQuery = "SELECT * FROM shifts JOIN user ON shifts.userID=user.userID ORDER BY start_day ASC;";
    $sqlResult = mysqli_query($conn, $sqlQuery) or die("Ikke mulig å hente data fra databasen");
    $numRows = mysqli_num_rows($sqlResult);

    print ("<option value='' disabled selected> Velg skift </option>");
	
	// henter datetime for i dag
	$d = new DateTime(date("Y-m-d"));
	$currentDay = $d->getTimestamp();

    for ($r = 1;$r <= $numRows;$r++)
    {
        $row = mysqli_fetch_array($sqlResult);
        $shiftID = $row["id"];
        $name = $row["name"];
        $start_day = $row["start_day"];
        $end_day = $row["end_day"];
        $start_time = sprintf("%02d:%02d", $row["start_time"]/60/60, ($row["start_time"]%(60*60)/60));
        $end_time = sprintf("%02d:%02d", $row["end_time"]/60/60, ($row["end_time"]%(60*60)/60));
        $convertedStartDay = date("j-m-y", $start_day);
		$convertedEndDay = date("j-m-y", $end_day);

		/* printer option om skiftet ikke er samme dag eller tidligere */
        if($end_day > $currentDay){
        print ("<option value='$shiftID'>$convertedStartDay $start_time til $end_time | $name </option>");
		}
    }
    print("</select>");
}

// forbereder tegning av kalender med array av måneder for en brukerID
// men viser alle om brukerID er null, se draw_calendar()
function prepareToDrawCalendar($months, $userID)
{
$d = new DateTime(date("Y-m-d"));
echo '<h3>' . $months[$d->format('n')-1] . ' ' . $d->format('Y') . '</h3>';
echo draw_calendar($d->format('m'),$d->format('Y'), $userID);

$d->modify( 'first day of next month' );
echo '<h3>' . $months[$d->format('n')-1] . ' ' . $d->format('Y') . '</h3>';
echo draw_calendar($d->format('m'),$d->format('Y'), $userID);

$d->modify( 'first day of next month' );
echo '<h3>' . $months[$d->format('n')-1] . ' ' . $d->format('Y') . '</h3>';
echo draw_calendar($d->format('m'),$d->format('Y'), $userID);
}

// tegner kalenderen
function draw_calendar($month,$year,$userID){
    
	date_default_timezone_set('Europe/Oslo');

	/* setter opp tilkoblingen og validerer */
	include 'config.php';
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if (!$conn) {
    	die("Connection failed: " . mysqli_connect_error());
	}

	/* lager tabell og headings til tabellen (dagene i uka) */
	$calendar = '<table cellpadding="0" cellspacing="0" class="calendar">';
	$calendar.= '<tr class="calendar-row"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$headings).'</td></tr>';

	/* dag/uke variabler */
	$running_day = date('w',mktime(0,0,0,$month,1,$year));
	$days_in_month = date('t',mktime(0,0,0,$month,1,$year));
	$days_in_this_week = 1;
	$day_counter = 0;

	/* raden for uke 1 */
	$calendar.= '<tr class="calendar-row">';

	/* grå dager på kalenderen helt til dag 1 */
	for($x = 0; $x < $running_day; $x++):
		$calendar.= '<td class="calendar-day-np"> </td>';
		$days_in_this_week++;
	endfor;

	$location = $_POST["listboxLocation"];
	$changeThis = false;

	/* fortsetter med dagene */
	for($list_day = 1; $list_day <= $days_in_month; $list_day++):
		$calendar.= '<td class="calendar-day">';
			/* add in the day number */
			$calendar.= '<div class="day-number">'.$list_day.'</div>';

			// setter inn paragraf tag for spacing
			$calendar.= str_repeat('<p> </p>',2);

			// lager denne epoken for å identifisere tidsrom
			$current_epoch = mktime(0,0,0,$month,$list_day,$year);

            // henter datetime for i dag
            $d = new DateTime(date("Y-m-d"));
            $currentDay = $d->getTimestamp();

            /* lager sql spørring for å hente data med informasjon fra config.php */
			if($userID == null){
				$changeThis = true;
            	$sql ="SELECT * FROM user JOIN $tablename ON user.userID=$tablename.userID WHERE $current_epoch BETWEEN start_day AND end_day";
			} else{
				$sql ="SELECT * FROM $tablename JOIN user ON $tablename.userID=user.userID WHERE $tablename.userID = $userID AND $current_epoch BETWEEN start_day AND end_day";
			}
			$result = mysqli_query($conn, $sql);

    		if (mysqli_num_rows($result) > 0) {
    			/* setter ut data av hver eneste rad */
    			while($row = mysqli_fetch_assoc($result)) {
					// skip om $userID er null som setter $changeThis til true
					// fordi det er fra den første listeboksen som velger lokasjon
					// så om ikke lokasjon er riktig og changethis er satt til true skipper den
					if ($row["location"]!= $location && $changeThis == true) goto skipthis;

					// filtrerer ut lørdag/søndag
					if ($days_in_this_week > 7 || $days_in_this_week ==1) goto skipthis;
					
					// ellers fullfør objekt 
    			    if($currentDay > $row["end_day"]) $calendar .= "<font color=\"grey\"><s>";
					if($row["canceled"] == 1) $calendar .= "<font color=\"grey\"><s>";
    				$calendar .= "Lokasjon: " . $row["location"] . "<br>" . $row["name"] . "<br>" . $row["phone"] . "<br>";
	    			$calendar .= "Skift starter: " . sprintf("%02d:%02d", $row["start_time"]/60/60, ($row["start_time"]%(60*60)/60)) . "<br>";
					$calendar .= "Skift slutter: " . sprintf("%02d:%02d", $row["end_time"]/60/60, ($row["end_time"]%(60*60)/60)) . "<br><hr><br>";
					if($row["canceled"] == 1) $calendar .= "</s></font>";
                    if($currentDay > $row["end_day"]) $calendar .= "</s></font>";
					
					skipthis:
    			}
			} 
			else {
    			$calendar .= "";
			}
		// kalender logikk og table struktur
		$calendar.= '</td>';
		if($running_day == 6):
			$calendar.= '</tr>';
			if(($day_counter+1) != $days_in_month):
				$calendar.= '<tr class="calendar-row">';
			endif;
			$running_day = -1;
			$days_in_this_week = 0;
		endif;
		$days_in_this_week++; $running_day++; $day_counter++;
	endfor;

	/* ugyldige dager på slutten av kalenderen som ikke er dager i måneden */
	if($days_in_this_week < 8 AND $days_in_this_week > 1):
		for($x = 1; $x <= (8 - $days_in_this_week); $x++):
			$calendar.= '<td class="calendar-day-np"> </td>';
		endfor;
	endif;

	/* siste rad, og slutter tabellen */
	$calendar.= '</tr>';
	$calendar.= '</table>';

	/* lukker tilkoblingen når man er ferdig for sikkerhetsmessige grunner */
	mysqli_close($conn);
	
	/* helt ferdig, returnerer resultat */
	return $calendar;
}