<?php
$pageTitle = 'List safeworking types';
include_once("common/dbConnection.php");
include_once("common/header.php");


$sql = "SELECT   * FROM safeworking_types ORDER BY ordered ASC";
$result = query_full_array($sql);
$numberOfRows = sizeof($result);
?>
<a href="enterNewSafeworkingTypes.php">Add new safeworking type</a><br><br>
<?php
if ($numberOfRows>0) {

	$i=0;
?>
<TABLE class="linedTable">
<TR>
	<th>ID</th>
	<th>Name</th>
	<th>Link</th>
	<th>Details</th>
</TR>
<?php
	while ($i<$numberOfRows)
	{

		if (($i%2)==0) { $bgColor = "odd"; } else { $bgColor = "even"; }

	$thisSafeworking_id = $result[$i]["safeworking_id"];
	$thisName = $result[$i]["name"];
	$thisLink = $result[$i]["link"];
	$thisDetails = stripslashes($result[$i]["details"]);

?>
	<TR class="<?php echo $bgColor; ?>">
		<TD><?php echo $thisSafeworking_id; ?></TD>
		<TD style="white-space: nowrap"><a href="editSafeworking_types.php?safeworking_idField=<?php echo $thisSafeworking_id; ?>"><?php echo $thisName; ?></a></TD>
		<TD><?php echo $thisLink; ?></TD>
		<TD><?php echo $thisDetails; ?></TD>
		<TD><a href="confirmDeleteSafeworking_types.php?safeworking_idField=<?php echo $thisSafeworking_id; ?>">Delete</a></TD>
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