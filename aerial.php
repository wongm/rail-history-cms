<?php
include_once("common/aerial-functions.php");
include_once("common/formatting-functions.php");
$id = $_REQUEST["id"];
$section = $_REQUEST["section"];
$preset = $_REQUEST["preset"];
$center = $_REQUEST["center"];
$zoom = $_REQUEST["zoom"];
$view = $_REQUEST["view"];
$lines = $_REQUEST["lines"];
$types = $_REQUEST["types"];

if ($id != '')
{
	drawSpecific($view, $id);
}
elseif ($section == 'overview')
{
	$pageTitle = "Aerial Explorer";
	include_once("common/header.php");
	drawDescription();
	include_once("common/footer.php");
}
elseif ($section == 'popup' OR $section == 'preset' )
{
	drawAllMap($center, $zoom, $types, $lines);
}
else
{
	$pageTitle = "Aerial Explorer";
	include_once("common/header.php");
	$query = "aerial.php?section=popup";

	if ($center != '')
	{
		$query = $query."&amp;center=".$center;
	}
	if ($zoom != '')
	{
		$query = $query."&amp;zoom=".$zoom;
	}
	if ($lines != '')
	{
		$query = $query."&amp;lines=".$lines;
	}
	if ($types != '')
	{
		$query = $query."&amp;types=".$types;
	}
	if ($preset != '' AND $section == 'preset')
	{
		$query = "aerial.php?section=popup&amp;preset=".$preset;
	}
	?>
<div id="headbar">
	<div class="link"><a href="/">Home</a> &raquo; Aerial Explorer</div>
	<div class="search"><? drawHeadbarSearchBox(); ?></div>
</div>
<?php
	
	include_once("common/midbar.php");?>
<p class="error" style="margin-top: 0"><a href="<? echo $query; ?>" class="error" onClick="pl('<? echo $query; ?>'); return false;" target="_blank">Something should have popped...</a></p>
<?	
drawDescription();
?>
<script type="text/javascript">
pl(<? echo '"'.$query.'"'; ?>);
</script>	<?
	include_once("common/footer.php");
}

function drawDescription()
{
?>
<h4>Overview</h4><hr/>
<p><a href="/aerial.php?section=popup" onClick="pl(this.href); return false;"><img src="/images/maps/map.jpg" class="photo-right" alt="Open the Aerial Map" title="Open the Aerial Map" /></a></p>
<p>This section gives you an aerial view of the Geelong region, with all of the railway locations of interest overlaid upon it.</p>
<p>For each location, a coloured marker is provided. Clicking on this marker will display the name of the location, a short rundown of the available data, and a link to the full history page.</p>
<p>Use the 'Location Types' and 'Rail Lines' tab to change what locations are shown on the map. Both the railways lines the location belongs to, as well as the type of location, can be changed. Click the 'Update' link to apply your changes to the map.</p>
<p>On the 'Link to View' tab a URL is provided to share with others. This URL allows you to show the exact same position, zoom, and displayed locations to someone else.</p>
<h4 style="clear:both;">Problems?</h4>
<hr/>
<p>Installed Popup Blockers may prevent the satellite map window from appearing. Click on the red "<a href="aerial.php?section=popup" onClick="pl('aerial.php?section=popup'); return false;">Something should have popped...</a>" bar that appears to open the window manually.</p>
<p><a href="http://maps.google.com/">Google Maps</a> requires a recent Javascript capable browser for use. As a consequence users with older browsers may not be able to access this section of the site. Upgrading your browser to a newer version will fix this. If you are still having problems please ensure you have Javascript turned on in your browser to view this section.</p>
<p>Users on slower (eg: non broadband) internet connections may find this section very slow to load, this is due to the large number of images being downloaded for viewing. Switching to the 'Map" display option in the top corner may speed loading times. Other than than you will just need to be patient.</p>
<p>For users still not able to use this feature, all location histories are still accessable via the links in the <a href="/locations.php">sidebar</a>.</p>
<?
}	// end function

?>