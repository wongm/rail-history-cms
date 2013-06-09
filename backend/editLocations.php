<?php

$locationToFind = $_REQUEST['location'];
$formType = $_REQUEST['formType'];
include_once("common/dbConnection.php");

if (is_numeric($locationToFind))
{
	$search = "l.location_id = '$locationToFind'";
}
else
{
	$search = "l.name = '$locationToFind'";
}


if ($formType == 'newLocationsRaillines')
{
	$thisLine_id = addslashes($_REQUEST['thisLine_idField']);
	$thisLocation_id = addslashes($_REQUEST['thisLocation_idField']);
	$thisKm = addslashes($_REQUEST['thisKmField']);
	$thisKmaccuracy = addslashes($_REQUEST['thisKmAccuracyField']);
	$thisJunctionType = addslashes($_REQUEST['thisJunctionTypeField']);
	$sqlQuery = "INSERT INTO locations_raillines (line_id , location_id , km , kmaccuracy , junctiontype ) VALUES ('$thisLine_id' , '$thisLocation_id' , '$thisKm' , '$thisKmaccuracy' , '$thisJunctionType' )";
	$result = MYSQL_QUERY($sqlQuery);
}
else if ($formType == 'newLocationYears')
{
	$addLocationYear_Location = addslashes($_REQUEST['thisNewYearLocationField']);
	$addLocationYear_Year = addslashes($_REQUEST['thisNewYearField']);
	
	if ($addLocationYear_Location != '' AND $addLocationYear_Year != '')
	{
		$sqlQuery = "INSERT INTO location_years (location , year) VALUES ('$addLocationYear_Location' , '$addLocationYear_Year')";
		$result = MYSQL_QUERY($sqlQuery);
	}
}
else if ($formType == 'deleteLocationYears')
{
	$deletethisId = addslashes($_REQUEST['thisDeleteYearIdField']);
	$sql = "DELETE FROM location_years WHERE id = '$deletethisId'";
	$result = MYSQL_QUERY($sql);
}
else if ($formType == 'deleteLocationEvents')
{
	$thisEvent_id = addslashes($_REQUEST['thisEvent_idField']);
	$sql = "DELETE FROM location_events WHERE event_id = '$thisEvent_id'";
	$result = MYSQL_QUERY($sql);
}
else if ($formType == 'newLocationEvents')
{
	$thisEvent_id = addslashes($_REQUEST['thisEvent_idField']);
	$thisLocation = addslashes($_REQUEST['thisLocationField']);
	$thisDate = addslashes($_REQUEST['thisDateField']);
	$thisDiagram = $_REQUEST['thisDiagramField'];
	$thisModified = date('Y-m-d H:i:s');
	$thisDetails = addslashes($_REQUEST['thisDetailsField']);
	$thisSource = addslashes($_REQUEST['thisSourceField']);
	$thisLxDetails = addslashes($_REQUEST['thisLxDetailsField']);
	$thisApprox = addslashes($_REQUEST['thisApproxField']);
	
	if ($thisLxDetails != '')
	{
		$thisDetails = $thisLxDetails;
	}
	
	if ($thisLocation != '' OR $thisDate != '')
	{
		$sqlQuery = "INSERT INTO location_events (event_id , location , date , details , source , dateAccuracy, modified ) VALUES ('$thisEvent_id' , '$thisLocation' , '$thisDate' , '$thisDetails' , '$thisSource' , '$thisApprox', '$thisModified')";
		$result = MYSQL_QUERY($sqlQuery);
		
		// update locations if they have events
		$sql = "UPDATE locations SET events = '1' WHERE location_id = '$thisLocation' ";
		MYSQL_QUERY($sql);	
		
		// add interesting year to 'location_years' table
		if ($thisDiagram == true)
		{
			$year = substr($thisDate,0,4);
			
			$sqlCheckingForYear = "SELECT * FROM location_years WHERE location = '$thisLocation' AND year = ".$year;
			$resultCheckingForYear = MYSQL_QUERY($sqlCheckingForYear);
			$numberOfRowsCheckingForYear = MYSQL_NUMROWS($resultCheckingForYear);
			
			if($numberOfRowsCheckingForYear == '0')
			{
				$sqlInsertingYear = "INSERT INTO location_years (`location` , `year`) VALUES ('".$thisLocation."' , '".$year."')";
				$resultInsertingYear = MYSQL_QUERY($sqlInsertingYear);
			}
		}
	}
}


$sql = "SELECT  l.*, lr.*, r.link  
	FROM locations l
	INNER JOIN locations_raillines lr ON l.location_id = lr.location_id 
	INNER JOIN raillines r ON r.line_id = lr.line_id 
	WHERE $search";
$result = MYSQL_QUERY($sql);
$numberOfRows = MYSQL_NUMROWS($result);
$pageTitle = 'Edit Location';

if ($numberOfRows > 1 and !is_numeric($locationToFind)) 
{  
	include_once("common/header.php");
	echo '<p class="error">Multiple records found!</p>';
}
else if ($numberOfRows == 0) 
{
	include_once("common/header.php");
	echo '<p class="error">No records found!</p>';
}
else
{

	//general crap
	$i=0;
	$thisLocationId = stripslashes(MYSQL_RESULT($result,$i,"l.location_id"));
	$thisName = stripslashes(MYSQL_RESULT($result,$i,"name"));
	$thisLink = stripslashes(MYSQL_RESULT($result,$i,"link"));
	$thisSuburb = stripslashes(MYSQL_RESULT($result,$i,"suburb"));
	$thisTracks = stripslashes(MYSQL_RESULT($result,$i,"tracks"));
	$thisType = stripslashes(MYSQL_RESULT($result,$i,"type"));
	$thisImage = stripslashes(MYSQL_RESULT($result,$i,"image"));
	$thisUrl = stripslashes(MYSQL_RESULT($result,$i,"url"));
	$thisDiagrams = stripslashes(MYSQL_RESULT($result,$i,"diagrams"));
	$thisDisplay = stripslashes(MYSQL_RESULT($result,$i,"display"));
	$thisDescription = stripslashes(MYSQL_RESULT($result,$i,"description"));
	$thisCredits = stripslashes(MYSQL_RESULT($result,$i,"credits"));
	$thisOpen = stripslashes(MYSQL_RESULT($result,$i,"open"));
	$thisOpenAccuracy = stripslashes(MYSQL_RESULT($result,$i,"openAccuracy"));
	$thisClose = stripslashes(MYSQL_RESULT($result,$i,"close"));
	$thisCloseAccuracy = stripslashes(MYSQL_RESULT($result,$i,"closeAccuracy"));
	$thisCoOrds = stripslashes(MYSQL_RESULT($result,$i,"long"));
	$thisPhotos = stripslashes(MYSQL_RESULT($result,$i,"photos"));
	
	$thisLine = stripslashes(MYSQL_RESULT($result,$i,"line_id"));
	$thisKm = stripslashes(MYSQL_RESULT($result,$i,"km"));
	$thisLineLink = stripslashes(MYSQL_RESULT($result,$i,"link"));
	
	$pageTitle = "Update Location - $thisName";
	include_once("common/header.php");
	
	drawHeadbar($thisKm, $thisLine);
	drawEditLineHeadbar($thisLineLink);
?>
<table width="100%">
<tr><td>
	<ul>
		<li><a href="#general">General</a></li>
		<li><a href="#lines">Lines</a></li>
		<li><a href="#years">Important Years</a></li>
		<li><a href="#edit">Edit Events</a></li>
		<li><a href="#add">Add Events</a></li>
		<li><a href="#sources">Sources</a></li>
	</ul>
</td>
<td valign="top">
	<p align="right"><a href="/location/<?=$thisLocationId?>">View location</a></p>
</td></tr>
</table>

<form name="locationsUpdateForm" method="POST" action="updateLocations.php">
<input type="hidden" name="thisLineField" value="<? echo $thisLine; ?>">
<input type="hidden" name="thisKmField" value="<? echo $thisKm; ?>">
<fieldset id="general"><legend>General</legend>
<table cellspacing="5" cellpadding="2" border="0" width="100%">
	<tr valign="top" height="20">
		<td align="right"> <b> Location ID :  </b> </td>
		<td><? echo $thisLocationId; ?></td> 
		<input type="hidden" name="thisLocation_idField" value="<? echo $thisLocationId; ?>">
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Name :  </b> </td>
		<td> <input type="text" name="thisNameField" size="30" value="<? echo $thisName; ?>">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Link (for page URL):  </b> </td>
		<td> <input type="text" name="thisLinkField" size="30" value="<? echo $thisLink; ?>">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Location Type :  </b> </td>
		<td> <select name="thisTypeField">
<? drawLocationTypeFields($thisType); ?>
		</select></td>
	</tr>
		<tr valign="top" height="20">
		<td align="right"> <b> Open :  </b> </td>
		<td> <input type="text" name="thisOpenField" size="15" value="<? echo $thisOpen; ?>">
		<select name="thisOpenAccuracyField">
<? drawApproxTimeFields($thisOpenAccuracy); ?>
		</select></td>
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Close :  </b> </td>
		<td> <input type="text" name="thisCloseField" size="15" value="<? echo $thisClose; ?>">
		<select name="thisCloseAccuracyField">
<? drawApproxTimeFields($thisCloseAccuracy); ?>
		</select></td>
	</tr>
	
<tr valign="top" height="20"><td align="left" colspan=2><hr>Track diagrams</td></tr>
    <tr valign="top" height="20">
		<td align="right"> <b> <abbr title="effects whether a gap is left in a lineguide diagram or not, and what side">Tracks</abbr> :  </b> </td>
		<td><select name="thisTracksField">
		<option <? if ($thisTracks == '1'){echo selected;} ?> value="1">Normal</option>
		<option <? if ($thisTracks == '0'){echo selected;} ?> value="0">No Gap (Top)</option>
		<option <? if ($thisTracks == '9'){echo selected;} ?> value="9">No Gap (Bottom)</option>
		<option <? if ($thisTracks == '2'){echo selected;} ?> value="2">2</option>
		</select></td> 
		<!--<input type="text" name="thisTracksField" size="30" value="<? echo $thisTracks; ?>"> -->
	</tr>
	
	<tr valign="top" height="20">
		<td align="right"> <b> <abbr title="String for the images to be used for the lineguide diagam">Images</abbr> (<abbr title="Defaults to the name of the location">optional</abbr>):  </b> </td>
		<td> <input type="text" name="thisImageField" size="30" value="<?=$thisImage?>">  </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> <abbr title="effects if displayed on lineguide diagram. extra option to hide lineguide images on location page">Display</abbr> :  </b> </td>
		<td><select name="thisDisplayField">
<? drawLocationDisplayTypeFields($thisDisplay); ?>		
		</select></td>
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> <abbr title="Can have text name of line; or line_id; if a junction">Redirect page</abbr> :  </b> </td>
		<td> <input type="text" name="thisUrlField" size="30" value="<? echo $thisUrl; ?>">  </td> 
	</tr>
<tr valign="top" height="20"><td align="left" colspan=2><hr>Own page details</td></tr>	

	<tr valign="top" height="20">
		<td align="right"> <b> <abbr title="Comma separated year values">Diagrams</abbr> :  </b> </td>
		<td> <input type="text" name="thisDiagramsField" size="60" value="<? echo $thisDiagrams; ?>">  
		<br><small>For where a 'full' diagram needs to be displayed on a page, not just the little lineguide ones. 
		<br>Images need to located in "/t/<?=$thisImage?>-full-[YEAR].gif"</small></td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Co-ordinates :  </b> </td>
		<td> <input type="text" name="thisCoordsField" size="60" value="<? echo $thisCoOrds; ?>"> 
		<br><small><a href="editAerial.php?locationtoset=<?=$thisLocationId?>" onClick="pl('editAerial.php?locationtoset=<?=$thisLocationId?>'); return false;" >Edit in new window?</a></small> </td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Gallery album :  </b> </td>
		<td><input type="text" name="thisPhotosField" size="60" value="<? echo $thisPhotos; ?>">
		<br><small>The folder located inside /gallery/albums/ - no need for leading or trailing "/"</small></td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Description :  </b> </td>
		<td> 
			<form>
			<script type="text/javascript" src="js_quicktags.js"></script>
			<script type="text/javascript">edToolbar();</script>
			<textarea name="thisDescriptionField" id="thisDescriptionField" wrap="VIRTUAL" cols="100" rows="30"><? echo $thisDescription; ?></textarea>
			<script type="text/javascript">var edCanvas = document.getElementById('thisDescriptionField');</script>
			</form>
		</td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"> <b> Credits :  </b> </td>
		<td> <textarea name="thisCreditsField" wrap="VIRTUAL" cols="80" rows="8"><? echo $thisCredits; ?></textarea></td> 
	</tr>
	<tr valign="top" height="20">
		<td align="right"><b>Show updated :  </b></td>
		<td><label><input type="checkbox" name="flag" /> <small>(check to show on main page as recently updated)</small></label></td> 
	</tr>
</table>

<input type="submit" name="submitUpdateLocationsForm" value="Update Locations" />
</form>
</fieldset><br>

<? drawHeadbar($thisKm, $thisLine); ?>

<br><fieldset id="lines"><legend>Railway Lines</legend>
<table cellspacing="1" cellpadding="5" width="80%" border="0">
<? /* 
		start looping though all railway lines for this location
		grab all railway lines for this location
	*/
	$sqllines = "SELECT * FROM locations_raillines lr, raillines r 
		WHERE lr.line_id = r.line_id AND location_id = '".$thisLocationId."'";
	$resultlines = MYSQL_QUERY($sqllines);
	$numlines = MYSQL_NUM_ROWS($resultlines);
	
	if ($numlines > 0)
	{
		/* 
			and output them
		*/
		for ($i = 0; $i < $numlines; $i++)
		{
			$thisLocalReadOnlyLine = stripslashes(MYSQL_RESULT($resultlines,$i,"lr.line_id"));
			$thisLocalReadOnlyLineName = stripslashes(MYSQL_RESULT($resultlines,$i,"name"));
			$thisLocalReadOnlyKm = stripslashes(MYSQL_RESULT($resultlines,$i,"km"));
			$thisLocalReadOnlyKmAccuracy = stripslashes(MYSQL_RESULT($resultlines,$i,"kmAccuracy"));
			$thisLocalReadOnlyType = stripslashes(MYSQL_RESULT($resultlines,$i,"junctiontype"));
			
?>			<tr><td>
				<fieldset><table cellspacing="2" cellpadding="2">
					<tr valign="top" height="20">
						<td align="right"> <b> Line :  </b> </td>
						<td width="220"><?=$thisLocalReadOnlyLineName?></td>
						<td align="left" rowspan="3">
							<a href="editLocationsRaillines.php?line=<?=$thisLocalReadOnlyLine?>&location=<?=$thisLocationId?>">Edit!</a><br>
							<a href="confirmDeleteLocationsRaillines.php?line=<?=$thisLocalReadOnlyLine?>&location=<?=$thisLocationId?>">Delete!</a>
						</td>
					</tr>
				    <tr valign="top" height="20">
						<td align="right"> <b> Km :  </b> </td>
						<td><? echo $thisLocalReadOnlyKm; ?></td> 
					</tr>
					<tr valign="top" height="20">
						<td align="right"> <b> KM Accuracy :  </b> </td>
						<td><?=$thisLocalReadOnlyKmAccuracy?></td>
					</tr>
					<tr valign="top" height="20">
						<td align="right"> <b> Type :  </b> </td>
						<td><?=$thisLocalReadOnlyType?></td>
					</tr>
				</fieldset></table>
			</td></tr>
<?			}
		}			
		?>
			<tr><td>
				<fieldset>
				<form name="locations_raillinesEnterForm" method="POST" action="<?=$_SERVER['REQUEST_URI']?>#lines">
				<input type="hidden" name="formType" value="newLocationsRaillines">
				<input type="hidden" name="thisLocation_idField" value="<? echo $thisLocationId; ?>">
	
				<table cellspacing="2" cellpadding="2" border="0">
					<tr valign="top" height="20">
					<td align="right"> <b> Line :  </b> </td>
					<td width="220"> <select name="thisLine_idField" id="thisLine_idField">
<? drawLineNameSelectFields($thisLine_id); ?>	
				    </select></td>
				    <td align="right" rowspan="2">
						<input type="submit" name="submitEnterLocationsRaillinesForm" value="Enter Line">
					</td></tr>
					<tr valign="top" height="20">
						<td align="right"> <b> Km :  </b> </td>
						<td> <input type="text" name="thisKmField" size="8" value="<? echo $thisKm; ?>">
						<select name="thisKmAccuracyField">
<? drawApproxDistanceFields() ?>	
						</select></td></tr>
					<tr valign="top" height="20">
						<td align="right"> <b> Type :  </b> </td>
						<td><select name="thisJunctionTypeField">
<? drawRaillineTypeFields() ?>	
						</select></td>
					</tr>
				</table>
				
				</form></fieldset>
			</td></tr>
		</table>
</fieldset>
</br>












<fieldset id="years"><legend>Important Years</legend>
<table class="linedTable">
<tr><th>Year</th><th align='left'>Delete?</th></tr>
<?
// gets the impiortant years for this location

$sql2 = "SELECT * FROM location_years WHERE `location` = '".$thisLocationId."' ORDER BY year ASC";
$result2 = MYSQL_QUERY($sql2);
$numberOfRows2 = MYSQL_NUMROWS($result2);
if ($numberOfRows2>0) 
{
	$thisYear = $thisYear.MYSQL_RESULT($result2,$i2,"year");
			
	for ($i2 = 0; $i2 < $numberOfRows2; $i2++)
	{	
		$year = MYSQL_RESULT($result2,$i2,"year");
		$yearID = MYSQL_RESULT($result2,$i2,"id");
		
		if (($i2%2)==0) 
		{ 
			$bgColor = "odd"; 
		} 
		else
		{ 
			$bgColor = "even";
		} ?>
<tr class="<? echo $bgColor; ?>">
	<td ALIGN='CENTER' class="date"><? echo $year; ?></td>
	<td><a href="confirmDeleteLocationYears.php?yearField=<? echo $yearID; ?>">Delete?</a></td>
</tr>
<?
	} // end for loop
} //end if
else
{
	echo '<tr><th colspan="2">NONE!</th></tr>';
}
?>
</table>
<br/>
<form name="location_yearsEnterForm" method="POST" action="<?=$_SERVER['REQUEST_URI']?>#years">
<input type="hidden" name="formType" value="newLocationYears">
<input type="hidden" name="thisNewYearLocationField" value="<? echo $thisLocationId; ?>">
<input type="text" name="thisNewYearField" size="30" value="">
<input type="submit" name="submitEnterLocation_yearsForm" value="Enter New Location Year">
</form>
</fieldset>
<br/>












<fieldset id="edit"><legend>Edit Events</legend>
<table class="linedTable">
<tr><th>Date</th><th>Event</th><th>Edit</th><th>Delete</th></tr>
<?
$sql = "SELECT date, details, event_id, added, modified, source FROM location_events WHERE location_events.location = '".$thisLocationId."' ORDER BY date ASC";
$result = MYSQL_QUERY($sql);
$numberOfRows = MYSQL_NUM_ROWS($result);

if ($numberOfRows>0) 
{
	for ($i = 0; $i < $numberOfRows; $i++)
	{	
		if (($i%2)==0) { $bgColor = "odd"; } else { $bgColor = "even"; }
		$thisEvent_id = MYSQL_RESULT($result,$i,"event_id");
		$date = MYSQL_RESULT($result,$i,"date");
		$thisAdded = MYSQL_RESULT($result,$i,"added");
		$thisModified = MYSQL_RESULT($result,$i,"modified");
		$details = stripslashes(MYSQL_RESULT($result,$i,"details"));
		$thisSource = MYSQL_RESULT($result,$i,"source");
		
		// for crossing change events
			if (is_numeric($details))
			{
				switch ($details) 
				{
					//	on the level
					case '8':	
						$details = 'Crossing provided';
						break;
					case '9':
						$details = 'Flashing lights provided';
						break;
					case '10':
						$details = 'Boom barriers provided';
						break;
					case '11':
						$details = 'Boom barriers and pedestrian gates provided';
						break;
					case '12':
						$details = 'Pedestrian gates provided';
						break;
					case '13':
						$details = 'Hand gates provided';
						break;
					case '14':
						$details = 'Crib crossing provided';
						break;
					case '38':
						$details = 'Interlocked gates provided';
						break;
					case '39':
						$details = 'Wicket gates provided';
						break;
					//	road bridges
					case '1':
						$details = 'Road underpass provided';
						break;
					case '2':
						$details = 'Road overbridge provided';
						break;
					case '3':
						$details = 'Pedestrian subway';
						break;
					case '4':
						$details = 'Footbridge provided';
						break;
					default:
						$details = '';
						break;
				}
			}	?>
<tr class="<? echo $bgColor; ?>">
	<td class="date"><? echo $date; ?></td>
	<td><? echo $details; ?></td>
	<TD><a href="editLocationEvents.php?eventid=<? echo $thisEvent_id; ?>">Edit</a></TD>
	<TD><a href="confirmDeleteLocationEvents.php?eventid=<? echo $thisEvent_id; ?>">Delete</a></TD>
</tr>	<?
	} // end for loop
} //end if
else
{
	echo '<tr><th colspan="4">NONE!</th></tr>';
}	?>
</table></fieldset>
<br/>







<fieldset id="add"><legend>Add Events</legend>

<form name="location_eventsEnterForm" method="POST" action="<?=$_SERVER['REQUEST_URI']?>#add">
<input type="hidden" name="formType" value="newLocationEvents">
<input type="hidden" name="thisLocationField" value="<? echo $thisLocationId; ?>">
<table>
<? drawAddNewLocationEvent(); ?>
</table>	
<input type="submit" name="submitEnterLocation_eventsForm" value="Enter New Location Event">
</form></fieldset>
<br/>






<? drawObjectSources('location', $thisLocationId); ?>

<? drawHeadbar($thisKm, $thisLine); ?>

<?php
}	// end zero result if
include_once("common/footer.php");
?>