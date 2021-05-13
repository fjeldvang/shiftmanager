<?php
session_start();
error_reporting(0);
ini_set('display_errors', 0);
/* Inneholder hovedsiden og alle brukerfunksjoner */ 
@$loginUser = $_SESSION["username"];
if (!$loginUser)
{
    print ("<br />");
    include("php/login.php");
}
else
{

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
		<title>Marcussen Eftf kalender</title>
		<link href="css/stylesheet.css?<?=filemtime("css/stylesheet.css")?>" rel="stylesheet" type="text/css"/>
		<link href="css/jquery-ui.css" rel="stylesheet" type="text/css"/>
		<script src="javascript/jquery-1.10.2.js"></script>
		<script src="javascript/jquery-ui.js"></script>
		<script src="javascript/datepicker.js"></script>
		<!--<script src="lang/datepicker-no.js"></script>-->
	</head>
	
<body>
	<div class="section_shadowPage">
    	<div class="container_shadowPage">
			<div class = "overskrift" style="margin:auto;">
				<img src="picture/logo-big.png" style="max-width:50%;margin-left:25%;">
			</div>
<?php

print("<p>" . "Velkommen " . $loginUser . "<p>" . "<br>");
/* inkluderer funksjonene for listeboks og kalender */
include_once 'php/functions.php';
print("<p><a href='php/logout.php' class='button' >Logg ut</a></p><br>");
/* sjekker om man er admin fra login, inkluderer admin panel om det er tilfellet */
if($_SESSION["admin"] == "true"){
    include 'php/adminPanel.php';
}

/* config inneholder $months, et array av alle månedene. trengs når man bruker draw_calendar() */
include 'php/config.php';

/* sjekker om innlogget bruker er admin, henter deretter bruker fra listeboks for å velge view 
 * dette er en rar måte å gjøre det på egentlig, men $_SESSION er ikke veldig glad i ordentlig bool
 * keep it simple, stupid! 
 */
if($_SESSION["admin"] == "true")
{
    if(isset($_POST["listboxLocation"])){
		print("<form method='post' action='' enctype='multipart/form-data' id='listboxForUserIDByLocation' name='listboxForUserIDByLocation' class ='listboxForUserIDByLocation' onsubmit=''>");
		listboxForUserIDByLocation();
		print("<p><input class='button' name='listboxForUserIDByLocation' type='submit' value='Filtrer etter ansatt' /></p>");
		print("</form>");
    }

	/* venter her med å gå videre til listbox er set, henter verdi og tegner kalender */
    if(isset($_POST["listbox"]))
	{
        $userID=$_POST["listbox"];
		prepareToDrawCalendar($months, $userID);
    } 
	/* viser egen timeplan til å begynne med */
	elseif(!isset($_POST["listbox"]))
	{	
		$userID= null;
		prepareToDrawCalendar($months, $userID);
	}
}
else
{
	/* om man ikke er admin ser man bare sin egen timeplan */
	$userID=$_SESSION["userID"];
	prepareToDrawCalendar($months, $userID);
}

print '<br/>';
print '<br/>';
print("<p><a href='php/logout.php' class='button' >Logg ut</a></p>");
print '<br/>';
print '<br/>';
}
?>
</div>
</div>
</body>
</html>
