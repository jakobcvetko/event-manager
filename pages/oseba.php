<?php
	$user_id = $_SESSION['login_id'];
	
	if(isset($_GET['id'])) {
		$oseba_id = $_GET['id'];
	} else {
		header("location: osebe");
	}
	
	$sql = mysql_query("SELECT * FROM osebe WHERE id = '$oseba_id'");
	$oseba = mysql_fetch_assoc($sql);
?>

      <div class="mainbar">
        <div class="article">
        <h1><?php echo $oseba['ime']." ".$oseba['priimek']; ?></h1>
          <p>
          	Opomba: <?php echo $oseba['opomba']; ?><br />
            Spol: <?php echo $oseba['spol']; ?><br />
            Datum rojstva: <?php echo $oseba['datum_rojstva']; ?><br />
          </p>
        </div>
      </div>