<ul class="sitemenu">
	<li class="menu"><a href="/backend/listSources.php">Sources</a></li>
	<li class="menu"><a href="/backend/listConfig.php">Variables</a></li>
	<li class="menu"><a href="/backend/listArticles.php">Articles</a></li>
	<li class="menu"><a href="/backend/listArticles.php?region=">Regions</a></li>
	<li class="menu"><a href="/backend/listRaillineRegion.php">Region Mappings</a></li>
</ul>
<ul class="sitemenu">
<?php
// get all raillines
include_once("dbConnection.php");
$sql = "SELECT * FROM raillines ORDER BY `order` ASC";//, LENGTH(description)";
$result = MYSQL_QUERY($sql, backendDBConnect());
$numberOfRows = MYSQL_NUMROWS($result);
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
		$thisLineLink = stripslashes(MYSQL_RESULT($result,$i,"link"));
		$thisLineId = stripslashes(MYSQL_RESULT($result,$i,"line_id"));
		$thisName = stripslashes(MYSQL_RESULT($result,$i,"name"));
		$todisplay = MYSQL_RESULT($result,$i,"todisplay");
		$showevents = substr($todisplay, 2, 1) == 1;
		$multipleLocationOnLine = (MYSQL_QUERY("SELECT count(*) FROM locations_raillines WHERE line_id = '".$thisLineId."'", backendDBConnect()) >= 1);
		
		if(mb_strlen($thisName) < 14)
		{
			$thisName .= ' Line';
		}
		$thisDisplay = stripslashes(MYSQL_RESULT($result,$i,"todisplay"));		
?>
<li class="menu"><a href="/backend/editLines.php?line=<? echo $thisLineLink; ?>"><? echo $thisName; ?></a></li>
<? 
	if ($multipleLocationOnLine)
	{
?>
<small><a href="/backend/listLineLocations.php?line=<? echo $thisLineLink; ?>">Locations</a></small>
<?	
	}
	if ($showevents)
	{	
?>
<small><a href="/backend/listLineEvents.php?line=<? echo $thisLineLink; ?>">Events</a></small>
<?	}	

	$extras = MYSQL_QUERY("SELECT * FROM articles WHERE `line_id` = '".$thisLineId."'", backendDBconnect());
	$extrasLength = MYSQL_NUM_ROWS($extras);
			
	for ($j = 0; $j < $extrasLength; $j++)
	{
		if ($j == 0)
		{
			echo '<br>';
		}
		echo '<small><a href="editArticles.php?id='.MYSQL_RESULT($extras,$j,"article_id").'">';
		echo stripslashes(MYSQL_RESULT($extras,$j,"title"))."</a></small><br>\n";
	}
?>
</ul>

<ul class="sitemenu">
<?	
	} 	// end for loop
}		// end if
?>
	<li class="menu"><a href="/backend/editAerial.php">Aerial Locations</a></li>
</ul>
<a class="sub">Add</a>
<ul class="sitemenu">
	<li class="menu"><a href="/backend/addLine.php">Lines</a></li>
	<li class="menu"><a href="/backend/addLocation.php">Locations</a></li>
	<li class="menu"><a href="/backend/addLineEvent.php">Line Events</a></li>
	<li class="menu"><a href="/backend/addLocationEvent.php">Location Events</a></li>
</ul>
<a class="sub">Misc</a>
<ul class="sitemenu">
	<li class="menu"><a href="/backend/sqlbits.php">SQL Bits</a></li>
</ul>