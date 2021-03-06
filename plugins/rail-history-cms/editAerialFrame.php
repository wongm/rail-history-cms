<?php
include_once("common/dbConnection.php");

$thisId = isset($_REQUEST["locationField"]) ? $_REQUEST["locationField"] : "";
$thisPoint = isset($_REQUEST["pointField"]) ? $_REQUEST["pointField"] : "";
$force = isset($_REQUEST["force"]) ? $_REQUEST["force"] : "";
?>
<html>
<head>
<title>Location Formzor</title>
</head>
<body>	<?php
// check for non empty name and point, and not already set
if ($thisId != '' AND $thisPoint != '')
{
	$result = query_full_array("SELECT * FROM locations WHERE `location_id` = '".$thisId."'");
	$thisName = stripslashes($result[0]["name"]);
	$thisSet = stripslashes($result[0]["long"]);
	if ($thisSet != '0' AND $thisSet != '' AND $force == '')
	{
		echo '<p></p>'; ?>
<form name="pointUpdateForm" method="POST" action="editAerialFrame.php">
<label for="submitPointUpdateForm">Overwrite <?php echo $thisName; ?> (<?php echo $thisId; ?>) </label>
<input type="submit" name="submitPointUpdateForm" value="Overwrite?" />
<input type="hidden" name="pointField" id="pointField" value="<?php echo $thisPoint; ?>"/>
<input type="hidden" name="locationField" id="locationField" value="<?php echo $thisId; ?>"/>
<input type="hidden" name="force" id="force" value="true"/>
</form>	<?php
	}
	else
	{
		$sql = "UPDATE locations SET `long` = '$thisPoint' WHERE location_id = '$thisId'";
		query_full_array($sql);
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
	$locationtoset = isset($_REQUEST["locationtoset"]) ? $_REQUEST["locationtoset"] : "";
?>
<form name="pointUpdateForm" method="POST" action="editAerialFrame.php">	
<?php
if ($message != '')	
{	
?>
<b><font color="red"><?php echo $message; ?></font></b>	<?php } ?>
<b> Point :  </b>
<input type="text" name="pointField" id="pointField" size="30"/>

<b> Location :  </b>
<select name="locationField">
<?php drawLocationNameSelectFields($locationtoset, true); ?>
</select>
<input type="submit" name="submitPointUpdateForm" value="GO" />
</form>
<?php 
}	// end functions
?>