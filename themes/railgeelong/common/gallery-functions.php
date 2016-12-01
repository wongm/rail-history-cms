<?php
require_once("definitions.php");
require_once("formatting-functions.php");

// check this location has images to show
function getLocationImages($location)
{
	$locationbits = explode(';', $location);
	$subLocation = sizeof($locationbits);
	
	// for comma seperated individual images
	if ($subLocation > 1)
	{
		$gallerySQL = "SELECT a.folder, i.filename, i.title, i.id 
			FROM " . prefix("images") . " i
			INNER JOIN " . prefix("albums") . " ON i.albumid = a.id 
			WHERE ( i.filename = ".db_quote(getFilename($locationbits[0]))." ";
		for ($i = 1; $i < $subLocation; $i++)
		{
			$gallerySQL .= " OR i.filename = ".db_quote(getFilename($locationbits[$i]))." ";
		}
		$gallerySQL .= " ) ORDER BY i.sort_order";
	}
	else if (strpos($location, '.jpg') > 0)
	{
		$gallerySQL = "SELECT a.folder, i.filename, i.title, i.id 
			FROM " . prefix("images") . "
			INNER JOIN " . prefix("albums") . " ON i.albumid = a.id 
			WHERE i.filename = ".db_quote(getFilename($location))." ";
	}
	// for album in the gallery 
	else
	{
		$gallerySQL = "SELECT a.folder, i.filename, i.title, i.id
			FROM " . prefix("images") . " i
			INNER JOIN " . prefix("albums") . " a ON i.albumid = a.id 
			WHERE a.folder = ".db_quote($location)." ORDER BY i.sort_order";
	}
	
	return query_full_array($gallerySQL);
}

function getFilename($fullpath)
{
	$fullpathbits = explode('/', $fullpath);
	$bitcount = sizeof($fullpathbits);
	
    return $fullpathbits[$bitcount - 1];
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

