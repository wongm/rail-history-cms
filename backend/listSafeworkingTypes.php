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

<TABLE CELLSPACING="0" CELLPADDING="3" BORDER="0" WIDTH="100%">
	<TR>
		<TD>
			<a href="<? echo $PHP_SELF; ?>?sortBy=safeworking_id&sortOrder=<? echo $newSortOrder; ?>&startLimit=<? echo $startLimit; ?>&rows=<? echo $limitPerPage; ?>">
				<B>Safeworking_id</B>
			</a>
</TD>
		<TD>
			<a href="<? echo $PHP_SELF; ?>?sortBy=name&sortOrder=<? echo $newSortOrder; ?>&startLimit=<? echo $startLimit; ?>&rows=<? echo $limitPerPage; ?>">
				<B>Name</B>
			</a>
</TD>
		<TD>
			<a href="<? echo $PHP_SELF; ?>?sortBy=link&sortOrder=<? echo $newSortOrder; ?>&startLimit=<? echo $startLimit; ?>&rows=<? echo $limitPerPage; ?>">
				<B>Link</B>
			</a>
</TD>
		<TD>
			<a href="<? echo $PHP_SELF; ?>?sortBy=details&sortOrder=<? echo $newSortOrder; ?>&startLimit=<? echo $startLimit; ?>&rows=<? echo $limitPerPage; ?>">
				<B>Details</B>
			</a>
</TD>
	</TR>
<?
	while ($i<$numberOfRows)
	{

		if (($i%2)==0) { $bgColor = "#FFFFFF"; } else { $bgColor = "#C0C0C0"; }

	$thisSafeworking_id = MYSQL_RESULT($result,$i,"safeworking_id");
	$thisName = MYSQL_RESULT($result,$i,"name");
	$thisLink = MYSQL_RESULT($result,$i,"link");
	$thisDetails = stripslashes(MYSQL_RESULT($result,$i,"details"));

?>
	<TR BGCOLOR="<? echo $bgColor; ?>">
		<TD><? echo $thisSafeworking_id; ?></TD>
		<TD><? echo $thisName; ?></TD>
		<TD><? echo $thisLink; ?></TD>
		<TD><? echo $thisDetails; ?></TD>
	<TD><a href="editSafeworking_types.php?safeworking_idField=<? echo $thisSafeworking_id; ?>">Edit</a></TD>
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