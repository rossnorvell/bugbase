<?php	
	//error_reporting(-1);
	session_start();//session_cache_expire(60);//1 hour	

	//Function to sanitize values received from the form. Prevents SQL injection	
	function sanitize($str){
		$str = trim($str);		
		if(get_magic_quotes_gpc()){
			$str = stripslashes($str);
		}		
	  //A MYSQL CONNECTION IS REQUIRED BEFORE THIS WILL WORK!
      return mysql_real_escape_string($str);
	}
	function superSanitize($something){
		if(is_array($something)){
			foreach($something as $k=>$v){
				$something[$k]=superSanitize($v);
			}
			return $something;
		}
		else{
			return sanitize($something);
		}
	}
	
	$page = explode("?",$_SERVER['REQUEST_URI']);//get the /folder/file.php part of the url
	$page = explode("/",$page[0]);
	$page = $page[count($page)-1];//something.php & actually works
	//Check whether the session variable STAFF_ID is present or not	
	if(!isset($_SESSION['STAFF_ID']) || (trim($_SESSION['STAFF_ID']) == '')){
		$_SESSION['ERRMSG_ARR'] = array("Cannot access ".$page." as current user.");
		header("location: index.php");		
		exit();	
	}
	//no soup for you! Unless you're an admin
	if($page=='admin.php' && $_SESSION['ISADMIN']!=1){
		$_SESSION['ERRMSG_ARR'] = array("Cannot access ".$page." as current user <br/>(need to be admin).");
		header("location: index.php");		
		exit();	
	}
	
	
	
	/* layout functions, display if they have certain permissions */
	function top(){?>
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
						
					 </div>
				  </a>
			   </div>
			</div>
		 </div>
		</div>
		
		<div class="top">
			<div style="float:left;"><img src="images/logo.png" height="100px" title="BugBase" alt="BugBase" border=0 /></div>
			<div class="name">
				<p>Hey <?php echo $_SESSION['FIRST_NAME']; ?>! - 
				<?php //admin or home panel depending on rights
				if($_SESSION['ISADMIN']){?>
					<a href="admin.php">Admin Panel</a> | 
				<?php }
				else{?>
					<a href="home.php">Home Panel</a> |
				<?php } ?>
				
				<a href="#" onclick="$('#tabs').tabs( 'option', 'selected', 2);">My Preferences</a> | <a href="index.php"> Log Out</a>
				</p>
			</div>
		</div>
	<?php	
	}
	
	
	function tabs(){
		if($_SESSION['ISADMIN']){
			$canAccess=array('tickets'=>'Tickets',
							 'directory'=>'Directory',
							 'account'=>'My Account',
							 'knowledgeBase'=>'Knowledge Base'
							 );
		}
		else{
			$canAccess=array('tickets'=>'Tickets',
							 'directory'=>'Directory',
							 'account'=>'My Account');
		}		
		?>
		<div id="tabs">
			<ul>
				<?php
					foreach($canAccess as $k=>$v){
						echo "   <li><a href='#$k'>$v</a></li> \n";
					}
				?>
			</ul>	
	<?php
		if(isset($canAccess['knowledgeBase'])){knowledgeBase();}
		if(isset($canAccess['account'])){account();}
		if(isset($canAccess['directory'])){staffDirectory();}
	}
	function knowledgeBase(){
		?>
		<div id="knowledgeBase">
			<p>Knowledge Base.</p>
		</div>
	<?php
	}
	function staffDirectory(){
		?>
		<div id="directory">
			<table class="datatable" summary="Data Sheet">
				<thead>
					<tr>
						<th scope="col">Name</th>
						<th scope="col">Email</th>
						<th scope="col">Phone</th>
						<th scope="col">Department</th>
					</tr>
				</thead>
				<tbody>
					<?php
					require_once('config.php');
					$link = mysql_connect(DB_HOST, DB_USER,DB_PASS) or die("Jingle Bells");
					mysql_select_db(DB_NAME) or die("...and there she blows.");
					
					$qry="SELECT firstname,lastname,email,phone,phone_ext, dept_name FROM ost_staff INNER JOIN ost_department ON ost_department.dept_id=ost_staff.dept_id ORDER BY lastname";
						$res = mysql_query($qry);
						$count=0;
						while($row = mysql_fetch_assoc($res)){
							if($count%2==0){$class ="";}
							else{$class="odd";}
							
							echo "<tr class=".$class.">";
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
	<?php
	}
	function account(){
		?>
		<div id="account">
			<style>
			.red{color:red;}
			</style>
			<script>
				function pass(){
					$cpass=$("#cpass").val();
					$npass=$("#npass").val();
					$vpass=$("#vpass").val();
					$.ajax({
					  cache:false,
					  type: "POST",
					  url: "user.php?pass=1",
					  data: {current:$cpass,
							newpass:$npass,
							verify:$vpass
					  },
					  success: function(data) {
						$("#notice_content").html(data);
						$("#notice").dialog("open");
						$cpass=$("#cpass").val('');
						$npass=$("#npass").val('');
						$vpass=$("#vpass").val('');
					  }
					});
				}
				function info(){
					$first=$("#fn").val();
					$last=$("#ln").val();
					$email=$("#email").val();
					$phone=$("#phone").val();
					$phone_ext=$("#phone_ext").val();
					$signature=$("#sig").val();
					if($first=='' || $last=='' || $email==''){$("#probs").html('* Fields with the red star may not be empty.');}
					else{
						$("#probs").html('');
						$.ajax({
						  cache:false,
						  type: "POST",
						  url: "user.php?info=1",
						  data: {first:$first,
								last:$last,
								email:$email,
								phone:$phone,
								phone_ext:$phone_ext,
								signature:$signature
						  },
						  success: function(data) {
							$("#notice_content").html(data);
							$("#notice").dialog("open");
						  }
						});
					}
				}
			</script>
			<table style="width:700px;">
				<tr><td></td><td id='probs' style='color:red'></td></tr>
				<tr><td>Username: </td><td><?php echo $_SESSION['USERNAME']; ?></td></tr>
				<tr><td>First Name: </td><td><input id="fn" type='text' value="<?php echo $_SESSION['FIRST_NAME'];?>" /><span class='red'> *</span></td></tr>
				<tr><td>Last Name: </td><td><input id="ln" type='text' value="<?php echo $_SESSION['LAST_NAME'];?>"/><span class='red'> *</span></td></tr>
				<tr><td>Email: </td><td><input id="email" type='text' value="<?php echo $_SESSION['EMAIL'];?>"/><span class='red'> *</span></td></tr>
				<tr><td>Telephone: </td><td><input id="phone" type='text' name="phone" value="<?php echo $_SESSION['PHONE'];?>" />  Ext.<input id="phone_ext" size="3" type='text' value="<?php echo $_SESSION['PHONE_EXT'];?>" /></td></tr>
				<tr><td>Signature: </td><td><textarea id="sig" rows=6 cols=30 /><?php echo $_SESSION['SIGNATURE']; ?></textarea></td></tr>
				<tr><td> </td><td><button id="update_info" onclick='info()' type='text'>Update Information</button></td></tr>
				
				<tr valign="bottom" height="55"><td><span style="color:blue;">Change Password</span></td><td> </td></tr>
				<tr><td>Current Password:</td><td><input id="cpass" type='password' value="" /><span class='red'> *</span></td></tr>
				<tr><td>New Password:</td><td><input id="npass" type='password' value="" /><span class='red'> *</span></td></tr>
				<tr><td>Verify Password:</td><td><input id="vpass" type='password' value="" /><span class='red'> *</span></td></tr>
				
				<tr><td> </td><td><button id="update_pass" onclick="pass()" type='text'>Update Password</button></td></tr>
							
			</table>
		</div>
	<?php
	}
	function panel(){
		?>
		<div class="panel">
			<?php 
				$link = mysql_connect(DB_HOST, DB_USER,DB_PASS) or die("Jingle Bells");
				mysql_select_db(DB_NAME) or die("...and there she blows.");
					
				$qry="SELECT COUNT(*) FROM ost_ticket WHERE status='open'";
				if(!$_SESSION['ISADMIN']){
					$qry=$qry." AND ost_ticket.dept_id='".$_SESSION['DEPT_ID']."' ";
				}
				$res = mysql_query($qry);
				$row = mysql_fetch_row($res);
				$open = $row[0];
				
				$qry="SELECT COUNT(*) FROM ost_ticket WHERE isoverdue!=0";
				if(!$_SESSION['ISADMIN']){
					$qry=$qry." AND ost_ticket.dept_id='".$_SESSION['DEPT_ID']."' ";
				}
				$res = mysql_query($qry);
				$row = mysql_fetch_row($res);
				$overdue = $row[0];
				
				$qry="SELECT COUNT(*) FROM ost_ticket WHERE status='closed'";
				if(!$_SESSION['ISADMIN']){
					$qry=$qry." AND ost_ticket.dept_id='".$_SESSION['DEPT_ID']."' ";
				}
				$res = mysql_query($qry);
				$row = mysql_fetch_row($res);
				$closed = $row[0];
				
			?>
				<a href="home.php">Open <?php echo "(".$open.")"; ?></a>
				<a href="home.php?overdue=1">Overdue <?php echo "(".$overdue.")"; ?></a>
				<a href="home.php?closed=1">Closed Tickets <?php echo "(".$closed.")"; ?></a>
				<a href="ticket.php?menu=new">New Ticket</a>
		</div>
	<?php
	} 
?>