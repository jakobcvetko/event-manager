<?php

	/* * * * * * * * * * * * * * * *\
	 *							   *
	 *  Php functions page		   *
	 *  Created by Jacob @ 2011	   *
	 *							   *
	\* * * * * * * * * * * * * * * */


/*
 * In-Menu Funkcija
 * Enaka kot in_array() le da preišče predefiniran multi-array
 * By Jacob
 */
function get_index($page, $menu) {
	for($i=0; $i< sizeof($menu); $i++) {
		if($menu[$i][0] == $page) {
			return $i;
		}
	}
	return -1;
}


function check_email($email) {
	if(preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/",$email)){
		list($username,$domain)=split('@',$email);
		if(!checkdnsrr($domain,'MX')) {
			 	return false;
		}
		return true;
	}
	return false;
}

function isdate($date) {
	if(preg_match("/^\d{2}\.\d{2}\.\d{4}$/", $date)) {
		return true;
	} else {
		return false;
	}

}

function istime($time) {
	if(preg_match("/^\d{2}:\d{2}$/", $time)) {
		return true;
	} else {
		return false;
	}
}

function events($user_id, $m, $d, $y) {
	//$query = "SELECT * FROM `termini_view` t, udelezba u, osebe o WHERE (t.`organizator_id` = '$user_id' OR (o.uporabnik_id = '$user_id' AND u.oseba_id = o.id AND u.termin_id=t.termin_id)) AND t.`datum` = FROM_UNIXTIME('".mktime(0, 0, 0, $m, $d, $y)."')";
	$query = "SELECT * FROM `termini_view` t, udelezba u, osebe o WHERE o.uporabnik_id = '$user_id' AND u.oseba_id = o.id AND u.termin_id=t.termin_id AND t.`datum` = FROM_UNIXTIME('".mktime(0, 0, 0, $m, $d, $y)."')";
	$sql = mysql_query($query) or die(mysql_error());
	if(mysql_num_rows($sql) > 0) {
		return true;
	} else {
		$query = "SELECT * FROM uporabniki WHERE DAY(datum_rojstva) = '$d' AND MONTH(datum_rojstva) = '$m'";
		$sql = mysql_query($query) or die(mysql_error());
		if(mysql_num_rows($sql) > 0) {
			return true;
		} else {
			$query = "SELECT * FROM osebe WHERE DAY(datum_rojstva) = '$d' AND MONTH(datum_rojstva) = '$m' AND uporabnik_id = '$user_id'";
			$sql = mysql_query($query) or die(mysql_error());
			if(mysql_num_rows($sql) > 0) {
				return true;
			} else {
				return false;
			}
		}
	}
}
?>