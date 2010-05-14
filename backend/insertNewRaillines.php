<?php
include_once("common/dbConnection.php");
include_once("common/header.php");
?>
<?php
	// Retreiving Form Elements from Form
	$thisName = addslashes($_REQUEST['thisNameField']);
	$thisLink = addslashes($_REQUEST['thisLinkField']);
	$thisStartlocation = addslashes($_REQUEST['thisStartlocationField']);
	$thisEndlocation = addslashes($_REQUEST['thisEndlocationField']);
	$thisOpened = addslashes($_REQUEST['thisOpenedField']);
	$thisClosed = addslashes($_REQUEST['thisClosedField']);
	$thisKmstart = addslashes($_REQUEST['thisKmstartField']);
	$thisKmend = addslashes($_REQUEST['thisKmendField']);
	$thisDescription = addslashes($_REQUEST['thisDescriptionField']);
	$thisCredits = addslashes($_REQUEST['thisCreditsField']);
	$thisTrackyears = addslashes($_REQUEST['thisTrackyearsField']);
	$thisTrackdefault = addslashes($_REQUEST['thisTrackdefaultField']);
	$thisSafeworkingyears = addslashes($_REQUEST['thisSafeworkingyearsField']);
	$thisSafeworkingdefault = addslashes($_REQUEST['thisSafeworkingdefaultField']);
	$thisTodisplay = addslashes($_REQUEST['thisTodisplayField']);

	if ($thisOpen == '')
	{
		$thisOpen = '0001-01-01';
	}
	
	if ($thisClose == '')
	{
		$thisClose = '9999-01-01';
	}
		
	if ($thisName == '' OR $thisLink == '')
	{
		insertfail();
	}
	else
	{
		$sqlQuery = "INSERT INTO raillines (name , link , startlocation , endlocation , opened , closed , kmstart , kmend , description , credits , trackyears , trackdefault , safeworkingyears , safeworkingdefault , todisplay ) VALUES ('$thisName' , '$thisLink' , '$thisStartlocation' , '$thisEndlocation' , '$thisOpened' , '$thisClosed' , '$thisKmstart' , '$thisKmend' , '$thisDescription' , '$thisCredits' , '$thisTrackyears' , '$thisTrackdefault' , '$thisSafeworkingyears' , '$thisSafeworkingdefault' , '$thisTodisplay' )";
		$result = MYSQL_QUERY($sqlQuery);
		
		if ($result != 0)
		{
			failed();
		}	?>
A new record has been inserted in the database. Here is the information that has been inserted :- <br><br>

<table>
<tr height="30">
	<td align="right"><b>Line_id : </b></td>
	<td><? echo $thisLine_id; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Name : </b></td>
	<td><? echo $thisName; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Link : </b></td>
	<td><? echo $thisLink; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Startlocation : </b></td>
	<td><? echo $thisStartlocation; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Endlocation : </b></td>
	<td><? echo $thisEndlocation; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Opened : </b></td>
	<td><? echo $thisOpened; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Closed : </b></td>
	<td><? echo $thisClosed; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Kmstart : </b></td>
	<td><? echo $thisKmstart; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Kmend : </b></td>
	<td><? echo $thisKmend; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Description : </b></td>
	<td><? echo $thisDescription; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Credits : </b></td>
	<td><? echo $thisCredits; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Trackyears : </b></td>
	<td><? echo $thisTrackyears; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Trackdefault : </b></td>
	<td><? echo $thisTrackdefault; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Safeworkingyears : </b></td>
	<td><? echo $thisSafeworkingyears; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Safeworkingdefault : </b></td>
	<td><? echo $thisSafeworkingdefault; ?></td>
</tr>
<tr height="30">
	<td align="right"><b>Todisplay : </b></td>
	<td><? echo $thisTodisplay; ?></td>
</tr>
</table>

<?php
}	// end null values if
include_once("common/footer.php");
?>