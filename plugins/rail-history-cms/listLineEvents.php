<?php
include_once("common/dbConnection.php");

$lineLink = $_REQUEST['line'];
$sql = "SELECT * FROM raillines WHERE link = '".$_REQUEST['line']."'";
$result = query_full_array($sql);
$numberOfRows = sizeof($result);
$thisName = stripslashes($result[$i]["name"]);
$pageTitle = 'Update '.$thisName.' Line Events';
include_once("common/header.php");

if ($numberOfRows==0) {  
?>

Sorry. No records found !!

<?php
}
else if ($numberOfRows>0) 
{
	$i = 0;
	$thisLine_id = stripslashes($result[$i]["line_id"]);
	$thisName = stripslashes($result[$i]["name"]);
	$thisLink = stripslashes($result[$i]["link"]);
	
	drawEditLineHeadbar($lineLink);

?>
<a href="#add">Add events</a><br><br>
<fieldset><legend>Edit Events</legend>
<?php

$sql = "SELECT * FROM railline_events WHERE line = ".$thisLine_id." ORDER BY date ASC";
$result = query_full_array($sql);
$numberOfRows = sizeof($result);

if ($numberOfRows==0) {  
?>

Sorry. No records found !!

<?php
}
else if ($numberOfRows>0) {

	$i=0;
?>

<TABLE class="linedTable">
<tr><th width="15">Date</th>
<th></th>
<th><abbr title="Display">D</abbr></th>
<th>Start</th>
<th>End</th>
<th width="3">Track</th>
<th><abbr title="Safeworking">SW</abbr></th>
<th width="3">Gauge</th>
<th width="3"><abbr title="Midle location?">M</abbr></th>
<th>Why</th>
</TR>
<?php
	while ($i<$numberOfRows)
	{

		if (($i%2)==0) { $bgColor = "odd"; } else { $bgColor = "even"; }

		$thisEvent_id = stripslashes($result[$i]["event_id"]);
		$thisLine = stripslashes($result[$i]["line"]);
		$thisDate = stripslashes($result[$i]["date"]);
		$thisApprox = stripslashes($result[$i]["dateAccuracy"]);
		$thisTracks = stripslashes($result[$i]["tracks"]);
		$thisSafeworking = stripslashes($result[$i]["safeworking"]);
		$thisGauge = stripslashes($result[$i]["gauge"]);
		$thisWhy = stripslashes($result[$i]["safeworking_why"]);
		$thisDisplay = stripslashes($result[$i]["display"]);
		$thisDescription = $result[$i]["description"];
		
		if ($result[$i]["safeworking_middle"] != 0)
		{
			$thisMiddle = 'Y';
		}
		else
		{
			$thisMiddle = '';
		}

?>
	<TR class="<?php echo $bgColor; ?>">
		<TD><a href="editRaillineEvents.php?event=<?php echo $thisEvent_id; ?>&line=<?php echo $_REQUEST['line']; ?> "><?php echo $thisDate; ?></a></TD>
		<TD><?php echo $thisApprox; ?></TD>
		<TD><?php echo $thisDisplay; ?></TD>
<?php 	if ($thisDescription != '')
{	?>
		<TD colspan="7"><?php echo $thisDescription; ?></TD>
<?php }
	else
	{	
		$thisStart_location = stripslashes($result[$i]["start_location"]);
		$thisStartName = stripslashes(query_full_array("SELECT name FROM locations WHERE location_id = '".$thisStart_location."'")[0]["name"]);
		$thisEnd_location = stripslashes($result[$i]["end_location"]);
		$thisEndName = stripslashes(query_full_array("SELECT name FROM locations WHERE location_id = '".$thisEnd_location."'")[0]["name"]);
?>		
		<TD><?php echo $thisStartName; ?></TD>
		<TD><?php echo $thisEndName; ?></TD>
		<TD><?php echo $thisTracks; ?></TD>
		<TD><abbr title="<?php echo $thisSafeworking; ?>"><?php echo substr($thisSafeworking, 0, 4); ?></abbr></TD>
		<TD><?php echo $thisGauge; ?></TD>
		<TD><?php echo $thisMiddle; ?></TD>
		<TD><abbr title="<?php echo $thisWhy; ?>"><?php echo substr($thisWhy, 0, 4); ?></abbr></TD>
<?php 	}	?>		
		<TD><a href="confirmDeleteRaillineEvents.php?event=<?php echo $thisEvent_id; ?>&line=<?php echo $_REQUEST['line']; ?>">Delete</a></TD>
	</TR>
<?php
		$i++;

	} // end while loop
?>
</TABLE>



<?php
} // end of if numberOfRows > 0 
 ?>
</fieldset><br><br>








<fieldset id="add"><legend>Add Events</legend>
<form name="railline_eventsEnterForm" method="POST" action="insertNewRaillineEvents.php">
<input type="hidden" name="thisLineField" value="<?php echo $thisLine_id; ?>">
<input type="hidden" name="thisLinkField" value="<?php echo $thisLink; ?>">

<table cellspacing="2" cellpadding="2" border="0" width="100%">
<?php drawAddNewRailineEvent(); ?>
</table>

<input type="submit" name="submitEnterRailline_eventsForm" value="Enter New Rail Line Event">
</form></fieldset>

<?php
}	//end else if
include_once("common/footer.php");
?>