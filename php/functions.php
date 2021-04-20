<?php

// https://www.linkedin.com/pulse/why-should-you-switch-pdo-from-mysql-mysqli-diwaker-mishra/

function getUserID($userID, $usn){
    include 'config.php';
    // Create connection
    $conn = mysqli_connect($servername, $username, $password,  $dbname);

	$sqlQuery = "SELECT * FROM user WHERE username LIKE '$usn'";
    $sqlResult = mysqli_query($conn, $sqlQuery) or die("Funker ikke");
        $row = mysqli_fetch_array($sqlResult);
		$userID = $row["userID"];
		setAsAdmin($userID);
}

function setAsAdmin($userID){
	
	include 'config.php';
    // Create connection
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
    print("<select name='listbox' id='listbox'>");
    include 'config.php';
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

		// skip admin bruker
		if($userID==1) continue;
        print ("<option value='$userID'>$name | $username </option>");
    }
    print("</select>");
}

function listboxForShiftID()
{
    print("<select name='listboxShift' id='listboxShift'>");
    include 'config.php';
    // Create connection
    $conn = mysqli_connect($servername, $username, $password,  $dbname);

    $sqlQuery = "SELECT * FROM shifts JOIN user ON shifts.userID=user.userID ORDER BY start_day ASC;";
    $sqlResult = mysqli_query($conn, $sqlQuery) or die("Ikke mulig å hente data fra databasen");
    $numRows = mysqli_num_rows($sqlResult);
    print ("<option value='' disabled selected> Velg skift </option>");
    for ($r = 1;$r <= $numRows;$r++)
    {
        $row = mysqli_fetch_array($sqlResult);
        $shiftID = $row["id"];
        $username = $row["username"];
        $name = $row["name"];
        $start_day = $row["start_day"];
        $start_time = sprintf("%02d:%02d", $row["start_time"]/60/60, ($row["start_time"]%(60*60)/60));
        $convertedDay = date("j-m-y", $start_day);
        // $convertedTime = date("H:i", $start_time);
        
        print ("<option value='$shiftID'>$convertedDay $start_time | $name </option>");
    }
    print("</select>");
}

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

function draw_calendar($month,$year,$userID){
    
	date_default_timezone_set('Europe/Oslo');
	include 'config.php';

	/* setter opp tilkoblingen */
	include 'config.php';
	$conn = mysqli_connect($servername, $username, $password, $dbname);

	/* validerer tilkobling */
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

	/* blanke dager på kalenderen helt til dag 1 */
	for($x = 0; $x < $running_day; $x++):
		$calendar.= '<td class="calendar-day-np"> </td>';
		$days_in_this_week++;
	endfor;

	/* fortsetter med dagene */
	for($list_day = 1; $list_day <= $days_in_month; $list_day++):
		$calendar.= '<td class="calendar-day">';
			/* add in the day number */
			$calendar.= '<div class="day-number">'.$list_day.'</div>';

			$calendar.= str_repeat('<p> </p>',2);
			$current_epoch = mktime(0,0,0,$month,$list_day,$year);

            /* lager sql spørring for å hente data med informasjon fra config.php */
            $sql ="SELECT * FROM $tablename JOIN user ON $tablename.userID=user.userID WHERE $tablename.userID = $userID AND $current_epoch BETWEEN start_day AND end_day";

			$result = mysqli_query($conn, $sql);
    		
    		if (mysqli_num_rows($result) > 0) {
    			/* setter ut data av hver eneste rad */
    			while($row = mysqli_fetch_assoc($result)) {
					if($row["canceled"] == 1) $calendar .= "<font color=\"grey\"><s>";
    				$calendar .= /*"ID: " . $row["id"] . "<br>" .*/ $row["name"] . "<br>" . $row["phone"] . "<br>";
    				if($current_epoch == $row["start_day"] AND $current_epoch != $row["end_day"]) {
    					$calendar .= "Skift starter: " . sprintf("%02d:%02d", $row["start_time"]/60/60, ($row["start_time"]%(60*60)/60)) . "<br><hr><br>";
    				}
    				if($current_epoch == $row["start_day"] AND $current_epoch == $row["end_day"]) {
    					$calendar .= "Skift starter: " . sprintf("%02d:%02d", $row["start_time"]/60/60, ($row["start_time"]%(60*60)/60)) . "<br>";
    				}
    				if($current_epoch == $row["end_day"]) {
    					$calendar .= "Skift slutter: " . sprintf("%02d:%02d", $row["end_time"]/60/60, ($row["end_time"]%(60*60)/60)) . "<br><hr><br>";
    				}
    				if($current_epoch != $row["start_day"] AND $current_epoch != $row["end_day"]) {
	    				$calendar .= "Skift: Hele dagen <br><hr><br>";
	    			}
					if($row["canceled"] == 1) $calendar .= "</s></font>";
    			}
			} else {
    			$calendar .= "Ingen skift";
			}
			
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

	/* resten av dagene i uken */
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