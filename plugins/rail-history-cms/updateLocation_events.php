<?php
include_once("common/dbConnection.php");
include_once("common/header.php");
?>
<?php
	// Retreiving Form Elements from Form
	$thisEvent_id = addslashes($_REQUEST['thisEvent_idField']);
	$thisLocation = addslashes($_REQUEST['thisLocationField']);
	$thisDate = addslashes($_REQUEST['thisDateField']);
	$thisDiagram = $_REQUEST['thisDiagramField'];
	$thisModified = date('Y-m-d H:i:s');
	$thisDetails = addslashes($_REQUEST['thisDetailsField']);
	$thisLxDetails = addslashes($_REQUEST['thisLxDetailsField']);
	$thisSource = addslashes($_REQUEST['thisSourceField']);
	$thisSourceDetails = addslashes($_REQUEST['thisSourceDetailsField']);
	$thisApprox = addslashes($_REQUEST['thisApproxField']);
	
	if ($thisLxDetails != '')
	{
		$thisDetails = $thisLxDetails;
	}
	
	if ($thisSourceDetails != "")
	{
		$sqlBit = " , sourcedetails = '$thisSourceDetails' ";
	}

$sql = "UPDATE location_events SET event_id = '$thisEvent_id' , location = '$thisLocation' , date = '$thisDate' , dateAccuracy = '$thisApprox' , modified = '$thisModified' , details = '$thisDetails' , source = '$thisSource' ".$sqlBit." WHERE event_id = '$thisEvent_id'";
$result = query_full_array($sql);

// update locations if they have events
$sql = "UPDATE locations SET events = '1' WHERE location_id = '$thisLocation' ";
query_full_array($sql);	

// add interesting year to 'location_years' table
if ($thisDiagram == true)
{
	$year = substr($thisDate,0,4);
	
	$sqlCheckingForYear = "SELECT * FROM location_years WHERE location = '$thisLocation' AND year = ".$year;
	$resultCheckingForYear = query_full_array($sqlCheckingForYear);
	$numberOfRowsCheckingForYear = sizeof($resultCheckingForYear);
	
	if($numberOfRowsCheckingForYear == '0')
	{
		$sqlInsertingYear = "INSERT INTO location_years (`location` , `year`) VALUES ('".$thisLocation."' , '".$year."')";
		$resultInsertingYear = query_full_array($sqlInsertingYear);
	}
}


if ($result != 0)
{
	failed();
}	?>
Record  has been updated in the database. Here is the updated information :- <br><br>

<table>
<tr height="30">
	<td align="right"><b>Event_id : </b></td>
	<td><?php echo $thisEvent_id; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Location : </b></td>
	<td><?php echo $thisLocation; ?></td>
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
	<td align="right"><b>Details : </b></td>
	<td><?php echo $thisDetails; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Source : </b></td>
	<td><?php echo $thisSource; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Source Details : </b></td>
	<td><?php echo $thisSourceDetails; ?></td>
</tr>
</table>
<a href="editLocations.php?location=<?php echo $thisLocation; ?>">Go Back!</a>

<?php
include_once("common/footer.php");
?>