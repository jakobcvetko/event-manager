<?php
	$user_id = $_SESSION['login_id'];
 
	if(isset($_POST['text_search'])) {
		$string = $_POST['text_search'];
		
		if($string != "") {		
			$uporabniki_q = "SELECT * FROM `uporabniki` WHERE  ime LIKE '%$string%'
															OR priimek LIKE '%$string%'
															OR email LIKE '%$string%'
															OR up_ime LIKE '%$string%'";
			$uporabniki_sql = mysql_query($uporabniki_q);
			
			
			$dogodki_q = "SELECT * FROM `dogodki_view` WHERE   naziv LIKE '%$string%'
															OR kraj LIKE '%$string%'
															OR drzava LIKE '%$string%'
															OR opomba LIKE '%$string%'";
			$dogodki_sql = mysql_query($dogodki_q);
			
			
			$osebe_q = "SELECT * FROM `osebe` ";
			$osebe_sql = mysql_query($osebe_q);
		}
	} else {
		header("location: home");
	}
	 
?>
     
     
      <div class="mainbar">
        <div class="article">
        	<h2>Zadetki za »<strong><?php echo $string; ?></strong>«</h2>
            <h2>Uporabniki</h2>
            <table class="uporabniki">
          	<tr>
            	<th>Uporabniško Ime</th>
            	<th>Ime</th>
            	<th>Priimek</th>
            	<th>Email</th>
            </tr>
            <?php 
				if(mysql_num_rows($uporabniki_sql) == 0) { ?>
                	<tr>
                    	<td colspan="5" class="no_results">Ni zadetkov...</td>
                    </tr>
				<?php } else {
					while($row = mysql_fetch_assoc($uporabniki_sql)) { ?>
                <tr>
                    <td><a href="uporabnik?id=<?php echo $row['id']; ?>"><?php echo str_ireplace($string, "<strong><u>".$string."</u></strong>", $row['up_ime']); ?></a></td>
                    <td><?php echo str_ireplace($string, "<strong><u>".$string."</u></strong>", $row['ime']); ?></td>
                    <td><?php echo str_ireplace($string, "<strong><u>".$string."</u></strong>", $row['priimek']); ?></td>
                    <td><?php echo str_ireplace($string, "<strong><u>".$string."</u></strong>", $row['email']); ?></td>
                </tr>
            <?php }
				} ?>
            </table>
            <h2>Dogodki</h2>
            <table class="uporabniki">
          	<tr>
            	<th>Naziv</th>
            	<th>Kraj</th>
            	<th>Drzava</th>
            	<th>Organizator</th>
            </tr>
            <?php 
				if(mysql_num_rows($dogodki_sql) == 0) { ?>
                	<tr>
                    	<td colspan="5" class="no_results">Ni zadetkov...</td>
                    </tr>
				<?php } else {
					while($row = mysql_fetch_assoc($dogodki_sql)) { ?>
                <tr>
                    <td><a href="dogodek?id=<?php echo $row['id']; ?>"><?php echo str_ireplace($string, "<strong><u>".$string."</u></strong>", $row['naziv']); ?></a></td>
                    <td><?php echo str_ireplace($string, "<strong><u>".$string."</u></strong>", $row['kraj']); ?></td>
                    <td><?php echo str_ireplace($string, "<strong><u>".$string."</u></strong>", $row['drzava']); ?></td>
                    <td><a href="dogodek?id=<?php echo $row['organizator_id']; ?>"><?php echo str_ireplace($string, "<strong><u>".$string."</u></strong>", $row['up_ime']); ?></a></td>
                </tr>
            <?php }
				} ?>
            </table>
        </div>
      </div>