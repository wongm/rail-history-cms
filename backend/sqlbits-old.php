<? 
$pageTitle = 'SQL Bits';
include_once("common/header.php"); ?>


REDUNDANT
FOR OLD GALLERY

<h4>Update Image Gallery <-> Location links:</h4>
<p>Give me an ID for a gallery folder,<br> and all of those images in there will be given an extra custom field, <br>being the location ID the photo is of.</p>
<form name="photoUpdateForm" method="GET" action="sqlbits.php">
<table>
	<tr valign="top" height="20">
		<td align="right"> <b> Gallery Parent ID :  </b> </td>
		<td> <input type="text" name="parent" size="20" value="<? echo $thisImage; ?>">  </td> 
	</tr>
	
	<tr valign="top" height="20">
		<td align="right"> <b> Location ID :  </b> </td>
		<td> <input type="text" name="location" size="20" value="<? echo $thisImage; ?>">  </td> 
	</tr>
</table>
<input type="submit" name="submitUpdateLocationsForm" value="Update Locations">
<input type="reset" name="resetForm" value="Clear Form">

</form><br>
<div class="results">	<?
if($_REQUEST['parent'] != '' AND $_REQUEST['location'] != '') 
{
	include_once("../common/galleryFunctions.php");

	$locationId = $_REQUEST['location'];
	$parentId = $_REQUEST['parent'];
	
	$sql = "SELECT * FROM g2_childentity WHERE `g_parentId` = '".$parentId."'";
	$galleryResult = MYSQL_QUERY($sql);
	$numberOfRows = MYSQL_NUM_ROWS($galleryResult);
	
	if ($numberOfRows>0)
	{
		$i=0;
		
		while ($i<$numberOfRows)
		{
			$photoId = MYSQL_RESULT($galleryResult,$i,"g_id");
			
			$sql2 = "INSERT INTO g2_customfieldmap (`g_itemId`, `g_field`, `g_value`, `g_setType`, `g_setId`) VALUES ('".$photoId."', 'location', '".$locationId."', '2', '0' )";
			MYSQL_QUERY($sql2);
			$i++;
			echo $i.' - '.$sql2.' added!<br><br>';
		}
		
	}	
	echo '<p class="error">All Done!</p>';

}	?>
</div>
<br><br>

<h4>Update Image Gallery <-> Location links:</h4>
<p>Give me an ID for a photo and it will be given an extra custom field, <br>being the location ID the photo is of.</p>
<form name="galleryUpdateForm" method="GET" action="sqlbits.php">
<table>
	<tr valign="top" height="20">
		<td align="right"> <b> Gallery Photo ID :  </b> </td>
		<td> <input type="text" name="photo" size="20">  </td> 
	</tr>
	
	<tr valign="top" height="20">
		<td align="right"> <b> Location ID :  </b> </td>
		<td> <input type="text" name="location" size="20">  </td> 
	</tr>
</table>
<input type="submit" name="submitGalleryUpdateForm" value="Update Locations">
<input type="reset" name="resetForm" value="Clear Form">

</form><br>
<div class="results">	<?
if($_REQUEST['photo'] != '' AND $_REQUEST['location'] != '') 
{
	include_once("../common/galleryFunctions.php");

	$locationId = $_REQUEST['location'];
	$photoId = $_REQUEST['photo'];
	
	$sql2 = "INSERT INTO g2_customfieldmap (`g_itemId`, `g_field`, `g_value`, `g_setType`, `g_setId`) VALUES ('".$photoId."', 'location', '".$locationId."', '2', '0' )";
	MYSQL_QUERY($sql2);
	
	echo $sql2.' added!<br><br>';
	echo '<p class="error">All Done!</p>';

}	?>
</div>
<br><br>

<h4>Update the Location <-> Gallery links:</h4>
<p>Updates the 'photos' flag for locations with photos tagged in the Gallery</p>
<a href="sqlbits.php?location-photos=true">Go!</a><br>
<div class="results">
<?

if($_REQUEST['location-photos'] == true)
{
	include_once("../common/galleryFunctions.php");
	
	$sqlLocations = "SELECT DISTINCT g_value FROM g2_customfieldmap";
	$galleryResult = MYSQL_QUERY($sqlLocations);
	$numberOfRows = MYSQL_NUM_ROWS($galleryResult);
	
	include_once("common/dbConnection.php");
	
	if ($numberOfRows>0)
	{
		$i=0;
		
		while ($i<$numberOfRows)
		{
			$sql2 = "UPDATE locations SET `photos` = '1' WHERE `location_id` = '".MYSQL_RESULT($galleryResult,$i,"g_value")."'";
			MYSQL_QUERY($sql2);
			echo $i.' - Location '.MYSQL_RESULT($galleryResult,$i,"g_value").' All Done!<br><br>';
			$i++;
		}
		
	}	
	echo '<p class="error">All Done!</p><br><a href="sqlbits.php">Back</a>';
	
	
}	?>
</div>

REDUNDANT!

<br><br>
<h4>Updates location if it has events:</h4>
<p>Hunts for events for all locations.<br> If there is any, then the location field is flagged.</p>
<a href="sqlbits.php?event-updates=true">Go!</a><br>
<div class="results">
<?



if($_REQUEST['event-updates'] == true)
{
	include_once("common/dbConnection.php");
	$sql = "update locations L set L.events = '1' WHERE EXISTS (select * from location_events E where E.location = L.location_id) ";
	MYSQL_QUERY($sql);	
	echo '<p class="error">All Done!</p><br><a href="sqlbits.php">Back</a>';
}	?>
</div>



REDUNDANT!
<br><br>
<h4>Updates location if it has events - REVERSE!</h4>
<p>Unflags the location field if there isn't any events.</p>
<a href="sqlbits.php?event-updates-reverse=true">Go!</a><br>
<div class="results">
<?

if($_REQUEST['event-updates-reverse'] == true)
{
	include_once("common/dbConnection.php");
	$sql = "update locations L set L.events = '0' WHERE not EXISTS (select * from location_events E where E.location = L.location_id)  ";
	MYSQL_QUERY($sql);	
	echo '<p class="error">All Done!</p><br><a href="sqlbits.php">Back</a>';
}	?>
</div>




<br><br>
<h4>Fix source</h4>
<p>Set source of railline event to 8 where line = 5.</p>
<a href="sqlbits.php?eventsource=true">Go!</a><br>
<div class="results">
<?

if($_REQUEST['eventsource'] == true)
{
	include_once("common/dbConnection.php");
	$sql = "update railline_events set source = '8' WHERE line = '5'";
	MYSQL_QUERY($sql);	
	echo '<p class="error">All Done!</p><br><a href="sqlbits.php">Back</a>';
}	?>
</div>






<br><br>
<h4>Fix credits</h4>
<p>Set credits of locations to NULL where given...</p>
<a href="sqlbits.php?credits=true">Go!</a><br>
<div class="results">
<?

if($_REQUEST['credits'] == true)
{
	include_once("common/dbConnection.php");
	backendDBconnect();
	
	$sql = "SELECT location_id FROM `locations` WHERE `credits` = 'I would like to thank Kathleen and Paul Kenny for giving me permission to use data from their book: <br /><cite>\"Trains, Troops, and Tourists - The South Geelong ~ Drysdale ~ Queenscliff Railway\"</cite>'";
	$result = MYSQL_QUERY($sql);	
	$numberOfRows = MYSQL_NUM_ROWS($result);
	echo $numberOfRows;
	
	for ($i = 0; $i < $numberOfRows; $i++)
	{
		$id = MYSQL_RESULT($result,$i,"location_id");
		$sql = "update `locations` set `credits` = '' WHERE `location_id` = '$id'";
		MYSQL_QUERY($sql);	
		echo $sql;
	}
	echo '<p class="error">All Done!</p><br><a href="sqlbits.php">Back</a>';
}	?>
</div>








<br><br>
<h4>Migrate location - line links</h4>
<p>For when the database schema was altered</p>
<a href="sqlbits.php?locationline=true">Go!</a><br>
<div class="results">
<?

if($_REQUEST['locationline'] == true)
{
	include_once("common/dbConnection.php");
	$sql = "SELECT location_id, km, kmaccuracy, line FROM locations";
	$result = MYSQL_QUERY($sql);	
	$numberOfRows = MYSQL_NUM_ROWS($result);
	
	for ($i = 0; $i < $numberOfRows; $i++)
	{
		$loc = MYSQL_RESULT($result,$i,"location_id");
		$km = MYSQL_RESULT($result,$i,"km");
		$kmac = MYSQL_RESULT($result,$i,"kmaccuracy");
		$line = MYSQL_RESULT($result,$i,"line");
		
		$sql = "INSERT INTO locations_raillines (`line_id`, `location_id`, `km`, `kmaccuracy`) VALUES ('".$line."', '".$loc."', '".$km."', '".$kmac."' )";
		MYSQL_QUERY($sql);
		echo $sql;
	}
	echo '<p class="error">All Done!</p><br><a href="sqlbits.php">Back</a>';
}	?>
</div>


<br><br>






<h4>G2 to zen script</h4>
<p>Transfers image captions between DBs.</p>
<a href="sqlbits.php?zen=true">Go!</a><br>
<div class="results">
<?

if($_REQUEST['zen'] == true)
{
	include_once("../common/galleryFunctions.php"); 
	
	$sqlLocations = "SELECT * FROM g2_item, g2_filesystementity WHERE g2_filesystementity.g_id = g2_item.g_id";
	$galleryResult = MYSQL_QUERY($sqlLocations);
	$numberOfRows = MYSQL_NUM_ROWS($galleryResult);
	
	if ($numberOfRows>0)
	{
		$i=0;
		
		while ($i<$numberOfRows)
		{
			$desc = addslashes(MYSQL_RESULT($galleryResult,$i,"g_title"));
			$file = MYSQL_RESULT($galleryResult,$i,"g_pathComponent");
			$len = strlen($file);
			
			if($file != '')
			{
				$title = stripslashes(substr(strtoupper($file), 0, $len-4));
				$title = split('_',$title);
				$title = $title[0].'_'.$title[1];
				$sqlzen = "UPDATE `zen_images` SET `title` = '$desc' WHERE `title` = '".$title."'";
				echo $sqlzen.'<br><br>';
				MYSQL_QUERY($sqlzen);
			}
			$i++;
		}
		
	}	
	echo '<p class="error">All Done!</p><br><a href="sqlbits.php">Back</a>';
}	?>
</div>
*/
?>




<? include_once("common/footer.php"); 

?>
