<?php

/* Inneholder hovedsiden og alle brukerfunksjoner */ 

session_start();
@$loginUser = $_SESSION["username"];
if (!$loginUser)
{
    print ("Denne siden krever innlogging <br />");
    include("php/login.php");
}
else
{

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
		<title>Marcussen Efte kalender</title>
		<link href="css/stylesheet.css" rel="stylesheet">
		<link href="css/jquery-ui.css" rel="stylesheet">
		<script src="javascript/jquery-1.10.2.js"></script>
		<script src="javascript/jquery-ui.js"></script>
		<script src="javascript/datepicker.js"></script>
		<!--<script src="lang/datepicker-no.js"></script>-->
	</head>
	
<body>
	<h1>Shiftmanager Marcussen Efte</h1>
<?php

print("<p>" . "Velkommen " . $loginUser . "<p>" . "<br>");
/* inkluderer funksjonene for listeboks og kalender */
include_once 'php/functions.php';

/* sjekker om man er admin fra login, inkluderer admin panel om det er tilfellet */
if($_SESSION["admin"] == "true"){
    //flytte denne ned?
    include 'php/adminPanel.php';
}

/* config inneholder $months, et array av alle månedene. trengs når man bruker draw_calendar() */
include 'php/config.php';

/* sjekker om innlogget bruker er admin, henter deretter bruker fra listeboks for å velge view */
if($_SESSION["admin"] == "true")
{
    if(isset($_POST["listboxLocation"])){

    print("<form method='post' action='' enctype='multipart/form-data' id='listboxForUserIDByLocation' name='listboxForUserIDByLocation' class ='listboxForUserIDByLocation' onsubmit=''>");
    listboxForUserIDByLocation();
    print("<p><input name='listboxForUserIDByLocation' type='submit' value='Velg lokasjon' /></p>");
    print("</form>");
    }

    /* !!!if isset location listeboks print user listbox med sql where location like location!!! */

	/* venter her med å gå videre til listbox2 er set, henter verdi og tegner kalender */
    if(isset($_POST["listbox"]))
	{
        $userID=$_POST["listbox"];
		prepareToDrawCalendar($months, $userID);
    } 
	/* viser egen timeplan til å begynne med */
	elseif(!isset($_POST["listbox"]))
	{	
		$userID=$_SESSION["userID"];
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
print("<a href='php/logout.php'>Logg ut</a>");

}
?>
</body>
</html>
