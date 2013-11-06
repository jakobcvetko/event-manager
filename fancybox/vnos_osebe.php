<?php

date_default_timezone_set("Europe/Ljubljana");

session_start();
$user_id = $_SESSION['login_id'];

include("../includes/mysql_connect.php");
/*
$query = "SELECT * FROM `dogodki` WHERE organizator_id = '$user_id'";
$sql = mysql_query($query) or die(mysql_error());
*/
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
    <h2>Ustvarjanje nove osebe</h2>
    
    <form action="osebe" method="post">
    <table width="100%">
    	<tr>
        	<td>Spol:</td>
        	<td><select name="spol_os">
            		<option value="m">Moški</option>
            		<option value="z">Ženska</option>
            	</select>
            </td>
        </tr>
    	<tr>
        	<td>Ime:</td>
        	<td><input type="text" name="ime_os" /></td>
        </tr>
    	<tr>
        	<td>Priimek:</td>
        	<td><input type="text" name="priimek_os" /></td>
        </tr>
    	<tr>
        	<td>Datum rojstva:</td>
        	<td><input type="text" name="datum_r_os" class="datum_rojstva" /></td>
        </tr>
    	<tr>
        	<td colspan="2"><input type="submit" name="dodaj_osebo" value="Shrani" /></td>
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
	$(".datum_rojstva").datepicker("setDate", new Date(1990, 1, 1));
</script>