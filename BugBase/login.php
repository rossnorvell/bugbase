<?php
	//session_cache_expire(60);//1 hour
	session_start();
	require_once('config.php');
	
	//Array to store validation errors
	$errmsg_arr = array();
	
	//Validation error flag
	$errflag = false;
	
	//Connect to mysql server
	$link = mysql_connect(DB_HOST, DB_USER, DB_PASS);
	if(!$link) {
		die('Failed to connect to server: ' . mysql_error());
	}
	
	//Select database
	$db = mysql_select_db(DB_NAME);
	if(!$db) {
		die("Unable to select database");
	}
	
	//Function to sanitize values received from the form. Prevents SQL injection, but needs a connection before working
	function clean($str) {
		$str = trim($str);
		if(get_magic_quotes_gpc()) {
			$str = stripslashes($str);
		}
		return mysql_real_escape_string($str);
	}
	
	//Sanitize the POST values
	$username = clean($_POST['username']);
	$password = clean($_POST['password']);
	
	//Input Validations
	if($username== '') {
		$errmsg_arr[] = 'Login ID missing';
		$errflag = true;
	}
	if($password == '') {
		$errmsg_arr[] = 'Password missing';
		$errflag = true;
	}
	
	//If there are input validations, redirect back to the login form
	if($errflag) {
		$_SESSION['ERRMSG_ARR'] = $errmsg_arr;
		session_write_close();
		header("location: index.php");
		exit();
	}
	
	//Create query
	$qry="SELECT signature,phone,phone_ext,email,firstname,lastname,staff_id,group_id,dept_id,username,isactive,isadmin,isvisible
			FROM ost_staff
			WHERE username='$username' AND passwd='".md5($_POST['password'])."'";
	$result=mysql_query($qry);
	//Check whether the query was successful or not
	if($result) {
			//Login Successful
			session_regenerate_id();
			while ($member = mysql_fetch_array($result, MYSQL_ASSOC)) {
				//these are going to be the same throughout...
				$_SESSION['STAFF_ID'] = $member['staff_id'];
				$_SESSION['GROUP_ID'] = $member['group_id'];
				$_SESSION['DEPT_ID'] = $member['dept_id'];
				$_SESSION['USERNAME'] = $member['username'];
				$_SESSION['ISACTIVE'] = $member['isactive'];
				$_SESSION['ISADMIN'] = $member['isadmin'];
				$_SESSION['ISVISIBLE'] = $member['isvisible'];
				$_SESSION['FIRST_NAME'] = $member['firstname'];
				$_SESSION['LAST_NAME'] = $member['lastname'];
				$_SESSION['EMAIL'] = $member['email'];
				$_SESSION['PHONE'] = $member['phone'];
				$_SESSION['PHONE_EXT'] = $member['phone_ext'];
				$_SESSION['SIGNATURE'] = $member['signature'];
				
			}
			$_SESSION['notification']="";//for now, we'll make it so messages appear also.
			session_write_close();
			header("location: home.php");
			exit();			
		}
	else {
		//Login failed
		$_SESSION['ERRMSG_ARR']=array("Incorrect username or password...");
		header("location: index.php");
		exit();
	}
?>
