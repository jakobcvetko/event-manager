<?php

date_default_timezone_set("Europe/Ljubljana");

session_start();
$user_id = $_SESSION['login_id'];

if(isset($_GET['edit'])) {
	$dog_id = $_GET['edit'];
} else {
	die("Prišlo je do napake!");
}

include("../includes/mysql_connect.php");

$sql = mysql_query("SELECT * FROM dogodki WHERE id = '$dog_id'");
$dogodek = mysql_fetch_assoc($sql);

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
    <h2>Urejanje dogodka</h2>
    
    <form action="dogodki?id_dog=<?php echo $dogodek['id']; ?>" method="post">
    <table width="100%">
    	<tr>
        	<td>Naziv:</td>
        	<td><input type="text" name="naziv_dog" value="<?php echo $dogodek['naziv']; ?>" /></td>
        </tr>
    	<tr>
        	<td>Kraj:</td>
        	<td><input type="text" name="kraj_dog" value="<?php echo $dogodek['kraj']; ?>" /></td>
        </tr>
    	<tr>
        	<td>Drzava:</td>
        	<td><input type="text" name="drzava_dog" value="<?php echo $dogodek['drzava']; ?>" /></td>
        </tr>
    	<tr>
        	<td>Opomba:</td>
        	<td><input type="text" name="opomba_dog" value="<?php echo $dogodek['opomba']; ?>" /></td>
        </tr>
        
    </table>
    <h3>Termini</h3>
    <table width="100%">
    	<tr>
        	<th>Termin</th>
        	<th>Izbriši</th>
        </tr>
    <?php
	$sql = mysql_query("SELECT * FROM termini_dog WHERE dogodek_id = '$dog_id'");
	while($row = mysql_fetch_assoc($sql)) { ?>
    	<tr>
        	<td><?php echo $row['datum']." ob ".$row['cas']; ?></td>
        	<td><input type="checkbox" name="termini[]" value="<?php echo $row['id']; ?>" /></td>
        </tr>
	<?php } ?>
    	<tr>
        	<td colspan="2"><input type="submit" name="posodobi_dog" value="Shrani" /></td>
        </tr>
    </table>
    </form>


</div>