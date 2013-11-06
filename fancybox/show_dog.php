<?php

date_default_timezone_set("Europe/Ljubljana");

if(isset($_GET['day'])) {
	$day = $_GET['day'];
} else {
	die("Prišlo je do napake!");
}

session_start();
$user_id = $_SESSION['login_id'];

include("../includes/mysql_connect.php");

//$query = "SELECT d.id, d.naziv, t.cas, u.up_ime, u.id as uid FROM `termini_dog` t, `dogodki` d, `uporabniki` u WHERE t.`datum` = FROM_UNIXTIME($day) AND t.dogodek_id = d.id AND d.organizator_id = u.id";
//$query = "SELECT * FROM `termini_view` WHERE `datum` = FROM_UNIXTIME($day) AND organizator_id = '$user_id'";

//$query = "SELECT * FROM `termini_view` t, udelezba u, osebe o WHERE (t.`organizator_id` = '$user_id' OR (o.uporabnik_id = '$user_id' AND u.oseba_id = o.id AND u.termin_id=t.termin_id)) AND t.`datum` = FROM_UNIXTIME('".mktime(0, 0, 0, $m, $d, $y)."')";

?>

<style type="text/css">

h2 {
	
}

.show_dog {
	width: 400px;
}

.termini {
	width: 100%;
}
.termini th {
	text-align: left;
}
</style>

<div class="show_dog">
<h2>Dogodki za <?php echo date("d.m.y", $day) ?></h2>
<?php 
	$query = "SELECT DISTINCT t.* FROM `termini_view` t, udelezba u, osebe o WHERE o.uporabnik_id = '$user_id' AND u.oseba_id = o.id AND u.termin_id=t.termin_id AND t.`datum` = FROM_UNIXTIME('$day')";	
	$sql = mysql_query($query) or die(mysql_error());
	if(mysql_num_rows($sql)) { ?>
		<h3>Dogodki</h3>
        <table class="termini">
        <?php 
            
            for($i=1;$row = mysql_fetch_assoc($sql);$i++) { ?>
            <tr>
            	<td>&nbsp;</td>
                <td><a href="dogodek?id=<?php echo $row['dogodek_id']; ?>"><?php echo $row['naziv']; ?></a></td>
                <td><?php echo substr($row['cas'], 0, 5); ?></td>
                <td><a href="uporabnik?id=<?php echo $row['organizator_id']; ?>"><?php echo $row['up_ime']; ?></a></td>
                <td><?php
                $osebe = mysql_query("SELECT o.* FROM osebe o, udelezba u, termini_dog t WHERE u.oseba_id = o.id AND u.termin_id = t.id AND t.id = '".$row['termin_id']."'");
                while($o = mysql_fetch_assoc($osebe)) {
                    echo $o['ime']." ".$o['priimek'].", ";
                } ?></td>
            </tr>
        <?php } ?>
        </table>
	<?php } ?>
    <?php 
	$d = date("d" , $day);
	$m = date("m" , $day);
	$y = date("Y", $day);
	$query = "SELECT *, YEAR(datum_rojstva) as 'y' FROM uporabniki WHERE DAY(datum_rojstva) = '$d' AND MONTH(datum_rojstva) = '$m'";	
	$sql = mysql_query($query) or die(mysql_error());
	if(mysql_num_rows($sql)) { ?>
		<h3>Rojstni dnevi uporabnikov</h3>
        <table class="termini">
        <?php 
            for($i=1;$row = mysql_fetch_assoc($sql);$i++) { ?>
            <tr>
            	<td>&nbsp;</td>
                <td width="100px"><a href="uporabnik?id=<?php echo $row['id']; ?>"><?php echo $row['up_ime']; ?></a></td>
                <td>Dopolnil bo <?php echo ($y - $row['y']); ?> let.</td>
            </tr>
        <?php } ?>
        </table>
	<?php }
	$query = "SELECT *, YEAR(datum_rojstva) as 'y' FROM osebe WHERE DAY(datum_rojstva) = '$d' AND MONTH(datum_rojstva) = '$m' AND uporabnik_id = '$user_id'";	
	$sql = mysql_query($query) or die(mysql_error());
	if(mysql_num_rows($sql)) { ?>
		<h3>Rojstni dnevi tvojih oseb</h3>
        <table class="termini">
        <?php 
            for($i=1;$row = mysql_fetch_assoc($sql);$i++) { ?>
            <tr>
            	<td>&nbsp;</td>
                <td width="100px"><a href="oseba?id=<?php echo $row['id']; ?>"><?php echo $row['ime']." ".$row['priimek']; ?></a></td>
                <td>Dopolnil bo <?php echo ($y - $row['y']); ?> let.</td>
            </tr>
        <?php } ?>
        </table>
	<?php } ?>
    
</div>