<?php
include_once("common/dbConnection.php");
include_once("common/header.php");
?>
<?php
$thisEvent_id = $_REQUEST['event'];
$thisLink = $_REQUEST['line'];
?>
<?php
$sql = "SELECT   * FROM railline_events WHERE event_id = '$thisEvent_id'";
$result = query_full_array($sql);
$numberOfRows = sizeof($result);
if ($numberOfRows==0) {  
?>

Sorry. No records found !!

<?php
}
else if ($numberOfRows>0) {

	$i=0;
	$thisEvent_id = $result[$i]["event_id"];
	$thisLine = $result[$i]["line"];
	$thisDate = $result[$i]["date"];
	$thisStart_location = $result[$i]["start_location"];
	$thisEnd_location = $result[$i]["end_location"];
	$thisStart_distance = $result[$i]["start_distance"];
	$thisEnd_distance = $result[$i]["end_distance"];
	$thisTracks = $result[$i]["tracks"];
	$thisSafeworking = $result[$i]["safeworking"];
	$thisGauge = $result[$i]["gauge"];

}
?>

<h2>Confirm Record Deletion</h2><br><br>

<table>
<tr height="30">
	<td align="right"><b>Event_id : </b></td>
	<td><?php echo $thisEvent_id; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Line : </b></td>
	<td><?php echo $thisLine; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Date : </b></td>
	<td><?php echo $thisDate; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Start_location : </b></td>
	<td><?php echo $thisStart_location; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>End_location : </b></td>
	<td><?php echo $thisEnd_location; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Start_distance : </b></td>
	<td><?php echo $thisStart_distance; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>End_distance : </b></td>
	<td><?php echo $thisEnd_distance; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Tracks : </b></td>
	<td><?php echo $thisTracks; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Safeworking : </b></td>
	<td><?php echo $thisSafeworking; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Gauge : </b></td>
	<td><?php echo $thisGauge; ?></td>
</tr>
</table>

<h3>If you are sure you want to delete the above record, please press the delete button below.</h3><br><br>
<form name="railline_eventsEnterForm" method="POST" action="deleteRaillineEvents.php">
<input type="hidden" name="thisLinkField" value="<?php echo $thisLink; ?>">
<input type="hidden" name="thisEvent_idField" value="<?php echo $thisEvent_id; ?>">
<input type="submit" name="submitConfirmDeleteRailline_eventsForm" value="Delete  from Railline_events">
<input type="button" name="cancel" value="Go Back" onClick="javascript:history.back();">
</form>

<?php
include_once("common/footer.php");
?>