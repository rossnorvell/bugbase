<?php
	require_once('auth.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
 <head>
   <title>BugBase| Home</title>
	<link rel="stylesheet" href="jquery-ui-custom/css/custom-theme/jquery-ui-1.8.20.custom.css">
	<script src="jquery-ui-custom/js/jquery-1.7.2.min.js"></script>
	<script src="jquery-ui-custom/js/jquery-ui-1.8.20.custom.min.js"></script>
	<script>
	$(function(){
		$( "#tabs" ).tabs();
		
		$( "#notice" ).dialog({
				autoOpen: false,
				show: "explode",
				hide: "explode",
				close: function(event, ui) {window.location='home.php';}
			});
	
		 // set the time for the beeper to be displayed as 5000 milli seconds (5 seconds)
		var timerId, delay = 2500;
		var a = $("#BeeperBox");
		//function to destroy the timeout
		function stopHide() {
			clearTimeout(timerId);
		}
		//function to display the beeper and hide it after a few seconds
		function showTip(text) {
			$("#Beeper_content").html(text);
			a.fadeIn('slow', function(){});
			timerId = setTimeout(function () {
				a.fadeOut('slow', function(){});
			}, delay);
			return false;
		}
		//function to hide the beeper after a few seconds
		function startHide(){
			timerId = setTimeout(function () {
				a.fadeOut('slow', function(){window.location.reload();});
			}, delay);
			return false;
		}
		
		//Clear timeout to hide beeper on mouseover
		//start timeout to hide beeper on mouseout
		a.mouseenter(stopHide).mouseleave(startHide);
		$('.beeper_x').click(function () {
			//hide the beeper when the close button on the beeper is clicked
			$("#BeeperBox").fadeOut('slow', function(){window.location.reload();});
		});
	
		$("#tester").click(function(){showTip('settings test.... oh yea baby.');return false;});
		
	});
	</script>
	<style>
	.ui-autocomplete {
		max-height: 150px;
		overflow-y: auto;
		/* prevent horizontal scrollbar */
		overflow-x: hidden;
		/* add padding to account for vertical scrollbar */
		padding-right: 20px;
	}
	/* IE 6 doesn't support max-height
	 * we use height instead, but this forces the menu to always be this tall
	 */
	* html .ui-autocomplete {
		height: 150px;
	}
	</style>
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
	  
<div id="notice" title="Notice">
	<p id="notice_content"></p>
</div>
<div id="container">
	<div class="top">
		<div style="float:left;"><img src="images/logo.png" height="100px" title="BugBase" alt="BugBase" border=0 /></div>
		<div class="name">
			<p>Hey <?php echo $_SESSION['FIRST_NAME']; ?>! - 
			<a href="home.php">Home Panel</a> |			
			<a href="home.php?tab=<?php echo ($_SESSION['ISADMIN'])?"3":"2";?>" >My Preferences</a> | <a href="index.php"> Log Out</a>
			</p>
		</div>
	</div>
	<div id="tabs">
		<ul>
			<li><a href='#Dashboard'>
			Dashboard
			</a></li>
			<li><a href='#Settings'>Settings</a></li>
			<li><a href='#Emails'>Emails</a></li>
			<li><a href='#HelpTopics'>Help Topics</a></li>
			<li><a href='#Staff'>Staff</a></li>
			<li><a href='#Departments'>Departments</a></li>
		</ul>	
		<div id='Dashboard'>Dashboard</div>
		<div id='Settings'>Settings - <button id="tester">I am button!</button></div>
		<div id='Emails'>Emails</div>
		<div id='HelpTopics'>Help Topics</div>
		<div id='Staff'>Staff</div>
		<div id='Departments'>Departments</div>
	
	</div><!-- end tabs -->

</div><!-- end container -->

</body>
</html>