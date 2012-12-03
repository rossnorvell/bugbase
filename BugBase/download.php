<?php require_once('auth.php');
	require_once('config.php');
	function download($filename,$fileloc){
		header('Content-type: text/plain');
		//open/save dialog box
		header('Content-Disposition: attachment; filename="'.$filename.'"');
		//read from server and write to buffer
		readfile($fileloc);
	}
	$filerow=array('created'=>$_GET['created'],'file_key'=>$_GET['key'],'file_name'=>$_GET['file']);
	$fileloc=ALT_ATTACHMENTS."/".date('my',strtotime($filerow['created']))."/".$filerow['file_key']."_".$filerow['file_name'];
	download($filerow['file_name'],$fileloc);
?>