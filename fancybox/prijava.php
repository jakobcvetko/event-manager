<?php

date_default_timezone_set("Europe/Ljubljana");

if(isset($_GET['ter'])) {
	$ter_id = $_GET['ter'];
} else {
	die("Prišlo je do napake!");
}

session_start();
$user_id = $_SESSION['login_id'];

include("../includes/mysql_connect.php");

$query = "SELECT d.*, UNIX_TIMESTAMP(t.datum) as 'phpdate', t.cas FROM `dogodki` d, `termini_dog` t WHERE t.id = '$ter_id' AND t.dogodek_id = d.id";
$sql = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_assoc($sql) or die("error");
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
<link type="text/css" href="../style/costum.css" />

<div class="show_dog">
    <h3>Prijava na <?php echo $row['naziv'].", ".$row['kraj'].", ".$row['drzava']; ?> za termin <?php echo date("d.m.Y", $row['phpdate']); ?> ob <?php echo substr($row['cas'], 0, 5); ?> uri</h3>
    
    <form action="prijava?ter=<?php echo $ter_id; ?>" method="post">
    <p>
    	<strong>Prijavi skupine:</strong> 
        <table class="uporabniki">
            <tr>
            	<th width="20px">Prijava</th>
            	<th width="20px">Odjava</th>
                <th>Skupina</th>
            </tr>
        	<?php 
			$query = "SELECT * FROM skupine_oseb WHERE uporabnik_id = '$user_id'";
			$sql = mysql_query($query);
			if(mysql_num_rows($sql) == 0) { ?>
            	<tr>
                	<td colspan="3">Ni najdenih skupin...</td>
                </tr>
				<?php } else {
			while($row = mysql_fetch_assoc($sql)) { 
			?>
            	<tr>
                	<td><input type="checkbox" name="prijava_sk[]" value="<?php echo $row['id']; ?>" /></td>
                	<td><input type="checkbox" name="odjava_sk[]" value="<?php echo $row['id']; ?>" /></td>
                	<td><?php echo $row['naziv']; ?></td>
                </tr>
            <?php } } ?>
        </table>
    </p>
    <p>
    	<strong>Prijavi posamezne osebe:</strong> 
        <table class="uporabniki">
            <tr>
            	<th width="20px">Prijava</th>
            	<th width="20px">Odjava</th>
                <th>Oseba</th>
                <th></th>
            </tr>
        	<?php 
			$query = "SELECT * FROM osebe WHERE uporabnik_id = '$user_id'";
			$sql = mysql_query($query);
			if(mysql_num_rows($sql) == 0) { ?>
                Ni najdenih oseb...
            <?php } else {
			while($row = mysql_fetch_assoc($sql)) { 
			$pr = ((mysql_num_rows(mysql_query("SELECT * FROM `udelezba` WHERE termin_id = '$ter_id' AND oseba_id = '".$row['id']."'"))) == 1 ? true : false);
			?>
            	<tr>
                	<td><input type="checkbox" name="prijava_os[]" value="<?php echo $row['id']; ?>" <?php echo ($pr) ? "disabled='disabled'" : ""; ?> /></td>
                	<td><input type="checkbox" name="odjava_os[]" value="<?php echo $row['id']; ?>" <?php echo ($pr) ? "" : "disabled='disabled'"; ?> /></td>
                	<td><a href="oseba?id=<?php echo $row['id']; ?>"><?php echo $row['ime']." ".$row['priimek']; ?></a></td>
                    <td><?php if($pr) { ?><a class="fancybox_fly" href="fancybox/dodaj_rezultate.php?ter_id=<?php echo $ter_id; ?>&os_id=<?php echo $row['id']; ?>">Dodaj rezultate</a><?php } ?></td>
                </tr>
            <?php } } ?>
        </table>
    </p>
    <input type="submit" name="prijavi" value="Potrdi" />
    <input type="button" value="Prekliči" onclick="$.fancybox.close();" />
    </form>


</div>
<script type="text/javascript">
	$(".fancybox_fly").fancybox();
</script>