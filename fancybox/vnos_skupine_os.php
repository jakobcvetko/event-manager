<?php

date_default_timezone_set("Europe/Ljubljana");

session_start();
$user_id = $_SESSION['login_id'];

include("../includes/mysql_connect.php");

$query = "SELECT * FROM `osebe` WHERE uporabnik_id = '$user_id'";
$sql = mysql_query($query) or die(mysql_error());
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
    <h2>Ustvarjanje nove skupine oseb</h2>
    
    <form action="osebe" method="post">
    <p>
    	Naziv skupine: <input type="text" name="naziv" />
    </p>
    <p>
    	Opomba: <input type="text" name="opomba" />
    </p>
    <p>
    	Osebe:
    </p>
    <div class="dogodki">
    <?php while($row = mysql_fetch_assoc($sql)) { ?>
        <input type="checkbox" name="os[]" value="<?php echo $row['id']; ?>" /> <?php echo $row['ime']." ".$row['priimek']; ?><br />
    <?php } ?>
    </div>
    <input type="submit" name="dodaj_skupino" value="Ustvari skupino" />
    </form>


</div>