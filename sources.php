<?php
include_once("common/dbConnection.php");
include_once("common/formatting-functions.php");

$pageTitle = 'Sources';
include_once("common/header.php");
?>
<div id="headbar">
	<div class="link"><a href="/">Home</a> &raquo; Sources</div>
	<div class="search"><?php drawHeadbarSearchBox(); ?></div>
</div>
<?php include_once("common/midbar.php"); ?>
<h3>Sources</h3>
<div id="sources">
<p>The following sources were used in the research for this site. The availability of them varies. I would also like to thank the people and organisations listed on the <a href="/credits.php">Credits</a> page for their assistance.</p>
<?php
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
<b name="id<?php echo $thisId; ?>" id="id<?php echo $thisId; ?>"><?php echo $thisShort; ?></b><?php
		}
		echo "<p>$thisDetails</p>";
		$lastName = $thisShort;
	} 	// end loop
}		// end if
echo '</div>';
include_once("common/footer.php"); ?>