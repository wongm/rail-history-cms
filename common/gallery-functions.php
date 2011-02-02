<?php
include_once(dirname(__FILE__) . "/dbConnection.php");
include_once(dirname(__FILE__) . "/formatting-functions.php");
include_once(dirname(__FILE__) . "/../gallery/themes/railgeelong/functions-search.php");
include_once(dirname(__FILE__) . "/../gallery/themes/railgeelong/functions-railgeelong.php");

// check this location has images to show
function getLocationImages($location)
{
	$location = split(';', $location);
	$subLocation = sizeof($location);
	
	// for comma seperated individual images
	if ($subLocation > 1)
	{
		$gallerySQL = "SELECT zen_albums.folder, zen_images.filename, zen_images.title, zen_images.id 
			FROM zen_images
			INNER JOIN zen_albums ON zen_images.albumid = zen_albums.id 
			WHERE ( zen_images.filename = '".mysql_real_escape_string($location[0])."' ";
		for ($i = 1; $i < $subLocation; $i++)
		{
			$gallerySQL .= " OR zen_images.filename = '".$location[$i]."' ";
		}
		$gallerySQL .= " ) ORDER BY zen_images.sort_order";
	}
	// for album in the gallery 
	else
	{
		$gallerySQL = "SELECT zen_albums.folder, zen_images.filename, zen_images.title, zen_images.id
			FROM zen_images
			INNER JOIN zen_albums ON zen_images.albumid = zen_albums.id 
			WHERE folder = '".mysql_real_escape_string($location[0])."' ORDER BY zen_images.sort_order";
	}
	$galleryResult = MYSQL_QUERY($gallerySQL, galleryDBconnect());
	
	for ($i = 0; $i < MYSQL_NUM_ROWS($galleryResult); $i++)
	{
		$photoArray[] = mysql_fetch_assoc($galleryResult);
	}
	
	return $photoArray;
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
<?
	if ($originalRows > MAXIMAGES_LOCATIONPAGE) 
	{
		$moreString = MAXIMAGES_LOCATIONPAGE.' of <a href="/gallery/'.$path.'">'.$originalRows.' images found</a> displayed.';
		$displayRows = MAXIMAGES_LOCATIONPAGE;
	}
	else		
	{
		$moreString = $displayRows.' images found.';
	}
?>
<p><?=$moreString?> Click them to enlarge.</p>
<table class="centeredTable">
<?
	$i=0;
	$j=0;
	
	if ($numberOfRows == '4')
	{
		$j=1;
	}
	
	if ($originalRows > MAXIMAGES_LOCATIONPAGE)
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
			/* <td><a href="/gallery/<? echo $photoPath; ?>/<? echo $photoUrl; ?>.html" target="new" ><img src="/gallery/cache/<? echo $photoPath; ?>/<? echo $photoUrl; ?>_<?php echo thumbsize; ?>.jpg" alt="<? echo $photoTitle; ?>" title="<? echo $photoTitle; ?>" /></a>*/
			// non rewrite
			/* <a href="/gallery/index.php?album=<? echo $photoPath; ?>&amp;image=<? echo $photoUrl; ?>&size=" target="new" ><img src="/gallery/cache/<? echo $photoPath; ?>/<? echo $photoUrl; ?>_<?php echo thumbsize; ?>.jpg" alt="<? echo $photoTitle; ?>" title="<? echo $photoTitle; ?>" /></a> */
			
			// old version
			/*<td class="i"><a href="/gallery/<? echo $photoPath; ?>/<? echo $photoUrl; ?>.html?size=" target="new" ><img src="/gallery/cache/<? echo $photoPath; ?>/<? echo $photoUrl; ?>_150_cw150_ch150.jpg" alt="<? echo $photoTitle; ?>" title="<? echo $photoTitle; ?>" />*/
?>
<td class="i">
	<a href="/gallery/albums/<? echo $photoPath; ?>/<? echo $photoUrl; ?>" rel="lightbox" title="<? echo $photoTitle; ?>"><img src="/gallery/<? echo $photoPath; ?>/image/thumb/<? echo $photoUrl; ?>" alt="<? echo $photoTitle; ?>" title="<? echo $photoTitle; ?>" /></a>
	<p><?=$photoTitle ?></p></td>
<?		$j++;
			$i++;
			$k = $k+(rand(1, ($original/7)));
	
		}	//end while for cols
		$j=0;
?>
</tr>
<?
		}	//end while for rows
		?>
</table>
<p><a href="#top" class="credit">Top</a></p>
<?	}		// end if
	return;	
	
}	//end function

