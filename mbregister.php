<?php
	include('header.php');
?>

<body>

	<div id="header">

		<h2>Message Board</h2>

	</div>

	<div id="login">

		<p>

			<?php

				echo '<h4>Welcome';

				if (isset($_SESSION['first_name']))
				{
					echo ", {$_SESSION['first_name']}!";
				}
				
				echo '</h4>';

				if (isset($_SESSION['user_id']) AND (substr($_SERVER['PHP_SELF'], -10) != 'logout.php'))
				{
					echo '<a href="logout.php">Logout</a><br /> <a href="change_password.php">Change Password</a><br />';
				} else 
				{
					echo '<a href="register.php">Register</a><br /> <a href="login.php">Login to your account</a><br />';
				}

			?>

		</p>

	</div>

	<div id="main">
		
		<?php

		if (isset($_POST['submitted']))
		{
			// If last name is valid

			if (preg_match('%^[-_a-zA-z ]{2,2-}$%', stripslashes(trim($_POST['lastname'])))) 
			{
				$ln = escape_data($_POST['lastname']);
			} else
			{
			$ln = FALSE;
			echo '<p><font color="red" size="+1">Please enter a valid last name</font></p>';
			}
		}

		// If first name is valid

			if (preg_match('%^[-_a-zA-z ]{2,2-}$%', stripslashes(trim($_POST['firstname'])))) 
			{
				$fn = escape_data($_POST['firstname']);
			} else
			{
			$fn = FALSE;
			echo '<p><font color="red" size="+1">Please enter a valid first name</font></p>';
			}
		// If email is valid

			if (preg_match('%^[A-Za-z0-9._\%-]+@[A-Za-z0-9._\%-]+\.[A-Za-Z]{2,4}$%', stripslashes(trim($_POST['email'])))) {
				$e = escape_data($_POST['email']);
			} else
			{
			$e = FALSE;
			echo '<p><font color="red" size="+1">Please enter a valid email</font></p>';
			}
		// If first username is valid

			if (preg_match('%\A(?=[-_a-zA-Z0-9]*?[A-Z])(?=[-_a-zA-Z0-9]*?[a-z])(?=[-_a-zA-Z0-9]*?[0-9])\S{12,}\z%', stripslashes(trim($_POST['userid'])))) 
			{
				$ui = escape_data($_POST['userid']);
			} else 
			{
			$ui = FALSE;
			echo '<p><font color="red" size="+1">Please enter a valid username</font></p>';
			}
		// If first password is valid

			if (preg_match('%\A(?=[-_a-zA-Z0-9]*?[A-Z])(?=[-_a-zA-Z0-9]*?[a-z])(?=[-_a-zA-Z0-9]*?[0-9])\S{12,}\z%', stripslashes(trim($_POST['password1'])))) 
			{
				if (($_POST['password1'] == $_POST['password2']) && ($_POST['password1'] != $_POST['userid'])) 
				{
				$p = escape_data($_POST['password1']);
				} elseif ($_POST['password1'] == $_POST['userid']) 
				{
					$p = FALSE;
					echo '<p><font color="red" size="+1">Your password can not be equal to the userid</font></p>';
				} else 
				{
					$p = FALSE;
					echo '<p><font color="red" size="+1">Your password doesnt match</font></p>';
				}
			}

			//PHP Captcha

				if(isset($_POST['g-recaptcha-response']))
				{
				$captcha=$_POST['g-recaptcha-response'];
        		}
        		if(!$captcha){
          			echo '<h2>Please check the captcha</h2>';
					exit;
        		}
        		$response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LdVMRoUAAAAAMkNf2-3jOdwRhHA9MNVaJAEfRoM&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']);
        		if($response.success==false) 
        		{	
          			echo '<h2>Please check the captcha again</h2>';
        	
        		}else 
        		{
          			echo '<h2>Thanks for posting comment.</h2>';
    			}
    			If ($fn && $ln && $e && $p && $ui && $response)
    			{
    				$query = "SELECT username FROM users WHERE username='$ui'";
    				$result = mysql_query ($query) or trigger_error("Sorry there is an account assigned to that userid");

    				if (mysql_num_rows($result)  == 0)
    				{
    					$a = md5(uniquid(rand(), true));

    					$query = mysql_query ($query) or trigger_error("Sorry an error occured");

    					if (mysql_affect_rows() == 1)
    					{
    						$body = "Thank you for registering. To activate account click this link";

    						$body .= "http://localhost/forum/mbactivate.php?x=" . mysql_insert_id() . $y=$a;

    						mail($_POST['email'], 'Registration Confirmed', $body, 'From:djlovelyvibes@gmail.com');

    						echo '<br/>Thank you for registering a confirmation email has been sent.</h3>';

    						exit();
    					} else {
    						echo '<p><font color="red" size="+1">Sorry there was an error.</font></p>';
    					}
    				} else {
    					echo '<p><font color="red" size="+1">Sorry there was an error.</font></p>';
    				}
    				mysql_close();
    			}
 

?>

	</div>

	<h1>Register</h1>

	<form action="mbregister.php" method="post">

		<fieldset>

		<p><b>First name:</b><input type="text" name="firstname" size="20" maxlength="20" value="<?php if (isset($_POST[firstname])) echo ['firstname']; ?>" /></p>

		<p><b>Last name:</b><input type="text" name="lastname" size="30" maxlength="30" value="<?php if (isset($_POST[lastname])) echo ['lastname']; ?>" /></p>

		<p><b>Email Address:</b><input type="text" name="email" size="40" maxlength="40" value="<?php if (isset($_POST[email])) echo ['email']; ?>" /></p>

		<p><b>Username:</b><input type="password" name="userid" size="20" maxlength="20" /><small>Must contain a letter of both cases, minimum length of 8 characters.</small></p>

		<p><b>Password:</b><input type="password" name="password1" size="20" /><small>Must contain a letter of both cases, minimum length of 8 characters.</small></p>

		<p><b>Confirm Password:</b><input type="password" name="password2" size="20" /><small>Must contain a letter of both cases, minimum length of 8 characters.</small></p>

		<div class="g-recaptcha" data-sitekey="6LdVMRoUAAAAAJI10wtTMoGnunS1NXNsaQbJd-Re"></div>

		</fieldset>

		<div align="center">

			<input type="submit" name="submit" value="Register"></div>

			<input type="hidden" name="submitted" value="TRUE" />

		</form>

		</div>

	</form>

</body>