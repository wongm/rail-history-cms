<?php 

include_once("common/dbConnection.php");

$lineLink = $_REQUEST['line'];

if ($lineLink != '')
{
	$limit = "AND r.link = '".$lineLink."' ";
}

$sql = "SELECT l.name AS locationame, l.*, lt.*, lr.*, r.*
        FROM locations l, location_types lt, locations_raillines lr, raillines r 
		WHERE lr.location_id = l.location_id AND lr.line_id = r.line_id 
		AND l.type = lt.type_id $limit
		ORDER BY km ASC";
$result = query_full_array($sql);
$numberOfRows = sizeof($result);

if ($numberOfRows > 0) 
{	
	$thisLineId = stripslashes($result[0]["line_id"]);
	$thisLineName = stripslashes($result[0]["name"]);
}
	
$pageTitle = 'Update '.$thisLineName.' Line Locations';
include_once("common/header.php");
drawEditLineHeadbar($lineLink);
?>
<a href="addLocation.php?line=<?php echo $lineLink; ?>">Add locations</a><br><br>
<?php
if ($numberOfRows == 0)
{
	echo '<p>No records found!</p>';  
}	
?>
<table class="linedTable">
<?php
for ($i = 0; $i < $numberOfRows; $i++)
{	
	if ($i % NUM_REPEAT_HEADER == 0)
	{
		drawHeader();
	}

	if ($i%2 == '0')
	{
		$style = 'odd';
	}
	else
	{
		$style = 'even';
	}
	
	$thisLocation_id = stripslashes($result[$i]["location_id"]);
	$thisName = stripslashes($result[$i]["locationame"]);
	$thisType = stripslashes($result[$i]["basic"]);
	$thisImg = stripslashes($result[$i]["image"]);
	$thisLink = stripslashes($result[$i]["url"]);
	$thisDisplay = stripslashes($result[$i]["display"]);
	$thisDescription = stripslashes($result[$i]["description"]);
	$thisCredits = stripslashes($result[$i]["credits"]);
	$thisOpen = stripslashes($result[$i]["open"]);
	$thisOpenAccuracy = stripslashes($result[$i]["openAccuracy"]);
	$thisClose = stripslashes($result[$i]["close"]);
	$thisCloseAccuracy = stripslashes($result[$i]["closeAccuracy"]);
	$thisCoOrds = stripslashes($result[$i]["long"]);
	$thisKm = stripslashes($result[$i]["km"]);
	$thisPhotos = stripslashes($result[$i]["photos"]);
	$thisEvents = stripslashes($result[$i]["events"]);
	$thisKmAccuracy = stripslashes($result[$i]["kmAccuracy"]);
	
	$thisTypeFull = stripslashes($result[$i]["basic"]) . "-" . stripslashes($result[$i]["more"]) . "-" . stripslashes($result[$i]["specific"]);
	$thisTypeFull = "$thisTypeFull==";
	$thisTypeFull = str_replace('--==', '', $thisTypeFull);
	$thisTypeFull = str_replace('-==', '', $thisTypeFull);
	
	if($thisKmAccuracy == 'approx')
	{
		$thisKm = "~$thisKm";
	}
	else if($thisKmAccuracy == 'hide')
	{
		$thisKm = "($thisKm)";
	}
	
	if ($thisCoOrds != '' AND $thisCoOrds != 0)
	{
		$thisCoOrds = '<abbr title="'.$thisCoOrds.'">C</abbr>';
	}
	else
	{
		$thisCoOrds = '';
	}
	
	if ($thisPhotos != '0' AND $thisPhotos != '')
	{
		$thisPhotos = '<abbr title="'.$thisPhotos.'">P</abbr>';
	}
	else
	{
		$thisPhotos = '';
	}
	
	if ($thisEvents == 1)
	{
		$thisEvents = 'E';
	}
	else
	{
		$thisEvents = '';
	}
	
	if($thisOpen == '0001-01-01')
	{
		$thisOpen = '';
	}
	
	if($thisClose == '9999-01-01')
	{
		$thisClose = '';
	}	
	
	?>
<tr class=<?php echo $style; ?>>
	<td align="center"><?php echo $thisLocation_id ?></td>
	<td align="left"><a href="editLocations.php?location=<?php echo $thisLocation_id; ?>"><?php echo $thisName ?></a></td>
	<td align="left"><abbr title="<?php echo $thisTypeFull; ?>"><?php echo $thisType; ?></abbr></td>
	<td><?php if ($thisImg != ''){?><abbr title="<?php echo $thisImg; ?>">Img</abbr><?php }?></td>
	<td><?php if ($thisLink != ''){?><abbr title="<?php echo $thisLink; ?>">Link</abbr><?php }?></td>
	<td><?php echo $thisDisplay; ?></td>
	<td><?php if ($thisDescription != ''){?>Y<?php }?></td>
	<td><?php if($thisOpenAccuracy != 'exact'){echo '~';} echo $thisOpen; ?></td>
	<td><?php if($thisCloseAccuracy != 'exact'){echo '~';} echo $thisClose; ?></td>
	<td><?php echo $thisCoOrds; ?></td>
	<td><?php echo $thisPhotos; ?></td>
	<td><?php echo $thisEvents; ?></td>
	<td><?php echo $thisKm; ?></td>
	<td><a href="confirmDeleteLocations.php?location=<?php echo $thisLocation_id; ?>">D</a></td>
</tr>
<?php
} // end for loop
?>
</TABLE>

<?php include_once("common/footer.php"); 

function drawHeader()
{
?>
<tr bgcolor="<?php echo $bgColor; ?>">
		<th>ID</th>
		<th>Name</th>
		<th>Type</th>
		<th>Img?</th>
		<th><abbr title="Link to use instead, to location or lineguide page">Link</abbr></th>
		<th>Display</th>
		<th><abbr title="Description">D</abbr></th>
		<th>Open</th>
		<th>Close</th>
		<th><abbr title="Coordinates">C</abbr></th>
		<th><abbr title="Photos">P</abbr></th>
		<th><abbr title="Events">E</abbr></th>
		<th>KM</th>
		<th><abbr title="Delete!">D</abbr></th>
</tr>
<?php
}	// end function
?>