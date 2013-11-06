<?php

date_default_timezone_set("Europe/Ljubljana");

session_start();
$user_id = $_SESSION['login_id'];

if(isset($_GET['edit'])) {
	$oseba_id = $_GET['edit'];
} else {
	die("Prišlo je do napake!");
}

include("../includes/mysql_connect.php");

$sql = mysql_query("SELECT *, UNIX_TIMESTAMP(datum_rojstva) as 'phpdate' FROM osebe WHERE id = '$oseba_id'");
$oseba = mysql_fetch_assoc($sql);

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
    <h2>Urejanje osebe</h2>
    
    <form action="osebe?id_os=<?php echo $oseba['id']; ?>" method="post">
    <table width="100%">
    	<tr>
        	<td>Spol:</td>
        	<td><select name="spol_os">
            		<option <?php echo ($oseba['spol'] == 'm') ? "selected='selected'" : ""; ?> value="m">Moški</option>
            		<option <?php echo ($oseba['spol'] == 'z') ? "selected='selected'" : ""; ?> value="z">Ženska</option>
            	</select>
            </td>
        </tr>
    	<tr>
        	<td>Ime:</td>
        	<td><input type="text" name="ime_os" value="<?php echo $oseba['ime']; ?>" /></td>
        </tr>
    	<tr>
        	<td>Priimek:</td>
        	<td><input type="text" name="priimek_os" value="<?php echo $oseba['priimek']; ?>" /></td>
        </tr>
    	<tr>
        	<td>Opomba:</td>
        	<td><input type="text" name="opomba_os" value="<?php echo $oseba['opomba']; ?>" /></td>
        </tr>
    	<tr>
        	<td>Datum rojstva:</td>
        	<td><input type="text" name="datum_r_os" class="datum_rojstva" value="<?php echo date("d.m.Y", $oseba['phpdate']); ?>" /></td>
        </tr>
    	<tr>
        	<td colspan="2"><input type="submit" name="posodobi_osebo" value="Shrani" /></td>
        </tr>
        
    </table>
    </form>


</div>
<script type="text/javascript">
	$(".datum_rojstva").datepicker({
			dateFormat: 'dd.mm.yy',
			changeMonth: true,
			changeYear: true
		});
</script>