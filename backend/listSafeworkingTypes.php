<?php
$pageTitle = 'List safeworking types';
include_once("common/dbConnection.php");
include_once("common/header.php");


$sql = "SELECT   * FROM safeworking_types ORDER BY ordered ASC";
$result = MYSQL_QUERY($sql);
$numberOfRows = MYSQL_NUM_ROWS($result);
?>
<a href="enterNewSafeworkingTypes.php">Add new safeworking type</a><br><br>
<?
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
<?
	while ($i<$numberOfRows)
	{

		if (($i%2)==0) { $bgColor = "odd"; } else { $bgColor = "even"; }

	$thisSafeworking_id = MYSQL_RESULT($result,$i,"safeworking_id");
	$thisName = MYSQL_RESULT($result,$i,"name");
	$thisLink = MYSQL_RESULT($result,$i,"link");
	$thisDetails = stripslashes(MYSQL_RESULT($result,$i,"details"));

?>
	<TR class="<? echo $bgColor; ?>">
		<TD><? echo $thisSafeworking_id; ?></TD>
		<TD style="white-space: nowrap"><a href="editSafeworking_types.php?safeworking_idField=<? echo $thisSafeworking_id; ?>"><? echo $thisName; ?></a></TD>
		<TD><? echo $thisLink; ?></TD>
		<TD><? echo $thisDetails; ?></TD>
		<TD><a href="confirmDeleteSafeworking_types.php?safeworking_idField=<? echo $thisSafeworking_id; ?>">Delete</a></TD>
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