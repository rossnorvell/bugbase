<?php
	require_once('auth.php');
	require_once('config.php');
	require_once('scripts.php');
	$modules=array('tabs','mass_tickets','search');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
 <head>
   <title>BugBase| Home</title>
	<?php onload($modules); ?>
 </head>
 
<body>

<div id="container">
	<?php top(); /* get the top area with name, logo, pref etc... */ 
		  tabs();/* make those tabs, tickets is always the wild card though */
	?>
	<div id="tickets">
		<?php 
			require_once('config.php');
			$link = mysql_connect(DB_HOST, DB_USER,DB_PASS) or die("Blibber bobber");
			mysql_select_db(DB_NAME) or die("db blobber");
			panel();
			if(isset($_GET['search'])){
				$key=strtolower($_GET['search']);
				$key=sanitize(preg_replace("/(\s){1,}/",'%',$key));
			}
			if(isset($_GET['special'])){
				$key=strtolower($_GET['special']);
				$key=sanitize(preg_replace("/(\s[()-_]){1,}/",'%',$key));
			}
		?>
			
			<div style="margin-left:25px;margin-bottom:10px;">
				<input type="text" size="50" id="search" /> <button id="search_button">Bug Search</button>
				<div style="float:right;">
				<?php
					$display=0;
					if(isset($_GET['p'])){$display=sanitize($_GET['p']);echo "Page - ".(($display/25)+1);}
				?>
				</div>
			</div>
			
			<table class="datatable" summary="Data Sheet">
				<thead>
					<tr>
						<th scope="col"> </th><!-- this one is used for checkboxes -->
						<th scope="col">Ticket</th>
						<th scope="col">Date</th>
						<th scope="col">Subject</th>
						<th scope="col">Department</th>
						<th scope="col">Priority</th>
						<th scope="col">From</th>
					</tr>
				</thead>
				<tbody>
					<?php
						/* 4open tickets */
						$qry="SELECT phone,ticket_id,name, ticketID, dept_name, ost_ticket.created, subject, priority_color, priority
								FROM `ost_ticket`
								JOIN ost_department ON ost_department.dept_id = ost_ticket.dept_id
								JOIN ost_ticket_priority ON ost_ticket.priority_id = ost_ticket_priority.priority_id
								WHERE status='open'";
						
						if(isset($_GET['overdue'])){
							$qry="SELECT phone,ticket_id,name, ticketID, dept_name, ost_ticket.created, subject, priority_color, priority
								FROM `ost_ticket`
								JOIN ost_department ON ost_department.dept_id = ost_ticket.dept_id
								JOIN ost_ticket_priority ON ost_ticket.priority_id = ost_ticket_priority.priority_id
								WHERE isoverdue!=0";
						}
						if(isset($_GET['closed'])){
							$qry="SELECT phone,ticket_id,name, ticketID, dept_name, ost_ticket.created, subject, priority_color, priority
								FROM `ost_ticket`
								JOIN ost_department ON ost_department.dept_id = ost_ticket.dept_id
								JOIN ost_ticket_priority ON ost_ticket.priority_id = ost_ticket_priority.priority_id
								WHERE status='closed'";
						}
						if(isset($_GET['special'])){
							$qry=$qry." AND (LOWER(subject) LIKE '%".$key."%' OR LOWER(name) LIKE '%".$key."%' OR LOWER(phone) LIKE '%".$key."%' OR LOWER(ticketID) LIKE '%".$key."%' OR LOWER(dept_name) LIKE '%".$key."%') ";
						}
						if(isset($_GET['search'])){
							$qry=$qry." AND LOWER(subject) LIKE '%".$key."%' ";
						}
						if(!$_SESSION['ISADMIN']){
							$qry=$qry." AND ost_ticket.dept_id='".$_SESSION['DEPT_ID']."' ";
						}
						$qry=$qry." ORDER BY priority_urgency ASC , ost_ticket.created DESC";
						$numrows=mysql_num_rows(mysql_query($qry));
						
						$qry=$qry." LIMIT ".$display.",".($display+25);//adjust this to the prefered results # e.g. 25
						$res = mysql_query($qry);
						$count=0;
						while($row = mysql_fetch_assoc($res)){
							if($count%2==0){$class ="";}
							else{$class="odd";}
							
							$t_id = $row['ticket_id'];
							echo "<tr class=".$class.">";
							echo "<td><input type=\"checkbox\" id=\"".$t_id."\" /></td>";
								echo "<td><a href='view.php?ticket=".$t_id."'>".$row['ticketID']."</a></td>";
								echo "<td><a href='view.php?ticket=".$t_id."'>".gmdate('m/d/y',strtotime($row['created']))."</a></td>";
								echo "<td><a href='view.php?ticket=".$t_id."'>".$row['subject']."</a></td>";
								echo "<td><a href='view.php?ticket=".$t_id."'>".$row['dept_name']."</a></td>";
								echo "<td style='background-color:".$row['priority_color']."'>".$row['priority']."</a></td>";
								echo "<td><a href='view.php?ticket=".$t_id."'>".$row['name']."</a></td>";
							echo "</tr>";	
							$count++;
						}
						$prev="";
						if(isset($_GET['overdue'])){$prev.="&overdue=1";}
						if(isset($_GET['closed'])){$prev.="&closed=1";}
						if(isset($_GET['search'])){$prev.="&search=".(preg_replace("/(%){1,}/",'_',$key));}
						if(isset($_GET['special'])){$prev.="&special=".(preg_replace("/(%){1,}/",'_',$key));}
						
						/* 
							If this is the case & there are no rows, you should do a did you mean with levenstien distance
							the tables in the back end can come from a like special query using a substring of the key.
						*/
						if($numrows<1){echo "<tr><td></td><td></td><td></td><td>No Results.</td><td></td><td></td><td></td></tr>";}
						echo "<tr><td></td><td></td><td></td><td><a style='color:blue' href='home.php?special=".(preg_replace("/(%){1,}/",'_',$key)).$prev."'>Try Smart Search</a></td><td></td><td></td><td></td></tr>";
						
						mysql_close($link);
					?>
	
				</tbody>
			</table>
			<div style="text-align:center;margin-top:10px;">
				<button id='mass_delete'>Delete Ticket(s)</button>
				<?php 
				if(!isset($_GET['closed'])){?>
					<button style="margin-left:10px;" id='mass_close'>Close Ticket(s)</button>
					<button style="margin-left:10px;" id='mass_overdue'>Overdue Ticket(s)</button>
				<?php 
				}
				?>
			</div>
			<?php 
				$num=$numrows;
			if($num>25){					
			?>
				<p>Page: <a href="home.php<?php echo "?p=0".$prev;?>">[1]</a>
				<?php
					$count=1;
					$param=(isset($_GET['overdue']))?"?overdue=1":(isset($_GET['closed'])?"?closed=1":"");
				
					while($count*25<($num-25)){
						echo " <a href='home.php".$param."?p=".($count*25).$prev."'>[".($count+1)."]</a>";
						$count++;
					}
				?>
				</p>
			<?php } ?>
		</div>
	</div><!-- end tabs -->

</div><!-- end container -->

</body>
</html>