<?php require_once('auth.php');
	
	$results=array();
	require_once('config.php');
	$link = mysql_connect(DB_HOST, DB_USER,DB_PASS) or die("Blibber bobber bloober");
	mysql_select_db(DB_NAME) or die("The world has ended, everything is lost");
	$key = sanitize($_GET['term']);
	
	$qry="SELECT ticket_id,ticketID,subject FROM ost_ticket WHERE STATUS = 'open' AND (subject LIKE '%".$key."%' OR ticketID LIKE '%".$key."%')";//adjust this to the prefered results # e.g. 25
	if(!$_SESSION['ISADMIN']){
		$qry=$qry." AND ost_ticket.dept_id='".$_SESSION['DEPT_ID']."' ";
	}
	$res = mysql_query($qry);
	while($row = mysql_fetch_assoc($res)){
		$format['id']=$row['ticket_id'];
		$format['label']=$row['ticketID']." ".$row['subject'];
		$format['value']=$row['subject'];
		array_push($results,$format);
	}
	mysql_close($link);
	echo json_encode($results);
?>