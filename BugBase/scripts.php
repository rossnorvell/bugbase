<?php require_once('auth.php');
/*
	Make sure to have JQuery & JQuery-UI in those locations
	Cool idea, have a program write a program.
*/
function onload($modules,$ticket_id=""){
		$load=array();
		foreach($modules as $k=>$v){
			$load[$v]=true;
		}
?>
<link rel="icon" type="image/ico" href="favicon.ico" />
<link rel="stylesheet" href="jquery-ui-custom/css/custom-theme/jquery-ui-1.8.20.custom.css">
	<script src="jquery-ui-custom/js/jquery-1.7.2.min.js"></script>
	<script src="jquery-ui-custom/js/jquery-ui-1.8.20.custom.min.js"></script>
	<?php
	if($load['uploader']){?>
		<script src="fileuploader.js" type="text/javascript"></script>
    <?php
	}
	
	?>
	<script>
	$(function(){
		/* ---------------helpful functions----------------- */
		function reload(){window.location.reload();}
		$callback=function(){};
		/* function set up for notices */
			var timerId, delay = 2000;
			var a = $("#BeeperBox");
			//function to destroy the timeout
			function stopHide() {
				clearTimeout(timerId);
			}
			//function to display the beeper and hide it after a few seconds
			function showTip(text,cb) {
				$callback=cb;
				$("#Beeper_content").html(text);
				a.fadeIn('slow', function(){});
				timerId = setTimeout(function () {
					a.fadeOut('slow', $callback);
				}, delay);
			}
			//function to hide the beeper after a few seconds
			function startHide(){
				timerId = setTimeout(function () {
					a.fadeOut('slow',$callback);
				}, delay);
			}
			
			//Clear timeout to hide beeper on mouseover
			//start timeout to hide beeper on mouseout
			a.mouseenter(stopHide).mouseleave(startHide);
			$('.beeper_x').click(function () {
				//hide the beeper when the close button on the beeper is clicked
				$("#BeeperBox").fadeOut('slow', $callback);
			});
		/* --------------end helpful functions--------------- */
		
		<?php
		if($load['tabs']){?>
			$("#tabs" ).tabs({
				fx: { opacity: 'toggle', duration:320}
			});		
			<?php 
			if(isset($_GET['tab'])){
				echo "$('#tabs').tabs( 'option', 'selected',".$_GET['tab'].");";
			}
		}	
		
		
		if($load['search']){?>				
			$("#search").autocomplete({
				source: "search.php",
				minLength: 2,
				select: function( event, ui ) {
					/* if you select it, that's probably what you wanted */
					window.location='view.php?ticket='+ui.item.id;
				}
			});
			/* make a trigger on enter & search click to actually search. */
			$("#search_button").click(function(){window.location="home.php?search="+$("#search").val();});
			$("#search").keypress(function(event) {
				  if ( event.which == 13 ) {
					event.preventDefault();
					window.location="home.php?<?php echo (isset($_GET['closed'])?'closed=1&':(isset($_GET['overdue'])?'overdue=1&':"")); ?>search="+$("#search").val();
				   }
			});
		<?php
		}
		
		
		
		if($load['mass_tickets']){?>	
			$("#mass_delete").click(function(){
				if(confirm('Are you sure you would like to delete ALL checked tickets?')){
					$ticket_ids=new Array();
					$('.datatable input:checkbox').each(function(e){
						if($(this).is(":checked")){
							$ticket_ids.push(this.id);
						}
					});
					for(i in $ticket_ids){
						$.ajax({
						  cache:false,
						  type: "POST",
						  url: "ticket.php?ticket="+$ticket_ids[i],
						  data: {action:'delete'},
						  success: function(data) {}
						});
					}					
					showTip('Delete Successful',reload);
				}			
			});
			<?php 
			/* these are the closed tickets */
			if(!isset($_GET['closed'])){?>
				$("#mass_close").click(function(){
					if(confirm('Are you sure you would like to close ALL checked tickets?')){
						$ticket_ids=new Array();
						$('.datatable input:checkbox').each(function(e){
							if($(this).is(":checked")){
								$ticket_ids.push(this.id);
							}
						});
						for(i in $ticket_ids){
							$.ajax({
							  cache:false,
							  type: "POST",
							  url: "ticket.php?ticket="+$ticket_ids[i],
							  data: {action:'close'},
							  success: function(data) {}
							});
						}
						showTip('Close Successful',reload);
					}			
				});
				$("#mass_overdue").click(function(){
					if(confirm('Are you sure you would like to mark ALL checked tickets as overdue?')){
						$ticket_ids=new Array();
						$('.datatable input:checkbox').each(function(e){
							if($(this).is(":checked")){
								$ticket_ids.push(this.id);
							}
						});
						for(i in $ticket_ids){
							$.ajax({
							  cache:false,
							  type: "POST",
							  url: "ticket.php?ticket="+$ticket_ids[i],
							  data: {action:'overdue_noproirity'},
							  success: function(data) {}
							});
						}
						showTip('Overdues Marked Successful',reload);
					}			
				});
		<?php
			}
		}
		
		
		
		if($load['uploader']){?>
			function createUploader(){            
				var uploader = new qq.FileUploader({
					element: document.getElementById('file-uploader'),
					action: 'ajax_upload.php?ticket=<?php echo $ticket_id; ?>',
					debug: true
				});           
			}
			var upl = createUploader();		
			$.fx.speeds._default = 500;			
		<?php	
		}
		
		
		
		if($load['hideNotes']){?>
			/* looks better, so we hide them */
			$("#notes4ticket").hide();
			$("#thread4ticket").hide();		
		<?php
		}
		
		
		
		
		if($load['actionBar']){?>
			/* action bar button */
			$( "#action" ).click(function() {
				/* update ticket first then confirm if success */
				$('#action').attr('disabled',true);
				$('#loading').html("<img src='images/loading.gif' />");
				$.ajax({
					  cache:false,
					  type: "POST",
					  url: "ticket.php?ticket=<?php echo $ticket_id; ?>",
					  data: { email:'<?php echo $row['email']; ?>',action:($("#ticket_action option:selected").val()), priority:($("#priority_action option:selected").val())},
					  success: function(data) {
						showTip(data,function(){
							$('#loading').html("");
							reload();
							});
					  }
				});
				return false;
			});
		<?php
		}
		
		
		
		
		if($load['reply_box']){?>
			$("#tabs-reply").tabs();
			/* reply button on post */
			$("#reply_button").click(function(){
				//get the textarea info
				$uptext = $("#reply_area").val();
				//get the file(s) uploaded info (that's already happened)
				$ids=new Array();
				var temp = ($('#attachment_ids').html()).split(" ");
				for(var i=0;i<temp.length-1;i++){
					$ids[i]=temp[i];
				}
				if($ids.length>0){
					$.ajax({
					  cache:false,
					  type: "POST",
					  url: "ticket.php?ticket=<?php echo $ticket_id; ?>",
					  data: {attachments:$ids,reply:$uptext},
					  success: function(data) {
						showTip(data,reload);
					  }
					});
				}
				else{
					$.ajax({
					  cache:false,
					  type: "POST",
					  url: "ticket.php?ticket=<?php echo $ticket_id; ?>",
					  data: {reply:$uptext},
					  success: function(data) {
						showTip(data,reload);
					  }
					});
				}
				
				//if the checkbox was checked close it
				if($('#closeOnPost').is(':checked')){
					$.ajax({
					  cache:false,
					  type: "POST",
					  url: "ticket.php?ticket=<?php echo $ticket_id; ?>",
					  data: { email:'<?php echo $row['email']; ?>',action:'close', priority:'<?php echo $row['priority_id']; ?>'},
					  success: function(data) {
						showTip(data,reload);
					  }
					});
				}
				return false;
			});
			
			
			/* department transfer */
			$("#dept_button").click(function(){
				$department = $("#departments").val();
				$uptext = $("#note_area2").val();			
				$.ajax({
				  cache:false,
				  type: "POST",
				  url: "ticket.php?ticket=<?php echo $ticket_id; ?>",
				  data: {depttrans:$department,deptnote:$uptext},
				  success: function(data) {
					showTip(data,reload);
				  }
				});
			});
			/* department transfer */
			$("#assign_button").click(function(){
				$staff = $("#staff").val();
				$uptext = $("#note_area3").val();			
				$.ajax({
				  cache:false,
				  type: "POST",
				  url: "ticket.php?ticket=<?php echo $ticket_id; ?>",
				  data: {reassign:$staff,staffnote:$uptext},
				  success: function(data) {
					showTip(data,reload);
				  }
				});
			});
			/* for inner note */
			$("#note_button").click(function(){
				//get the info
				$title = $("#note_title").val();
				$uptext = $("#note_area").val();			
				$.ajax({
				  cache:false,
				  type: "POST",
				  url: "ticket.php?ticket=<?php echo $ticket_id; ?>",
				  data: {title:$title,note:$uptext},
				  success: function(data) {
					showTip(data,reload);
				  }
				});			
				//if the checkbox was checked close it
				if($('#closeOnNote').is(':checked')){
					$.ajax({
					  cache:false,
					  type: "POST",
					  url: "ticket.php?ticket=<?php echo $ticket_id; ?>",
					  data: { email:'<?php echo $row['email']; ?>',action:'close', priority:'<?php echo $row['priority_id']; ?>'},
					  success: function(data) {
						showTip(data,reload);
					  }
					});
				}
				return false;
			});
		<?php
		}		
		
		
		if($load['datepicker']){?>
			$( "#datepicker" ).datepicker();
		<?php
		}
		
		
		
		
		if($load['new_ticket']){?>
			$("#ticket_new").click(function(){
			$email=$('#email').val();
			$alert2user=$("#alert2user").is(':checked');
			$name=$('#name').val();
			$phone=$('#phone').val();
			$ext=$('#phone_ext').val();
			
			$ticket_source=$("#source").val();
			$dept_id=$('#department').val();
			$subject=$('#subject').val();
			$issue=$('#summary').val();
			$attachment_ids=new Array();
			var temp = $("#attachment_ids").html().split(" ");
			for(i=0;i<temp.length-1;i++){//take all but last, empty string
				$attachment_ids[i]=temp[i];
			}
			$duedate=$('#datepicker').val();
			$priority=$('#priority').val();
			$helptopicID=$('#helptopic').val();
			$helptopic=$('#helptopic'+"[value='"+$helptopicID+"']").text();
			$assign=$('#assign').val();
			$alert2staff=$("#alert2staff").is(":checked");
			$.ajax({
			  cache:false,
			  type: "POST",
			  url: "ticket.php?new=1",
			  data: {email:$email,
					alert2user:$alert2user,								
					name:$name,
					phone:$phone,
					phone_ext:$ext,
					
					ticket_source:$ticket_source,
					dept_id:$dept_id,
					subject:$subject,
					issue:$issue,
					
					duedate:$duedate,
					priority_id:$priority,
					helptopic:$helptopic,
					helptopic_id:$helptopicID,
					assigned:$assign,
					alert2staff:$alert2staff,
					
					ticket_id:'<?php echo $ticket_id; ?>'
					},
			  success: function(data) {
				if($attachment_ids.length>0){
					$.ajax({
						  cache:false,
						  type: "POST",
						  url: "ticket.php?ticket=<?php echo $ticket_id; ?>",
						  data: {attachments:$attachment_ids,reply:$issue},
						  success: function(dat) {}
						});
				}
				else{
					$.ajax({
					  cache:false,
					  type: "POST",
					  url: "ticket.php?ticket=<?php echo $ticket_id; ?>",
					  data: {reply:$issue},
					  success: function(dta) {}
					});
				}
				showTip(data,function(){window.location='home.php';});
			  }
			});
		});
		<?php
		}
		if($load['update_ticket']){?>
			$("#ticket_update").click(function(){
				$email=$('#email').val();
				$name=$('#name').val();
				$subject=$('#subject').val();
				$phone=$('#phone').val();
				$ext=$('#phone_ext').val();
				$duedate=$('#datepicker').val();
				$priority=$('#priority').val();
				$helptopic=$('#helptopic').val();
				$note=$('#note').val();
				
				$.ajax({
				  cache:false,
				  type: "POST",
				  url: "ticket.php?update=<?php echo $ticket_id; ?>",
				  data: {email:$email,
						name:$name,
						subject:$subject,
						phone:$phone,
						phone_ext:$ext,
						duedate:$duedate,
						priority_id:$priority,
						helptopic:$helptopic,
						innernote:$note},
				  success: function(data) {
					showTip(data,function(){window.location='view.php<?php echo "?".$_GET['edit'];?>';});
				  }
				});
			});	

		<?php		
		}
		
		
		?>
	});
	</script>
	<!-- take over conflicting styles here -->
	<link rel="stylesheet" href="style.css">
	
<?php } /* end onload function */ 
	
	/* make sure to have an open mysql connection & have setup reply_box in onload function */
	function reply_box(){?>
		<center>
			<div id="tabs-reply" style="margin-top:10px;width:760px;text-align:left;">
				<ul>
					<li><a href="#postReply">Post Reply</a></li>
					<li><a href="#innerNote">Post Internal Note</a></li>
				<?php if($_SESSION['ISADMIN']){?>	
					<li><a href="#deptTrans">Department Transfter</a></li>
					<li><a href="#reassign">Re-Assign Ticket</a></li>
				<?php } ?>
				</ul>
				<div id="postReply">
					<textarea rows="6" cols="80" id='reply_area'></textarea>
					<?php file_uploader(); ?>
						<button id="reply_button">Post Reply</button>
						<input id='closeOnPost' type="checkbox" />Close on Post
				</div>
				<div id="innerNote">
					Title: <input type="text" id="note_title" name="note_title" /><br/>
					<textarea rows="6" cols="80" id='note_area'></textarea>
					<div style="text-align:left;">
						<button id="note_button">Post Note</button>
						<input id='closeOnNote' type="checkbox" />Close Ticket
					</div>
				</div>
				<?php if($_SESSION['ISADMIN']){?>
				<div id="deptTrans">
					Department: 
					<select id="departments" style="width:204px;">
						<?php
							$qry = "SELECT dept_id,dept_name FROM `ost_department`";
							$res = mysql_query($qry);
							while($row = mysql_fetch_assoc($res)){
								echo "<option value=\"".$row['dept_id']."\">".$row['dept_name']."</option>\n";
							}					
						?>
					</select>
					<textarea rows="6" cols="80" id='note_area2'></textarea>
					<button id="dept_button">Transfer Ticket</button>
				</div>
				<div id="reassign">
					Staff: 
					<select id="staff" style="width:204px;">
						<?php
							$qry = "SELECT firstname,lastname,staff_id FROM `ost_staff` ORDER BY lastname";
							$res = mysql_query($qry);
							while($row = mysql_fetch_assoc($res)){
								echo "<option value=\"".$row['staff_id']."\">".$row['lastname'].", ".$row['firstname']."</option>\n";
							}	
						?>
					</select>
					<textarea rows="6" cols="80" id='note_area3'></textarea>
					<button id="assign_button">Assign Ticket</button>
				</div>
				<?php } ?>
			</div>
			</center>	
	<?php	
	}

	function file_uploader(){?>
		<div id="file-uploader">	
			<noscript>			
				<p>Please enable JavaScript to use file uploader.</p>
				<!-- or put a simple form for upload here -->
			</noscript>
		</div>
		<div id='attachment_ids' style="visibility:hidden;"></div>
	<?php
	}

?>