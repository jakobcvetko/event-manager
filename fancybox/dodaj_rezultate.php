<?php

date_default_timezone_set("Europe/Ljubljana");

session_start();
$user_id = $_SESSION['login_id'];

include("../includes/mysql_connect.php");

if(isset($_GET['ter_id']) && isset($_GET['os_id'])) {
	$ter_id = $_GET['ter_id'];
	$os_id = $_GET['os_id'];
} else {
	echo "Prišlo je do napake!";
}

	$ter_sql = mysql_query("SELECT * FROM termini_dog WHERE id = '$ter_id'");
	$ter = mysql_fetch_assoc($ter_sql);
	
	$os_sql = mysql_query("SELECT * FROM osebe WHERE id = '$os_id'");
	$os = mysql_fetch_assoc($os_sql);

?>

<style type="text/css">

h2 {
	
}

.show_dog {
	width: 400px;
	margin: 20px;
}
.dogodki {
	margin-left: 30px;
	margin-bottom: 30px;
}
</style>

<div class="show_dog">
    <h2>Dodajanje rezultatov za termin <?php echo $ter['datum']." ob ".substr($ter['cas'], 0, 5); ?> za <?php echo $os['ime']." ".$os['priimek']; ?></h2>
    
    <form action="dogodek?id=<?php echo $ter['dogodek_id']; ?>&ter_id=<?php echo $ter_id; ?>&os_id=<?php echo $os_id; ?>" method="post">
    <table width="100%">
    	<tr>
        	<th width="40%">Tip rezultata</th>
            <th></th>
        	<th width="40%">Rezultat</th>
        </tr>
    	<tr>
        	<td><input type="text" name="tip_1" /></td>
            <td align="center"><strong> : </strong></td>
        	<td><input type="text" name="rez_1" /></td>
        </tr>
    	<tr>
        	<td><input type="text" name="tip_2" /></td>
            <td align="center"><strong> : </strong></td>
        	<td><input type="text" name="rez_2" /></td>
        </tr>
    	<tr>
        	<td><input type="text" name="tip_3" /></td>
            <td align="center"><strong> : </strong></td>
        	<td><input type="text" name="rez_3" /></td>
        </tr>
    	<tr>
        	<td><input type="text" name="tip_4" /></td>
            <td align="center"><strong> : </strong></td>
        	<td><input type="text" name="rez_4" /></td>
        </tr>
    	<tr>
        	<td><input type="text" name="tip_5" /></td>
            <td align="center"><strong> : </strong></td>
        	<td><input type="text" name="rez_5" /></td>
        </tr>
    </table>
    <input type="submit" name="dodaj_rezultate" value="Dodaj" />
    </form>


</div>