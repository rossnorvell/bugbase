<?php
	session_start();
	//Unset the variables stored in session
	$_SESSION = array(); 	
	if (isset($_SESSION)){     
		unset($_SESSION);     
		session_destroy();		
		$cookieParams = session_get_cookie_params();
		setcookie(session_name(), '', 1, $cookieParams['path'], $cookieParams['domain'], $cookieParams['secure'], $cookieParams['httponly']);
		session_unset();     
	}
?>
<a href="index.php">home</a>