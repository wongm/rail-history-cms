<?php
include_once("common/dbConnection.php");
include_once("common/header.php");
?>
<?php
	// Retreiving Form Elements from Form
	$thisEvent_id = addslashes($_REQUEST['thisEvent_idField']);
	$thisLine = addslashes($_REQUEST['thisLineField']);
	$thisLink = addslashes($_REQUEST['thisLinkField']);
	$thisDate = addslashes($_REQUEST['thisDateField']);
	$thisStart_location = addslashes($_REQUEST['thisStart_locationField']);
	$thisEnd_location = addslashes($_REQUEST['thisEnd_locationField']);
	$thisStart_distance = addslashes($_REQUEST['thisStart_distanceField']);
	$thisEnd_distance = addslashes($_REQUEST['thisEnd_distanceField']);
	$thisTracks = addslashes($_REQUEST['thisTracksField']);
	$thisSafeworking = addslashes($_REQUEST['thisSafeworkingField']);
	$thisGauge = addslashes($_REQUEST['thisGaugeField']);

?>
<?
$sql = "DELETE FROM railline_events WHERE event_id = '$thisEvent_id'";
$result = MYSQL_QUERY($sql);

?>
Record  has been deleted from database. Here is the deleted record :-<br><br>

<table>
<tr height="30">
	<td align="right"><b>Event_id : </b></td>
	<td><? echo $thisEvent_id; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Line : </b></td>
	<td><? echo $thisLine; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Date : </b></td>
	<td><? echo $thisDate; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Start_location : </b></td>
	<td><? echo $thisStart_location; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>End_location : </b></td>
	<td><? echo $thisEnd_location; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Start_distance : </b></td>
	<td><? echo $thisStart_distance; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>End_distance : </b></td>
	<td><? echo $thisEnd_distance; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Tracks : </b></td>
	<td><? echo $thisTracks; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Safeworking : </b></td>
	<td><? echo $thisSafeworking; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Gauge : </b></td>
	<td><? echo $thisGauge; ?></td>
</tr>
</table>
<a href="editLineEvents.php?line=<? echo $thisLink; ?>">Go Back!</a>

<?php
include_once("common/footer.php");
?>