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
    <h2><?php echo $row_skupina['naziv']; ?></h2>
    
    <p>
    	Opomba: <?php echo $row_skupina['opomba']; ?>
    <table class="uporabniki">
          	<tr>
            	<th>Naziv</th>
            	<th>Spol</th>
            	<th>Datum Rojstva</th>
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
            </tr>
    	<?php }
			}?>
    </table>
    </p>


</div>