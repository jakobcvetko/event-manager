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

$query = "	SELECT * FROM `skupine_dog` WHERE id = '$skupina_id'";
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
    <form action="dogodki?id_sk=<?php echo $skupina_id; ?>" method="post">
    <p>
    	Naziv: <input type="text" value="<?php echo $row_skupina['naziv']; ?>" name="naziv" /><br />
    	Opomba: <input type="text" value="<?php echo $row_skupina['opomba']; ?>" name="opomba" />
    </p>
    <h3>Dogodki v skupini</h3>
    <table class="uporabniki">
          	<tr>
            	<th>Naziv</th>
            	<th>Kraj</th>
            	<th>Drzava</th>
            	<th>Odstrani</th>
            </tr>
    <?php 
		$query = "SELECT d.* FROM dogodki d, dogodki_po_skupinah dps WHERE dps.skupina_id = '$skupina_id' AND dps.dogodek_id = d.id";
		$sql = mysql_query($query);
		
		if(mysql_num_rows($sql) == 0) { ?>
                <tr>
                    <td colspan="5" class="no_results">Ni najdenih dogodkov...</td>
                </tr>
            <?php } else {
		while($row = mysql_fetch_assoc($sql)) { ?>
        	<tr>
                <td><a href="dogodek?id=<?php echo $row['id']; ?>"><?php echo $row['naziv']; ?></a></td>
                <td><?php echo $row['kraj']; ?></td>
                <td><?php echo $row['drzava']; ?></td>
                <td><input type="checkbox" value="<?php echo $row['id']; ?>" name="izbrisi_dog[]" /></td>
            </tr>
    	<?php }
			}?>
    </table>
    <h3>Dodaj dogodke v skupino:</h3>
    <table class="uporabniki">
          	<tr>
            	<th>Naziv</th>
            	<th>Kraj</th>
            	<th>Drzava</th>
            	<th>Dodaj</th>
            </tr>
    <?php
		$sql = mysql_query("SELECT * FROM `dogodki` WHERE organizator_id = '$user_id'") or die(mysql_error()); 
		while($row = mysql_fetch_assoc($sql)) { 
			$os_sql = mysql_query("SELECT * FROM dogodki_po_skupinah WHERE skupina_id = '$skupina_id' AND dogodek_id = '".$row['id']."'");
			if(mysql_num_rows($os_sql) == 0) {
		?>
        	<tr>
                <td><a href="dogodek?id=<?php echo $row['id']; ?>"><?php echo $row['naziv']; ?></a></td>
                <td><?php echo $row['kraj']; ?></td>
                <td><?php echo $row['drzava']; ?></td>
                <td><input type="checkbox" value="<?php echo $row['id']; ?>" name="dodaj_dog[]" /></td>
            </tr>
    <?php } } ?>
    	</table>
    	<input type="submit" name="potrdi_spremembo_skupine" value="Shrani" />
    </form>
    </div>
</div>