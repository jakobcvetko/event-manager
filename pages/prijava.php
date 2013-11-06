<?php
	$user_id = $_SESSION['login_id'];

	if(isset($_GET['ter'])) {
		$ter_id = $_GET['ter'];
		$query = "SELECT dogodek_id FROM termini_dog WHERE id = $ter_id";
		$sql = mysql_query($query);
		$row = mysql_fetch_assoc($sql);
		$dog_id = $row['dogodek_id'];
	} else {
		# header("location: home");
		echo "Something wrong! Click here: <a href='home'>Home</a>";
	}

	if(isset($_POST['prijavi'])) {
		//prijava_sk[] --> skupine
		//prijava_os[] --> osebe

		if(isset($_POST['prijava_sk'])) { //Prijavljamo skupine

			$skupine = $_POST['prijava_sk'];
			foreach($skupine as $s) {
				$sql_s = mysql_query("SELECT * FROM osebe_po_skupinah WHERE skupina_id = '$s'");
				while($row_s = mysql_fetch_assoc($sql_s)) {
					$o = $row_s['oseba_id'];
					$query = "INSERT INTO udelezba (oseba_id, termin_id) VALUES ('$o', '$ter_id')";
					mysql_query($query);
				}
			}
		}
		if(isset($_POST['odjava_sk'])) { //Odjavljamo skupine

			$skupine = $_POST['odjava_sk'];
			foreach($skupine as $s) {
				$sql_s = mysql_query("SELECT * FROM osebe_po_skupinah WHERE skupina_id = '$s'");
				while($row_s = mysql_fetch_assoc($sql_s)) {
					$o = $row_s['oseba_id'];
					$query = "DELETE FROM udelezba WHERE oseba_id = '$o' AND termin_id = '$ter_id'";
					mysql_query($query) or die(mysql_error());
				}
			}
		}

		if(isset($_POST['prijava_os'])) { //Prijavljamo posamezne osebe
			$osebe = $_POST['prijava_os'];
			foreach($osebe as $o) {
				$query = "INSERT INTO udelezba (oseba_id, termin_id) VALUES ('$o', '$ter_id')";
				mysql_query($query);
			}
		}
		if(isset($_POST['odjava_os'])) { //Odjavljamo posamezne osebe
			$osebe = $_POST['odjava_os'];
			foreach($osebe as $o) {
					$query = "DELETE FROM udelezba WHERE oseba_id = '$o' AND termin_id = '$ter_id'";
				mysql_query($query) or die(mysql_error());
			}
		}

		# header("location: dogodek?id=$dog_id");
		echo "You should not be here! Click here: <a href='home'>Home</a>";
	}

?>