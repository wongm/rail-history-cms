<?php

$thisName = addslashes($_REQUEST['thisNameField']);
$pageTitle = "Updated Location - ".stripslashes(stripslashes($thisName));

include_once("common/dbConnection.php");
include_once("common/header.php");
include_once("../common/formatting-functions.php");

// Retreiving Form Elements from Form
$thisLocationId = addslashes($_REQUEST['thisLocation_idField']);
$thisName = addslashes($_REQUEST['thisNameField']);
$thisSuburb = addslashes($_REQUEST['thisSuburbField']);
$thisLine = addslashes($_REQUEST['thisLineField']);
$thisTracks = addslashes($_REQUEST['thisTracksField']);
$thisType = addslashes($_REQUEST['thisTypeField']);
$thisImage = addslashes($_REQUEST['thisImageField']);
$thisUrl = addslashes($_REQUEST['thisUrlField']);
$thisDisplay = addslashes($_REQUEST['thisDisplayField']);
//$thisStatus = addslashes($_REQUEST['thisStatusField']);
$thisDescription = addslashes($_REQUEST['thisDescriptionField']);
$thisCredits = addslashes($_REQUEST['thisCreditsField']);
$thisOpen = addslashes($_REQUEST['thisOpenField']);
$thisOpenAccuracy = addslashes($_REQUEST['thisOpenAccuracyField']);
$thisClose = addslashes($_REQUEST['thisCloseField']);
$thisCloseAccuracy = addslashes($_REQUEST['thisCloseAccuracyField']);
$thisLong = addslashes($_REQUEST['thisCoordsField']);
$thisDiagrams = addslashes($_REQUEST['thisDiagramsField']);
$thisKm = addslashes($_REQUEST['thisKmField']);
$thisKmAccuracy = addslashes($_REQUEST['thisKmAccuracyField']);
$thisYear = addslashes($_REQUEST['thisYearField']);
$thisPhotos = addslashes($_REQUEST['thisPhotosField']);

if ($thisPhotos == '')
{
	$thisPhotos = 0;
}
	
//create the SQL string
$sql = "UPDATE locations SET `name` = '$thisName' , `tracks` = '$thisTracks' , `display` = '$thisDisplay' , `type` = '$thisType' ";

// for auto modification of last modified 

if ($_REQUEST['flag'] == 'on')
{
	$thisModified = date('Y-m-d H:i:s');
	$sql = $sql." , modified = '$thisModified'";
	$done .= '<p>Last updated locations updated!</p>';
}

$sql = $sql." , `url` = '$thisUrl'";
$sql = $sql." , `image` = '$thisImage'";
$sql = $sql." , `description` = '$thisDescription'";
$sql = $sql." , `credits` = '$thisCredits'";
if ($thisLong != "")
{
	$sql = $sql." , `long` = '$thisLong'";
}
$sql = $sql." , `diagrams` = '$thisDiagrams'";
if ($thisOpen != "")
{
	$sql = $sql." , `open` = '$thisOpen', `openAccuracy` = '$thisOpenAccuracy'";
}
if ($thisClose != "")
{
	$sql = $sql." , `close` = '$thisClose', `closeAccuracy` = '$thisCloseAccuracy'";
}
$sql = $sql." , `photos` = '$thisPhotos'";
$sql = $sql." WHERE location_id = '$thisLocationId'";

/*
 * --------------------------------------------------
 * add it to table
 * --------------------------------------------------
 */
$result = MYSQL_QUERY($sql, backendDBConnect());
$done .= '<p>Location data updated!</p>';

/*
 * --------------------------------------------------
 * for the gallery database table
 * --------------------------------------------------
 */
if ($thisPhotos != '')
{
	if (sizeof(split(';', $thisPhotos)) == 1)
	{
		$gallerysql = "UPDATE `zen_albums` 
		SET `location_id` = '$thisLocationId' , `location_name` = '$thisName' 
		WHERE `folder` = '$thisPhotos'";
		$galleryresult = MYSQL_QUERY($gallerysql, galleryDBConnect());
		$done .= '<p>Gallery location links updated!</p>';
	}
}

if ($result != 0)
{
	failed();
}

/*
 * --------------------------------------------------
 * for the years table
 * --------------------------------------------------
 */
if($thisYear != '')
{
	$i = 0;
	$yearArray = split (';', $thisYear);
	$yearArrayLength = sizeOf($yearArray);
	
	while ($i<$yearArrayLength)
	{
		$sql23 = "SELECT * FROM location_years WHERE location = '$thisLocationId' AND year = ".$yearArray[$i];
		$result23 = MYSQL_QUERY($sql23, backendDBConnect());
		$numberOfRows23 = MYSQL_NUMROWS($result23);
		
		if($numberOfRows23 == '0')
		{
			$sql34 = "INSERT INTO location_years (`location` , `year`) VALUES ('".$thisLocationId."' , '".$yearArray[$i]."')";
			$result34 = MYSQL_QUERY($sql34, backendDBConnect());
		}
		$i++;
	}
	$done .= '<p>Location year links updated!</p>';
}

/*
 * --------------------------------------------------
 * next and forward linkzor - to display, not update
 * --------------------------------------------------
 */ 
$sqlBack = "SELECT * FROM locations l, locations_raillines lr WHERE lr.location_id = l.location_id 
	AND km < '".$thisKm."' AND lr.line_id = '".$thisLine."' ORDER BY km DESC LIMIT 0, 1";
$sqlNext = "SELECT * FROM locations l, locations_raillines lr WHERE lr.location_id = l.location_id 
	AND km > '".$thisKm."' AND lr.line_id = '".$thisLine."' ORDER BY km ASC LIMIT 0, 1";

$resultBack = MYSQL_QUERY($sqlBack, backendDBConnect());
$resultNext = MYSQL_QUERY($sqlNext, backendDBConnect());

if (MYSQL_NUM_ROWS($resultBack) > 0)
	{
	$Name = stripslashes(MYSQL_RESULT($resultBack,0,"name"));
	$id = stripslashes(MYSQL_RESULT($resultBack,0,"location_id"));
	$back = '<a href="./editLocations.php?location='.$id.'">&laquo; '.$Name.'</a>'; 
}
if (MYSQL_NUM_ROWS($resultNext) > 0)
	{
	$Name = stripslashes(MYSQL_RESULT($resultNext,0,"name"));
	$id = stripslashes(MYSQL_RESULT($resultNext,0,"location_id"));
	$next = '<a href="./editLocations.php?location='.$id.'">'.$Name.' &raquo;</a>'; 
}
?>
<!-- next / back links -->
<table class="headbar">
<tr><td><? echo $back; ?></td><td align="right"><? echo $next; ?></td></tr>
</table>

<table width="100%">
<tr><td><h2><a href="<?=$_SERVER['HTTP_REFERER']?>">Return to editing!</a></h2>
</td><td  valign="top" align="right"><a href="/location/<?=$thisLocationId?>">View location</a>
</td></tr></table>
<?=$done;?>
<hr>
<table>
<tr height="30">
	<td align="right"><b>Location_id : </b></td>
	<td><? echo $thisLocationId; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Name : </b></td>
	<td><? echo stripslashes(stripslashes($thisName)); ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Suburb : </b></td>
	<td><? echo $thisSuburb; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Line : </b></td>
	<td><? echo $thisLine; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Tracks : </b></td>
	<td><? echo $thisTracks; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Type : </b></td>
	<td><? echo $thisType; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Image : </b></td>
	<td><? echo $thisImage; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Url : </b></td>
	<td><? echo $thisUrl; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Extra Diagrams? : </b></td>
	<td><? echo $thisDiagrams; ?></td>
</tr>
<!--
<tr height="30">
	<td align="right"><b>Status : </b></td>
	<td><? echo $thisStatus; ?></td>
</tr>
-->
<tr height="30">
	<td align="right"><b>Display : </b></td>
	<td><? echo $thisDisplay; ?></td>
</tr>
<tr height="30">
    <td valign="top" align="right"><b>Description : </b></td>
    <td bgcolor="white"><? echo drawFormattedText(stripslashes(stripslashes($thisDescription))); ?></td>
</tr>
<tr height="30">
    <td valign="top" align="right"><b>Credits : </b></td>
    <td bgcolor="white"><? echo stripslashes(stripslashes($thisCredits)); ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Open : </b></td>
	<td><? echo $thisOpen; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>OpenAccuracy : </b></td>
	<td><? echo $thisOpenAccuracy; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Close : </b></td>
	<td><? echo $thisClose; ?></td>
<tr height="30">
	<td align="right"><b>Close Accuracy : </b></td>
	<td><? echo $thisCloseAccuracy; ?></td>
</tr>
</tr><tr height="30">
	<td align="right"><b>Co-ordinates : </b></td>
	<td><? echo $thisLong; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Km : </b></td>
	<td><? echo $thisKm; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Km Accuracy : </b></td>
	<td><? echo $thisKmAccuracy; ?></td>
</tr>
</table>
<hr>
<a href="editLocations.php?location=<? echo $thisLocationId; ?>">Go Back!</a>

<?php
include_once("common/footer.php");
?>