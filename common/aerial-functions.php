<?php

include_once(dirname(__FILE__) . "/../common/dbConnection.php");

function drawLineguideGoogleMap($line)
{
	getTabs($line["lineLink"], $line["lineId"]);
?>
<div name="map" id="map" style="position: relative; height: 600px; background:#222; color:#222;"></div>
<?		
}	// end function	

function drawAllMap($center, $zoom, $types, $lines)
{	
	$section = $_REQUEST["section"];
	//$preset = $_REQUEST["preset"];
	
	if ($center == '')
	{
		$center = "-38.14454755370596, 144.3548154830932";
	}
	if ($zoom == '')
	{
		$zoom = 13;
	}
	
	$pageTitle = "Aerial Explorer";	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
<html>
<head>
<!-- Description: <?php echo $pageTitle;?> -->
<!-- Author: Marcus Wong -->
<title>Rail Geelong - <?php echo $pageTitle;?></title>
<meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1"/>
<meta name="author" content="Marcus Wong" />
<meta name="description" content="Rail Geelong Homepage" />
<meta name="keywords" content="railways trains geelong victoria" />
<link rel="stylesheet" type="text/css" href="/common/css/style.css" media="all" title="Normal" />
<link rel="stylesheet" type="text/css" href="/common/css/aerialstyle.css" />
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?=GOOGLE_KEY?>" type="text/javascript"></script>
<script src="/common/js/functions.js" type="text/javascript"></script>
<script src="/common/js/aerialfunctions.js" type="text/javascript"></script></head>
<script src="/common/aerialjavascript.php?lines=<? echo $lines; ?>&types=<? echo $types; ?>" type="text/javascript"></script>
<body style="width: 98%; height: 100%; margin: 1%; padding: 0;" onload="loadExplorerAll(<?=$center.",".$zoom; ?>)" onunload="GUnload()">
<? getTabs('lines'); ?>
<div name="map" id="map" style="position: absolute; width: 98%; height: 85%;"></div>
</body>
</html>
<?php
}	//	end function








function getTabs($lineLink, $lineId='all')
{
	if ($lineLink == 'lines')
	{
		$updatecustomText = "'aerial', 'all'";
	}
	else
	{
		$updatecustomText = "'$lineLink', $lineId";
	}
		?>
<div><? /* div for tabs */?>
<ul id="maintab" class="shadetabs">
<? 
if($lineLink == 'lines') // for aerial explorer
{ ?>
<li class="selected"><a href="#" rel="intro">Intro</a></li>
<li><a href="#" rel="types">Location Types</a></li>
<li><a href="#" rel="lines">Rail Lines</a></li>
<? }
else// for lineguide map
{	?>
<li><a href="#" rel="types">Location Types</a></li>
<? } ?>
<li><a href="#" rel="link" onclick="updatecustom(<?=$updatecustomText?>)" >Link to View</a></li>
</ul>
</div>

<div class="tabcontentstyle">
<?
if($lineLink == 'lines') // for aerial explorer
{ ?>
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
<a href="#" onclick="selectAll(this.parentNode,'all')" alt="Select All" title="Select All">[A]</a> <a href="#" onclick="selectAll(this.parentNode,'none')" alt="Unselect All" title="Unselect All">[N]</a>
</form></td><td><a href="#" onclick="updatecustom(<?=$updatecustomText?>,'draw')"><b>Update</b></a>
</td></tr></table>
</div><? /* end lines tab */ ?>
<div id="lines" class="tabcontent">
<div id="tabtitle2">Rail Lines</div>
<table><tr><td><form name="customlines" id="customlines"><?	
$result = MYSQL_QUERY("SELECT * FROM raillines WHERE todisplay != 'hide' ORDER BY name", locationDBconnect());
$numberOfRows = MYSQL_NUMROWS($result);
if ($numberOfRows>0) 
{
	for ($i = 0;$i<$numberOfRows; $i++)
	{
		$thisLine_id = stripslashes(MYSQL_RESULT($result,$i,"line_id"));
		$thisName = stripslashes(MYSQL_RESULT($result,$i,"name"));	?>
<label for="<? echo $thisLine_id?>"><? echo $thisName?>: </label><input type="checkbox" name="<? echo $thisLine_id?>" id="<? echo $thisLine_id?>" />
<?	} // end while loop
}	?>
<a href="#" onclick="selectAll(this.parentNode,'all')" alt="Select All" title="Select All">[A]</a> <a href="#" onclick="selectAll(this.parentNode,'none')" alt="Unselect All" title="Unselect All">[N]</a>
</form></td><td><a href="#" onclick="updatecustom(<?=$updatecustomText?>,'draw')"><b>Update</b></a>
</td></tr></table>
</div><? /* end custom map tab */ ?>
<div id="link" class="tabcontent">
<div id="tabtitle3">Link to View</div>
<input id="directlink" name="directlink" type="text" size="120" value="http://railgeelong.com/aerial.php" onclick="highlight(this);" />
</div>
<? }
else	// for lineguide map
{	?>
<div id="types" class="tabcontent">
<div id="tabtitle0">Location Types</div>
<table><tr><td><form name="customtypes" id="customtypes">
<label for="s">Stations: </label><input type="checkbox" id="s" name="s" /> 
<label for="i">Industries: </label><input type="checkbox" id="i" name="i" /> 
<label for="b">Signal Boxes: </label><input type="checkbox" id="b" name="b" /> 
<label for="r">Bridges and Crossings: </label><input type="checkbox" id="r" name="r" /> 
<label for="m">Other: </label><input type="checkbox" id="m" name="m" />
<a href="#" onclick="selectAll(this.parentNode,'all')" alt="Select All" title="Select All">[A]</a> <a href="#" onclick="selectAll(this.parentNode,'none')" alt="Unselect All" title="Unselect All">[N]</a>
</form></td><td><a href="#" onclick="updatecustom(<?=$updatecustomText?>,'draw')"><b>Update</b></a>
</td></tr></table>
</div><? /* end lines tab */ ?>
<div id="link" class="tabcontent">
<div id="tabtitle1">Link to View</div>
<input id="directlink" name="directlink" type="text" size="120" value="http://www.railgeelong.com/lineguide/<?=$lineLink;?>/map/" onclick="highlight(this);" />
</div>
<? } ?>

<? /* fixes which tab is open */ ?>
<script type="text/javascript">
initializetabcontent("maintab")
<? if($lineLink == 'lines')
{	?>expandtab('maintab', 0)<?	}
else
{	?>expandtab('maintab', 0)<?	}	?>
</script>
</div><? /* end tabs div */	?>
<? } 	// end function

/*
 *
 * Draw a Google Map for a specific location
 * Either map or satelite views
 *
 *
 *
 *
 */
function drawSpecific($view, $id)
{
	$pageTitle = "Aerial Explorer";
	
	// get data from DB
	$sql = "SELECT * FROM locations l
		INNER JOIN location_types lt ON lt.type_id = l.type 
		WHERE location_id = '".$id."'";
		
	$result = MYSQL_QUERY($sql, locationDBconnect());
	
	if (MYSQL_NUM_ROWS($result) == 0)
	{
		echo '<p class="error">Error - Invalid Location!</p>';
	}
	else	// start zero result if
	{
		$name = stripslashes(MYSQL_RESULT($result,0,"name")); 
		$typeName = stripslashes(MYSQL_RESULT($result,0,"basic"));
		$coords = stripslashes(MYSQL_RESULT($result,0,"long")); 
		$photos = stripslashes(MYSQL_RESULT($result,0,"photos")); 
		$events = stripslashes(MYSQL_RESULT($result,0,"events")); 
		$length = strlen((MYSQL_RESULT($result,0,"description")));	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html height="100%" xmlns="http://www.w3.org/1999/xhtml">
<head>
<!-- Description: <?php echo $pageTitle;?> -->
<!-- Author: Marcus Wong -->
<title>Rail Geelong - <?php echo $pageTitle;?></title>
<meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1"/>
<meta name="author" content="Marcus Wong" />
<meta name="description" content="Rail Geelong Homepage" />
<meta name="keywords" content="railways trains geelong victoria" />
<link rel="stylesheet" type="text/css" href="/common/css/style.css" media="all" title="Normal" />
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=<?=GOOGLE_KEY?>&sensor=false"></script>
<script type="text/javascript">
<? /* google map function - single location */ ?>
var map, marker, infowindow;
function initialize() {
	var locationLatlng = new google.maps.LatLng(<? echo $coords; ?>);
	var mapOptions = {
		zoom: 15,
		center: locationLatlng,
		scaleControl: true,
		mapTypeControl: true,
		mapTypeControlOptions: {
			style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
		},
		zoomControl: true,
		zoomControlOptions: {
			style: google.maps.ZoomControlStyle.SMALL
		},
<?php if ($view == 'map') {	?>
		mapTypeId: google.maps.MapTypeId.ROADMAP
<?php } else if ($view == 'satellite') {	?>
		mapTypeId: google.maps.MapTypeId.SATELLITE 
<?php } ?>
	};	
	map = new google.maps.Map(document.getElementById('map'), mapOptions);	
	infowindow = new google.maps.InfoWindow({
		position: locationLatlng,
		content: '<b><?php echo $name ?></b><p><?php echo $typeName ?></p>'
	});
	marker = new google.maps.Marker({
		position: locationLatlng,
		map: map,
		title: '<?php echo $name ?>'
	});
	marker.setIcon('https://maps.google.com/mapfiles/ms/icons/blue.png');	
	infowindow.open(map,marker);
	google.maps.event.addListener(marker, 'click', function() {
		infowindow.open(map,marker);
	});
};
google.maps.event.addDomListener(window, 'load', initialize);
</script>
<noscript>
Sorry! Your browser is not compatible with Google Maps. Please upgrade your browser to a more recent version to use this feature.
</noscript>
</head>
<body style="margin: 0;">
<div name="map" id="map" style="padding: 0; position: absolute; width: 100%; height: 100%;"></div>
</body>
</html>
<?php
		} // end if
}	/*	end overview if	*/?>