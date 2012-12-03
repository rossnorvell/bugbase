<?php
	require_once('auth.php');
	require_once('config.php');
	
	function getRealIpAddr(){
		if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
		{
		  $ip=$_SERVER['HTTP_CLIENT_IP'];
		}
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
		{
		  $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else
		{
		  $ip=$_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
	/*--------------End Helper Functions---------------------------------*/
	
	
	if(isset($_POST['reply'])){
		$link = mysql_connect(DB_HOST, DB_USER,DB_PASS) or die("Blibber bobber gobber");
		mysql_select_db(DB_NAME) or die("blabber blobber blueber");
		
		$id=sanitize($_GET['ticket']);
		$reply = sanitize($_POST['reply']);
			$qry="SELECT msg_id FROM `ost_ticket_message` WHERE ticket_id ='".$id."' LIMIT 1";
			$res = mysql_query($qry) or die("Hamburger blurger");
			$row = mysql_fetch_row($res);
		$msg_id = $row[0];
		$ip = getRealIpAddr();
		//insert into ticket reponses
		$qry="INSERT INTO ost_ticket_response(msg_id,ticket_id,staff_id,staff_name,response,ip_address,created) VALUES('".$msg_id."','".$id."','".$_SESSION['STAFF_ID']."','".($_SESSION['FIRST_NAME']." ".$_SESSION['LAST_NAME'])."','".$reply."','".$ip."',NOW())";
		$res = mysql_query($qry) or die("Blarg.".mysql_error());
		echo "Reply Successful! I love your opinions...";
		//now update the attachments if there are any
		if(isset($_POST['attachments'])){
			$qry="SELECT max( response_id ) FROM `ost_ticket_response`";
			$res = mysql_query($qry) or die("Hum diggity");
			$row = mysql_fetch_row($res);
			$resp_id = $row[0];
			//we did this with ajax so we have uploaded them, but never actually submit the response until now
			foreach($_POST['attachments'] as $attach_id){
				$qry="UPDATE ost_ticket_attachment SET ref_id='".$resp_id."' WHERE attach_id='".sanitize($attach_id)."' LIMIT 1";
				$res = mysql_query($qry) or die("Jingle bells" .mysql_error());
			}
			echo "and you attachments";
		}
		
		mysql_close($link);
	}
	
	/* a quick update for the ticket, derived from action on view.php */
	if(isset($_POST['action'])){
		$option=$_POST['action'];
		
	  $link = mysql_connect(DB_HOST, DB_USER,DB_PASS) or die("Blibber bobber gobber");
	  mysql_select_db(DB_NAME) or die("blabber blobber blueber");
		
		$id=sanitize($_GET['ticket']);
		$prio = sanitize($_POST['priority']);
			
		switch ($option) {
			case "change":
				$qry="UPDATE ost_ticket SET priority_id = '".$prio."' WHERE ticket_id = '".$id."' LIMIT 1";
				$res = mysql_query($qry) or die("Hamburger blurger");
				echo "Priority Successfully Changed! You Rock.";
				break;
			case "overdue":
				$qry="UPDATE ost_ticket SET priority_id = '".$prio."',isoverdue='1' WHERE ticket_id = '".$id."' LIMIT 1";
				$res = mysql_query($qry) or die("Booger blurger");
				echo "Marked as Overdue Successful! You are a rockstar.";
				break;
			case "overdue_noproirity":
				$qry="UPDATE ost_ticket SET isoverdue='1' WHERE ticket_id = '".$id."' LIMIT 1";
				$res = mysql_query($qry) or die("Booger blurger");
				echo "Marked as Overdue Successful! You are a rockstar.";
				break;
			case "close":
				$qry="UPDATE ost_ticket SET status='closed',closed=NOW() WHERE ticket_id = '".$id."' LIMIT 1";
				$res = mysql_query($qry) or die("Booger blurger");
				echo "Ticket Closed Successfully! You are my favorite rockstar.";
				break;
			case "banClose":
				$qry="UPDATE ost_ticket SET status='closed',closed=NOW() WHERE ticket_id = '".$id."' LIMIT 1";
				$res = mysql_query($qry) or die("Booger blurger Tom.");
				$qry="INSERT INTO ost_email_banlist(email,submitter,added) VALUES('".sanitize($_POST['email'])."','System',NOW())";
				$res = mysql_query($qry) or die("Booger blurger Jerry!");
				echo "Ticket Closed &amp; Email Banned Successfully! You're so cool the way you handled that...";
				break;
			case "delete":
				$qry="SELECT * FROM ost_ticket_attachment WHERE ticket_id='".$id."' ";
				$res = mysql_query($qry) or die("Attach!!!! WE ARE SPARTA!");
				while($filerow=mysql_fetch_assoc($res)){
					$fileloc=ALT_ATTACHMENTS."/".date('my',strtotime($filerow['created']))."/".$filerow['file_key']."_".$filerow['file_name'];
					try{unlink($fileloc);/* actually delete the file */}
					catch(Exception $e){}
				}				
				/* Basically go through anything with ticket it & kill things with that id! */
				$tables = array('ost_ticket','ost_ticket_attachment','ost_ticket_message','ost_ticket_lock','ost_ticket_note','ost_ticket_response');
				foreach($tables as $t){
					$qry="DELETE FROM  ".$t." WHERE ticket_id = '".$id."'";
					$res = mysql_query($qry) or die("Booger blurger TABLE death.");
				}
				echo "Successfully Deleted Ticket! I cannot believe you actually did it though. You must be really brave.";
				break;
			case "reopen":
				$qry="UPDATE ost_ticket SET priority_id = '".$prio."', status='open',reopened=NOW() WHERE ticket_id = '".$id."' LIMIT 1";
				$res = mysql_query($qry) or die("Booger blurger");
				echo "Ticket Re-Opened Successfully! I am sorry you have to go through this again.";
				break;
			default:
				echo "Unable to process request...";
		}
	  mysql_close($link);
	}
	if(isset($_POST['note'])){
		$link = mysql_connect(DB_HOST, DB_USER,DB_PASS) or die("Blibber bobber BLOB");
		mysql_select_db(DB_NAME) or die("Bingle bop");
		
		$note=sanitize($_POST['note']);
		$title=sanitize($_POST['title']);
		$ticket_id=sanitize($_GET['ticket']);
		
		if($note!=""){
			$name=$_SESSION['FIRST_NAME']." ".$_SESSION['LAST_NAME'];
			$qry="INSERT INTO ost_ticket_note(ticket_id,staff_id,source,title,note,created) VALUES('".$ticket_id."','".$_SESSION['STAFF_ID']."','".$name."','".$title."','".$note."',NOW())";
			$res = mysql_query($qry) or die("Jig Jag");
		}
		mysql_close($link);
		echo "Note Posted Successful!";
	}
	if(isset($_POST['depttrans'])){
		$link = mysql_connect(DB_HOST, DB_USER,DB_PASS) or die("Blibber bobber BLOB");
		mysql_select_db(DB_NAME) or die("Bingle bop");
		
		$note=sanitize($_POST['deptnote']);
		$department=sanitize($_POST['depttrans']);
		$ticket_id=sanitize($_GET['ticket']);
		$qry="UPDATE ost_ticket SET dept_id='".$department."' WHERE ticket_id='".$ticket_id."' LIMIT 1";
		$res = mysql_query($qry) or die("Big Bag");
		
		if($note!=""){
			$name=$_SESSION['FIRST_NAME']." ".$_SESSION['LAST_NAME'];
			$qry="INSERT INTO ost_ticket_note(ticket_id,staff_id,source,title,note,created) VALUES('".$ticket_id."','".$_SESSION['STAFF_ID']."','".$name."','Department Transfer by ".$name."','".$note."',NOW())";
			$res = mysql_query($qry) or die("Jig Jag 2");
		}
		mysql_close($link);
		echo "Ticket Transfer Successful!";
	}
	if(isset($_POST['reassign'])){
		$link = mysql_connect(DB_HOST, DB_USER,DB_PASS) or die("Blibber bobber BLOB");
		mysql_select_db(DB_NAME) or die("Bingle bop");
		
		$note=sanitize($_POST['staffnote']);
		$staff=sanitize($_POST['reassign']);
		$ticket_id=sanitize($_GET['ticket']);
		$qry="UPDATE ost_ticket SET staff_id='".$staff."' WHERE ticket_id='".$ticket_id."' LIMIT 1";
		$res = mysql_query($qry) or die("Jim boodf");
		
		if($note!=""){
			$name=$_SESSION['FIRST_NAME']." ".$_SESSION['LAST_NAME'];
			$qry="INSERT INTO ost_ticket_note(ticket_id,staff_id,source,title,note,created) VALUES('".$ticket_id."','".$_SESSION['STAFF_ID']."','".$name."','Department Transfer by ".$name."','".$note."',NOW())";
			$res = mysql_query($qry) or die("Jig Jag 2");
		}
		mysql_close($link);
		echo "Ticket Re-Assigned Successful!";
	}
	/* Update the ticket (from the edit menu below) */
	if(isset($_GET['update'])){
		$link = mysql_connect(DB_HOST, DB_USER,DB_PASS) or die("Blibber bobber BLOB");
		mysql_select_db(DB_NAME) or die("Jacklebobbin");
		
		//clean that dirty data...
		$ticket_id=sanitize($_GET['update']);
		foreach($_POST as $k=>$v){
			$_POST[$k]=sanitize($v);
		}
		$dateset=false;
		if($_POST['duedate']!=''){$dateset=true;}
		$duedate=explode('/',$_POST['duedate']);
		$qry="UPDATE ost_ticket SET email='".$_POST['email']."',name='".$_POST['name']."', subject='".$_POST['subject']."', phone='".$_POST['phone']."', phone_ext='".$_POST['phone_ext']."',".(($dateset)?("duedate='".(gmdate('Y-m-d H:i:s',mktime(0,0,0,$duedate[0],$duedate[1],$duedate[2])))."',"):"")."updated=NOW(),priority_id='".$_POST['priority_id']."',helptopic='".$_POST['helptopic']."' WHERE ticket_id='".$ticket_id."' LIMIT 1";
		$res = mysql_query($qry) or die("The world is ending! ");
		//also make a note in the db also
		$note = $_POST['innernote'];
		if($note!=""){
			$name=$_SESSION['FIRST_NAME']." ".$_SESSION['LAST_NAME'];
			$qry="INSERT INTO ost_ticket_note(ticket_id,staff_id,source,title,note,created) VALUES('".$ticket_id."','".$_SESSION['STAFF_ID']."','".$name."','Ticket Updated by ".$name."','".$note."',NOW())";
			$res = mysql_query($qry) or die("Bingo...right?");
		}
		echo "Update Successful! Wow, you just totally changed that data like a pro.";
		mysql_close($link);
	}
	/* Create a new ticket */
	if(isset($_GET['new'])){
		$link = mysql_connect(DB_HOST, DB_USER,DB_PASS) or die("pickle");
		mysql_select_db(DB_NAME) or die("Dig Doug");
		$data = superSanitize($_POST);
		
		require_once('util.php');
		$ticketID=randNumber();
		$duedate=explode('/',$data['duedate']);
		$qry="INSERT INTO 
		ost_ticket(`ticket_id`, `ticketID`, `dept_id`, `priority_id`, `topic_id`, `staff_id`, `email`, `name`, `subject`, `helptopic`, `phone`, `phone_ext`, `ip_address`, `status`, `source`, `isoverdue`, `isanswered` ".(($data['duedate']=='')?"":",`duedate`").",`created`) 
		 VALUES('".$data['ticket_id']."', '".$ticketID."', '".$data['dept_id']."', '".$data['priority_id']."', '".$data['helptopicID']."', '".$data['assigned']."', '".$data['email']."', '".$data['name']."', '".$data['subject']."', '".$data['helptopic']."', '".$data['phone']."', '".$data['phone_ext']."', '".getRealIpAddr()."', '"."open"."', '".$data['ticket_source']."', '"."0"."', '"."0"."',".(($data['duedate']=='')?"":(" '".(gmdate('Y-m-d H:i:s',mktime(0,0,0,$duedate[0],$duedate[1],$duedate[2])))."', "))." NOW()) ";
		mysql_query($qry) or die("that is a funny joke there bob.");
		//update attachments
		
		/* send email here depending on whether alert user or staff is checked */
		
		echo "New Ticket Created Successfully! I just cannot believe you did it SO fast.";
		//echo sendMail("ross.norvell@gmail.com","ross@softwaretech.com","Test Message","<p>Are <b>you</b> actually named <em>Ross</em> too!</p>")?"Mail Sent":"Mail Fail";
		mysql_close($link);
	}
	/* Ticket - menu, Edit & New */
	if(isset($_GET['menu'])){
		if($_GET['menu']=='new'){
			require_once('auth.php');
			require_once('config.php');
			require_once('scripts.php');
			$modules=array('tabs','uploader','new_ticket','datepicker');
			
			//first make up a ticket id for later use
			$link = mysql_connect(DB_HOST, DB_USER,DB_PASS) or die("StarWars Rocks.");
			mysql_select_db(DB_NAME) or die("Hey there suzen");
			$qry="SELECT MAX(ticket_id) FROM ost_ticket";
			$res = mysql_query($qry) or die("Wow, a flying catfish in brooklyn?");
			$row = mysql_fetch_row($res);
			$ticket_id = $row[0]+1;
			//get last count as oppose to max, sql could overwrite
			
		?>
			<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
			 <head>
			   <title>BugBase| New Ticket</title>
				<?php onload($modules,$ticket_id); ?>
			 </head>
			 
			<body>
			
			<div id="container">
				<?php top(); /* get the top area with name, logo, pref etc... */ 
					tabs();/* make those tabs, tickets is always the wild card though */
				?>
					<div id="tickets">
					<?php 
						panel();
					?>
						
						<div style="margin-left:25px;">
							<a style="color:#0073EA;" >New Ticket</a>
							<style>
								table tr td input{color:#555555;}
							</style>
							<table style="width:700px;">
								<tr><td>Email Address: </td><td><input id="email" type='text' name="email" value="" /><input type="checkbox" id="alert2user" />Send Alert to User</td></tr>
								<tr><td>Full Name: </td><td><input id="name" type='text' name="name" value=""/></td></tr>
								<tr><td>Telephone: </td><td><input id="phone" type='text' name="phone" value="" />  Ext.<input id="phone_ext" size="3" type='text' name="phone_ext" value="" /></td></tr>
								<tr height="25"><td> </td><td> </td></tr>
								<tr><td>Ticket Source: </td>
								<td>
									<select id="source">
										<option value="Phone">Phone</option>
										<option value="Email">Email</option>
										<option value="Other">Other</option>
									</select>
								</td>
								<tr><td>Department: </td>
								<td>
									<select id="department">
										<?php
											$qry = "SELECT dept_id,dept_name FROM `ost_department`";
											$res = mysql_query($qry);
											while($row = mysql_fetch_assoc($res)){
												echo "<option value=\"".$row['dept_id']."\">".$row['dept_name']."</option>\n";
											}					
										?>
									</select>
								</tr>
								<tr><td>Subject: </td><td><input id="subject" type='text' name="subject" value="" /></td></tr>
								<tr><td> </td><td><em>Visible to client.*</em></td></tr>
								<tr><td>Issue Summary: </td><td><textarea  id="summary" rows="4" cols="33" > </textarea></td></tr>
								<tr height="25"><td> </td><td> </td></tr>
								<tr><td>Attachments: </td>
								<td>
									<?php file_uploader(); ?>
								</td></tr>
								<tr><td>Due Date: </td><td><input id='datepicker' type='text' name="duedate" value="" /></td></tr>
								<tr><td>Priority: </td><td>
									<select id="priority" name='priority' style="width:204px;">
										<?php
											$qry = "SELECT priority_id, priority_desc , priority_color FROM `ost_ticket_priority`";
											$res = mysql_query($qry);
											while($row = mysql_fetch_assoc($res)){
												echo "<option style='background-color:".$row['priority_color']."' value=\"".$row['priority_id']."\">".$row['priority_desc']."</option>\n";
											}					
										?>
									</select>
								</td></tr>
								<tr><td>Help Topic: </td><td>
									<select id="helptopic" name='helptopic' style="width:204px;">
										<option value=""> </option>
										<?php
											$qry = "SELECT topic_id,topic FROM `ost_help_topic` ORDER BY topic";
											$res = mysql_query($qry);
											while($row = mysql_fetch_assoc($res)){
												echo "<option value=\"".$row['topic_id']."\">".$row['topic']."</option>\n";
											}
										?>
									</select></td></tr>
									<tr><td>Assign To: </td><td>
									<select id="assign" style="width:204px;">
										<option value=""> </option>
										<?php
											$qry = "SELECT staff_id,firstname,lastname FROM `ost_staff` ORDER BY lastname";
											$res = mysql_query($qry);
											while($row = mysql_fetch_assoc($res)){
												echo "<option value=\"".$row['staff_id']."\">".$row['lastname'].", ".$row['firstname']."</option>\n";
											}

											mysql_close($link);
										?>
									</select><input type='checkbox' id='alert2staff' />Send Alert To Staff</td></tr>
								</table><br/>
							<button id="ticket_new">Create</button>
						</div>
					</div>
					
				</div><!-- end tabs -->

			</div><!-- end container -->

			</body>
			</html>
		<?php	
		}
		
		/* edit menu, which will submit to ticket.php */
		if($_GET['menu']=='edit'){$ticket_id=$_GET['edit'];
			/* make sure only admins get this menu */
			if(!$_SESSION['ISADMIN']){
					header("location:home.php");
			}
			require_once('config.php');
			require_once('scripts.php');
			$modules=array('tabs','update_ticket','datepicker');
		?>
			
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
			 <head>
			   <title>BugBase| Edit Ticket</title>
				<?php onload($modules); ?>
			 </head>
			 
			<body>
			<div id="notice" title="Notice">
				<p id="notice_content"></p>
			</div>
			<div id="container">
				<?php top(); /* get the top area with name, logo, pref etc... */ 
					tabs();/* make those tabs, tickets is always the wild card though */
				?>
					<div id="tickets">
					<?php 
						require_once('config.php');
						$link = mysql_connect(DB_HOST, DB_USER,DB_PASS) or die("Blibber bobber");
						mysql_select_db(DB_NAME) or die("db blobber");	

						$ticket_id = sanitize($_GET['edit']);
						$qry="SELECT * FROM ost_ticket WHERE ticket_id='".$ticket_id."' LIMIT 1";
						$res = mysql_query($qry);
						$ticket_info = mysql_fetch_assoc($res);
						
						panel();
					?>
						
						<div style="margin-left:25px;">
							<a style="color:#0073EA;" href="view.php?ticket=<?php echo $ticket_id; ?>">Update Ticket #<?php echo $ticket_info['ticketID']; ?></a>
							<style>
								table tr td input{color:#CCC;}
							</style>
							<table style="width:700px;">
								<tr><td>Email Address: </td><td><input id="email" type='text' name="email" value="<?php echo $ticket_info['email']; ?>" /></td></tr>
								<tr><td>Full Name: </td><td><input id="name" type='text' name="name" value="<?php echo $ticket_info['name']; ?>"/></td></tr>
								<tr><td>Subject: </td><td><input id="subject" type='text' name="subject" value="<?php echo $ticket_info['subject']; ?>" /></td></tr>
								<tr><td>Telephone: </td><td><input id="phone" type='text' name="phone" value="<?php echo $ticket_info['phone']; ?>" />  Ext.<input id="phone_ext" size="3" type='text' name="phone_ext" value="<?php echo $ticket_info['phone_ext']; ?>" /></td></tr>
								<tr><td>Due Date: </td><td><input id='datepicker' type='text' name="duedate" value="<?php echo ($ticket_info['duedate']!=null)?gmdate('m/d/y',strtotime($ticket_info['duedate'])):""; ?>" /></td></tr>
								<tr><td>Priority: </td><td>
									<select id="priority" name='priority' style="width:204px;">
										<?php
											$qry = "SELECT priority_id, priority_desc , priority_color FROM `ost_ticket_priority`";
											$res = mysql_query($qry);
											while($row = mysql_fetch_assoc($res)){
												echo "<option ".(($row['priority_id']==$ticket_info['priority_id'])?" selected='selected' ":"")."style='background-color:".$row['priority_color']."' value=\"".$row['priority_id']."\">".$row['priority_desc']."</option>\n";
											}					
										?>
									</select>
								</td></tr>
								<tr><td>Help Topic: </td><td>
									<select id="helptopic" name='helptopic' style="width:204px;">
										<option value=""> </option>
										<?php
											$qry = "SELECT topic_id,topic FROM `ost_help_topic` ORDER BY topic";
											$res = mysql_query($qry);
											while($row = mysql_fetch_assoc($res)){
												echo "<option ".(($row['topic_id']==$ticket_info['topic_id'])?" selected='selected' ":"")." value=\"".$row['topic']."\">".$row['topic']."</option>\n";
											}					
										?>
									</select></td></tr>
								<tr><td></td><td><em>(Why did you update this?)</em></td></tr>
								<tr><td>Internal Note: </td><td><textarea  id="note" rows="4" cols="33" name="note"> </textarea></td></tr>
							</table>
							<button id="ticket_update">Update</button>
						</div>
					</div>
					
				</div><!-- end tabs -->

			</div><!-- end container -->

			</body>
			</html>
	<?php
		}/* end edit menu option */
		
	}
	/* end edit is option */
	
?>