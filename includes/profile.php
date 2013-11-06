<?php
	$user_id = $_SESSION['login_id'];
	$query = "SELECT * FROM `uporabniki` WHERE `id` = '$user_id'";
	$sql = mysql_query($query);
	$user = mysql_fetch_assoc($sql);
?>
<div class="gadget">
      	<?php 
			if($user['slika'] != "" && file_exists("uploads/pics/".$user['slika'])) {
				$pic = "uploads/pics/".$user['slika'];
			} else {
				$pic = "images/avatar.png";
			}
		?>
    <div style="text-align:center;"><img src="<?php echo $pic; ?>" alt="user_icon" /></div>
    <p>Pozdravljen, <?php echo $user['ime']; ?>.</p>
    <p><a href="login?logout">Izpis</a></p>
</div>