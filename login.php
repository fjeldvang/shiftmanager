<?php

// Inneholder innloggingsskjema

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<h3>Innlogging</h3>
<form action="" id="loginForm" name="loginForm" method="post">
    Brukernavn <input name="username" type="text" id="username"> <br />
    Passord <input name="password" type="password" id="password">  <br />
    <input type="submit" name="loginButton" value="Logg inn">
    <input type="reset" name="reset" id="reset" value="Nullstill"> <br />
</form>
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
