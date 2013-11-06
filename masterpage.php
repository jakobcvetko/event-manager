<?php 
	include("includes/mysql_connect.php");
	include("includes/functions.php"); 
	
	date_default_timezone_set("Europe/Ljubljana");
	
	session_start();
	
	if(isset($_GET['page'])) {
		$page = $_GET['page'];
	} else {
		$page = "index";
	}
	
	/* Login Logika */
	
	if($page == "loginpage" && isset($_POST['username']) && isset($_POST['password'])) {
		$username = mysql_real_escape_string($_POST['username']);
		$geslo = sha1($_POST['password']);
		
		$login_query = "SELECT * FROM `uporabniki` WHERE `up_ime` = '$username' AND `geslo` = '$geslo'";
		$login_sql = mysql_query($login_query);
		
		if(mysql_num_rows($login_sql) > 0) {
			$row = mysql_fetch_assoc($login_sql);
			
			$_SESSION['login'] = true;
			$_SESSION['login_id'] = $row['id'];
			
			header("location: home");
			
		} else { //Bad login
			$_SESSION['login'] = false;
			header("location: login?wrong_login");
		}
	}
	
	/* Register Logika */
	
	if($page == "register") {
		if(isset($_POST['reg_name']) 
		&& isset($_POST['reg_lastname']) 
		&& isset($_POST['reg_email'])
		&& isset($_POST['reg_username']) 
		&& isset($_POST['reg_password'])) {
								
		//echo $_POST['reg_username'];
		$name = mysql_real_escape_string($_POST['reg_name']);
		$lastname = mysql_real_escape_string($_POST['reg_lastname']);
		$email = mysql_real_escape_string($_POST['reg_email']);
		$username = mysql_real_escape_string($_POST['reg_username']);
		$password = sha1($_POST['reg_password']);
		
		if($name == "" || $lastname == "" || $email == "" || $username == "" || $password == "") {
			header("location: login?bad_register");
		}
		
		if(!check_email($email)) {
			header("location: login?wrong_email");
		}
		
		$up_check_query = "SELECT * FROM `uporabniki` WHERE `up_ime` = '$username'";
		$up_check_sql = mysql_query($up_check_query);
		
		if(mysql_num_rows($up_check_sql) > 0) {
			header("location: login?username_taken");
		}
		
		$reg_query = "INSERT INTO `uporabniki` (`ime`, `priimek`, `email`, `up_ime`, `geslo`) VALUES ('$name', '$lastname', '$email', '$username', '$password')";
		
		$reg_sql = mysql_query($reg_query) or die(mysql_error());
		
		$_SESSION['login'] = true;
		$_SESSION['login_id'] = mysql_insert_id();
		
		header("location: home");
		} else {
			header("location: login?bad_register");
		}
	}
	
	/* 
	 * Pages array properties: 
	 * array( request_name, display_name, file_name[.php], bool show_in_menu, parent_menu )
	 * 		0: request_name
	 *		1: display_name
	 *		2: file_name[.php]
	 *		3: bool show_in_menu
	 *		4: parent_menu
	 */
	$pages = array( array("home", "Home", "uporabnik", true, "home"),
					array("dogodki","Dogodki", "dogodki", true, "dogodki"),
					array("dogodek","Dogodek", "dogodek", false, "dogodki"),
					array("osebe","Osebe", "osebe", true, "osebe"),	
					array("oseba","Oseba", "oseba", false, "osebe"),	
					array("uporabniki", "Uporabniki", "uporabniki", true, "uporabniki"),
					array("uporabnik", "Uporabnik", "uporabnik", false, "uporabniki"),
					array("vnos_dogodka", "Vnos Dogodka", "vnos_dogodka", false, "dogodki"),
					array("search", "Iskanje", "search", false, ""),
					array("prijava", "", "prijava", false, ""));
	
	$page_index = get_index($page, $pages);
	
	if($page_index == -1) {
		$page = "errorpage";
	}
	
	if(isset($_SESSION['login']) && $_SESSION['login']) {
		$user_id = $_SESSION['login_id'];
		$sql = mysql_query("SELECT * FROM uporabniki WHERE id = '$user_id'");
		if(mysql_num_rows($sql) == 0) {
			header("location: login?logout");
		}
	} else {
		header("location: login");
	}
	  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $pages[$page_index][1]; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="style/style.css" rel="stylesheet" type="text/css" />
<link href="style/costum.css" rel="stylesheet" type="text/css" />
<link href="style/koledar.css" rel="stylesheet" type="text/css" />
<link href="js/blitzer/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" />
<link href="js/fancybox/jquery.fancybox-1.3.4.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/cufon-yui.js"></script>
<script type="text/javascript" src="js/arial.js"></script>
<script type="text/javascript" src="js/cuf_run.js"></script>
<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<script type="text/javascript" src="js/setup.js"></script>
</head>
<body>
<div class="main">
  <div class="header">
    <div class="header_resize">
        <div class="logo">
            <h1><a href="home"><span>Event</span>Manager <small>for every need</small></a></h1>
        </div>
        <?php 
			if($page != "errorpage") { ?>
                <div class="menu_nav">
                    <ul>
                        <?php foreach($pages as $item) { 
                            if($item[3]) { //if show_in_menu == true ?>
                                <li <?php echo (($item[0] == $pages[$page_index][4]) ? "class='active'" : ""); ?>><a href="<?php echo $item[0]; ?>"><?php echo $item[1]; ?></a></li>
                        <?php } 
                        } ?>
                    </ul>
                </div>
            <?php } ?>
      <div class="clr"></div>
    </div>
  </div>
  <div class="content">
    <div class="content_resize">
    <?php /* INCLUDING MAINBAR */ 
	
		if($page == "errorpage") {
			include("pages/errorpage.php");
		} else {
			include("pages/".$pages[$page_index][2].".php"); ?>
			  <div class="sidebar">
				<div class="searchform">
				  <form id="formsearch" name="formsearch" method="post" action="search">
					<input name="button_search" src="images/search_btn.gif" class="button_search" type="image" />
					<span>
					<input name="text_search" class="editbox_search dynamic_input" id="editbox_search" maxlength="80" value="Search" type="text" />
					</span>
				  </form>
				</div>
				<?php include("includes/profile.php"); ?>
				<?php include("includes/koledar.php"); ?>
		  </div>
		<?php } ?>
    </div>
    <div class="clr"></div>
  </div>
  <div class="footer">
    <div class="footer_resize">
      <p class="lf">&copy; Copyright <a href="#">Jacob Sites</a>.</p>
      <p class="rf">Layout by <a href="#">Jacob Sites 2011</a></p>
      <div class="clr"></div>
    </div>
  </div>
</div>
</body>
</html>