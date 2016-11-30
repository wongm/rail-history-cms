<ul class="sitemenu">
	<li class="menu"><a href="listSources.php">Sources</a></li>
	<li class="menu"><a href="listArticles.php">Articles</a></li>
</ul>
<ul class="sitemenu">
	<li class="menu"><a href="listArticles.php?region=">Regions</a></li>
	<small><a href="listRaillineRegion.php">Line Mappings</a></small>
</ul>
<ul class="sitemenu">
<?php
// get all raillines
include_once("dbConnection.php");
$sql = "SELECT * FROM raillines ORDER BY `order` ASC";//, LENGTH(description)";
$result = query_full_array($sql);
$numberOfRows = sizeof($result);
if ($numberOfRows == 0) 
{
?>
<li class="menu"><a href="">No Lines!</a></li>	
<?php
}
else if ($numberOfRows>0) 
{
	for ($i = 0; $i<$numberOfRows; $i++)
	{
		$thisLineLink = stripslashes($result[$i]["link"]);
		$thisLineId = stripslashes($result[$i]["line_id"]);
		$thisName = stripslashes($result[$i]["name"]);
		$todisplay = $result[$i]["todisplay"];
		$showevents = substr($todisplay, 2, 1) == 1;
		$multipleLocationOnLine = (query_full_array("SELECT count(*) FROM locations_raillines WHERE line_id = '".$thisLineId."'") >= 1);
		if(mb_strlen($thisName) < 14)
		{
			$thisName .= ' Line';
		}
		$thisDisplay = stripslashes($result[$i]["todisplay"]);		
?>
<li class="menu"><a href="editLines.php?line=<?php echo $thisLineLink; ?>"><?php echo $thisName; ?></a></li>
<?php 
	if ($multipleLocationOnLine)
	{
?>
<small><a href="listLineLocations.php?line=<?php echo $thisLineLink; ?>">Locations</a></small>
<?php 
	}
	if ($showevents)
	{	
?>
<small><a href="listLineEvents.php?line=<?php echo $thisLineLink; ?>">Events</a></small>
<?php }	

	$extras = query_full_array("SELECT * FROM articles WHERE `line_id` = '".$thisLineId."'");
	$extrasLength = sizeof($extras);
			
	for ($j = 0; $j < $extrasLength; $j++)
	{
		if ($j == 0)
		{
			echo '<br>';
		}
		echo '<small><a href="editArticles.php?id='.$extras[$j]["article_id"].'">';
		echo stripslashes($extras[$j]["title"])."</a></small><br>\n";
	}
?>
</ul>

<ul class="sitemenu">
<?php 
	} 	// end for loop
}		// end if
?>
	<li class="menu"><a href="editAerial.php">Aerial Locations</a></li>
</ul>
<a class="sub">Add</a>
<ul class="sitemenu">
	<li class="menu"><a href="addLine.php">Lines</a></li>
	<li class="menu"><a href="addLocation.php">Locations</a></li>
	<li class="menu"><a href="addLineEvent.php">Line Events</a></li>
	<li class="menu"><a href="addLocationEvent.php">Location Events</a></li>
</ul>
<a class="sub">Misc</a>
<ul class="sitemenu">
	<li class="menu"><a href="sqlbits.php">SQL Bits</a></li>
</ul>