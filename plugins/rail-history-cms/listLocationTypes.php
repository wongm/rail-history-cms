<?php
$pageTitle = 'List location types';
include_once("common/dbConnection.php");
include_once("common/header.php");

$sql = "SELECT   * FROM location_types ORDER BY basic ASC, more ASC";
$result = query_full_array($sql);
$numberOfRows = sizeof($result);

?>
<a href="enterNewLocationTypes.php">Add new location type</a><br><br>
<?php

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
<?php
	while ($i<$numberOfRows)
	{
		if (($i%2)==0) { $bgColor = "odd"; } else { $bgColor = "even"; }

		$thisType_id = $result[$i]["type_id"];
		$thisBasic = $result[$i]["basic"];
		$thisMore = $result[$i]["more"];
		$thisSpecific = $result[$i]["specific"];
		$thisUrl = "/t/1-$thisType_id.gif"
?>
	<TR class="<?php echo $bgColor; ?>">
		<TD><?php echo $thisType_id; ?></TD>
		<TD><?php echo $thisBasic; ?></TD>
		<TD><?php echo $thisMore; ?></TD>
		<TD><?php echo $thisSpecific; ?></TD>
		<TD><img src="<?php echo $thisUrl; ?>" /></TD>
	</TR>
<?php
		$i++;

	} // end while loop
?>
</TABLE>
<?php
} // end of if numberOfRows > 0 
 
include_once("common/footer.php");
?>