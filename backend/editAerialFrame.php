<? 	include_once("common/dbConnection.php");
	$thisId = addslashes($_REQUEST['locationField']);
	$thisPoint = addslashes($_REQUEST['pointField']); 
	$force = addslashes($_REQUEST['force']); 
	 ?>
<html>
<head>
<title>Location Formzor</title>
</head>
<body>	<?
// check for non empty name and point, and not already set
if ($thisId != '' AND $thisPoint != '')
{
	$thisResult = MYSQL_QUERY("SELECT * FROM locations WHERE `location_id` = '".$thisId."'");
	$thisName = stripslashes(MYSQL_RESULT($thisResult,'0',"name"));
	$thisSet = stripslashes(MYSQL_RESULT($thisResult,'0',"long"));
	if ($thisSet != '0' AND $thisSet != '' AND $force == '')
	{
		echo '<p></p>'; ?>
<form name="pointUpdateForm" method="POST" action="editAerialFrame.php">
<label for="submitPointUpdateForm">Overwrite <? echo $thisName; ?> (<? echo $thisId; ?>) </label>
<input type="submit" name="submitPointUpdateForm" value="Overwrite?" />
<input type="hidden" name="pointField" id="pointField" value="<? echo $thisPoint; ?>"/>
<input type="hidden" name="locationField" id="locationField" value="<? echo $thisId; ?>"/>
<input type="hidden" name="force" id="force" value="true"/>
</form>	<?
	}
	else
	{
		$sql = "UPDATE locations SET `long` = '$thisPoint' WHERE location_id = '$thisId'";
		MYSQL_QUERY($sql);
		drawForm('Updated!  ');
	}
}
else
{
	drawForm('');
}	?>
</body>
</html>

<?php

function drawForm($message)
{	
	$locationtoset = addslashes($_REQUEST['locationtoset']);
?>
<form name="pointUpdateForm" method="POST" action="editAerialFrame.php">	
<?
if ($message != '')	
{	
?>
<b><font color="red"><? echo $message; ?></font></b>	<? } ?>
<b> Point :  </b>
<input type="text" name="pointField" id="pointField" size="30"/>

<b> Location :  </b>
<select name="locationField">
<? drawLocationNameSelectFields($locationtoset, true); ?>		
</select>
<input type="submit" name="submitPointUpdateForm" value="GO" />
</form>
<? 
}	// end functions
?>