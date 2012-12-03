<?php
	require_once('auth.php');
	require_once('config.php');
	require_once('scripts.php');
	$modules=array('tabs','uploader','reply_box'=>'reply_box','hideNotes','actionBar');
	
	$link = mysql_connect(DB_HOST, DB_USER,DB_PASS) or die("Blibber bobber");
	mysql_select_db(DB_NAME) or die("blabber blobber");	
	$ticket_id=sanitize($_GET['ticket']);
	
	 /* fill up some variables */
	$qry="SELECT isoverdue,closed,ticketID, dept_name, ost_ticket.priority_id,priority_color, firstname, lastname, ost_ticket.email, source, ip_address,priority_desc, topic_id, subject, ost_ticket.phone,ost_ticket.phone_ext, ip_address, name,
		status , ost_ticket.created, duedate, lastresponse, lastmessage, helptopic
		FROM `ost_ticket`
		JOIN ost_ticket_priority ON ost_ticket.priority_id = ost_ticket_priority.priority_id
		JOIN ost_department ON ost_ticket.dept_id = ost_department.dept_id
		JOIN ost_staff ON ost_ticket.staff_id = ost_staff.staff_id
		WHERE ticket_id = '".$ticket_id."'
		LIMIT 1";
	$res = mysql_query($qry);
	$row = mysql_fetch_assoc($res);
	//the dude was not assigned or taken out of db
	if(count($row)<=1){
		$qry="SELECT isoverdue,closed,ticketID, dept_name, ost_ticket.priority_id,priority_color,ost_ticket.email, source, ip_address,priority_desc, topic_id, subject, ost_ticket.phone,ost_ticket.phone_ext, ip_address, name,
		status , ost_ticket.created, duedate, lastresponse, lastmessage, helptopic
		FROM `ost_ticket`
		JOIN ost_ticket_priority ON ost_ticket.priority_id = ost_ticket_priority.priority_id
		JOIN ost_department ON ost_ticket.dept_id = ost_department.dept_id
		WHERE ticket_id = '".$ticket_id."'
		LIMIT 1";
		$res = mysql_query($qry);
		$row = mysql_fetch_assoc($res);
		$row['firstname']="-- unassigned --";
		$row['lastname']="";
	}
	$isClosed = ($row['status']=='closed');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
 <head>
   <title>BugBase| Ticket #<?php echo $row['ticketID']; ?></title>
	<?php
		onload($modules,$ticket_id);
	?>
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
		
			
			<?php panel(); ?>
			
			<div style="float:left;">
				<table style="width:350px;"  cellspacing="1" cellpadding="3" class="datatable" summary="Data Sheet">
					<thead>
						<tr>
							<th scope="col"><a href="view.php?ticket=<?php echo $ticket_id; ?>">Ticket #<?php echo $row['ticketID']; ?></a></th><!-- this one is used for Ticket# refresh -->
							<th scope="col"> </th>
							<th scope="col"> </th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><b>Status: </b></td>
							<td></td>
							<td><?php echo $row['status']; if($row['isoverdue'] && !$isClosed){echo " &amp; <span style='color:red;'>overdue!</span>";} ?></td>
						</tr>
						<tr>
							<td><b>Priority: </b></td>
							<td></td>
							<td style="background-color:<?php echo $row['priority_color']; ?>"><?php echo $row['priority_desc']; ?></td>
						</tr>
						<tr>
							<td><b>Department: </b></td>
							<td></td>
							<td><?php echo $row['dept_name']; ?></td>
						</tr>
						<tr>
							<td><b>Create Date: </b></td>
							<td></td>
							<td><?php echo ($row['created']==null)?"---":date('m.d.y',strtotime($row['created'])); ?></td>
						</tr>
						
					</tbody>
				</table>
			</div>
			<!-- top right, margin on right is to push the other table away -->
			<div style="float:left;margin-left:100px;margin-right:100px;">
				<table style="width:350px;" cellspacing="1" cellpadding="3" class="datatable" summary="Data Sheet">
					<thead>
						<tr>
							<th scope="col"><a href="<?php echo ($_SESSION['ISADMIN'])?("ticket.php?menu=edit&edit=".$ticket_id) : "#"; ?>"><?php echo ($_SESSION['ISADMIN'])?("Edit Ticket") : "&nbsp;---&nbsp;"; ?></a></th><!-- this one is used for Ticket# refresh -->
							<th scope="col"> </th>
							<th scope="col"> </th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><b>Name: </b></td>
							<td></td>
							<td><?php echo $row['name']; ?></td>
						</tr>
						<tr>
							<td><b>Email: </b></td>
							<td></td>
							<td><?php echo "<a href='mailto:".$row['email']."'>".$row['email']."</a>"; ?></td>
						</tr>
						<tr>
							<td><b>Phone: </b></td>
							<td></td>
							<td><?php echo $row['phone']." ".(($row['phone_ext']!=null)?("Ext.".$row['phone_ext']):""); ?></td>
						</tr>
						<tr>
							<td><b>Source	: </b></td>
							<td></td>
							<td><?php echo $row['source']; ?></td>
						</tr>
					</tbody>
				</table>
			</div>
		<br/>
			<!-- bottom left -->
			<div style="float:left;margin-top:10px;">
				<table style="width:350px;" cellspacing="1" cellpadding="3" class="datatable" summary="Data Sheet">
					<thead>
						<tr>
							<a style="margin-left:25px; color: #003399;font-size: 14px;font-weight: normal;">Subject: <?php echo $row['subject']; ?></a>
							<th  scope="col"> </th>
							<th  scope="col"> </th>
							<th  scope="col"> </th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><b>Assigned Staff: </b></td>
							<td></td>
							<td><em><?php echo $row['firstname']." ",$row['lastname']; ?></em></td>
						</tr>
						<tr>
							<td><b>Last Reponse: </b></td>
							<td></td>
							<td><?php echo ($row['lastresponse']==null)?"---":date('m.d.y',strtotime($row['lastresponse']));; ?></td>
						</tr>
						<tr>
							<td><b>Due Date: </b></td>
							<td></td>
							<td><?php echo ($row['duedate']==null)?"---":date('m.d.y',strtotime($row['duedate']));; ?></td>
						</tr>
						
					</tbody>
				</table>
			</div>
			<!-- bottom right -->
			<div style="float:left;margin-left:100px;margin-top:30px;">
				<table style="width:350px;" cellspacing="1" cellpadding="3" class="datatable" summary="Data Sheet">
					<thead>
						<tr>
							<th scope="col"> </th>
							<th scope="col"> </th>
							<th scope="col"> </th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><b>Help Topic: </b></td>
							<td></td>
							<td><?php echo $row['helptopic']; ?></td>
						</tr>
						<tr>
							<td><b>IP Address: </b></td>
							<td></td>
							<td><?php echo $row['ip_address']; ?></td>
						</tr>
						<tr>
							<td><b>Last Message: </b></td>
							<td></td>
							<td><?php echo ($row['lastmessage']==null)?"---":date('m.d.y',strtotime($row['lastmessage']));; ?></td>
						</tr>
						
					</tbody>
				</table>
			</div>
			<!-- spacer for tables -->
			<div style="width:100%;height:300px;"></div>
			<!-- action for the ticket bar -->
			<div class="action">
				<select style="height:30px;width:150px;" id="ticket_action" onchange="$('#priority_action').attr('disabled',!(this.value=='change' || this.value=='overdue' || this.value=='reopen'));">
					<option value="" > Select Action </option>
					<option value="change" >Change Priority</option>
					<option value="overdue">Mark Overdue</option>
					<option value="close">Close Ticket</option>
					<?php if($_SESSION['ISADMIN']){?>	
					<option value="banClose">Ban Email &amp; Close</option>
					<option value="delete">Delete Ticket</option>					
					<?php 
					}
					if($isClosed){
					?>
						<option value="reopen">Re-Open</option>
					<?php
					}
					?>
				</select>
				<select style="margin-left:20px;height:30px;width:150px;" disabled="disabled" id="priority_action">
					<?php
						$qry = "SELECT priority_id, priority_desc , priority_color FROM `ost_ticket_priority`";
						$res = mysql_query($qry);
						while($row = mysql_fetch_assoc($res)){
							echo "<option style='background-color:".$row['priority_color']."' value=\"".$row['priority_id']."\">".$row['priority_desc']."</option>\n";
						}					
					?>
				</select>
				<button style="margin-left:10px;" onclick="$(this).attr('disabled',true);" id="action">Go</button>
				<div id='loading'></div>
			</div>
			<hr/>
			<?php
				$qry = "SELECT COUNT(*) FROM `ost_ticket_note` WHERE ticket_id='".$ticket_id."'";
				$res = mysql_query($qry);
				$row = mysql_fetch_row($res);
				$numnotes = $row[0];
				
				/* get the thread's count */
				$qry = "SELECT COUNT(*) FROM `ost_ticket_response` WHERE ticket_id='".$ticket_id."'";
				$res = mysql_query($qry);
				$row = mysql_fetch_row($res);
				$others = $row[0];
				
				$qry = "SELECT COUNT(*) FROM `ost_ticket_message` WHERE ticket_id='".$ticket_id."'";
				$res = mysql_query($qry);
				$row = mysql_fetch_row($res);
				$others = $others + $row[0];
				
				if($numnotes>0){
					?>
					<div style="margin-top:10px;">
						<button onclick="if($('#notes4ticket').is(':visible')){$('#notes4ticket').fadeOut(500,function(){$(this).hide()});}else{$('#notes4ticket').fadeIn(500,function(){$(this).show()});}">Internal Notes (<?php echo $numnotes; ?>)</button>
					</div>
					<?php 
					} ?>
					
					<div id="notes4ticket">
					<?php
						$qry = "SELECT source,title,note,created FROM `ost_ticket_note` WHERE ticket_id='".$ticket_id."'";
						$res = mysql_query($qry);
						while($row = mysql_fetch_assoc($res)){
							echo "<div class='note'>\n";
							echo "<div class='time'>".date("F jS, Y - g:ia",strtotime($row['created']))." by ".$row['source']."</div>";
							echo "<div class='message'>".nl2br(htmlspecialchars($row['title']))."<hr/><br/>".nl2br(htmlspecialchars($row['note']))."</div>";
							echo "</div>";
						}
					?>
					</div>
					
				
			<div style="margin-top:10px;">
				<button onclick="if($('#thread4ticket').is(':visible')){$('#thread4ticket').fadeOut(500,function(){$(this).hide()});}else{$('#thread4ticket').fadeIn(500,function(){$(this).show()});}">Toggle Thread (<?php echo $others; ?>)</button>
			</div>
			  <?php if(isset($modules['reply_box'])){?>
				<div id='thread4ticket'>
				<?php /* show threads on ticket */
					$qry = "SELECT msg_id,message,created FROM `ost_ticket_message` WHERE ticket_id='".$ticket_id."' ORDER BY created ASC";
					$res = mysql_query($qry);
				  	while($row = mysql_fetch_assoc($res)){
						echo "<div class='thread'>\n";
						echo "<div class='time'>".date("F jS, Y - g:iA",strtotime($row['created']))."</div>";
						/* get the attachment if it has one */
						$qry2="SELECT file_size, file_key, file_name, created FROM ost_ticket_attachment WHERE deleted !=1 AND ref_id = '".$row['msg_id']."'";
						$att = mysql_query($qry2);
						echo "<div class='message'>";
							while($filerow=mysql_fetch_assoc($att)){
								$dlink="download.php?file=".$filerow['file_name']."&key=".$filerow['file_key']."&created=".$filerow['created'];
								//$linkURL=ALT_ATTACHMENTS."/".date('my',strtotime($filerow['created']))."/".$filerow['file_key']."_".$filerow['file_name'];
								echo "<a href=".$dlink."><span style='color:blue;'>".($filerow['file_name'])."</span> <em style='color:#444444;'>(".($filerow['file_size']/1000)." kb)</em></a><br/>";
							}						
						echo nl2br(htmlspecialchars($row['message']))."</div>";
						echo "</div>";
					}
					/* show responses/replies on ticket */
					$qry = "SELECT response_id,response,staff_name,created FROM `ost_ticket_response` WHERE ticket_id='".$ticket_id."' ORDER BY created ASC";
					$res = mysql_query($qry);
					while($row = mysql_fetch_assoc($res)){
						echo "<div class='post'>\n";
						echo "<div class='time'>".date("F jS, Y - g:iA",strtotime($row['created']))." by ".$row['staff_name']."</div>";
						/* get the attachment(s) if it has one */
						$qry2="SELECT file_size, file_key, file_name, created FROM ost_ticket_attachment WHERE deleted !=1 AND ref_id = '".$row['response_id']."'";
						$att = mysql_query($qry2);
						echo "<div class='message'>";
							while($filerow=mysql_fetch_assoc($att)){
								$dlink="download.php?file=".$filerow['file_name']."&key=".$filerow['file_key']."&created=".$filerow['created'];
								//$linkURL=ALT_ATTACHMENTS."/".date('my',strtotime($filerow['created']))."/".$filerow['file_key']."_".$filerow['file_name'];
								echo "<a href=".$dlink."><span style='color:blue;'>".($filerow['file_name'])."</span> <em style='color:#444444;'>(".($filerow['file_size']/1000)." kb)</em></a><br/>";
							}
						
						echo nl2br(htmlspecialchars($row['response']))."</div>";
						echo "</div>";
					}
				?>
				</div>
				
			<?php
				reply_box();
			} /* end reply box */ 
			mysql_close($link);
			?>
		</div>
		
	</div><!-- end tabs -->

</div><!-- end container -->

</body>
</html>
