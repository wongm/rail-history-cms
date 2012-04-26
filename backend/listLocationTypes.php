<?php
$pageTitle = 'List location types';
include_once("common/dbConnection.php");
include_once("common/header.php");

$sql = "SELECT   * FROM location_types ORDER BY basic ASC, more ASC";
$result = MYSQL_QUERY($sql);
$numberOfRows = MYSQL_NUM_ROWS($result);

?>
<a href="enterNewLocationTypes.php">Add new location type</a><br><br>
<?

if ($numberOfRows>0) {

	$i=0;
?>
<TABLE class="linedTable">
	<TR>
		<th>ID</th>
		<th>Basic</th>
		<th>More</th>
		<th>Specific</th>
	</TR>
<?
	while ($i<$numberOfRows)
	{
		if (($i%2)==0) { $bgColor = "odd"; } else { $bgColor = "even"; }

		$thisType_id = MYSQL_RESULT($result,$i,"type_id");
		$thisBasic = MYSQL_RESULT($result,$i,"basic");
		$thisMore = MYSQL_RESULT($result,$i,"more");
		$thisSpecific = MYSQL_RESULT($result,$i,"specific");
		$thisUrl = "/t/1-$thisType_id.gif"
?>
	<TR class="<? echo $bgColor; ?>">
		<TD><? echo $thisType_id; ?></TD>
		<TD><? echo $thisBasic; ?></TD>
		<TD><? echo $thisMore; ?></TD>
		<TD><? echo $thisSpecific; ?></TD>
		<TD><img src="<? echo $thisUrl; ?>" /></TD>
	</TR>
<?
		$i++;

	} // end while loop
?>
</TABLE>
<?
} // end of if numberOfRows > 0 
 
include_once("common/footer.php");
?>