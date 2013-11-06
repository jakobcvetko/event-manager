<?php

date_default_timezone_set("Europe/Ljubljana");

if(isset($_GET['id'])) {
	$skupina_id = $_GET['id'];
} else {
	die("Prišlo je do napake!");
}


session_start();
$user_id = $_SESSION['login_id'];

include("../includes/mysql_connect.php");

$query = "	SELECT * FROM `skupine_oseb` WHERE id = '$skupina_id'";
$sql = mysql_query($query) or die(mysql_error());
$row_skupina = mysql_fetch_assoc($sql);
?>
<link type="text/css" href="../style/costum.css" />
<style type="text/css">

h2 {
	
}

.show_dog {
	width: 400px;
	margin: 20px;
}
.dogodki {
	margin-bottom: 30px;
}
</style>

<div class="show_dog">
    <div class="dogodki">
    <h2>Urejanje Skupine</h2>
    <form action="osebe?id_sk=<?php echo $skupina_id; ?>" method="post">
    <p>
    	Naziv: <input type="text" value="<?php echo $row_skupina['naziv']; ?>" name="naziv" /><br />
    	Opomba: <input type="text" value="<?php echo $row_skupina['opomba']; ?>" name="opomba" />
    </p>
    <h3>Osebe v skupini</h3>
    <table class="uporabniki">
          	<tr>
            	<th>Ime in Priimek</th>
            	<th>Spol</th>
            	<th>Datum Rojstva</th>
            	<th>Odstrani</th>
            </tr>
    <?php 
		$query = "SELECT o.*, UNIX_TIMESTAMP(o.datum_rojstva) as 'phpdate' FROM osebe o, osebe_po_skupinah ops WHERE ops.skupina_id = '$skupina_id' AND ops.oseba_id = o.id";
		$sql = mysql_query($query);
		
		if(mysql_num_rows($sql) == 0) { ?>
                <tr>
                    <td colspan="5" class="no_results">Ni najdenih oseb...</td>
                </tr>
            <?php } else {
		while($row = mysql_fetch_assoc($sql)) { ?>
        	<tr>
                <td><a href="oseba?id=<?php echo $row['id']; ?>"><?php echo $row['ime']." ".$row['priimek'];; ?></a></td>
                <td><?php echo ($row['spol'] == 'z' ? "Ženska": "Moški"); ?></td>
                <td><?php echo date("M jS, Y", $row['phpdate']); ?></td>
                <td><input type="checkbox" value="<?php echo $row['id']; ?>" name="izbrisi_os[]" /></td>
            </tr>
    	<?php }
			}?>
    </table>
    <h3>Dodaj osebe v skupino:</h3>
    <table class="uporabniki">
          	<tr>
            	<th>Ime in Priimek</th>
            	<th>Spol</th>
            	<th>Datum Rojstva</th>
            	<th>Dodaj</th>
            </tr>
    <?php
		$sql = mysql_query("SELECT *, UNIX_TIMESTAMP(datum_rojstva) as 'phpdate' FROM `osebe` WHERE uporabnik_id = '$user_id'") or die(mysql_error()); 
		while($row = mysql_fetch_assoc($sql)) { 
			$os_sql = mysql_query("SELECT * FROM osebe_po_skupinah WHERE skupina_id = '$skupina_id' AND oseba_id = '".$row['id']."'");
			if(mysql_num_rows($os_sql) == 0) {
		?>
        	<tr>
                <td><a href="oseba?id=<?php echo $row['id']; ?>"><?php echo $row['ime']." ".$row['priimek'];; ?></a></td>
                <td><?php echo ($row['spol'] == 'z' ? "Ženska": "Moški"); ?></td>
                <td><?php echo date("M jS, Y", $row['phpdate']); ?></td>
                <td><input type="checkbox" value="<?php echo $row['id']; ?>" name="dodaj_os[]" /></td>
            </tr>
    <?php } } ?>
    	</table>
    	<input type="submit" name="potrdi_spremembo_skupine" value="Shrani" />
    </form>
    </div>
</div>