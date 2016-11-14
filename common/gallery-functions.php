<?php
include_once(dirname(__FILE__) . "/dbConnection.php");
include_once(dirname(__FILE__) . "/formatting-functions.php");
include_once(dirname(__FILE__) . "/../gallery/themes/railgeelong/functions-search.php");
include_once(dirname(__FILE__) . "/../gallery/themes/railgeelong/functions.php");

// check this location has images to show
function getLocationImages($location)
{
	$locationbits = split(';', $location);
	$subLocation = sizeof($locationbits);
	
	// for comma seperated individual images
	if ($subLocation > 1)
	{
		$gallerySQL = "SELECT zen_albums.folder, zen_images.filename, zen_images.title, zen_images.id 
			FROM zen_images
			INNER JOIN zen_albums ON zen_images.albumid = zen_albums.id 
			WHERE ( zen_images.filename = '".mysql_real_escape_string(getFilename($locationbits[0]))."' ";
		for ($i = 1; $i < $subLocation; $i++)
		{
			$gallerySQL .= " OR zen_images.filename = '".mysql_real_escape_string(getFilename($locationbits[$i]))."' ";
		}
		$gallerySQL .= " ) ORDER BY zen_images.sort_order";
	}
	else if (strpos($location, '.jpg') > 0)
	{
		$gallerySQL = "SELECT zen_albums.folder, zen_images.filename, zen_images.title, zen_images.id 
			FROM zen_images
			INNER JOIN zen_albums ON zen_images.albumid = zen_albums.id 
			WHERE zen_images.filename = '".mysql_real_escape_string(getFilename($location))."' ";
	}
	// for album in the gallery 
	else
	{
		$gallerySQL = "SELECT zen_albums.folder, zen_images.filename, zen_images.title, zen_images.id
			FROM zen_images
			INNER JOIN zen_albums ON zen_images.albumid = zen_albums.id 
			WHERE folder = '".mysql_real_escape_string($location)."' ORDER BY zen_images.sort_order";
	}
	
	$galleryResult = MYSQL_QUERY($gallerySQL, galleryDBconnect());
	
	$photoArray = array();
	for ($i = 0; $i < MYSQL_NUM_ROWS($galleryResult); $i++)
	{
		$photoArray[] = mysql_fetch_assoc($galleryResult);
	}
	
	return $photoArray;
}

function getFilename($fullpath)
{
	$fullpathbits = split('/', $fullpath);
	$bitcount = sizeof($fullpathbits);
	
    return $fullpathbits[$bitcount - 1];
}

function printFrontpageRecent()
{
	$sql = "SELECT i.filename, i.id, zen_albums.folder, zen_albums.title, zen_albums.id, zen_albums.date, i.date as fdate 
				FROM zen_images i
				INNER JOIN zen_albums ON i.albumid = zen_albums.id 
				LEFT JOIN
				(
				    select max(id) id1 from zen_images insideimage
				    where albumid <> 0
				    group by albumid
				    order by max(id) desc
				    limit ".FRONT_PAGE_MAX_IMAGES."
				) t ON id1 = i.id
				WHERE id1 is not null or albumid = 0 
				ORDER BY i.id DESC
				LIMIT 0,".FRONT_PAGE_MAX_IMAGES;
	$galleryResult = MYSQL_QUERY($sql, galleryDBconnect());	
	
	drawAlbums($galleryResult);
}

/*
 * Gets the images for a specified location
 * $locations = path to album, or CSV of image ids
 */
function drawLocationImages($locationPhotos, $path='')
{
	$displayRows = $originalRows = sizeof($locationPhotos);
	$path = $locationPhotos[0]['folder'];
	
	if ($originalRows > 0) 
	{	
?>
<h4 id="photos" name="photos">Photos</h4><hr />
<?php
	if ($originalRows > 9)
	{
		$moreString = 'Nine of <a href="/gallery/'.$path.'">'.$originalRows.' images found</a> displayed.';
		$displayRows = 9;
	}
	else		
	{
		$moreString = '<a href="/gallery/'.$path.'">'.$displayRows.' images found</a>.';
	}
?>
<p><?php echo $moreString?> Click them to enlarge.</p>
<table class="centeredTable">
<?php
	$i=0;
	$j=0;
	
	if ($numberOfRows == '4')
	{
		$j=1;
	}
	
	if ($originalRows > 9)
	{
		$k=rand(0, ($originalRows/9));
	}
	else
	{
		$k = 0;
	}
	
	while ($i<$displayRows)
	{
		echo "<tr>\n";

		while ($j < 3 AND $i<$displayRows )
		{
			// check index is within limits...
			if ($k >= $originalRows)
			{
				$k = $originalRows-1;
			}
			
			$photoPath = $locationPhotos[$k]['folder'];
			$photoUrl = $locationPhotos[$k]['filename'];
			$photoTitle = $locationPhotos[$k]['title'];
			$photoId = $locationPhotos[$k]['id'];
			// for when URL rewrite is on
			/* <td><a href="/gallery/<?php echo $photoPath; ?>/<?php echo $photoUrl; ?>.html" target="new" ><img src="/gallery/cache/<?php echo $photoPath; ?>/<?php echo $photoUrl; ?>_<?php echo thumbsize; ?>.jpg" alt="<?php echo $photoTitle; ?>" title="<?php echo $photoTitle; ?>" /></a>*/
			// non rewrite
			/* <a href="/gallery/index.php?album=<?php echo $photoPath; ?>&amp;image=<?php echo $photoUrl; ?>&size=" target="new" ><img src="/gallery/cache/<?php echo $photoPath; ?>/<?php echo $photoUrl; ?>_<?php echo thumbsize; ?>.jpg" alt="<?php echo $photoTitle; ?>" title="<?php echo $photoTitle; ?>" /></a> */
			
			// old version
			/*<td class="i"><a href="/gallery/<?php echo $photoPath; ?>/<?php echo $photoUrl; ?>.html?size=" target="new" ><img src="/gallery/cache/<?php echo $photoPath; ?>/<?php echo $photoUrl; ?>_150_cw150_ch150.jpg" alt="<?php echo $photoTitle; ?>" title="<?php echo $photoTitle; ?>" />*/
			
			$thumbUrl = str_ireplace('.jpg', '_' . THUMBNAIL_IMAGE_SIZE . '_thumb.jpg', $photoUrl);
			$normalImageUrl = str_ireplace('.jpg', '_' . NORMAL_IMAGE_SIZE . '.jpg', $photoUrl);
?>
<td class="i">
	<a href="/gallery/cache/<?php echo $photoPath; ?>/<?php echo $normalImageUrl; ?>" rel="lightbox" title="<?php echo $photoTitle; ?>"><img src="/gallery/cache/<?php echo $photoPath; ?>/<?php echo $thumbUrl; ?>" alt="<?php echo $photoTitle; ?>" title="<?php echo $photoTitle; ?>" /></a>
	<p><?php echo $photoTitle ?></p></td>
<?php 	$j++;
			$i++;
			$k = $k+(rand(1, ($original/7)));
	
		}	//end while for cols
		$j=0;
?>
</tr>
<?php
		}	//end while for rows
		?>
</table>
<p><a href="#top" class="credit">Top</a></p>
<?php }		// end if
	return;	
	
}	//end function

