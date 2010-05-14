<?php

$pageTitle = 'Page variables';
include_once("common/header.php");
include_once("common/dbConnection.php");

$sql = "SELECT   * FROM config".$orderByQuery.$limitQuery;
$result = MYSQL_QUERY($sql);
$numberOfRows = MYSQL_NUM_ROWS($result);

?>
<a href="enterNewConfig.php">Add new variable</a><hr/>
<?

if ($numberOfRows>0) {

	$i=0;
?>
<TABLE CELLSPACING="0" CELLPADDING="3" BORDER="0" WIDTH="100%">
	<TR>
		<TD>
			<a href="<? echo $PHP_SELF; ?>?sortBy=name&sortOrder=<? echo $newSortOrder; ?>&startLimit=<? echo $startLimit; ?>&rows=<? echo $limitPerPage; ?>">
				<B>Name</B>
			</a>
</TD>
		<TD>
			<a href="<? echo $PHP_SELF; ?>?sortBy=value&sortOrder=<? echo $newSortOrder; ?>&startLimit=<? echo $startLimit; ?>&rows=<? echo $limitPerPage; ?>">
				<B>Value</B>
			</a>
</TD>
	</TR>
<?
	while ($i<$numberOfRows)
	{

		if (($i%2)==0) { $bgColor = "#FFFFFF"; } else { $bgColor = "#C0C0C0"; }

	$thisName = MYSQL_RESULT($result,$i,"name");
	$thisValue = stripslashes(MYSQL_RESULT($result,$i,"value"));

?>
	<TR BGCOLOR="<? echo $bgColor; ?>">
		<TD><? echo $thisName; ?></TD>
		<TD><? echo $thisValue; ?></TD>
	<TD><a href="editConfig.php?nameField=<? echo $thisName; ?>">Edit</a></TD>
	<TD><a href="confirmDeleteConfig.php?nameField=<? echo $thisName; ?>">Delete</a></TD>
	</TR>
<?
		$i++;

	} // end while loop
?>
</TABLE>
<br><hr>
<a href="enterNewConfig.php">Add new variable</a>

<?
} // end of if numberOfRows > 0 
 ?>

<?php
include_once("common/footer.php");
?>