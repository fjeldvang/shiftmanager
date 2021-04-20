<?php?>

<table border="1" cellpadding="5" width="800">
	<tr>
		<td valign="top">
        
		<form action="php/register.php" method="post">
			<h3>Registrer skift</h3>
			<table style="width: 70%">
				<tr>
					<td>Navn:</td>
                    <td><?php listboxForUserID();?></td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
    <td>Skift tid:</td>
    <td>
        <input id="from" name="start_day" required="" type="text"  autocomplete="off" /></td>
    <td>-</td>
    <td><input id="to" name="end_day" required="" type="text"  autocomplete="off" /></td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td> <select name="start_hour">
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
        </select></td>
    <td>&nbsp;</td>
    <td><select name="end_hour">
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
        </select></td>
</tr>
</table>
<br><br>
<input name="book" type="submit" value="Opprett skift" />
</form>
</td>

<td valign="top">
    <h3>Kanseller skift</h3>

    <form action="php/cancel.php" method="post">
        <p></p>
        <?php listboxForShiftID(); ?>
        <p><input name="cancel" type="submit" value="Kanseller" /></p>
    </form>

</td>
<td valign="top">
    <h3>Slett skift</h3>

    <form action="php/delete.php" method="post">
        <p></p>
        <?php listboxForShiftID(); ?>
        <p><input name="delete" type="submit" value="Slett" /></p>
    </form>

</td>
<td valign="top">
<h3>Lag ny bruker</h3>

<form method="post" action="php/newUser.php" enctype="multipart/form-data" id="createUser" name="createUser" onsubmit="#">
    Fornavn/Etternavn<input type="text" id="fname" name="fname"><br>
    Brukernavn<input type="text" id="username" name="username"><br>
    Passord<input type="password" id="password" name="password"><br>
    Telefonnr<input type="text" id="phone" name="phone"><br>

    <input type="checkbox" id="admin" name="admin" value="admin">
    <label for="admin"> Sett som admin? </label><br><br>
    <input name="find" type="submit" value="Lag ny bruker" />
</form>

</td>
</tr>
</table><br/>

<form method="post" action="" enctype="multipart/form-data" id="showUserSpecific" name="showUserSpecific" onsubmit="#">
    Filtrer etter bruker  <?php listboxForUserID2(); ?> <br/>
    <input name="find" type="submit" value="Vis skift" />
</form>
