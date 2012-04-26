<?php

// Retreiving Form Elements from Form
$thisLine_id = addslashes($_REQUEST['thisLine_idField']);
$thisName = addslashes($_REQUEST['thisNameField']);
$thisLink = addslashes($_REQUEST['thisLinkField']);
$thisOrder = addslashes($_REQUEST['thisOrderField']);
$thisPhotos = addslashes($_REQUEST['thisPhotosField']);
$thisOpened = addslashes($_REQUEST['thisOpenedField']);
$thisClosed = addslashes($_REQUEST['thisClosedField']);

$pageTitle = "Updated line - $thisName";
include_once("common/dbConnection.php");
	
$thisDescription = addslashes($_REQUEST['thisDescriptionField']);
$thisCredits = addslashes($_REQUEST['thisCreditsField']);
$thisTrackyears = addslashes($_REQUEST['thisTrackyearsField']);
$thisTrackdefault = addslashes($_REQUEST['thisTrackdefaultField']);
$thisTracksubpage = addslashes($_REQUEST['thisTracksubpageField']);
$thisSafeworkingyears = addslashes($_REQUEST['thisSafeworkingyearsField']);
$thisSafeworkingdefault = addslashes($_REQUEST['thisSafeworkingdefaultField']);
$thisImageCaption = addslashes($_REQUEST['thisImageCaptionField']);
$thisTrackDiagramNote = addslashes($_REQUEST['thisTrackDiagramNoteField']);
$thisSafeworkingDiagramNote = addslashes($_REQUEST['thisSafeworkingDiagramNoteField']);
$thisDiagramTabs = addslashes($_REQUEST['thisDiagramTabsField']);

// for tab display
$thisTodisplay = 10000;

$showtrack = addslashes($_REQUEST['showTrack']);
$showsafeworking = addslashes($_REQUEST['showSafeworking']);
$showevents = addslashes($_REQUEST['showEvents']);
$showlocations = addslashes($_REQUEST['showLocations']);
$hideall = addslashes($_REQUEST['hideAll']);
	
if ($showtrack)
{
	$thisTodisplay += 1;
}
if ($showsafeworking)
{
	$thisTodisplay += 10;
}
if ($showevents)
{
	$thisTodisplay += 100;
}
if ($showlocations)
{
	$thisTodisplay += 1000;
}
if ($hideall)
{
	$thisTodisplay = 'hide';
}

$sql = "UPDATE raillines SET name = '$thisName' , link = '$thisLink' , 
	startlocation = '$thisStartlocation' , endlocation = '$thisEndlocation' , 
	opened = '$thisOpened' , closed = '$thisClosed' , `order` = '$thisOrder' , 
	kmstart = '$thisKmstart' , kmend = '$thisKmend' , 
	description = '$thisDescription' , credits = '$thisCredits' , 
	trackyears = '$thisTrackyears' , trackdefault = '$thisTrackdefault' , tracksubpage = '$thisTracksubpage' , 
	safeworkingyears = '$thisSafeworkingyears' , safeworkingdefault = '$thisSafeworkingdefault' , 
	todisplay = '$thisTodisplay' , imagecaption = '$thisImageCaption' , 
	safeworkingdiagramnote = '$thisSafeworkingDiagramNote', trackdiagramnote = '$thisTrackDiagramNote', 
	trackdiagramtabs = '$thisDiagramTabs', photos = '$thisPhotos' ";
	
// for auto modification of last modified 
if ($_REQUEST['flag'] == 'on')
{
	$thisModified = date('Y-m-d H:i:s');
	$sql = $sql." , modified = '$thisModified'";
	$done .= '<p>Last updated line updated!</p>';
}

$sql .= "  WHERE line_id = '$thisLine_id' ";
$result = MYSQL_QUERY($sql);
$done .= '<p>Line data updated!</p>';

/*
 * --------------------------------------------------
 * for the galley database table
 * --------------------------------------------------
 */
if ($thisPhotos != '')
{
	galleryDBConnect();
	
	if (sizeof(split(';', $thisPhotos)) == 1)
	{
		$gallerysql = "UPDATE `zen_albums` 
			SET `line_link` = '$thisLink' , `line_name` = '$thisName' 
			WHERE `folder` = '$thisPhotos'";
		$galleryresult = MYSQL_QUERY($gallerysql);///echo $gallerysql;
		$done .= '<p>Gallery location links updated!</p>';
	}
	backendDBConnect();
}

if ($result != 0)
{
	failed();
}

Header("Location: ".$_SERVER['HTTP_REFERER'] . "#general");
?>