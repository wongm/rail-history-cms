<?php 

include_once("common/dbConnection.php");

$lineLink = $_REQUEST['line'];

if ($lineLink != '')
{
	$limit = "AND r.link = '".$lineLink."' ";
}

$sql = "SELECT * FROM locations l, location_types lt, locations_raillines lr, raillines r 
		WHERE lr.location_id = l.location_id AND lr.line_id = r.line_id 
		AND l.type = lt.type_id $limit
		ORDER BY km ASC";
$result = MYSQL_QUERY($sql);
$numberOfRows = MYSQL_NUM_ROWS($result);


if ($numberOfRows==0) 
{
	$pageTitle = 'List Line Locations';
	include_once("common/header.php");
	echo '<p class="error">No records found!</p>';  
}
else if ($numberOfRows>0) 
{	
	$thisLineId = stripslashes(MYSQL_RESULT($result,'0',"r.line_id"));
	$thisLineName = stripslashes(MYSQL_RESULT($result,'0',"r.name"));
	
	$pageTitle = 'Update '.$thisLineName.' Line Locations';
	include_once("common/header.php");
	drawEditLineHeadbar($lineLink);
	?>
<a href="addLocation.php?line=<? echo $thisLineId; ?>">Add locations</a><br><br>

<table CELLPADDING="5">
<?	for ($i = 0; $i < $numberOfRows; $i++)
	{	
		if ($i % NUM_REPEAT_HEADER == 0)
		{
			drawHeader();
		}
	
		if ($i%2 == '0')
		{
			$style = 'bgcolor="white"';
		}
		else
		{
			$style = 'bgcolor="#F5F7F5"';
		}
		
		$thisLocation_id = stripslashes(MYSQL_RESULT($result,$i,"location_id"));
		$thisName = stripslashes(MYSQL_RESULT($result,$i,"name"));
		//$thisType = stripslashes(MYSQL_RESULT($result,$i,"basic")) . "-" . stripslashes(MYSQL_RESULT($result,$i,"more")) . "-" . stripslashes(MYSQL_RESULT($result,$i,"specific"));
		$thisType = stripslashes(MYSQL_RESULT($result,$i,"basic"));
		$thisImg = stripslashes(MYSQL_RESULT($result,$i,"image"));
		$thisLink = stripslashes(MYSQL_RESULT($result,$i,"url"));
		$thisDisplay = stripslashes(MYSQL_RESULT($result,$i,"display"));
		$thisDescription = stripslashes(MYSQL_RESULT($result,$i,"description"));
		$thisCredits = stripslashes(MYSQL_RESULT($result,$i,"credits"));
		$thisOpen = stripslashes(MYSQL_RESULT($result,$i,"open"));
		$thisOpenAccuracy = stripslashes(MYSQL_RESULT($result,$i,"openAccuracy"));
		$thisClose = stripslashes(MYSQL_RESULT($result,$i,"close"));
		$thisCloseAccuracy = stripslashes(MYSQL_RESULT($result,$i,"closeAccuracy"));
		$thisCoOrds = stripslashes(MYSQL_RESULT($result,$i,"long"));
		$thisKm = stripslashes(MYSQL_RESULT($result,$i,"km"));
		$thisPhotos = stripslashes(MYSQL_RESULT($result,$i,"photos"));
		$thisEvents = stripslashes(MYSQL_RESULT($result,$i,"events"));
		$thisKmAccuracy = stripslashes(MYSQL_RESULT($result,$i,"kmAccuracy"));
		
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
			$thisPhotos = '<abbr title="'.$thisPhotos.'">Y</abbr>';
		}
		else
		{
			$thisPhotos = '';
		}
		
		if ($thisEvents == 1)
		{
			$thisEvents = 'Y';
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
<tr align="center"<? echo $style; ?>>
		<td align="left"><a href="editLocations.php?location=<? echo $thisLocation_id; ?>"><? echo $thisName.' ('.$thisLocation_id.')'; ?></a></td>
		<td align="left"><? echo $thisType; ?></td>
		<td><? if ($thisImg != ''){?><abbr title="<? echo $thisImg; ?>">Img</abbr><? }?></td>
		<td><? if ($thisLink != ''){?><abbr title="<? echo $thisLink; ?>">Link</abbr><? }?></td>
		<td><? echo $thisDisplay; ?></td>
		<td><? if ($thisDescription != ''){?>Y<? }?></td>
		<td><? if($thisOpenAccuracy != 'exact'){echo '~';} echo $thisOpen; ?></td>
		<td><? if($thisCloseAccuracy != 'exact'){echo '~';} echo $thisClose; ?></td>
		<td><? echo $thisCoOrds; ?></td>
		<td><? echo $thisPhotos; ?></td>
		<td><? echo $thisEvents; ?></td>
		<td><? echo $thisKm; ?></td>
		<td><a href="confirmDeleteLocations.php?location=<? echo $thisLocation_id; ?>">D</a></td>
</tr>
<?
	} // end for loop
}
?>
</TABLE>

<? include_once("common/footer.php"); 

function drawHeader()
{
?>
<tr bgcolor="<? echo $bgColor; ?>">
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
<?
}	// end function
?>