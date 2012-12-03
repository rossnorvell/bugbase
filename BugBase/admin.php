<?php
	require_once('auth.php');
	require_once('config.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
 <head>
<link rel="icon" type="image/ico" href="favicon.ico" />

   <title>BugBase| Admin</title>
	<link rel="stylesheet" href="jquery-ui-custom/css/custom-theme/jquery-ui-1.8.20.custom.css">
	<script src="jquery-ui-custom/js/jquery-1.7.2.min.js"></script>
	<script src="jquery-ui-custom/js/jquery-ui-1.8.20.custom.min.js"></script>
	<script>
	$(function(){
		$("#tabs" ).tabs({
				fx: { opacity: 'toggle', duration:320}
			});	
		
		$("#add_dept_button").click(function(){
			$("#add_dept").hide();
			$("#add_dept").html("Name: <input type='text' id='dept_name' /> Email: <select id='dept_email'><?php 
				$link = mysql_connect(DB_HOST, DB_USER,DB_PASS) or die("Blibber bobber bloop");
				mysql_select_db(DB_NAME) or die("Jing blue blobber");
				$qry = "SELECT email_id,email FROM `ost_email`";
				$res = mysql_query($qry);
							
				while($row = mysql_fetch_assoc($res)){
					echo "<option value='".$row['email_id']."'>".$row['email']."</option>";
				}
					
			?></select> <input type='checkbox' id='dept_priv' />Private <button style='margin-left:30px;' onclick='mkdept()'>Make Department</button>");
			$("#add_dept").fadeIn(500);
		});
		
		// set the time for the beeper to be displayed as 5000 milli seconds (5 seconds)
		var timerId, delay = 2500;
		var a = $("#BeeperBox");
		//function to destroy the timeout
		function stopHide() {
			clearTimeout(timerId);
		}
		//function to display the beeper and hide it after a few seconds
		function showTip(text,callback) {
			$("#Beeper_content").html(text);
			a.fadeIn('slow', function(){});
			timerId = setTimeout(function () {
				a.fadeOut('slow', callback);
			}, delay);
		}
		//function to hide the beeper after a few seconds
		function startHide(){
			timerId = setTimeout(function () {
				a.fadeOut('slow');
			}, delay);
		}
		
		//Clear timeout to hide beeper on mouseover
		//start timeout to hide beeper on mouseout
		a.mouseenter(stopHide).mouseleave(startHide);
		$('.beeper_x').click(function () {
			//hide the beeper when the close button on the beeper is clicked
			$("#BeeperBox").fadeOut('slow', function(){window.location.reload();});
		});
		
		
		function tester(){console.log("Hits..");}
		$("#tester").click(function(){showTip('settings test.... oh yea baby.',tester);return false;});
		
		
		
	});
	
	function mkdept(){
		console.log("In theory, department has been made....");
	}
	</script>
	<!-- take over conflicting styles here -->
	<link rel="stylesheet" href="style.css">
 </head>
 
<body>

<div id="BeeperBox" class="UIBeeper">
 <div class="UIBeeper_Full">
	<div class="Beeps">
	   <div class="UIBeep UIBeep_Top UIBeep_Bottom UIBeep_Selected" style="opacity: 1; ">
	   <!-- Below Is The Link To Which Bepper Will Point To (replace # with the required link) -->
		  <a class="UIBeep_NonIntentional" href="#">
			 <div class="UIBeep_Icon">
				<i class="beeper_icon image2"></i>
			 </div>
			 <span class="beeper_x">&nbsp;</span>
			 <div id="Beeper_content" class="UIBeep_Title">
				blah blah bla... and a blue flower.
			 </div>
		  </a>
	   </div>
	</div>
 </div>
</div>

<div id="container">
	<div class="top">
		<div style="float:left;"><img src="images/logo.png" height="100px" title="BugBase" alt="BugBase" border=0 /></div>
		<div class="name">
			<p>Hey <?php echo $_SESSION['FIRST_NAME']; ?>! - 
			<a href="home.php">Home Panel</a> |			
			<a href="home.php?tab=2" >My Preferences</a> | <a href="index.php"> Log Out</a>
			</p>
		</div>
	</div>
	<div id="tabs">
		<ul>
			<li><a href='#Settings'>Settings</a></li>
			<li><a href='#Emails'>Emails</a></li>
			<li><a href='#HelpTopics'>Help Topics</a></li>
			<li><a href='#Staff'>Staff</a></li>
			<li><a href='#Departments'>Departments</a></li>
		</ul>	
		
		<div id='Settings'>Settings - <button id="tester">I am button!</button></div>
		<div id='Emails'>Emails</div>
		<div id='HelpTopics'>Help Topics</div>
		<div id='Staff'>
			<table class="datatable" summary="Data Sheet">
				<thead>
					<tr>
						<th scope="col"> </th><!-- used for delete & lock -->
						<th scope="col">Name</th>
						<th scope="col">Email</th>
						<th scope="col">Phone</th>
						<th scope="col">Department</th>
					</tr>
				</thead>
				<tbody>
					<?php
					/* you can also click on a person to edit */
					$qry="SELECT staff_id,firstname,lastname,email,phone,phone_ext, dept_name FROM ost_staff INNER JOIN ost_department ON ost_department.dept_id=ost_staff.dept_id ORDER BY lastname";
						$res = mysql_query($qry);
						$count=0;
						while($row = mysql_fetch_assoc($res)){
							if($count%2==0){$class ="";}
							else{$class="odd";}
							
							echo "<tr class=".$class.">";
								echo "<td><input type='checkbox' id='".$row['staff_id']."' /></td>";
								echo "<td>".$row['lastname'].", ".$row['firstname']."</td>";
								echo "<td><a href='mailto:".$row['email']."'>".$row['email']."</a></td>";
								echo "<td>".$row['phone'].(($row['phone_ext']=='')?"":(" Ext.".$row['phone_ext']))."</td>";
								echo "<td>".$row['dept_name']."</td>";
							echo "</tr>";	
							$count++;
						}						
					?>
				</tbody>
			</table>
		</div>
		<!-- add dept still needs functionality -->
		<div id='Departments'>
			<div style="float:left;margin-left:25px;" id="dept_add"></div>
			<div id='add_dept' style="float:right;cursor:pointer;margin-right:15px;padding:0px;"><img id="add_dept_button" src="images/plus.png" title='Add Department' height='20' alt="Add Department" border=0 /></div>
			<table class="datatable" summary="Data Sheet">
				<thead>
					<tr>
						<th scope="col"> </th><!-- this one is used for checkboxes -->
						<th scope="col">Department</th>
						<th scope="col">Privacy</th>
						<th scope="col">Staff</th>
						<th scope="col">Email</th>
					</tr>
				</thead>
				<tbody>
					<?php
						
						$qry="SELECT ost_department.dept_id,ispublic,dept_name,count(staff_id) staff, ost_email.email FROM ost_staff JOIN ost_department ON ost_department.dept_id=ost_staff.dept_id JOIN ost_email ON ost_email.email_id=ost_department.email_id GROUP BY dept_name";
						$res = mysql_query($qry);
						$count=0;
						while($row = mysql_fetch_assoc($res)){
							if($count%2==0){$class="";}
							else{$class="odd";}
							
							$t_id = $row['ticket_id'];
							echo "<tr class=".$class.">";
							echo "<td><input type=\"checkbox\" id=\"".$row['dept_id']."\" /></td>";
								echo "<td>".$row['dept_name']."</td>";
								echo "<td>".($row['ispublic']?"Public":"Private")."</td>";
								echo "<td>".$row['staff']."</td>";
								echo "<td>".$row['email']."</td>";
							echo "</tr>";
							$count++;
						}
						mysql_close($link);
					?>
				</tbody>
			</table>
		
		</div>
	
	</div><!-- end tabs -->

</div><!-- end container -->

</body>
</html>