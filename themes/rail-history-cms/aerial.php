<?php
require_once("common/aerial-functions.php");
require_once("common/formatting-functions.php");
$section = isset($_REQUEST["section"]) ? $_REQUEST["section"] : "";
$center = isset($_REQUEST["center"]) ? $_REQUEST["center"] : "";
$zoom = isset($_REQUEST["zoom"]) ? $_REQUEST["zoom"] : "";
$lines = isset($_REQUEST["lines"]) ? $_REQUEST["lines"] : "";
$types = isset($_REQUEST["types"]) ? $_REQUEST["types"] : "";

if ($section == 'popup')
{
	drawAllMap($center, $zoom, $types, $lines);
}
else
{
	$pageTitle = "Aerial Explorer";
	require_once("common/header.php");
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
	?>
<div id="headbar">
	<div class="link"><a href="/">Home</a> &raquo; Aerial Explorer</div>
	<div class="search"><?php drawHeadbarSearchBox(); ?></div>
</div>
<?php
	
	require_once("common/midbar.php");
	
	if ($section != 'overview')
	{
	?>
<p class="error" clear="all" style="margin-top: 0"><a href="<?php echo $query; ?>" class="error" onClick="pl('<?php echo $query; ?>'); return false;" target="_blank">Something should have popped...</a></p>
<?php 
	}
	drawDescription();
	
	if ($section != 'overview')
	{	
?>
<script type="text/javascript">
pl(<?php echo '"'.$query.'"'; ?>);
</script>	<?php
	}
	
	require_once("common/footer.php");
}
?>