<?php
error_reporting(0);
ini_set('display_errors', 0);
/* Inneholder admin panelet med php CRUD funksjonalitet */

// sjekker om session admin er true i tilfelle noen åpner direkte url til adminpanel
if ($_SESSION["admin"] != "true") {
    echo 'Du har ikke tilgang til admin panelet';
} else {
?>
<head>
    <link href="css/panelStylesheet.css?<?= filemtime("css/panelStylesheet.css") ?>" rel="stylesheet" type="text/css"/>
</head>
<div class="gridbox">
    <div class="div1">
    <form action="php/register.php" method="post" class="regForm">
        <h3>Registrer skift</h3>
        <div class="formlist">
        <?php
    listboxForUserID();
?> <br><br></div>
        <div class="regsift">
        <input id="from" name="start_day" placeholder="Skift start" required="" type="text"  autocomplete="off" />
        <input id="to" name="end_day" placeholder="Skift slutt" required="" type="text"  autocomplete="off" />
        <br>
            <div class="hoursoption">
            <select name="start_hour">
            <option>00</option>
            <option>01</option>
            <option>02</option>
            <option>03</option>
            <option>04</option>
            <option>05</option>
            <option>06</option>
            <option>07</option>
            <option selected="selected">08</option>
            <option>09</option>
            <option>10</option>
            <option>11</option>
            <option>12</option>
            <option>13</option>
            <option>14</option>
            <option>15</option>
            <option>16</option>
            <option>17</option>
            <option>18</option>
            <option>19</option>
            <option>20</option>
            <option>21</option>
            <option>22</option>
            <option>23</option>
        </select>:<select name="start_minute">
            <option selected="selected">00</option>
            <option>15</option>
            <option>30</option>
            <option>45</option>
        </select> |
        <select name="end_hour">
            <option>00</option>
            <option>01</option>
            <option>02</option>
            <option>03</option>
            <option>04</option>
            <option>05</option>
            <option>06</option>
            <option>07</option>
            <option>08</option>
            <option>09</option>
            <option>10</option>
            <option>11</option>
            <option>12</option>
            <option>13</option>
            <option>14</option>
            <option>15</option>
            <option selected="selected">16</option>
            <option>17</option>
            <option>18</option>
            <option>19</option>
            <option>20</option>
            <option>21</option>
            <option>22</option>
            <option>23</option>
        </select>:<select name="end_minute">
            <option selected="selected">00</option>
            <option>15</option>
            <option>30</option>
            <option>45</option>
        </select>
    </div>
    <br><br> </div>
    <input name="book" type="submit" value="Opprett skift" class='button' style="width:80%;"/>
</form>
</div>
<div class="div2">
<h3>Kanseller skift</h3>
<form action="php/cancel.php" method="post" class="cancelForm">
    <div class="formlist">
     <?php
    listboxForShiftID();
?> <br></div>
     <input name="cancel" type="submit" value="Kanseller" class='button' style="width:80%;"/>
</form>
</div>

<div class="div3">
<h3>Slett skift</h3>
<form action="php/delete.php" method="post" class="deleteForm">
    <div class="formlist">
     <?php
    listboxForShiftID();
?> <br> </div>
     <input name="delete" type="submit" value="Slett" class='button' style="width:80%;"/>
</form>
</div>

<div class="div4">
<h3>Lag ny bruker</h3>
<form method="post" action="php/newUser.php" enctype="multipart/form-data" id="createUser" name="createUser" class ="createUser" onsubmit="#">
    <div class="inputbruker">
    <input type="text" placeholder="Fornavn/Etternavn" id="fname" name="fname"><br>
    <input type="text" id="username" placeholder="Brukernavn" name="username"><br>
    <input type="password" placeholder="Passord" id="password" name="password"><br>
    <input type="text" placeholder="Telefonnummer" id="phone" name="phone"><br>
</div>
    <div class="formlist">
    <?php
    listboxForLocation();
?><br><br> </div>
    <input type="checkbox" id="admin" name="admin" value="admin">
    <label for="admin"> Sett som admin? </label><br>
    <input name="find" type="submit" value="Lag ny bruker" class='button' style="width:80%;"/>
</form>
</div>

<div class="div5">
<h3>Slett bruker</h3>

<form method="post" action="php/delUser.php" enctype="multipart/form-data" id="delUser" name="delUser" class ="delUser" onsubmit="#">
    <div class="formlist">
    <?php
    listboxForUserID();
?> </div>
   <input name="delUser" type="submit" value="Slett bruker" class='button' style="width:80%;"/>
</form>
</div>
</div>
<form method="post" action="" enctype="multipart/form-data" id="showLocation" name="showLocation" class="showLocation" onsubmit="#">
    <p class="filtertext">Filtrer etter ansatt på lokasjon</p> </br> <div class= "showSelect">
        <div class="formlist">
     <?php
    listboxForLocation();
?></div></div>
<br><br>
    <input class="button" name="find" type="submit" value="Velg lokasjon" />
</form>

<?php
}
?>
</body>
</html>