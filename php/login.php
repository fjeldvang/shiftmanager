<?php
error_reporting(0);
ini_set('display_errors', 0);
// Inneholder innloggingsskjema

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link href="css/stylesheet.css" rel="stylesheet" type="text/css"/>
    <link href="css/login.css" rel="stylesheet" type="text/css"/>
    <!-- test ut disse ikonene utenom xampp -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
</head>
<body>
<div class ="login-container">
<h3>Innlogging</h3>
<form action="" id="loginForm" name="loginForm" method="post">
    <div class = "formbox">
        <input name="username" type="text" placeholder="Brukernavn" id="username"> <br />
    </div>
    <div class="formbox">
        <input name="password" type="password" placeholder="Passord" id="password">  <br />
    </div>
    <input class="btn" type="submit" name="loginButton" value="Logg inn">
    <input class="btn" type="reset" name="reset" id="reset" value="Nullstill"> <br />
</form>
</div>
<div class="Marcussen-bilde">
    <img src="picture/MMarcussen.jpg" width="450" height="450">
</div>
<?php
if (isset($_POST["loginButton"]))
{

    $username = $_POST["username"];
    $password = $_POST["password"];
    $userID = "fail";

    include ("check.php");

    if (!checkUserPass($username, $password))
    {
        print ("Feil brukernavn eller passord <br />");
    }
    else
    {
        $_SESSION["username"] = $username;
        print("<meta http-equiv='refresh' content='0;url=index.php'>");
    }
}

?>

</body>
</html>