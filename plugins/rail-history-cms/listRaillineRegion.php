<?php
$pageTitle = 'Region to Lineguide Mappings';
include_once("common/dbConnection.php");
include_once("common/header.php");

$sql = "SELECT   * FROM railline_region, articles, raillines 
	WHERE railline_region.line_id = raillines.line_id AND railline_region.article_id = articles.article_id
	ORDER BY articles.title, raillines.order";
$result = query_full_array($sql);
$numberOfRows = sizeof($result);

if ($numberOfRows>0) 
{
?>	
<a href="editRaillineRegion.php">Add new mapping</a>
	<TABLE class="linedTable">
	<TR>
		<th>Line</th>
		<th>Region</th>
	</TR>
<?php
	$i = 0;
	while ($i<$numberOfRows)
	{

		if (($i%2)==0) { $bgColor = "odd"; } else { $bgColor = "even"; }

	$thisLinkzor_id = $result[$i]["linkzor_id"];
	$thisLine_id = $result[$i]["name"];
	$thisArticle_id = $result[$i]["title"];

?>
	<TR class="<?php echo $bgColor; ?>">
		<TD><?php echo $thisLine_id; ?></TD>
		<TD><?php echo $thisArticle_id; ?></TD>
	<TD><a href="editRaillineRegion.php?id=<?php echo $thisLinkzor_id; ?>">Edit</a></TD>
	<TD><a href="confirmDeleteRaillineRegion.php?id=<?php echo $thisLinkzor_id; ?>">Delete</a></TD>
	</TR>
<?php
		$i++;

	} // end while loop
?>
</TABLE>
<?php
} // end of if numberOfRows > 0 
 ?>

<?php
include_once("common/footer.php");
?>