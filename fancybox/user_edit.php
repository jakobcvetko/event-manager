<?php

date_default_timezone_set("Europe/Ljubljana");

session_start();
$user_id = $_SESSION['login_id'];

include("../includes/mysql_connect.php");

$sql = mysql_query("SELECT *, UNIX_TIMESTAMP(datum_rojstva) as 'phpdate' FROM uporabniki WHERE id = '$user_id'");
$uporabnik = mysql_fetch_assoc($sql);

$naslov = str_replace("<br />", "\r\n", $uporabnik['naslov']);

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
    <h2>Urejanje profila <?php echo $uporabnik['up_ime']; ?></h2>
    
    <form action="home" method="post" enctype="multipart/form-data">
    <table width="100%">
    	<tr>
        	<td>Spremeni sliko:</td>
        	<td><input type="file" name="slika" id="slika" class="img_check" /></td>
        </tr>
    	<tr>
        	<td>Ime:</td>
        	<td><input type="text" name="ime" value="<?php echo $uporabnik['ime']; ?>" /></td>
        </tr>
    	<tr>
        	<td>Priimek:</td>
        	<td><input type="text" name="priimek" value="<?php echo $uporabnik['priimek']; ?>" /></td>
        </tr>
    	<tr>
        	<td>Spol:</td>
        	<td><select name="spol">
            		<option <?php echo ($uporabnik['spol'] == 'm') ? "selected='selected'" : ""; ?> value="m">Moški</option>
            		<option <?php echo ($uporabnik['spol'] == 'z') ? "selected='selected'" : ""; ?> value="z">Ženska</option>
            	</select>
        </tr>
    	<tr>
        	<td>Datum rojstva:</td>
        	<td><input type="text" name="datum_r" class="datum_rojstva" value="<?php echo ($uporabnik['phpdate'] == "") ? "" : date("d.m.Y", $uporabnik['phpdate']); ?>" /></td>
        </tr>
    	<tr>
        	<td>Email:</td>
        	<td><input type="text" name="email" value="<?php echo $uporabnik['email']; ?>" /></td>
        </tr>
    	<tr>
        	<td>Naslov:</td>
        	<td><textarea name="naslov" rows="3"><?php echo $naslov; ?></textarea></td>
        </tr>
    	<tr>
        	<td>Telefon:</td>
        	<td><input type="text" name="telefon" value="<?php echo $uporabnik['telefon']; ?>" /></td>
        </tr>
    	<tr>
        	<td>Geslo(Če ga želite spremeniti):</td>
        	<td><input type="password" name="geslo" /></td>
        </tr>
    	<tr>
        	<td>Ponovi Geslo:</td>
        	<td><input type="password" name="p_geslo" /></td>
        </tr>
    	<tr>
        	<td colspan="2"><input type="submit" name="posodobi" value="Shrani" /></td>
        </tr>
        
    </table>
    </form>


</div>
<script type="text/javascript">
	$(".datum_rojstva").datepicker({
			dateFormat: 'dd.mm.yy',
			changeMonth: true,
			changeYear: true,
			yearRange: '1900:2012'
		});
	$(".img_check").change(function() {
		var ext = this.value;
		ext = ext.substring(ext.length-3,ext.length);
		ext = ext.toLowerCase();
		if(ext != 'jpg' && ext != 'png' && ext != 'gif' && ext != 'jpeg') {
			alert('You selected a .'+ext+' file; please select a .jpg file instead!');
			this.value = "";
		}
	});
</script>