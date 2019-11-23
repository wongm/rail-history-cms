<?php
require_once("definitions.php");
require_once("formatting-functions.php");

// check this location has images to show
function getLinkedPhotoCount($linkedPhotoPathString)
{
	$linkedPhotoPathStringBits = explode(';', $linkedPhotoPathString);
	$subLocation = sizeof($linkedPhotoPathStringBits);
	
	// for comma seperated individual images
	if ($subLocation > 1)
	{
		$sqlWhere = "( i.filename = ".db_quote(getFilenameFromPath($linkedPhotoPathStringBits[0]))." ";
		for ($i = 1; $i < $subLocation; $i++)
		{
			$sqlWhere .= " OR i.filename = ".db_quote(getFilenameFromPath($linkedPhotoPathStringBits[$i]))." ";
		}
		$sqlWhere .= ")";
	}
	else if (strpos($linkedPhotoPathString, '.jpg') > 0)
	{
		$sqlWhere = "i.filename = ".db_quote(getFilenameFromPath($linkedPhotoPathString))." ";
	}
	// for album in the gallery 
	else
	{
		$sqlWhere = "a.folder = ".db_quote($linkedPhotoPathString);
	}
	
	setCustomPhotostream($sqlWhere);	
	return getNumPhotostreamImages();
}

function getFilenameFromPath($fullpath)
{
	$fullpathbits = explode('/', $fullpath);
	$bitcount = sizeof($fullpathbits);
	
    return $fullpathbits[$bitcount - 1];
}

/*
 * Gets the images for a specified location
 * $locations = path to album, or CSV of image ids
 */
function drawLinkedPhotosFromGallery()
{
	$displayRows = $originalRows = getNumPhotostreamImages();
	
	if ($originalRows > 0) 
	{
		$i=0;
		$j=0;
		
		if ($originalRows == '4')
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
	
		next_photostream_image();
		while ($i < $displayRows)
		{
			$linkedGalleryItemPath = getAlbumURL();
			
			if ($i == 0)
			{
?>
<h4 id="photos" name="photos">Photos</h4><hr />
<?php
				if ($originalRows > 9)
				{
					$moreString = 'Nine of <a href="'.$linkedGalleryItemPath.'">'.$originalRows.' images found</a> displayed.';
					$displayRows = 9;
				}
				else		
				{
					$moreString = '<a href="'.$linkedGalleryItemPath.'">'.$displayRows.' images found</a>.';
				}
?>
<p><?php echo $moreString?> Click them to enlarge.</p>
<table class="centeredTable">
<?php
			}
			
			echo "<tr>\n";
			while ($j < 3 AND $i<$displayRows )
			{
?>
	<td class="i">
			<div class="imagethumb"><a href="<?php echo getDefaultSizedImage();?>" rel="lightbox" title="<?php echo getImageTitle();?>">
			 <?php printImageThumb(getImageTitle()); ?></a></div>
			<div class="imagetitle">
				<h4><a href="<?php echo getImageURL();?>" title="<?php echo getImageTitle();?>"><?php printImageTitle(); ?></a></h4>
				<?php echo printImageDescWrapped(); ?>
				<small><?php printImageDate(); ?><br/><?php if(function_exists('printHitCounter')) { printHitCounter($_zp_current_image); } ?></small>
			</div>
		</td>
<?php 			$j++;
				$i++;
+    			next_photostream_image();
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

