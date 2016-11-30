<?php
include_once("common/dbConnection.php");
//include_once("common/header.php");
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
	$thisDescription = addslashes($_REQUEST['thisDescriptionField']);
	$thisSafeworking = addslashes($_REQUEST['thisSafeworkingField']);
	$thisSafeworkingWhy = addslashes($_REQUEST['thisSafeworkingWhyField']);
	$thisSafeworkingMiddle = addslashes($_REQUEST['thisSafeworkingMiddleField']);
	$thisGauge = addslashes($_REQUEST['thisGaugeField']);
	
	if (($thisLine == '' OR $thisStart_location == '' OR $thisEnd_location == '') AND $thisDescription == '')
	{
		insertfail();
	}
	else
	{	
		$sqlFirstHalf = "INSERT INTO railline_events (line , date , dateAccuracy, start_location , end_location , display ";
		$sqlLastHalf = "'$thisLine' , '$thisDate' , '$thisApprox' , '$thisStart_location' , '$thisEnd_location' , '$thisDisplay' ";
		
		if($thisDescription != ''){
			$sqlFirstHalf = $sqlFirstHalf." , `description`";
			$sqlLastHalf = $sqlLastHalf." , '$thisDescription'";
		}
		if($thisTracks != ''){
			$sqlFirstHalf = $sqlFirstHalf." , `tracks`";
			$sqlLastHalf = $sqlLastHalf." , '$thisTracks'";
		}
		if($thisSafeworking != ''){
			$sqlFirstHalf = $sqlFirstHalf." , `safeworking`";
			$sqlLastHalf = $sqlLastHalf." , '$thisSafeworking'";
		}
		if($thisGauge != ''){
			$sqlFirstHalf = $sqlFirstHalf." , `gauge`";
			$sqlLastHalf = $sqlLastHalf." , '$thisGauge'";
		}
		if($thisSafeworkingWhy != ''){
			$sqlFirstHalf = $sqlFirstHalf." , `safeworking_why`";
			$sqlLastHalf = $sqlLastHalf." , '$thisSafeworkingWhy'";
		}
		
		if($thisSafeworkingWhy != ''){
			$sqlFirstHalf = $sqlFirstHalf." , `safeworking_middle`";
			$sqlLastHalf = $sqlLastHalf." , '$thisSafeworkingMiddle'";
		}
		
		$sqlQuery = $sqlFirstHalf.") VALUES (".$sqlLastHalf.")";
		$result = MYSQL_QUERY($sqlQuery);
		
		// update this event
		//$sql = "update railline_events E set E.start_distance = (select km from locations L where L.location_id = E.start_location), E.end_distance = (select km from locations M where M.location_id = E.end_location)";
		//MYSQL_QUERY($sql);	
		
		// and update km for end locations
		//$sql = "update railline_events E set E.start_distance = (select km from locations L where L.location_id = E.start_location), E.end_distance = (select km from locations M where M.location_id = E.end_location)";
		//MYSQL_QUERY($sql);	
			
		// and for the middle location
		//$sql = "update railline_events E set E.safeworking_km = (select km from locations L where L.location_id = E.safeworking_middle)";
		//MYSQL_QUERY($sql);
		
		Header("Location: ".$_SERVER['HTTP_REFERER']."#add");
		
		if ($result != 0)
		{
			failed();
		}	?>
A new record has been inserted in the database. Here is the information that has been inserted :- <br><br>

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
<a href="editRaillines.php?line=<?php echo $thisLink; ?>">Go Back!</a><br><br>

<?php
echo $sqlQuery;
}	// end if
include_once("common/footer.php");



?>