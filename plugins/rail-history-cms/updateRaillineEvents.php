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
	$thisApprox = addslashes($_REQUEST['thisApproxField']);
	$thisDisplay = addslashes($_REQUEST['thisDisplayField']);
	$thisStart_location = addslashes($_REQUEST['thisStart_locationField']);
	$thisEnd_location = addslashes($_REQUEST['thisEnd_locationField']);
	$thisTracks = addslashes($_REQUEST['thisTracksField']);
	
	if ($thisTracks == '')
	{
		//$thisTracks = 4565474567567564;
	}
	$thisDescription = addslashes($_REQUEST['thisDescriptionField']);
	$thisSafeworking = addslashes($_REQUEST['thisSafeworkingField']);
	$thisSafeworkingWhy = addslashes($_REQUEST['thisSafeworkingWhyField']);
	$thisSafeworkingMiddle = addslashes($_REQUEST['thisSafeworkingMiddleField']);
	$thisGauge = addslashes($_REQUEST['thisGaugeField']);

if ($thisTracks != '')
{
	$bit = " tracks = '$thisTracks' , ";
}


?>
Record  has been updated in the database. Here is the updated information :- <br><br>

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
	<td align="right"><b>Date Approx : </b></td>
	<td><?php echo $thisApprox; ?></td>
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
<a href="editLineEvents.php?line=<?php echo $thisLink; ?>">Go Back!</a>

<?php

echo '<br><br><hr>';

	
$sql = "UPDATE railline_events SET ".$bit." line = '$thisLine' , dateAccuracy = '$thisApprox' , description = '$thisDescription' , display = '$thisDisplay' , date = '$thisDate' , start_location = '$thisStart_location' , end_location = '$thisEnd_location' , safeworking = '$thisSafeworking' , safeworking_why = '$thisSafeworkingWhy' , safeworking_middle = '$thisSafeworkingMiddle' , gauge = '$thisGauge'  WHERE event_id = '$thisEvent_id'";
$result = MYSQL_QUERY($sql);
echo $sql.'<br><br>';



if ($result != 0)
{
	failed();
}

// fix up kms
//$sql = "update railline_events E set E.start_distance = (select km from locations L where L.location_id = E.start_location), E.end_distance = (select km from locations M where M.location_id = E.end_location)";
//MYSQL_QUERY($sql);	
//echo $sql.'<br><br>';


include_once("common/footer.php");
?>