<?php
include_once("common/dbConnection.php");
include_once("common/header.php");
?>
<?php
$thisEvent_id = $_REQUEST['eventid']
?>
<?php
$sql = "SELECT   * FROM location_events WHERE event_id = '$thisEvent_id'";
$result = MYSQL_QUERY($sql);
$numberOfRows = MYSQL_NUMROWS($result);
if ($numberOfRows==0) {  
?>

Sorry. No records found !!

<?php
}
else if ($numberOfRows>0) {

	$i=0;
	$thisEvent_id = MYSQL_RESULT($result,$i,"event_id");
	$thisLocation = MYSQL_RESULT($result,$i,"location");
	$thisDate = MYSQL_RESULT($result,$i,"date");
	$thisAdded = MYSQL_RESULT($result,$i,"added");
	$thisModified = MYSQL_RESULT($result,$i,"modified");
	$thisDetails = MYSQL_RESULT($result,$i,"details");
	$thisSource = MYSQL_RESULT($result,$i,"source");

}
?>

<h2>Confirm Record Deletion</h2><br><br>

<table>
<tr height="30">
	<td align="right"><b>Event_id : </b></td>
	<td><? echo $thisEvent_id; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Location : </b></td>
	<td><? echo $thisLocation; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Date : </b></td>
	<td><? echo $thisDate; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Added : </b></td>
	<td><? echo $thisAdded; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Modified : </b></td>
	<td><? echo $thisModified; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Details : </b></td>
	<td><? echo $thisDetails; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Source : </b></td>
	<td><? echo $thisSource; ?></td>
</tr>
</table>

<h3>If you are sure you want to delete the above record, please press the delete button below.</h3><br><br>
<form name="location_eventsEnterForm" method="POST" action="editLocations.php?location=<?=$thisLocation?>#edit">
<input type="hidden" name="formType" value="deleteLocationEvents">
<input type="hidden" name="thisEvent_idField" value="<? echo $thisEvent_id; ?>">
<input type="hidden" name="thisLocationField" value="<? echo $thisLocation; ?>">
<input type="submit" name="submitConfirmDeleteLocation_eventsForm" value="Delete  from Location_events">
<input type="button" name="cancel" value="Go Back" onClick="javascript:history.back();">
</form>

<?php
include_once("common/footer.php");
?>