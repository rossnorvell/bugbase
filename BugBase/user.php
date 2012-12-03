<?php require_once('auth.php');
		require_once('config.php');
		
	/* user updating user's information */
	if(isset($_GET['info'])){
		$link = mysql_connect(DB_HOST, DB_USER,DB_PASS) or die("Wong tongs");
		mysql_select_db(DB_NAME) or die("bing bongs");
		$data=superSanitize($_POST);
		/* now update session information so the user can see */
		if($data['firstname']!=''){$_SESSION['FIRST_NAME'] = $data['firstname'];}
		if($data['lastname']!=''){$_SESSION['LAST_NAME'] = $data['lastname'];}
		if($data['email']!=''){$_SESSION['EMAIL'] = $data['email'];}
		if($data['phone']!=''){$_SESSION['PHONE'] = $data['phone'];}
		if($data['phone_ext']!=''){$_SESSION['PHONE_EXT'] = $data['phone_ext'];}
		if($data['signature']!=''){$_SESSION['SIGNATURE'] = $data['signature'];}
		
		$qry="UPDATE ost_staff SET firstname='".$data['first']."',lastname='".$data['last']."',email='".$data['email']."',phone='".$data['phone']."',phone_ext='".$data['phone_ext']."',signature='".$data['signature']."' WHERE staff_id='".$_SESSION['STAFF_ID']."' LIMIT 1";
		mysql_query($qry) or die("it was bound to happen sometime.");
		mysql_close($link);
		
		echo "Successfully Updated Information.";
	}
	
	/* admin updating user's information */
	if(isset($_GET['admin_info'])){
	/* //also check if session is admin before 
		$link = mysql_connect(DB_HOST, DB_USER,DB_PASS) or die("Wong tongs");
		mysql_select_db(DB_NAME) or die("bing bongs");
		$data=superSanitize($_POST);
		
		if($data['firstname']!=''){$_SESSION['FIRST_NAME'] = $data['firstname'];}
		if($data['lastname']!=''){$_SESSION['LAST_NAME'] = $data['lastname'];}
		if($data['email']!=''){$_SESSION['EMAIL'] = $data['email'];}
		if($data['phone']!=''){$_SESSION['PHONE'] = $data['phone'];}
		if($data['phone_ext']!=''){$_SESSION['PHONE_EXT'] = $data['phone_ext'];}
		if($data['signature']!=''){$_SESSION['SIGNATURE'] = $data['signature'];}
		
		$qry="UPDATE ost_staff SET firstname='".$data['first']."',lastname='".$data['last']."',email='".$data['email']."',phone='".$data['phone']."',phone_ext='".$data['phone_ext']."',signature='".$data['signature']."' WHERE staff_id='".$_SESSION['STAFF_ID']."' LIMIT 1";
		mysql_query($qry) or die("it was bound to happen sometime.");
		mysql_close($link);
		
		echo "Successfully Updated Information.";
		
	*/
	}
	
	/* updating user's password */
	if(isset($_GET['pass'])){
		$link = mysql_connect(DB_HOST, DB_USER,DB_PASS) or die("Wong tongs");
		mysql_select_db(DB_NAME) or die("bing bongs");
		$qry="SELECT firstname FROM ost_staff WHERE passwd='".md5($_POST['current'])."' AND staff_id='".$_SESSION['STAFF_ID']."'";
		mysql_query($qry) or die("it was bound to happen sometime.");
		$res = mysql_query($qry);
		$num = mysql_num_rows($res);
		if($num==1){
			if(md5($_POST['verify'])==md5($_POST['newpass'])){
			$qry="UPDATE ost_staff SET passwd='".md5($_POST['verify'])."' WHERE staff_id='".$_SESSION['STAFF_ID']."' LIMIT 1";
			mysql_query($qry) or die("it was bound to happen sometime.");
			echo "Successfully Changed Password.";
			}
			else{echo "Password change unsuccessful, passwords do not match"; }
		}
		else{echo "Password change unsuccessful, current password not valid.";}
		
		mysql_close($link);		
	}
	
?>