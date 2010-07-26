<?php
include_once("common/dbConnection.php");
include_once("common/formatting-functions.php");

$pageTitle = 'Sources';
include_once("common/header.php");?>
<div id="sources">
<p>The following sources were used in the research for this site. The availability of them varies. I would also like to thank the people and organisations listed on the <a href="/credits.php">Credits</a> page for their assistance.</p>
<?
$sql = "SELECT * FROM sources ORDER BY name ASC";
$result = MYSQL_QUERY($sql);
$numberOfRows = MYSQL_NUM_ROWS($result);

if ($numberOfRows>0)
{
	for ($i=0; $i<$numberOfRows; $i++)
	{
		$thisId = stripslashes(MYSQL_RESULT($result,$i,"source_id"));
		$thisShort = stripslashes(MYSQL_RESULT($result,$i,"short"));
		$thisDetails = stripslashes(MYSQL_RESULT($result,$i,"details"));

		if ($lastName != $thisShort)
		{	?>
<h4 name="id<? echo $thisId; ?>" id="id<? echo $thisId; ?>"><? echo $thisShort; ?></h4><?
		}
		echo "<p>$thisDetails</p>";
		$lastName = $thisShort;
	} 	// end loop
}		// end if
echo '</div>';
include_once("common/footer.php"); ?>