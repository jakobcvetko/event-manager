<?php
	$user_id = $_SESSION['login_id'];
	
	if(isset($_POST['dodaj_osebo'])) {
		$ime = $_POST['ime_os'];
		$priimek = $_POST['priimek_os'];
		$spol = $_POST['spol_os'];
		$datum_r = explode(".", mysql_real_escape_string($_POST['datum_r_os']));
		$datum_r_s = mktime(0, 0, 0, $datum_r[1], $datum_r[0], $datum_r[2]);
		
		if($ime != "" && $priimek != "") {
			$query = "INSERT INTO `osebe` (uporabnik_id, ime, priimek, datum_rojstva, spol) VALUES ('$user_id', '$ime', '$priimek', FROM_UNIXTIME('$datum_r_s'), '$spol')";
			mysql_query($query) or die(mysql_error());
		}
	}
	
	if(isset($_GET['del'])) {
		$os_id = mysql_real_escape_string($_GET['del']);
		$query = "SELECT * FROM `osebe` WHERE id = '$os_id'";
		$sql = mysql_query($query);
		
		if(mysql_num_rows($sql) > 0) {
			$row = mysql_fetch_assoc($sql);
			if($user_id == $row['uporabnik_id']) {
				$query = "DELETE FROM `osebe` WHERE id = '$os_id'";
				mysql_query($query) or die(mysql_error());
			}
		}
	}
	if(isset($_GET['del_sk'])) {
		$sk_id = mysql_real_escape_string($_GET['del_sk']);
		$query = "SELECT * FROM `skupine_oseb` WHERE id = '$sk_id'";
		$sql = mysql_query($query);
		
		if(mysql_num_rows($sql) > 0) {
			$row = mysql_fetch_assoc($sql);
			if($user_id == $row['uporabnik_id']) {
				$query = "DELETE FROM `skupine_oseb` WHERE id = '$sk_id'";
				mysql_query($query) or die(mysql_error());
			}
		}
	}
	if(isset($_POST['dodaj_skupino'])) {
		
		if($_POST['naziv'] != "" && isset($_POST['os'])) {
			$naziv = $_POST['naziv'];
			$osebe = $_POST['os'];
			$opomba = $_POST['opomba'];
			
			$query = "INSERT INTO `skupine_oseb` (uporabnik_id, naziv, opomba) VALUES ('$user_id', '$naziv', '$opomba')";
			mysql_query($query) or die(mysql_error());
			
			$skupina_id = mysql_insert_id();
			foreach($osebe as $oseba) {				
				$query = "INSERT INTO `osebe_po_skupinah` (skupina_id, oseba_id) VALUES ('$skupina_id', '$oseba')";
				mysql_query($query) or die(mysql_error());
			}
		}

	}
	
	if(isset($_POST['potrdi_spremembo_skupine'])) {
		if(isset($_GET['id_sk'])) {
			$id_skupine = $_GET['id_sk'];
			
			$naziv = $_POST['naziv'];
			$opomba = $_POST['opomba'];
			
			if($naziv != "") {
				mysql_query("UPDATE skupine_oseb SET naziv = '$naziv', opomba = '$opomba' WHERE id = '$id_skupine'");
				
				if(isset($_POST['izbrisi_os'])) {
					$osebe = $_POST['izbrisi_os'];
					foreach($osebe as $o) {
						mysql_query("DELETE FROM osebe_po_skupinah WHERE skupina_id = '$id_skupine' AND oseba_id = '$o'");
					}
				}
				if(isset($_POST['dodaj_os'])) {
					$osebe = $_POST['dodaj_os'];
					foreach($osebe as $o) {
						mysql_query("INSERT INTO osebe_po_skupinah (skupina_id, oseba_id) VALUES ('$id_skupine', '$o')");
					}
				}
				
				
			}
		}
	}
	if(isset($_POST['posodobi_osebo'])) {
		if(isset($_GET['id_os'])) {
			$id_osebe = $_GET['id_os'];
			$ime = $_POST['ime_os'];
			$priimek = $_POST['priimek_os'];
			$opomba = $_POST['opomba_os'];
			$spol = $_POST['spol_os'];
			$datum_r = explode(".", mysql_real_escape_string($_POST['datum_r_os']));
			$datum_r_s = mktime(0, 0, 0, $datum_r[1], $datum_r[0], $datum_r[2]);
			
			if($ime != "" && $priimek != "") {
				$query = "UPDATE `osebe` SET ime='$ime', priimek='$priimek', datum_rojstva=FROM_UNIXTIME('$datum_r_s'), spol='$spol', opomba='$opomba'  WHERE id ='$id_osebe'";
				mysql_query($query) or die(mysql_error());
			}
		}
	}
	
?>

      <div class="mainbar">
        <div class="article">
        <h1>Skupine oseb</h1>
          <table class="uporabniki">
          	<tr>
            	<th width="60%">Skupina</th>
            	<th>Opombe</th>
            	<th width="15%">Urejanje</th>
            </tr>
            <?php
				
				$query = "SELECT * FROM `skupine_oseb` WHERE `uporabnik_id` = '$user_id'";
				$sql = mysql_query($query) or die(mysql_error());
				
				if(mysql_num_rows($sql) == 0) { ?>
                	<tr>
                    	<td colspan="5" class="no_results">Ni najdenih skupin...</td>
                    </tr>
				<?php } else {
					while($row = mysql_fetch_assoc($sql)) { ?>                	
						<tr>
							<td><a class="fancybox_item" href="fancybox/skupina_os.php?id=<?php echo $row['id']; ?>"><?php echo $row['naziv']; ?></a></td>
							<td><?php echo $row['opomba']; ?></td>
							<td><a class="fancybox_item" href="fancybox/skupina_os_edit.php?id=<?php echo $row['id']; ?>"><img src="images/icons/edit.png" /></a>
                            	<a href="osebe?del_sk=<?php echo $row['id']; ?>"><img src="images/icons/delete.png" /></a></td>
						</tr>
				<?php } 
				} ?>
          </table>
          <p><a class="fancybox_item" href="fancybox/vnos_skupine_os.php"> + Kliknite tukaj za ustvarjanje nove skupine</a></p>
          
        	<h1>Lista Mojih Oseb</h1>
          <table class="uporabniki">
          	<tr>
            	<th>Ime in Priimek</th>
            	<th>Spol</th>
            	<th>Datum rojstva</th>
            	<th width="15%">Urejanje</th>
            </tr>
            <?php
			
				
				$query = "SELECT *, UNIX_TIMESTAMP(datum_rojstva) as 'phpdate' FROM `osebe` WHERE `uporabnik_id` = '$user_id'";
				$sql = mysql_query($query);
				
				if(mysql_num_rows($sql) == 0) { ?>
                	<tr>
                    	<td colspan="5" class="no_results">Ni oseb v listi...</td>
                    </tr>
				<?php } else {
				while($row = mysql_fetch_assoc($sql)) { ?>                	
                    <tr>
                        <td><a href="oseba?id=<?php echo $row['id']; ?>"><?php echo $row['ime']." ".$row['priimek']; ?></a></td>
                        <td><?php echo ($row['spol'] == 'z' ? "Ženska": "Moški"); ?></td>
                        <td><?php echo date("M jS, Y", $row['phpdate']); ?></td>
                        <td><a class="fancybox_item" href="fancybox/oseba_edit.php?edit=<?php echo $row['id']; ?>"><img src="images/icons/edit.png" /></a>
                            <a href="osebe?del=<?php echo $row['id']; ?>"><img src="images/icons/delete.png" /></a></td>
                    </tr>
					<?php }
                    }?>
          </table>
          <p><a class="fancybox_item" href="fancybox/vnos_osebe.php"> + Kliknite tukaj za dodajanje novih oseb</a></p>
        </div>
      </div>