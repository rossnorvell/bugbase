$(document).ready(function () {
    // set the time for the beeper to be displayed as 5000 milli seconds (5 seconds)
    var timerId, delay = 2500;
    var a = $("#BeeperBox"),
        b = $("a.control");;
    //function to destroy the timeout
    function stopHide() {
        clearTimeout(timerId);
    }
    //function to display the beeper and hide it after a few seconds
	function showTip() {
        a.fadeIn('slow', function() {});
        timerId = setTimeout(function () {
            a.fadeOut('slow', function(){});
        }, delay);

    }
    //function to hide the beeper after a few seconds
	function startHide() {
        timerId = setTimeout(function () {
            a.fadeOut('slow', function(){window.location.reload();});
        }, delay);
    }
	
	
    //how to use
    b.click(showTip);
	
	
    //Clear timeout to hide beeper on mouseover
    //start timeout to hide beeper on mouseout
    a.mouseenter(stopHide).mouseleave(startHide);
	$('.beeper_x').click(function () {
        //hide the beeper when the close button on the beeper is clicked
        $("#BeeperBox").fadeOut('slow', function(){window.location.reload();});
    });

});