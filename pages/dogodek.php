<?php

	$user_id = $_SESSION['login_id'];

	if(isset($_GET['id'])) {
		$id = mysql_real_escape_string($_GET['id']);
		$query = "SELECT * FROM `dogodki` d, `uporabniki` u WHERE d.`id` = '$id' AND d.organizator_id = u.id";
		$sql = mysql_query($query);
		if($row = mysql_fetch_assoc($sql)) {
			$naziv = $row['naziv'];
			$organizator = $row['ime'];
			$org_id = $row['organizator_id'];
			$kraj = $row['kraj'];
			$drzava = $row['drzava'];
		} else {
			header("location:home");
		}
	} else {
		header("location:home");
	}
	
	if(isset($_POST['message'])) {
		$message = mysql_real_escape_string($_POST['message']);
		
		if($message != "") {
			$query = "INSERT INTO `komentarji_dog`(`dogodek_id`, `uporabnik_id`, `komentar`, `datum`) VALUES ('$id', '$user_id', '$message', FROM_UNIXTIME('".time()."'))";
			mysql_query($query) or die(mysql_error());
		}
	}
	
	if(isset($_POST['dodaj_rezultate'])) {
		if(isset($_GET['ter_id']) && isset($_GET['os_id'])) {
			$os_id = $_GET['os_id'];
			$ter_id = $_GET['ter_id'];
			for($i=1; $i<=5; $i++) {
				if($_POST['tip_'.$i] != "" && $_POST['rez_'.$i] != "") {
					$tip = $_POST['tip_'.$i];
					$rez = $_POST['rez_'.$i];
					
					mysql_query("INSERT INTO rezultati (oseba_id, termin_id, naziv, rezultat) VALUES ('$os_id', '$ter_id', '$tip', '$rez');");
				}
			}
		}
	}
?>

  <div class="mainbar">
    <div class="article">
      <h2><span><?php echo $naziv.", ".$kraj.", ".$drzava; ?></span></h2>
      <div class="clr"></div>
        <table class="dogodek_table">
            <tr>
                <th>Organizator:</th>
                <td><a href="uporabnik?id=<?php echo $org_id; ?>"><?php echo $organizator; ?></a></td>
            </tr>
            <tr>
                <th>Kraj:</th>
                <td><?php echo $kraj; ?><br /><?php echo $drzava; ?></td>
            </tr>
            <?php
				$organizatorji = mysql_query("SELECT DISTINCT up.*
											FROM termini_dog t, udelezba u, osebe o, uporabniki up
											WHERE u.termin_id = t.id
											AND u.oseba_id = o.id
											AND o.uporabnik_id = up.id
											AND t.dogodek_id = $id") or die(mysql_error());
			?>
            <tr>
                <th>Udeleženi organizatorji:</th>
                <td><?php 
					$comma = false;
					while($row = mysql_fetch_assoc($organizatorji)) { 
						if($comma) echo ", ";
						else $comma = true; ?><a href="uporabnik?id=<?php echo $row['id']; ?>"><?php echo $row['up_ime']; ?></a><?php } ?></td>
            </tr>
            <?php
				$st_prij_oseb = mysql_num_rows(mysql_query("SELECT u.*
											FROM termini_dog t, udelezba u
											WHERE u.termin_id = t.id
											AND t.dogodek_id = $id"));
			?>
            <tr>
                <th>Udeležene osebe:</th>
                <td><?php echo $st_prij_oseb; ?> oseb</td>
            </tr>
            <tr>
                <th>Termini dogodka:</th>
                <td><input type="button" value="Pokaži / Skrij Termine" id="switch_termine" />
                <div class="hidden">
                <?php
					$query = "SELECT *, UNIX_TIMESTAMP(`datum`) as phpdate FROM `termini_dog` WHERE `dogodek_id` = '$id'";
					$sql = mysql_query($query) or die(mysql_error());
					for($i=1; $row = mysql_fetch_assoc($sql); $i++) { 
						$st_oseb = mysql_num_rows(mysql_query("SELECT o.id 
									FROM termini_dog t, udelezba u, osebe o
									WHERE u.oseba_id = o.id
									AND u.termin_id = t.id
									AND t.id = ".$row['id']."
									AND o.uporabnik_id = $user_id"));
						?>
                    	<a class="fancybox_item" href="fancybox/prijava.php?ter=<?php echo $row['id']; ?>">Prijava na Termin <?php echo $i; ?></a> dne <?php echo strftime("%A", $row['phpdate'])." ".date("d.m.Y", $row['phpdate']); ?> ob <?php echo substr($row['cas'], 0, 5); ?> uri. (<?php echo $st_oseb; ?> prijavljenih)<br />
					<?php }
				?>
                </div>
                </td>
            </tr>
        </table>
    </div>
    <?php 
		$query = "SELECT k.komentar, UNIX_TIMESTAMP(datum) as 'phpdate', u.id, u.up_ime, u.slika FROM `komentarji_dog` k, uporabniki u WHERE `dogodek_id` = '$id' AND k.uporabnik_id = u.id ORDER BY datum";
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
          <form action="dogodek?id=<?php echo $id; ?>" method="post" id="leavereply">
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
  <script type="text/javascript">
  	$("#switch_termine").click(function() {
		$(".hidden").animate({
			height: 'toggle'
		});
	});
  </script>