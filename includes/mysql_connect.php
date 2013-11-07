<?php

  //Dotenv load
  include("dotenv.php");
  Dotenv::load(dirname(__DIR__));

  //Database Config and Connection
  $db_host = getenv('DB_HOST');
  $db_username = getenv('DB_USER');
  $db_password = getenv('DB_PASS');
  $db_name = getenv('DB_NAME');

  if(getenv("CLEARDB_DATABASE_URL") != "") {
    $url=parse_url(getenv("CLEARDB_DATABASE_URL"));

    $db_host = $url["host"];
    $db_username = $url["user"];
    $db_password = $url["pass"];
    $db_name = substr($url["path"],1);
  }

  if($db_password != "") {
    mysql_connect($db_host, $db_username, $db_password);
  } else {
    mysql_connect($db_host, $db_username);
  }

  mysql_select_db($db_name);

  mysql_set_charset('utf8');
  date_default_timezone_set("UTC");

?>
