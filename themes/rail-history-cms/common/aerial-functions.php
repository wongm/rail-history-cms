<?php

require_once("definitions.php");

function drawAllMap($center, $zoom, $types, $lines)
{
	global $_zp_themeroot;
	$pageTitle = "Aerial Explorer";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
<html>
<head>
<title><?php echo getGalleryTitle();?> - <?php echo $pageTitle;?></title>
<meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1"/>
<meta name="author" content="Marcus Wong" />
<meta name="description" content="<?php echo getGalleryDesc();?>" />
<link rel="stylesheet" type="text/css" href="<?php echo $_zp_themeroot ?>/css/style.css" media="all" title="Normal" />
<link rel="stylesheet" type="text/css" href="<?php echo $_zp_themeroot ?>/css/aerialstyle.css" />
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_KEY_v3 ?>" type="text/javascript"></script>
<script src="<?php echo $_zp_themeroot ?>/js/functions.js" type="text/javascript"></script>
<script src="<?php echo $_zp_themeroot ?>/js/aerialfunctions.js" type="text/javascript"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body style="width: 98%; height: 100%; margin: 1%; padding: 0;">
<?php getAerialExplorerTabs(); ?>
<div name="map" id="map-canvas" style="position: absolute; width: 98%; height: 85%;"></div>
<script type="text/javascript">
var map;
var markers = [];
var types = null;
var lines = null;

var bounds = new google.maps.LatLngBounds();
var infowindow = new google.maps.InfoWindow();

google.maps.event.addDomListener(window, 'load', initialize);

<?php
// set up the initial values if given as params

if ($types != '' OR $lines != '')
{
?>
types = [<?php echo $types; ?>];
lines = [<?php echo $lines; ?>];

function initialiseCustomFilters()
{
<?php
	$typesarray = explode(',', $types);
	$linesarray = explode(',', $lines);
	
	// get the types of locations we want
	for ($i = 0; $i < sizeOf($typesarray);  $i++)
	{
		switch ($typesarray[$i]) 
		{
			case 's':	/*	stations	*/	?>	
	document.getElementById('s').checked = true;
	<?php
				break;	
			case 'i':	/*	industry	*/	?>	
	document.getElementById('i').checked = true;
	<?php
				break;	
			case 'b':	/*	signal boxes	*/	?>		
	document.getElementById('b').checked = true;
	<?php
				break;	
			case 'r':	/*	roads	*/		?>	
	document.getElementById('r').checked = true;
	<?php
				break;	
			case 'm':	/*	misc	*/		?>
	document.getElementById('m').checked = true
	<?php
				break;
			default:
		}	// end switch
	}
	/* end of for loop */	?>
	<?php
	
	// get the lines we want
	for ($j = 0; $j < sizeOf($linesarray);  $j++)
	{	?>
	document.getElementById('<?php echo $linesarray[$j]; ?>').checked = true;
	lines=lines+<?php echo $linesarray[$j]; ?>+",";	<?php
	}	/* end for loop */	?>
	lines = (lines+",").replace(',,','');
}
<?php // end javascript 'initialiseCustomFilters()' function
}		// end php set up the initial values if given as params
?>
</script>
</body>
</html>
<?php
}	//	end function




function drawDescription()
{
?>
<h3>Aerial Explorer</h3>
<p><a href="/aerial.php?section=popup" onClick="pl(this.href); return false;"><img src="/images/maps/map.jpg" class="photo-right" alt="Open the Aerial Map" title="Open the Aerial Map" /></a></p>
<p>This section gives you an aerial view of the Geelong region, with all of the railway locations of interest overlaid upon it.</p>
<p>For each location, a coloured marker is provided. Clicking on this marker will display the name of the location, a short run down of the available data, and a link to the full history page.</p>
<p>Use the 'Location Types' and 'Rail Lines' tab to change what locations are shown on the map. Both the railways lines the location belongs to, as well as the type of location, can be changed. Click the 'Update' link to apply your changes to the map.</p>
<h4 style="clear:both;">Problems?</h4>
<hr/>
<p>Installed Popup Blockers may prevent the satellite map window from appearing. Click on the red "<a href="aerial.php?section=popup" onClick="pl('aerial.php?section=popup'); return false;">Something should have popped...</a>" bar that appears to open the window manually.</p>
<p><a href="https://maps.google.com/">Google Maps</a> requires a recent Javascript capable browser for use. As a consequence users with older browsers may not be able to access this section of the site. Upgrading your browser to a newer version will fix this. If you are still having problems please ensure you have Javascript turned on in your browser to view this section.</p>
<p>Users on slower (eg: non broadband) internet connections may find this section very slow to load, this is due to the large number of images being downloaded for viewing. Switching to the 'Map" display option in the top corner may speed loading times. Other than than you will just need to be patient.</p>
<p>For users still not able to use this feature, all location histories are still accessible via the links in the <a href="/locations">sidebar</a>.</p>
<?php
}	// end function




function getAerialExplorerTabs()
{
?>
<div><?php /* div for tabs */?>
<ul id="maintab" class="shadetabs">
<li class="selected"><a href="#" rel="intro">Intro</a></li>
<li><a href="#" rel="types">Location Types</a></li>
<li><a href="#" rel="lines">Rail Lines</a></li>
<li><a href="#" rel="link">Link to View</a></li>
</ul>
</div>
<div class="tabcontentstyle">
<div id="intro" class="tabcontent">
<div id="tabtitle0">Intro</div>
Click on the icons to view more details of the location. The tabs along the top allow you to alter what is shown. You can also share the view with others. Need more <a href="/aerial.php?section=overview" target="_blank">help</a>?.
</div>
<div id="types" class="tabcontent">
<div id="tabtitle1">Location Types</div>
<table><tr><td><form name="customtypes" id="customtypes">
<label for="s">Stations: </label><input type="checkbox" id="s" name="s" /> 
<label for="i">Industries: </label><input type="checkbox" id="i" name="i" /> 
<label for="b">Signal Boxes: </label><input type="checkbox" id="b" name="b" /> 
<label for="r">Bridges and Crossings: </label><input type="checkbox" id="r" name="r" /> 
<label for="m">Other: </label><input type="checkbox" id="m" name="m" />
<a href="#" onclick="selectAllAndRefresh(this.parentNode,'all')" alt="Select All" title="Select All">[A]</a> <a href="#" onclick="selectAllAndRefresh(this.parentNode,'none')" alt="Unselect All" title="Unselect All">[N]</a>
</form></td><td><a href="#" onclick="updateMapOnClick()"><b>Update</b></a>
</td></tr></table>
</div><?php /* end lines tab */ ?>
<div id="lines" class="tabcontent">
<div id="tabtitle2">Rail Lines</div>
<table><tr><td><form name="customlines" id="customlines"><?php 
$filter = "";
// show if admin when page is in edit mode
if ( !zp_loggedin() ) {
	$filter = " WHERE todisplay != 'hide' ";
}
$result = query_full_array("SELECT * FROM raillines $filter ORDER BY name");
$numberOfRows = sizeof($result);
if ($numberOfRows>0) 
{
	for ($i = 0;$i<$numberOfRows; $i++)
	{
		$thisLine_id = stripslashes($result[$i]["line_id"]);
		$thisName = stripslashes($result[$i]["name"]);	?>
<label for="<?php echo $thisLine_id?>"><?php echo $thisName?>: </label><input type="checkbox" name="<?php echo $thisLine_id?>" id="<?php echo $thisLine_id?>" />
<?php } // end while loop
}	?>
<a href="#" onclick="selectAllAndRefresh(this.parentNode,'all')" alt="Select All" title="Select All">[A]</a> <a href="#" onclick="selectAllAndRefresh(this.parentNode,'none')" alt="Unselect All" title="Unselect All">[N]</a>
</form></td><td><a href="#" onclick="updateMapOnClick()"><b>Update</b></a>
</td></tr></table>
</div><?php /* end custom map tab */ ?>
<div id="link" class="tabcontent">
<div id="tabtitle3">Link to View</div>
<input id="directlink" name="directlink" type="text" size="120" value="https://railgeelong.com/aerial.php" onclick="highlight(this);" />
</div>
<?php /* fixes which tab is open */ ?>
<script type="text/javascript">
initializetabcontent("maintab")
expandtab('maintab', 0);
</script>
</div><?php /* end tabs div */	?>
<?php } 	// end function