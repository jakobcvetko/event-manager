<?php

//Database Config and Connection

$db_host = "127.0.0.1";
$db_port = "3306";
$db_username = "root";
$db_password = "";
$db_name = "event_manager";

if($db_password != "") {
  mysql_connect($db_host.":".$db_port, $db_username, $db_password);
} else {
  mysql_connect($db_host.":".$db_port, $db_username);
}

mysql_select_db($db_name);
mysql_set_charset('utf8');

date_default_timezone_set("UTC");

?>