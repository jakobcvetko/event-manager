<?php 
	if(isset($_GET['m'])) {
		$offset = $_GET['m'];
	} else {
		$offset = 0;
	}
	
	$user_id = $_SESSION['login_id'];
	
	$today = getdate();
	
	$displaymonth = getdate(mktime(0,0,0,$today['mon']+$offset,1,$today['year'])); 
	
	$firstDay=getdate(mktime(0,0,0,$displaymonth['mon'],1,$displaymonth['year'])); 
	$lastDay=getdate(mktime(0,0,0,$displaymonth['mon']+1,0,$displaymonth['year'])); 
	
	
	?>
<div class="gadget">
	<h2><a name="koledar">Koledar</a></h2>
    <table class="koledar">
    	<tr>
        	<th><a href="<?php echo $page; ?>?m=<?php echo ($offset-1); ?>#koledar">&lt;&lt;</a></th>
        	<th colspan="5"><?php echo $displaymonth['month'].", ".$displaymonth['year']; ?></th>
        	<th><a href="<?php echo $page; ?>?m=<?php echo ($offset+1); ?>#koledar">&gt;&gt;</a></th>
        </tr>
        <tr class="days">
            <th>Po</th>
            <th>To</th>
            <th>Sr</th>
            <th>Če</th>
            <th>Pe</th>
            <th>So</th>
            <th>Ne</th>
        </tr>
        
        <tr>
        <?php 
		if($firstDay['wday'] == 0) {
			$firstDay['wday'] = 7;
		}
		
		$day_zero = getdate(mktime(0, 0, 0, $displaymonth['mon'], 2-$firstDay['wday'], $displaymonth['year']));
		$day_over = getdate(mktime(0, 0, 0, $displaymonth['mon']+1, 1, $displaymonth['year']));
		
		for($i=$day_zero['mday'];$i<$day_zero['mday']+$firstDay['wday']-1;$i++){
			if($i == $today['mday'] && $day_zero['mon'] == $today['mon'] && $day_zero['year'] == $today['year']) { ?>
                <td class="actdayout"><?php echo $i; ?></td>
            <?php } else { ?>
                <td class="out"><?php echo $i; ?></td>
            <?php }
        } 
        $actday=0; 
        for($i=$firstDay['wday'];$i<=7;$i++){ 
            $actday++; 

			if(($actday == $today['mday']) && ($displaymonth['mon'] == $today['mon']) && ($displaymonth['year'] == $today['year'])){ 
				$class="class='actday'"; 
			}else{ 
				$class=''; 
			} /*
			$query = "SELECT * FROM `termini_view` WHERE `organizator_id` = '$user_id' AND `datum` = FROM_UNIXTIME('".mktime(0, 0, 0, $displaymonth['mon'], $actday, $displaymonth['year'])."')";
			$sql = mysql_query($query) or die(mysql_error());*/
			echo"<td $class>";
			//if(mysql_num_rows($sql) > 0) {
			if(events($user_id, $displaymonth['mon'], $actday, $displaymonth['year'])) {
				echo "<a class='fancybox_item' href='fancybox/show_dog.php?day=".mktime(0, 0, 0, $displaymonth['mon'], $actday, $displaymonth['year'])."'>$actday</a>";
			} else {
            	echo"$actday"; 
			}
			echo "</td>";
        } 
        echo'</tr>'; 
        
        $fullWeeks=floor(($lastDay['mday']-$actday)/7); 
        for($i=0;$i<$fullWeeks;$i++){ 
            echo'<tr>'; 
            for($j=0;$j<7;$j++){ 
                $actday++;
                if(($actday == $today['mday']) && ($displaymonth['mon'] == $today['mon']) && ($displaymonth['year'] == $today['year'])){ 
                    $class="class='actday'"; 
                }else{ 
                    $class=''; 
                }/*
				$query = "SELECT * FROM `termini_view` WHERE `organizator_id` = '$user_id' AND `datum` = FROM_UNIXTIME('".mktime(0, 0, 0, $displaymonth['mon'], $actday, $displaymonth['year'])."')";
				$sql = mysql_query($query) or die(mysql_error());*/
				echo"<td $class>";
				//if(mysql_num_rows($sql) > 0) {
				if(events($user_id, $displaymonth['mon'], $actday, $displaymonth['year'])) {
					echo "<a class='fancybox_item' href='fancybox/show_dog.php?day=".mktime(0, 0, 0, $displaymonth['mon'], $actday, $displaymonth['year'])."'>$actday</a>";
				} else {
					echo"$actday"; 
				}
				echo "</td>";
            } 
            echo'</tr>'; 
        } 
        
        $over = 1;
        if($actday<$lastDay['mday']){ 
            echo'<tr>'; 
            for($i=0;$i<7;$i++){ 
                $actday++; 
                if(($actday == $today['mday']) && ($displaymonth['mon'] == $today['mon']) && ($displaymonth['year'] == $today['year'])){ 
                    $class='class="actday"'; 
                }else{ 
                    $class=''; 
                } 
                if($actday<=$lastDay['mday']){/*
					$query = "SELECT * FROM `termini_view` WHERE `organizator_id` = '$user_id' AND `datum` = FROM_UNIXTIME('".mktime(0, 0, 0, $displaymonth['mon'], $actday, $displaymonth['year'])."')";
					$sql = mysql_query($query) or die(mysql_error());*/
                    echo"<td $class>";
					//if(mysql_num_rows($sql) > 0) {
					if(events($user_id, $displaymonth['mon'], $actday, $displaymonth['year'])) {
						echo "<a class='fancybox_item' href='fancybox/show_dog.php?day=".mktime(0, 0, 0, $displaymonth['mon'], $actday, $displaymonth['year'])."'>$actday</a>";
					} else {
						echo"$actday"; 
					}
					echo "</td>"; 
                } else{ 
					if($over == $today['mday'] && $day_over['mon'] == $today['mon'] && $day_over['year'] == $today['year']) { ?>
						<td class="actdayout"><?php echo $over; ?></td>
					<?php } else { ?>
                		<td class="out"><?php echo $over; ?></td>
					<?php }
					
                    $over++; 
                } 
            } 
            echo'</tr>'; 
        } 
        ?>
    </table> 
    <p>
    	Calendar by Jacob 2011
    </p>
    </div>