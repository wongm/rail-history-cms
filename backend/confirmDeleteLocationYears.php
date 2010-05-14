<?php
include_once("common/dbConnection.php");
include_once("common/header.php");
?>
<?php
$thisLocation = $_REQUEST['yearField']
?>
<?php
$sql = "SELECT   * FROM location_years WHERE id = '$thisLocation'";
$result = MYSQL_QUERY($sql);
$numberOfRows = MYSQL_NUMROWS($result);
if ($numberOfRows==0) {  
?>

Sorry. No records found !!

<?php
}
else if ($numberOfRows>0) {

	$i=0;
	$thisLocation = MYSQL_RESULT($result,$i,"location");
	$thisYear = MYSQL_RESULT($result,$i,"year");
	$thisId = MYSQL_RESULT($result,$i,"id");

}
?>

<h3>Confirm Record Deletion</h3><hr>

<table>
<tr height="30">
	<td align="right"><b>Location : </b></td>
	<td><? echo $thisLocation; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Year : </b></td>
	<td><? echo $thisYear; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Id : </b></td>
	<td><? echo $thisId; ?></td>
</tr>

</table>

<h3>If you are sure you want to delete the above record, please press the delete button below.</h3><br><br>
<form name="location_yearsEnterForm" method="POST" action="editLocations.php?location=<?=$thisLocation?>#years">
<input type="hidden" name="formType" value="deleteLocationYears">
<input type="hidden" name="thisDeleteYearIdField" value="<? echo $thisId; ?>">
<input type="hidden" name="thisLocationField" value="<? echo $thisLocation; ?>">
<input type="hidden" name="thisYearField" value="<? echo $thisYear; ?>">
<input type="submit" name="submitConfirmDeleteLocation_yearsForm" value="Delete  from Location_years">
<input type="button" name="cancel" value="Go Back" onClick="javascript:history.back();">
</form>

<?php
include_once("common/footer.php");
?>