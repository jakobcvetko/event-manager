<?php
	$user_id = $_SESSION['login_id'];
	
	if(isset($_GET['friend'])) {
		
		$friend_id = $_GET['friend'];
		
		$query = "SELECT * FROM `prijateljstvo` WHERE (`posiljatelj_id` = '$user_id' AND `prejemnik_id` = '$friend_id')
													OR (`posiljatelj_id` = '$friend_id' AND `prejemnik_id` = '$user_id')";
		$sql = mysql_query($query);
		
		if(mysql_num_rows($sql) == 0) {
			$query = "INSERT INTO `prijateljstvo` (posiljatelj_id, prejemnik_id, datum) VALUES ('$user_id', '$friend_id', FROM_UNIXTIME('".time()."'))";
			mysql_query($query);
		}
	}
	
	if(isset($_GET['potrdi'])) {
		$friend_id = $_GET['potrdi']; //katerega zelimo potrditi
		
		$query = "SELECT * FROM `prijateljstvo` WHERE posiljatelj_id = '$friend_id' AND prejemnik_id = $user_id AND potrditev='0'";
		$sql = mysql_query($query);
		if(mysql_num_rows($sql)) { //Ali obstaja prošnja, ki jo želimo potrditi
			$query = "UPDATE `prijateljstvo` SET `potrditev` = '1' WHERE posiljatelj_id = '$friend_id' AND prejemnik_id = $user_id";
			mysql_query($query);
		}
		
	}
	
?>

      <div class="mainbar">
        <div class="article">
        	<h1>Uporabniki</h1>
          <table class="uporabniki">
          	<tr>
            	<th>Uporabniško Ime</th>
            	<th>Ime</th>
            	<th>Priimek</th>
            	<th>Email</th>
            	<th>Prijateljstvo</th>
            </tr>
            <?php
			
				
				$query = "SELECT * FROM `uporabniki` WHERE NOT `id` = '$user_id'";
				$sql = mysql_query($query);
				if(mysql_num_rows($sql) == 0) { ?>
                	<tr>
                    	<td colspan="5" class="no_results">Ni najdenih uporabnikov...</td>
                    </tr>
				<?php } else {
				while($row = mysql_fetch_assoc($sql)) { ?>                	
                    <tr>
                        <td><a href="uporabnik?id=<?php echo $row['id']; ?>"><?php echo $row['up_ime']; ?></a></td>
                        <td><?php echo $row['ime']; ?></td>
                        <td><?php echo $row['priimek']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td>
                        	<?php
							//Ce je prijatelj
							$friend_id = $row['id'];
							$friend_q = "SELECT * FROM `prijateljstvo` WHERE ((`posiljatelj_id` = '$user_id' AND `prejemnik_id` = '$friend_id')
																		OR (`posiljatelj_id` = '$friend_id' AND `prejemnik_id` = '$user_id'))
																		AND potrditev = '1'";
							$friend_sql = mysql_query($friend_q);
							if(mysql_num_rows($friend_sql)) { ?>
                            	Prijatelj
							<?php } else { 
								//Ce smo mu poslali prosnjo
								$friend_q = "SELECT * FROM `prijateljstvo` WHERE (`posiljatelj_id` = '$user_id' AND `prejemnik_id` = '$friend_id')
																			AND potrditev = '0'";
								$friend_sql = mysql_query($friend_q);
								if(mysql_num_rows($friend_sql)) { ?>
									Čakam potrditev
								<?php } else { 
									//Ce je on nam poslal prosnjo
									$friend_q = "SELECT * FROM `prijateljstvo` WHERE (`posiljatelj_id` = '$friend_id' AND `prejemnik_id` = '$user_id')
																				AND potrditev = '0'";
									$friend_sql = mysql_query($friend_q);
									if(mysql_num_rows($friend_sql)) { ?>
										<a href="uporabniki?potrdi=<?php echo $friend_id; ?>">Potrdi prosnjo</a>
									<?php } else { ?>
                                        <a href="uporabniki?friend=<?php echo $row['id']; ?>">Dodaj prijatelja</a>
                                    <?php } 
									}
								} ?>
                        </td>
                    </tr>
				<?php }
				} ?>
          </table>
        </div>
      </div>