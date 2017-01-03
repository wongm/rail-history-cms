<?php 
$pageTitle = 'SQL Bits';
include_once("common/dbConnection.php");
include_once("common/header.php"); ?>

<h4>Update the line kms for safeworking events:</h4>
<p>Fixes up the distances for each start / end location for safeworking or duplication events. <br>You need to do this after adding new events, or the lineguide won't notice them.</p>
<a href="sqlbits.php?line-safeworking=true">Go!</a><br>
<div class="results">
<?php



if($_REQUEST['line-safeworking'] == true)
{
	
	$sql = "update railline_events E set 
		E.start_distance = (select lr.km from locations L, locations_raillines lr  where L.location_id = E.start_location 
			AND lr.location_id = l.location_id AND lr.line_id = E.line ),
	 	E.end_distance = (select lr.km from locations M locations_raillines lr   where M.location_id = E.end_location 
	 		AND lr.location_id = M.location_id AND lr.line_id = E.line )";
	query_full_array($sql);	
	
	$sql = "update railline_events E set 
		E.safeworking_km = 
			(select lr.km from locations L, locations_raillines lr 
				WHERE L.location_id = E.safeworking_middle
				AND lr.location_id = L.location_id)";
	query_full_array($sql);
	
	echo '<p class="error">All Done!</p><br><a href="sqlbits.php">Back</a>';
}	?>
</div>





<h4>Fix photo field</h4>
<p>Set photo flag against Locations to 0 if blank.</p>
<a href="sqlbits.php?fixphotoflag=true">Go!</a><br>
<div class="results">
<?php

if($_REQUEST['fixphotoflag'] == true)
{
	$sql = "update locations set photos = '0' WHERE photos = ''";
	query_full_array($sql);	
	echo '<p class="error">All Done!</p><br><a href="sqlbits.php">Back</a>';
}	?>
</div>




<h4>Zenphoto clean script</h4>
<p>Cleanup zenphoto - checks for orphaned images that were deleted as files, and deletes from DB. Prevents all kind of weird 'next image not found' errors when clicking though albums. Also move captions when you move images between folders.</p>
<a href="sqlbits.php?zenclean=true">Go!</a><br>
<div class="results">
<?php

if($_REQUEST['zenclean'] == true)
{
	include_once("../common/linked-photo-functions.php"); 
	
	$sqlLocations = "SELECT * FROM zen_images, zen_albums WHERE zen_images.albumid = zen_albums.id";
	$galleryResult = query_full_array($sqlLocations);
	$numberOfRows = sizeof($galleryResult);
	
	if ($numberOfRows>0)
	{
		$i=0;
		
		while ($i<$numberOfRows)
		{
			$filename = addslashes($galleryResult[$i]["filename"]);
			$directory = $galleryResult[$i]["folder"];
			$title = $galleryResult[$i]["title"];
			$id = $galleryResult[$i]["zen_images.id"];
			$path = '../gallery/albums/'.$directory.'/'.$filename;
			
			if (!file_exists($path))
			{
				echo 'Image not found so deleted: '.$path;
				
				//re-caption moved image
				$sqlzen = "UPDATE `zen_images` SET `title` = '$title' WHERE `filename` = '".$filename."'";
				query_full_array($sqlzen);
				//echo $sqlzen;
				// delete
				$sqlzen = "DELETE FROM zen_images WHERE zen_images.id = '".$id."'";
				query_full_array($sqlzen);
				echo '<br><br>';
			}
			$i++;
		}
		echo '<p>Images deleted!</p>';
		
	}	
	
	// check for albums
	$sqlLocations = "SELECT * FROM zen_albums";
	$galleryResult = query_full_array($sqlLocations);
	$numberOfRows = sizeof($galleryResult);
	
	if ($numberOfRows>0)
	{
		$i=0;
		
		while ($i<$numberOfRows)
		{
			$directory = $galleryResult[$i]["folder"];
			$title = $galleryResult[$i]["title"];
			$id = $galleryResult[$i]["id"];
			$path = '../gallery/albums/'.$directory;
			
			if (!file_exists($path))
			{
				echo 'Folder not found so deleted: '.$path;
				
				//re-caption moved album
				$sqlzen = "UPDATE `zen_albums` SET `title` = '$title' WHERE `directory` = '".$filename."'";
				query_full_array($sqlzen);
				// delete
				$sqlzen = "DELETE FROM zen_albums WHERE id = '".$id."'";
				query_full_array($sqlzen);
				echo '<br><br>';
			}
			$i++;
		}
		echo '<p>Albums deleted!</p>';
		
	}	
	
	echo '<p class="error">All Done!</p><br><a href="sqlbits.php">Back</a>';
}	?>
</div>




<h4>Zenphoto image importer</h4>
<p>Transfers image captions from backup to production. 
It looks for images with default titles (from filename) and sets the caption based on the already existing image in database.</p>
<a href="sqlbits.php?zencaptionsbackup=true">Go!</a><br>
<div class="results">
<?php

if($_REQUEST['zencaptionsbackup'] == true)
{
	$sqlLocations = "SELECT * FROM `zen_images` WHERE id != ''";
	$galleryResult = query_full_array($sqlLocations);
	$numberOfRows = sizeof($galleryResult);
	
	if ($numberOfRows>0)
	{
		$i=0;
		
		while ($i<$numberOfRows)
		{
			$desc = addslashes($galleryResult[$i]["desc"]);
			$title = addslashes($galleryResult[$i]["title"]);
			$id = $galleryResult[$i]["filename"];
			
			echo "UPDATE `zen_images` SET title = '$title', desc = '$desc' WHERE title = '$id';<br>";
			$i++;
		}
		
	}	
	echo '<p class="error">All Done!</p><br><a href="sqlbits.php">Back</a>';
}	?>
</div>



<?php include_once("common/footer.php"); 

?>


