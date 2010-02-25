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
	echo getConfigVariable('aerial');
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
	}	?>
<p class="error"><a href="<? echo $query; ?>" class="error" onClick="pl('<? echo $query; ?>'); return false;" target="_blank">Something should have popped...</a></p>	<?
echo getConfigVariable('aerial'); ?>
<script type="text/javascript">
pl(<? echo '"'.$query.'"'; ?>);
</script>	<?
	include_once("common/footer.php");
}	?>