<?php
	$user_id = $_SESSION['login_id'];
	
	if(isset($_GET['id'])) {
		
		$up_id = $_GET['id'];
		
		$query = "SELECT * FROM `uporabniki` WHERE id = '$up_id'";
		$sql = mysql_query($query);
		
		if(mysql_num_rows($sql) == 0) {
			$up_id = $user_id;
		}
	} else {
		$up_id = $user_id;
	}
	
	if(isset($_POST['posodobi'])) {
		$ime = $_POST['ime'];
		$priimek = $_POST['priimek'];
		$spol = $_POST['spol'];
		$email = $_POST['email'];
		$naslov = str_replace("\r\n", "<br />", $_POST['naslov']);
		$telefon = $_POST['telefon'];
		if(isdate($_POST['datum_r'])) {
			$polje = explode(".", $_POST['datum_r']);
			$datum_r = mktime(0, 0, 0, $polje[1], $polje[0], $polje[2]);
		} else {
			$datum_r = "";
		}
		
		$geslo = $_POST['geslo'];
		$p_geslo = $_POST['p_geslo'];
		
		if($ime != "" && $priimek != "" && $email != "") {
			mysql_query("UPDATE uporabniki SET ime = '$ime', priimek = '$priimek', spol = '$spol', email = '$email', naslov = '$naslov', telefon = '$telefon', datum_rojstva = FROM_UNIXTIME('$datum_r') WHERE id = '$user_id'");
			if($geslo != "" && $geslo == $p_geslo) {
				$new_geslo = sha1($geslo);
				mysql_query("UPDATE uporabniki SET geslo = '$new_geslo' WHERE id = '$user_id'");
			}
		}
		
		if(isset($_FILES['slika'])) {
			if($_FILES['slika']['error'] > 0) {
				//die("error code");
			} else {
				
				$slike = "uploads/pics/";
				$thumbs = "uploads/thumbs/";
				$temp = "uploads/temp/";
				
				$file = "uporabnik_".$user_id.".".end(explode(".",strtolower($_FILES['slika']['name'])));
				
				if(file_exists($slike.$file)) {
					unlink($slike.$file);
				}
				if(file_exists($thumbs.$file)) {
					unlink($thumbs.$file);
				}
				if(file_exists($temp.$file)) {
					unlink($temp.$file);
				}

				if(move_uploaded_file($_FILES['slika']['tmp_name'], $temp.$file)) {
					
					include_once("includes/thumb_generator.php");
					createThumb($temp.$file, $slike, 200, 150);
					createThumb($temp.$file, $thumbs, 40, 40);
					
					
					if(file_exists($temp.$file)) {
						unlink($temp.$file);
					}
					
					mysql_query("UPDATE uporabniki SET slika = '$file' WHERE id = '$user_id'");
					
				}
			}
		}


	}
	
	if(isset($_POST['message'])) {
		$message = mysql_real_escape_string($_POST['message']);
		
		if($message != "") {
			$query = "INSERT INTO `komentarji_org`(`uporabnik_id`, `komentator_id`, `komentar`, `datum`) VALUES ('$up_id', '$user_id', '$message', FROM_UNIXTIME('".time()."'))";
			mysql_query($query) or die(mysql_error());
		}
	}
	
	$up_sql = mysql_query("SELECT *, UNIX_TIMESTAMP(datum_rojstva) as 'phpdate' FROM uporabniki WHERE id = '$up_id'");
	$uporabnik = mysql_fetch_assoc($up_sql);
?>
  <div class="mainbar">
    <div class="article">
      <h2><span>Profil uporabnika <strong><?php echo $uporabnik['up_ime']; ?></strong></span></h2>
      <div class="clr"></div>
      
      <div class="profile_left">
      	<?php 
			if($uporabnik['slika'] != "" && file_exists("uploads/pics/".$uporabnik['slika'])) {
				$pic = "uploads/pics/".$uporabnik['slika'];
			} else {
				$pic = "images/avatar.png";
			}
		?>
      	<img src="<?php echo $pic; ?>" width="150px" height="200px" alt="user_icon" />
        	<?php 
				$sql = mysql_query("SELECT v.* FROM uporabniki u, uporabniki v, prijateljstvo p WHERE p.potrditev = '1' AND u.id = '$up_id' AND NOT (v.id = '$up_id') AND (p.posiljatelj_id = u.id OR p.prejemnik_id = u.id) AND (p.posiljatelj_id = v.id OR p.prejemnik_id = v.id)");
				
			?>
        	<h3><a href="#">Prijatelji (<?php echo mysql_num_rows($sql); ?>)</a></h3>
            <ul class="fr_menu">
            	<?php while( $row = mysql_fetch_assoc($sql) ) { ?>
            		<li><a href="uporabnik?id=<?php echo $row['id']; ?>"><?php echo $row['up_ime']; ?></a></li>
                <?php } ?>
            </ul>
      </div>
      <div class="profile_right">
            <table class="dogodek_table">
            	<tr>
                	<th colspan="2"><h2>Osebni podatki</h2></th>
                </tr>
                <tr>
                	<th>Ime in priimek:</th>
                    <td><?php echo $uporabnik['ime']." ".$uporabnik['priimek']; ?></td>
                </tr>
                <tr>
                	<th>Spol:</th>
                    <td><?php echo ($uporabnik['spol'] == 'z' ? "Ženska": "Moški"); ?></td>
                </tr>
                <tr>
                    <th>Datum rojstva:</th>
                    <td><?php echo ($uporabnik['phpdate'] == "") ? " - " : date("M jS, Y", $uporabnik['phpdate']); ?></td>
                </tr>
            	<tr>
                	<th colspan="2"><h2>Kontaktni podatki</h2></th>
                </tr>
                <tr>
                    <th>Naslov:</th>
                    <td><?php echo ($uporabnik['naslov'] == "") ? " - " : $uporabnik['naslov']; ?></td>
                </tr>
                <tr>
                    <th>Email:</th>
                    <td><a href="mailto:<?php echo $uporabnik['email']; ?>"><?php echo $uporabnik['email']; ?></a></td>
                </tr>
                <tr>
                    <th>Telefon:</th>
                    <td><?php echo ($uporabnik['telefon'] == "") ? " - " : $uporabnik['telefon']; ?></td>
                </tr>
                <?php
					$dog = mysql_query("SELECT * FROM `dogodki` WHERE organizator_id = '$up_id'");
				?>
            	<tr>
                	<th colspan="2"><h2>Organizator Dogodkov (<?php echo mysql_num_rows($dog); ?>)</h2></th>
                </tr>
                <?php while($row = mysql_fetch_assoc($dog)) { ?>
                    <tr>
                        <td colspan="2"><a href="dogodek?id=<?php echo $row['id']; ?>"><?php echo $row['naziv'].", ".$row['kraj'].", ".$row['drzava']; ?></a></td>
                    </tr>
                <?php } 
					
					$sk = mysql_query("SELECT * FROM `skupine_oseb` WHERE uporabnik_id = '$up_id'");
					
				?>
            	<tr>
                	<th colspan="2"><h2>Skupine oseb (<?php echo mysql_num_rows($sk); ?>)</h2></th>
                </tr>
                <?php while($skupina = mysql_fetch_assoc($sk)) { 
					$os = mysql_query("SELECT * FROM `osebe_po_skupinah` WHERE skupina_id = '".$skupina['id']."'");
					?>
                    <tr>
                        <td colspan="2"><a class="fancybox_item" href="fancybox/skupina_os.php?id=<?php echo $skupina['id']; ?>"><?php echo $skupina['naziv']; ?> (<?php echo mysql_num_rows($os); ?> oseb)</a></td>
                    </tr>
                <?php } ?>
            </table>
           	<a href="pdf/dogodki.php" target="_blank">Prenesi dogodke in prijave v PDF! <img align="middle" src="images/icons/pdf.png" /></a>
            <?php if($up_id == $user_id) { ?><a class="fancybox_item" href="fancybox/user_edit.php"><img align="right" src="images/icons/edit.png" /></a><?php } ?>
      </div>
      <div class="clr"></div>
    </div>
    <?php 
		$query = "SELECT k.komentar, UNIX_TIMESTAMP(datum) as 'phpdate', u.id, u.up_ime, u.slika FROM `komentarji_org` k, uporabniki u WHERE `uporabnik_id` = '$up_id' AND k.komentator_id = u.id ORDER BY datum";
		$sql = mysql_query($query) or die(mysql_error());
	?>
    <div class="article">
          <h2><span><?php echo mysql_num_rows($sql); ?></span> Komentarji</h2>
          <div class="clr"></div>
          <?php while($row = mysql_fetch_assoc($sql)) { 
		  	if($row['slika'] != "" && file_exists("uploads/thumbs/".$row['slika'])) {
				$pic = "uploads/thumbs/".$row['slika'];
			} else {
				$pic = "images/userpic.gif";
			}
		  	?>
          <div class="comment"> <a href="uporabniki?id=<?php echo $row['id']; ?>"><img src="<?php echo $pic; ?>" width="40" height="40" alt="" class="userpic" /></a>
            <p><a href="uporabnik?id=<?php echo $row['id']; ?>"><?php echo $row['up_ime']; ?></a> Says:<br />
              <?php echo date("F j, Y \o\b g:i a", $row['phpdate']); ?></p>
            <p><?php echo $row['komentar']; ?></p>
          </div>
          <?php } ?>
        </div>
        <div class="article">
          <h2><span>Komentiraj</span></h2>
          <div class="clr"></div>
          <form action="uporabnik?id=<?php echo $up_id; ?>" method="post" id="leavereply">
            <ol>
              <li>
                <textarea id="message" name="message" rows="8" cols="30"></textarea>
              </li>
              <li>
                <input type="image" name="imageField" id="imageField" src="images/submit.gif" class="send" />
                <div class="clr"></div>
              </li>
            </ol>
          </form>
        </div>
  </div>