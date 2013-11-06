<?php
	$user_id = $_SESSION['login_id'];
 
	if(isset($_GET['del'])) {
		$dog_id = mysql_real_escape_string($_GET['del']);
		$query = "SELECT * FROM `dogodki` WHERE id = '$dog_id'";
		$sql = mysql_query($query);
		
		if(mysql_num_rows($sql) > 0) {
			$row = mysql_fetch_assoc($sql);
			if($user_id == $row['organizator_id']) {
				$query = "DELETE FROM `dogodki` WHERE id = '$dog_id'";
				mysql_query($query) or die(mysql_error());
			}
		}
	}
	if(isset($_GET['del_sk'])) {
		$sk_id = mysql_real_escape_string($_GET['del_sk']);
		$query = "SELECT * FROM `skupine_dog` WHERE id = '$sk_id'";
		$sql = mysql_query($query);
		
		if(mysql_num_rows($sql) > 0) {
			$row = mysql_fetch_assoc($sql);
			if($user_id == $row['uporabnik_id']) {
				$query = "DELETE FROM `skupine_dog` WHERE id = '$sk_id'";
				mysql_query($query) or die(mysql_error());
			}
		}
	}
	 
	if(isset($_POST['dodaj_skupino'])) {
		
		if($_POST['naziv'] != "" && isset($_POST['dog'])) {
			$naziv = $_POST['naziv'];
			$dogodki = $_POST['dog'];
			$opomba = $_POST['opomba'];
			
			$query = "INSERT INTO `skupine_dog` (uporabnik_id, naziv, opomba) VALUES ('$user_id', '$naziv', '$opomba')";
			mysql_query($query) or die(mysql_error());
			
			$skupina_id = mysql_insert_id();
			foreach($dogodki as $dogodek) {				
				$query = "INSERT INTO `dogodki_po_skupinah` (skupina_id, dogodek_id) VALUES ('$skupina_id', '$dogodek')";
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
				mysql_query("UPDATE skupine_dog SET naziv = '$naziv', opomba = '$opomba' WHERE id = '$id_skupine'");
				
				if(isset($_POST['izbrisi_dog'])) {
					$dog = $_POST['izbrisi_dog'];
					foreach($dog as $d) {
						mysql_query("DELETE FROM dogodki_po_skupinah WHERE skupina_id = '$id_skupine' AND dogodek_id = '$d'");
					}
				}
				if(isset($_POST['dodaj_dog'])) {
					$dog = $_POST['dodaj_dog'];
					foreach($dog as $d) {
						mysql_query("INSERT INTO dogodki_po_skupinah (skupina_id, dogodek_id) VALUES ('$id_skupine', '$d')");
					}
				}
				
				
			}
		}
	}
	if(isset($_POST['posodobi_dog'])) {
		if(isset($_GET['id_dog'])) {
			$id_dog = $_GET['id_dog'];
			
			$naziv = $_POST['naziv_dog'];
			$kraj = $_POST['kraj_dog'];
			$drzava = $_POST['drzava_dog'];
			$opomba = $_POST['opomba_dog'];
			
			if($naziv != "" && $kraj != "" && $drzava != "") {
				$query = "UPDATE `dogodki` SET naziv='$naziv', kraj='$kraj', drzava='$drzava', opomba='$opomba'  WHERE id ='$id_dog'";
				mysql_query($query) or die(mysql_error());
				
				if(isset($_POST['termini'])) {
					$termini = $_POST['termini'];
					
					foreach($termini as $t) {
						mysql_query("DELETE FROM `termini_dog` WHERE id = '$t'");
					}
				}
			}
		}
	}
?>
     
     
      <div class="mainbar">
        <div class="article">
        	<h1>Skupine dogodkov</h1>
          <table class="uporabniki">
          	<tr>
            	<th width="60%">Skupina</th>
            	<th>Opombe</th>
            	<th width="15%">Urejanje</th>
            </tr>
            <?php
				
				$query = "SELECT * FROM `skupine_dog` WHERE `uporabnik_id` = '$user_id'";
				$sql = mysql_query($query) or die(mysql_error());
				
				if(mysql_num_rows($sql) == 0) { ?>
                	<tr>
                    	<td colspan="5" class="no_results">Ni najdenih skupin...</td>
                    </tr>
				<?php } else {
					while($row = mysql_fetch_assoc($sql)) { ?>                	
						<tr>
							<td><a class="fancybox_item" href="fancybox/skupina_dog.php?id=<?php echo $row['id']; ?>"><?php echo $row['naziv']; ?></a></td>
							<td><?php echo $row['opomba']; ?></td>
							<td><a class="fancybox_item" href="fancybox/skupina_dog_edit.php?id=<?php echo $row['id']; ?>"><img src="images/icons/edit.png" /></a>
                            	<a href="dogodki?del_sk=<?php echo $row['id']; ?>"><img src="images/icons/delete.png" /></a></td>
						</tr>
				<?php } 
				} ?>
          </table>
          <p><a class="fancybox_item" href="fancybox/vnos_skupine_dog.php"> + Kliknite tukaj za ustvarjanje nove skupine</a></p>
          
          <h1>Vsi Moji Dogodki</h1>
          <table class="uporabniki">
          	<tr>
            	<th>Naziv</th>
            	<th>Kraj</th>
            	<th>Drzava</th>
            	<th width="15%">Urejanje</th>
            </tr>
            <?php
				
				$query = "SELECT `dogodki`.* FROM `dogodki` WHERE `organizator_id` = '$user_id'";
				$sql = mysql_query($query) or die(mysql_error());
				
				if(mysql_num_rows($sql) == 0) { ?>
                	<tr>
                    	<td colspan="5" class="no_results">Ni najdenih dogodkov...</td>
                    </tr>
				<?php } else {
					while($row = mysql_fetch_assoc($sql)) { ?>                	
						<tr>
							<td><a href="dogodek?id=<?php echo $row['id']; ?>"><?php echo $row['naziv']; ?></a></td>
							<td><?php echo $row['kraj']; ?></td>
							<td><?php echo $row['drzava']; ?></td>
							<td><a class="fancybox_item" href="fancybox/dogodek_edit.php?edit=<?php echo $row['id']; ?>"><img src="images/icons/edit.png" /></a>
                            	<a href="dogodki?del=<?php echo $row['id']; ?>"><img src="images/icons/delete.png" /></a></td>
						</tr>
				<?php } 
				} ?>
          </table>
          <p><a href="vnos_dogodka"> + Kliknite tukaj za ustvarjanje dogodka</a></p>
        	<h1>Dogodki drugih uporabnikov</h1>
          <table class="uporabniki">
          	<tr>
            	<th>Naziv</th>
            	<th>Kraj</th>
            	<th>Drzava</th>
            	<th>Organizator</th>
            </tr>
            <?php
				
				$query = "SELECT `dogodki`.*, u.up_ime FROM `dogodki`, `uporabniki` u WHERE NOT (`organizator_id` = '$user_id') AND `organizator_id` = u.id";
				$sql = mysql_query($query) or die(mysql_error());
				
				if(mysql_num_rows($sql) == 0) { ?>
                	<tr>
                    	<td colspan="5" class="no_results">Ni najdenih dogodkov...</td>
                    </tr>
				<?php } else {
					while($row = mysql_fetch_assoc($sql)) { ?>                	
						<tr>
							<td><a href="dogodek?id=<?php echo $row['id']; ?>"><?php echo $row['naziv']; ?></a></td>
							<td><?php echo $row['kraj']; ?></td>
							<td><?php echo $row['drzava']; ?></td>
							<td><a href="uporabnik?id="><?php echo $row['up_ime']; ?></a></td>
						</tr>
				<?php } 
				} ?>
          </table>
        </div>
      </div>