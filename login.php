<?php
	session_start();
	if(isset($_GET['logout'])) {
		session_destroy();
	}
	if(isset($_SESSION['login'])) {
		if($_SESSION['login'] == true) {
			header("location: home");
		}
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Event Manager | Login</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="style/login.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <div class="top_graphic">
    	<div class="bottom_graphic">
            <div class="register">
                <form action="register" method="post">
                   <img src="images/text/registracija.png" align="right" alt="Registracija" /><br />
                	<table width="100%">
                    	<tr>
                        	<td width="150px"><span>Ime:</span></td>
                            <td><input name="reg_name" type="text" class="empty" value="Ime" size="15" /></td>
                        </tr>
                        <tr>
                        	<td>
                        		<span>Priimek:</span>
                            </td>
                        	<td>
                            	<input name="reg_lastname" type="text" class="empty" value="Priimek" /><br />
                            </td>
                        </tr>
                        <tr>
                        	<td>
                        		<span>Email:</span>
                            </td>
                        	<td>
                            	<input name="reg_email" type="email" class="empty" value="Email" /><br />
                            </td>
                        </tr>
                        <tr>
                        	<td>
                        		<span>Uporabniško ime:</span>
                            </td>
                        	<td>
                            	<input name="reg_username" type="text" class="empty" value="Uporabniško ime" /><br />
                            </td>
                        </tr>
                        <tr>
                        	<td>
                        		<span>Geslo:</span>
                            </td>
                        	<td>
                            	<input name="reg_password" type="password" class="empty" value="xxxxxxx" /><br />
                            </td>
                        </tr>
                        <tr>
                        	<td>
                        		<span>Ponovno geslo:</span>
                            </td>
                        	<td>
                            	<input name="reg_rpassword" type="password" class="empty" value="xxxxxxx" /><br />
                            </td>
                        </tr>
                        <tr>
                        	<td></td>
                        	<td>
                            	<input name="reg_submit" type="submit" value="Registracija" class="prijava" />
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        	<div>&nbsp;</div>
            <div class="logo_box">
            	<div class="logo">
                	<h1><font>Event</font>Manager</h1>
                </div>
            </div>
        	<div class="login">
                <form action="loginpage" method="post">
                	<img src="images/text/prijava.png" alt="Prijava" /><br />
                    <span>Uporabniško ime:</span><br />
                    <input type="text" class="empty" value="Username" name="username" /><br />
                    <span>Geslo:</span><br />
                    <input type="password" class="empty" value="Password" name="password" /><br />
                    <div class="center"><input name="login_submit" type="submit" value="Prijava" class="prijava" /></div>
                </form>
                <?php if(isset($_GET['wrong_login'])) { ?>
                <p class="napaka">
                	Vnesli ste napačno uporabniško ime ali geslo!
                </p>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="footer">
        <div class="footer_resize">
            <p class="lf">&copy; Copyright <a href="#">Jacob Sites</a>.</p>
            <p class="rf">Layout by <a href="#">Jacob Sites 2011</a></p>
            <div class="clr"></div>
        </div>
    </div>
    <script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
    <script type="text/javascript">
    	$().ready(function() {
			$("input").focus(function() {
				//console.log("Focus in!");
				if(this.value == this.defaultValue) {
					this.value = '';
					$(this).removeClass("empty");
				}
			});
			$(":text").focusout(function() {
				//console.log("Focus out!");
				if(this.value == '') {
					this.value = this.defaultValue;
					$(this).addClass("empty");
				}
			});
			
		});
    
    </script>
</body>
</html>
