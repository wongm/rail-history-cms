<?php
$pageTitle = 'Region to Lineguide Mappings';
include_once("common/dbConnection.php");
include_once("common/header.php");

$sql = "SELECT   * FROM railline_region, articles, raillines 
	WHERE railline_region.line_id = raillines.line_id AND railline_region.article_id = articles.article_id";
$result = MYSQL_QUERY($sql);
$numberOfRows = MYSQL_NUM_ROWS($result);

if ($numberOfRows>0) 
{
?>	
<a href="enterNewRaillineRegion.php">Add new mapping</a><hr/>
	<TABLE CELLSPACING="0" CELLPADDING="3" BORDER="0" WIDTH="100%">
	<TR>
		<TD>
				<B>Linkzor ID</B>
</TD>
		<TD>
				<B>Line</B>
</TD>
		<TD>
				<B>Article</B>
</TD>
	</TR>
<?
	while ($i<$numberOfRows)
	{

		if (($i%2)==0) { $bgColor = "#FFFFFF"; } else { $bgColor = "#C0C0C0"; }

	$thisLinkzor_id = MYSQL_RESULT($result,$i,"linkzor_id");
	$thisLine_id = MYSQL_RESULT($result,$i,"raillines.name");
	$thisArticle_id = MYSQL_RESULT($result,$i,"articles.title");

?>
	<TR BGCOLOR="<? echo $bgColor; ?>">
		<TD><? echo $thisLinkzor_id; ?></TD>
		<TD><? echo $thisLine_id; ?></TD>
		<TD><? echo $thisArticle_id; ?></TD>
	<TD><a href="editRaillineRegion.php?id=<? echo $thisLinkzor_id; ?>">Edit</a></TD>
	<TD><a href="confirmDeleteRaillineRegion.php?id=<? echo $thisLinkzor_id; ?>">Delete</a></TD>
	</TR>
<?
		$i++;

	} // end while loop
?>
</TABLE>
<?
} // end of if numberOfRows > 0 
 ?>

<?php
include_once("common/footer.php");
?>