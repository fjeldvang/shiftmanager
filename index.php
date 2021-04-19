<?php

// Inneholder form for sletting av produkter

session_start();
@$loginUser = $_SESSION["username"];
if (!$loginUser)
{
    print ("Denne siden krever innlogging <br />");
    include("login.php");
}
else
{

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<style>
html *
{
   font-family: Arial !important;
}
table.calendar {
	border-left: 1px solid #999;
}
tr.calendar-row {
}
td.calendar-day {
	min-height: 80px;
	font-size: 11px;
	position: relative;
	vertical-align: top;
}
* html div.calendar-day {
	height: 80px;
}
td.calendar-day:hover {
	background: #eceff5;
}
td.calendar-day-np {
	background: #eee;
	min-height: 80px;
}
* html div.calendar-day-np {
	height: 80px;
}
td.calendar-day-head {
	background: #ccc;
	font-weight: bold;
	text-align: center;
	width: 120px;
	padding: 5px;
	border-bottom: 1px solid #999;
	border-top: 1px solid #999;
	border-right: 1px solid #999;
}
div.day-number {
	background: #999;
	padding: 5px;
	color: #fff;
	font-weight: bold;
	float: right;
	margin: -5px -5px 0 0;
	width: 20px;
	text-align: center;
}
td.calendar-day, td.calendar-day-np {
	width: 120px;
	padding: 5px;
	border-bottom: 1px solid #999;
	border-right: 1px solid #999;
}
</style>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>Shift calendar</title>
<link href="jquery-ui.css" rel="stylesheet">
<script src="jquery-1.10.2.js"></script>
<script src="jquery-ui.js"></script>

<!--<script src="lang/datepicker-no.js"></script>-->

<script>
    $(function() {
	$.datepicker.setDefaults($.datepicker.regional['no']);
    $( "#from" ).datepicker({
      defaultDate: "+0w",
      changeMonth: true,
      numberOfMonths: 3,
      onClose: function( selectedDate ) {
        $( "#to" ).datepicker( "option", "minDate", selectedDate );
      }
    });
    $( "#to" ).datepicker({
      defaultDate: "+0w",
	  regional: "no",
      changeMonth: true,
      numberOfMonths: 3,
      onClose: function( selectedDate ) {
        $( "#from" ).datepicker( "option", "maxDate", selectedDate );
      }
    });
  });  </script>
</head>

<body>

<h1>Shiftmanager Marcussen Efte</h1>
<?php
/* sjekker om man er admin fra login, inkluderer admin panel om det er tilfellet */
if($_SESSION["admin"] == "true"){
    include 'adminPanel.php';
}

/* hovedfunksjon som skriver ut kalenderen basert på valgt brukerID */
function draw_calendar($month,$year,$userID){

	include 'config.php';

	/* setter opp tilkoblingen */
	$servername = "localhost";
	$username ="225299";
	$password = "992522";
	$dbname = "225299";
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
    				$calendar .= "ID: " . $row["id"] . "<br>" . $row["name"] . "<br>" . $row["phone"] . "<br>";
    				if($current_epoch == $row["start_day"] AND $current_epoch != $row["end_day"]) {
    					$calendar .= "Shift starts: " . sprintf("%02d:%02d", $row["start_time"]/60/60, ($row["start_time"]%(60*60)/60)) . "<br><hr><br>";
    				}
    				if($current_epoch == $row["start_day"] AND $current_epoch == $row["end_day"]) {
    					$calendar .= "Shift starts: " . sprintf("%02d:%02d", $row["start_time"]/60/60, ($row["start_time"]%(60*60)/60)) . "<br>";
    				}
    				if($current_epoch == $row["end_day"]) {
    					$calendar .= "Shift ends: " . sprintf("%02d:%02d", $row["end_time"]/60/60, ($row["end_time"]%(60*60)/60)) . "<br><hr><br>";
    				}
    				if($current_epoch != $row["start_day"] AND $current_epoch != $row["end_day"]) {
	    				$calendar .= "Shift: 24h<br><hr><br>";
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

/* config inneholder $months, et array av alle månedene. trengs når man bruker draw_calendar() */
include 'config.php';

/* sjekker om innlogget bruker er admin, henter deretter bruker fra listeboks for å velge view */
if($_SESSION["admin"] == "true"){
    if(isset($_POST["listbox2"])){
        $userID=$_POST["listbox2"];

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
}

/* om man ikke er admin ser man bare sin egen timeplan */
else{

$userID=$_SESSION["userID"];

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

print '<br/>';
print '<br/>';
print("<a href='logout.php'>Logg ut</a>");

}
?>
</body>

</html>
