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
	MYSQL_QUERY($sql);	
	
	$sql = "update railline_events E set 
		E.safeworking_km = 
			(select lr.km from locations L, locations_raillines lr 
				WHERE L.location_id = E.safeworking_middle
				AND lr.location_id = L.location_id)";
	MYSQL_QUERY($sql);
	
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
	MYSQL_QUERY($sql);	
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
	include_once("../common/gallery-functions.php"); 
	
	$sqlLocations = "SELECT * FROM zen_images, zen_albums WHERE zen_images.albumid = zen_albums.id";
	$galleryResult = MYSQL_QUERY($sqlLocations);
	$numberOfRows = MYSQL_NUM_ROWS($galleryResult);
	
	if ($numberOfRows>0)
	{
		$i=0;
		
		while ($i<$numberOfRows)
		{
			$filename = addslashes(MYSQL_RESULT($galleryResult,$i,"filename"));
			$directory = MYSQL_RESULT($galleryResult,$i,"folder");
			$title = MYSQL_RESULT($galleryResult,$i,"title");
			$id = MYSQL_RESULT($galleryResult,$i,"zen_images.id");
			$path = '../gallery/albums/'.$directory.'/'.$filename;
			
			if (!file_exists($path))
			{
				echo 'Image not found so deleted: '.$path;
				
				//re-caption moved image
				$sqlzen = "UPDATE `zen_images` SET `title` = '$title' WHERE `filename` = '".$filename."'";
				MYSQL_QUERY($sqlzen);
				//echo $sqlzen;
				// delete
				$sqlzen = "DELETE FROM zen_images WHERE zen_images.id = '".$id."'";
				MYSQL_QUERY($sqlzen);
				echo '<br><br>';
			}
			$i++;
		}
		echo '<p>Images deleted!</p>';
		
	}	
	
	// check for albums
	$sqlLocations = "SELECT * FROM zen_albums";
	$galleryResult = MYSQL_QUERY($sqlLocations);
	$numberOfRows = MYSQL_NUM_ROWS($galleryResult);
	
	if ($numberOfRows>0)
	{
		$i=0;
		
		while ($i<$numberOfRows)
		{
			$directory = MYSQL_RESULT($galleryResult,$i,"folder");
			$title = MYSQL_RESULT($galleryResult,$i,"title");
			$id = MYSQL_RESULT($galleryResult,$i,"id");
			$path = '../gallery/albums/'.$directory;
			
			if (!file_exists($path))
			{
				echo 'Folder not found so deleted: '.$path;
				
				//re-caption moved album
				$sqlzen = "UPDATE `zen_albums` SET `title` = '$title' WHERE `directory` = '".$filename."'";
				MYSQL_QUERY($sqlzen);
				// delete
				$sqlzen = "DELETE FROM zen_albums WHERE id = '".$id."'";
				MYSQL_QUERY($sqlzen);
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
	$galleryResult = MYSQL_QUERY($sqlLocations);
	$numberOfRows = MYSQL_NUM_ROWS($galleryResult);
	
	if ($numberOfRows>0)
	{
		$i=0;
		
		while ($i<$numberOfRows)
		{
			$desc = addslashes(MYSQL_RESULT($galleryResult,$i,"desc"));
			$title = addslashes(MYSQL_RESULT($galleryResult,$i,"title"));
			$id = MYSQL_RESULT($galleryResult,$i,"filename");
			
			echo "UPDATE `zen_images` SET title = '$title', desc = '$desc' WHERE title = '$id';<br>";
			$i++;
		}
		
	}	
	echo '<p class="error">All Done!</p><br><a href="sqlbits.php">Back</a>';
}	?>
</div>




<h4>Zenphoto location / lineguide mapping importer</h4>
<p>Transfers location / lineguide photo album fields into the Zenphoto database from Rail History CMS data.</p>
<a href="sqlbits.php?zenlocation=true">Go!</a><br>
<div class="results">
<?php

if($_REQUEST['zenlocation'] == true)
{		
	$sqlLocations = "SELECT * FROM `locations` WHERE photos != '' AND photos != '0'";
	$locationResult = query_full_array($sqlLocations);
	$numberOfRows = sizeof($locationResult);
	
	if ($numberOfRows>0)
	{
		echo "<p>$numberOfRows locations updated!</p>";
		for ($i=0; $i<$numberOfRows; $i++)
		{
			$id = addslashes($locationResult[$i][ "location_id"]);
			$name = addslashes($locationResult[$i]["name"]);
			$folder = addslashes($locationResult[$i]["photos"]);
			$sql = "UPDATE " . prefix("albums") . " SET `location_id` = '$id', `location_name` = '$name' WHERE `folder` = '$folder'";
			query($sql);
		}
	}	
	
	$sqllineguide = "SELECT * FROM `raillines` WHERE photos != '' AND photos != '0'";
	$lineguideResult = query_full_array($sqllineguide);
	$numberOfRows = sizeof($lineguideResult);
	
	if ($numberOfRows>0)
	{
		echo "<p>$numberOfRows lines updated!</p>";
		for ($i=0; $i<$numberOfRows; $i++)
		{
			$name = addslashes($lineguideResult[$i][ "name"]);
			$link = addslashes($lineguideResult[$i]["link"]);
			$folder = addslashes($lineguideResult[$i]["photos"]);
			$sql = "UPDATE " . prefix("albums") . " SET `line_link` = '$link', `line_name` = '$name' WHERE `folder` = '$folder'";
			query($sql);
		}
	}
	echo '<p class="error">All Done!</p><br><a href="sqlbits.php">Back</a>';
}	?>
</div>





<h4>Find possible CMS to Zenphoto links</h4>
<p>Creates SQL for zenphoto to update location / lineguide photo album fields if something matching is in the Zenphoto database. 
You will then need to run the Zenphoto location / lineguide mapping importer to get it back into the Zenphoto database.</p>
<a href="sqlbits.php?cmstozenphoto=true">Go!</a><br>
<div class="results">
<?php

if($_REQUEST['cmstozenphoto'] == true)
{	
	$sqlLocations = "SELECT * FROM `locations` WHERE photos = '' OR photos = '0'";
	$locationResult = query_full_array($sqlLocations);	
	$numberOfRows = sizeof($locationResult);
	
	if ($numberOfRows>0)
	{
		for ($i=0; $i<$numberOfRows; $i++)
		{
			$id = addslashes($locationResult[$i][ "location_id"]);
			$name = addslashes($locationResult[$i]["name"]);
			$folder = convertToLink($locationResult[$i]["name"]);
			$folder = eregi_replace("\\\'", '', $folder);
			$folder = eregi_replace('\'', '', $folder);
			$folder = eregi_replace('/', '', $folder);
			
			if ($name != '')
			{
				//search zenphoto DB for albums thart might match the location
				$sql = "SELECT * FROM " . prefix("albums") . " WHERE `folder` LIKE '%$folder' OR title = '$name'";
				$albumsResult = query_full_array($sql);
				
				if (sizeof($albumsResult) == 1)
				{
					$fullFolder = addslashes($albumsResult[0]["folder"]);
					echo "UPDATE locations SET photos = '$fullFolder' WHERE location_id = $id;<br>";
				}
			}
		}
	}
	
	$sqlLineguide = "SELECT * FROM `raillines` WHERE photos = '' OR photos = '0'";
	$lineguideResult = query_full_array($sqlLineguide);
	$numberOfRows = sizeof($lineguideResult);
	
	if ($numberOfRows>0)
	{
		echo '<br>';
		for ($i=0; $i<$numberOfRows; $i++)
		{
			$id = addslashes($lineguideResult[$i][ "line_id"]);
			$name = addslashes($lineguideResult[$i]["name"]);
			$folder = convertToLink($lineguideResult[$i]["name"]);
			$folder = eregi_replace("\\\'", '', $folder);
			$folder = eregi_replace('\'', '', $folder);
			$folder = eregi_replace('---', '-', $folder);
			$folder = eregi_replace('/', '', $folder);
			
			if ($name != '')
			{
				//search zenphoto DB for albums that might match the location
				$sql = "SELECT * FROM " . prefix("albums") . " WHERE `folder` LIKE '$folder%' OR title = '$name'";
				$albumsResult = query_full_array($sql);
				
				if (sizeof($albumsResult) == 1)
				{
					$fullFolder = addslashes($albumsResult[0]["folder"]);
					echo "UPDATE raillines SET photos = '$fullFolder' WHERE line_id = $id;<br>";
				}
			}
		}
	}
	
	
	echo '<p class="error">All Done!</p><br><a href="sqlbits.php">Back</a>';
}	?>
</div>




<h4>Add required DB columns</h4>
<p>Add location / lineguide columns to the Zenphoto DB.</p>
<a href="sqlbits.php?dbcolumns=true">Go!</a><br>
<div class="results">
<?php

if($_REQUEST['dbcolumns'] == true)
{	
	query("ALTER TABLE " . prefix("albums") . " ADD location_id int");
	query("ALTER TABLE " . prefix("albums") . " ADD location_name varchar(255)");
	query("ALTER TABLE " . prefix("albums") . " ADD line_link varchar(255)");
	query("ALTER TABLE " . prefix("albums") . " ADD line_name varchar(255)");
	
	echo '<p class="error">All Done!</p><br><a href="sqlbits.php">Back</a>';
}	?>
</div>



<?php include_once("common/footer.php"); 

?>


