<?php

$thisName = addslashes($_REQUEST['thisNameField']);
$pageTitle = "Updated Location - ".stripslashes(stripslashes($thisName));

include_once("common/dbConnection.php");

// Retreiving Form Elements from Form
$thisLocationId = addslashes($_REQUEST['thisLocation_idField']);
$thisName = addslashes($_REQUEST['thisNameField']);
$thisLink = strtolower(addslashes($_REQUEST['thisLinkField']));
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
$sql = "UPDATE locations SET `name` = '$thisName' , `link` = '$thisLink' , `tracks` = '$thisTracks' , `display` = '$thisDisplay' , `type` = '$thisType' ";

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

Header("Location: ".$_SERVER['HTTP_REFERER']."#general");

include_once("common/footer.php");
?>