<?php

//******************************************************************************
// Miscellaneous functions for the ZenPhoto gallery that I need
//
// For railgeelong.com and wongm.railgeelong.com
//
// V 1.0.0
//
//******************************************************************************

function pluralNumberWord($number, $text)
{
	if (is_numeric($number))
	{
		if ($number == 0)
		{
			return $number.' '.$text.'s';
		}
		if ($number > 1)
		{
			return $number.' '.$text.'s';
		}
		else
		{
			return "$number $text";
		}
	}
}


/*
 * prints a pretty dodad that lists the total number of pages in a set
 * give it the index you are up to,
 * the total number of items,
 * the number  to go per page,
 * and the URL to link to
 */
function drawGallerySearchPageNumberLinks($index, $totalimg, $max, $url)
{
	$total = floor(($totalimg)/$max)+1;
	$current = $index/$max;
	$url = fixNavigationUrl($url);

	echo "<div class=\"pagelist\"\n>";

	if ($current > 3 AND $total > 7)
	{
		$url1 = $url."1";
		echo "\n <a href=\"$url1\" alt=\"First page\" title=\"First page\">1</a>&nbsp;";

		if ($current > 4)
		{
			echo "...&nbsp;";
		}
	}

	for ($i=($j=max(1, min($current-2, $total-6))); $i <= min($total, $j+6); $i++)
	{
		if ($i == $current+1)
		{
			echo $i;
		}
		else
		{
			echo '<a href="'.$url.$i.'" alt="Page '.$i.'" title="Page '.$i.'">'.($i).'</a>';
		}
		echo "&nbsp;";
	}
	if ($i <= $total)
	{
		if ($current < $total-5)
		{
			echo "...&nbsp;";
		}

		echo "<a href=\"$url$total\" alt=\"Last page\" title=\"Last page\">" . $total . "</a>";
	}

	echo "</div>";
}	// end function


function getNumberCurrentDispayedRecords($maxRecordsPerPage,$numberOfRecords,$searchPageNumber)
{
	if ($numberOfRecords != $totalNumberOfRecords)
	{
		$lowerBound = ($maxRecordsPerPage*$searchPageNumber)+1;
		$upperBound = $lowerBound+$numberOfRecords-1;
		$extraBit = "$lowerBound to $upperBound shown on this page";
	}
	return $extraBit;
}

function getFullImageLinkURL() {
	if(!in_context(ZP_IMAGE)) return false;
  	global $_zp_current_image;

	if ($_REQUEST['p'] == 'full')
	{
    	return rewrite_path('/' . pathurlencode($_zp_current_image->album->name) . '/' . urlencode($_zp_current_image->filename) . im_suffix(),
			'/index.php?album=' . urlencode($_zp_current_image->album->name) . '&image=' . urlencode($_zp_current_image->filename));
	}
	else
	{
	  	return rewrite_path('/' . pathurlencode($_zp_current_image->album->name) . '/' . urlencode($_zp_current_image->filename) . im_suffix() . '?p=full',
	    	'/index.php?album=' . urlencode($_zp_current_album->name) . '&image=' . urlencode($_zp_current_image->name));
    }
}



/**
 * Returns the url of the previous image.
 *
 * @return string
 */
function getPrevImageTitle() {
	if(!in_context(ZP_IMAGE)) return false;
	global $_zp_current_album, $_zp_current_image;
	$previmg = $_zp_current_image->getPrevImage();
	return $previmg->getTitle();
}

function getNextImageTitle() {
	if(!in_context(ZP_IMAGE)) return false;
	global $_zp_current_album, $_zp_current_image;
	$nextimg = $_zp_current_image->getNextImage();
	return $nextimg->getTitle();
}

/**
 * Prints the exif data of the current image.
 *
 */
function printEXIFData()
{
	global $_zp_current_image;
	$result = getImageMetaData();
	$hitCounterText = formatHitCounter(incrementAndReturnHitCounter('image'));

	if (function_exists('getDeathmatchRatingsText'))
	{
		$ratingsText = getDeathmatchRatingsText();
	}

	if ( zp_loggedin() )
	{
		if (strlen($hitCounterText) > 0)
		{
			$hitCounterText .= "<br/>";
		}

		$hitCounterText .= "Week reset = ".$_zp_current_image->get('hitcounter_week_reset').", Month reset = ".$_zp_current_image->get('hitcounter_month_reset');
	}

	if (sizeof($result) > 1 AND $result[EXIFDateTimeOriginal] != '')
	{
		$date = split(':', $result[EXIFDateTimeOriginal]);
		$splitdate = split(' ', $date[2]);
		$udate = mktime($splitdate[1], $date[3],$date[4],$date[1],$splitdate[0],$date[0]);
		$fdate = strftime('%B %d, %Y', $udate);
		$ftime = strftime('%H:%M %p', $udate);

		//check if seach by date exists, should be in set up in plugins\archive_days.php
		if (function_exists('printAllDays'))
		{
			$dateLink = "<a href=\"".SEARCH_URL_PATH."/archive/$date[0]-$date[1]-$splitdate[0]\" alt=\"See other photos from this date\" title=\"See other photos from this date\">$fdate</a> $ftime";
		}
		else
		{
			$dateLink = $fdate.'&nbsp;'.$ftime;
		}
	?>
<p class="exif">
Taken with a <?=$result[EXIFModel] ?><br/>
Date: <?=$dateLink;?><br/>
Exposure Time: <?=$result[EXIFExposureTime] ?><br/>
Aperture Value: <?=$result[EXIFFNumber] ?><br/>
Focal Length: <?=$result[EXIFFocalLength] ?><br/>
<?=$hitCounterText.$ratingsText?>
</p>
<?
	}
	else
	{
?>
<p class="exif">
<?=$hitCounterText.$ratingsText; ?>
</p>
<?
	}	// end if
}		// end function

/**
 * Prints a list of all pages.
 *
 * @param string $class the css class to use, "pagelist" by default
 * @param string $id the css id to use
 */
function drawGalleryPageNumberLinks($url='')
{
	$total = getTotalPages();
	$current = getCurrentPage();

	echo '<p>';

  	if ($total > 0)
  	{
		echo 'Page: ';
	}

	if ($current > 3 AND $total > 7)
	{
		echo "\n <a href=\"".$url.getMyPageURL(getPageURL(1))."\" alt=\"First page\" title=\"First page\">1</a>&nbsp;";

		if ($current > 4)
		{
			echo "...&nbsp;";
		}
	}

	for ($i=($j=max(1, min($current-2, $total-6))); $i <= min($total, $j+6); $i++)
	{
		if ($i == $current)
		{
			echo $i;
		}
		else
		{
			echo '<a href="'.$url.getMyPageURL(getPageURL($i)).'"\" alt="Page '.$i.'" title="Page '.$i.'">'.($i).'</a>';
		}
		echo "&nbsp;";
	}
	if ($i <= $total)
	{
		if ($current < $total-5)
		{
			echo "...&nbsp;";
		}

		echo "<a href=\"".$url.getMyPageURL(getPageURL($total))."\" alt=\"Last page\" title=\"Last page\">" . $total . "</a>";
	}
	echo '</p>';
}

function getMyPageURL($defaultURL)
{
	$defaultURL = str_replace('/page/search/', '/gallery/search/', $defaultURL);
	$defaultURL = str_replace('/page/page/', '/page/', $defaultURL);
	return str_replace('/gallery/everything/', '/gallery/everything/page/', $defaultURL);
}

/**
 * Returns the title of the current image.
 * Truncates it if over given length
 *
 * @param bool $editable if set to true and the admin is logged in allows editing of the title
 */
function printTruncatedImageTitle($editable=false) {
	global $_zp_current_image;

	if ($editable && zp_loggedin())
	{
		$text = getImageTitle();

		if (empty($text))
		{
			$text = gettext('(...)');
		}

		$class= 'class="' . trim("zp_editable zp_editable_image_title") . '"';
		echo "<span id=\"editable_image_truncate\" $class>" . $text . "</span>\n";
		echo "<script type=\"text/javascript\">editInPlace('editable_image_truncate', 'image', 'title');</script>";
	}
	else
	{
		$imageTitle = getImageTitle();

		if (strlen($imageTitle) > getOption('wongm_imagetitle_truncate_length'))
		{
			//$imageTitle = "<abbr title=\"$imageTitle\">" . substr($imageTitle, 0, getOption('wongm_imagetitle_truncate_length')) . "</abbr>...";
			$imageTitle = substr($imageTitle, 0, getOption('wongm_imagetitle_truncate_length')) . "...";
		}
		echo "<span id=\"imageTitle\" style=\"display: inline;\">" . $imageTitle . "</span>\n";
	}
}

function drawNewsNextables()
{
	$next = getNextNewsURL();
	$prev = getPrevNewsURL();

	if($next OR $prev) {
	?>
<table class="nextables"><tr><td>
  <?php if($prev) { ?><a class="prev" href="<?=$prev['link'];?>" title="<?=$prev['title']?>"><span>&laquo;</span> <?=$prev['title']?></a> <? } ?>
  <?php if($next) { ?><a class="next" href="<?=$next['link'];?>" title="<?=$next['title']?>"><?=$next['title']?> <span>&raquo;</span></a> <? } ?>
</td></tr></table>
  <?php }
}

function drawNewsFrontpageNextables()
{
	$next = getNextNewsPageURL();
	$prev = getPrevNewsPageURL();

	if($next OR $prev) {
	?>
<table class="nextables"><tr><td>
  <?php if($prev) { ?><a class="prev" href="<?="http://".$_SERVER['HTTP_HOST'].$prev;?>" title="Previous page"><span>&laquo;</span> Previous page</a> <? } ?>
  <?php if($next) { ?><a class="next" href="<?="http://".$_SERVER['HTTP_HOST'].$next;?>" title="Next page">Next page <span>&raquo;</span></a> <? } ?>
</td></tr></table>
  <?php }
}

/*
 *
 * drawRecentImagesNextables()
 *
 * Back and forwards links for the recent images page
 *
 */
function drawRecentImagesNextables($showpagelist=false)
{
	if (hasPrevPhotostreamPage() OR hasNextPhotostreamPage())
	{
?>
<table class="nextables"><tr><td>
<?
	}
	if (hasPrevPhotostreamPage())
	{
?>
<a class="prev" href="<? echo getPrevPhotostreamPageURL() ?>" title="Previous Page"><span>&laquo;</span> Previous</a>
<?
	}
	if (hasNextPhotostreamPage())
	{	?>
<a class="next" href="<? echo getNextPhotostreamPageURL() ?>" title="Next Page">Next <span>&raquo;</span></a>
<?
	}
	if (hasPrevPhotostreamPage() OR hasNextPhotostreamPage())
	{
?>
</td></tr></table>
<?
	}

	if ($showpagelist)
		printPhotostreamPageList();
}

/*
 *
 * drawWongmGridSubalbums()
 *
 * Draw a grid of sub-albums for an album
 * Used by Rail Geelong
 *
 */
function drawWongmGridSubalbums()
{
?>
<!-- Sub-Albums -->
<table class="centeredTable">
<?php
	// neater for when only 4 items
	if (getNumAlbums() == 4)
	{
		$i = 1;
	}
	else
	{
		$i = 0;
	}
	while (next_album()):
	if ($i == 0)
	{
		echo '<tr>';
	}
	global $_zp_current_album;
?>
<td class="album" valign="top">
	<div class="albumthumb"><a href="<?=getAlbumLinkURL();?>" title="<?=getAlbumTitle();?>">
	<?php printAlbumThumbImage(getAlbumTitle()); ?></a></div>
	<div class="albumtitle"><h4><a href="<?=getAlbumLinkURL();?>" title="<?=getAlbumTitle();?>">
	<?php printAlbumTitle(); ?></a></h4><small><?php printAlbumDate(); ?><?php printHitCounter($_zp_current_album, true); ?></small></div>
	<div class="albumdesc"><?php printAlbumDesc(); ?></div>
</td>
<?php

	if ($i == 2)
	{
		echo "</tr>\n";
		$i = 0;
	}
	else
	{
		$i++;
	}
	endwhile;
?>
</table>
<?
}	/// end function



/**
 * Returns the raw title of the current image.
 *
 * @return string
 */
function getImageAlbumLink() {
	if(!in_context(ZP_IMAGE)) return false;
	global $_zp_current_image;
	$title = $_zp_current_image->getAlbum()->getTitle();
	$folder = getAlbumLinkURL($_zp_current_image->getAlbum());
	return "<br/>In album: <a href=\"$folder\">$title</a>";
}



/*
 *
 * drawWongmGridImages()
 *
 * Draw a grid of images for an album
 * Used by album.php and search.php
 *
 */
function drawWongmGridImages()
{
	?>
<!-- Images -->
<table class="centeredTable">
<?php
  // neater for when only 4 items
  if ($num == 4)
  {
	  $i = 1;
  }
  else
  {
	  $i = 0;
	  $style = 'width="33%" ';
  }

  while (next_image()): $c++;
  if ($i == 0)
  {
	  echo '<tr>';
  }

  if (in_context(ZP_SEARCH))
  {
	  $albumlink = getImageAlbumLink();
  }



  global $_zp_current_image;
?>
<td class="image" <?=$style?>valign="top">
	<div class="imagethumb"><a href="<?=getImageLinkURL();?>" title="<?=getImageTitle();?>">
	<?php printImageThumb(getImageTitle()); ?></a></div>
	<div class="imagetitle"><h4><a href="<?=getImageLinkURL();?>" title="<?=getImageTitle();?>">
	<?php printImageTitle(); ?></a></h4><small><?php printImageDate(); ?><?php printHitCounter($_zp_current_image, true); ?></small><?= $albumlink?></div>
</td>
<?php

  if ($i == 2)
  {
	  echo "</tr>\n";
	  $i = 0;
  }
  else
  {
	  $i++;
  }
  endwhile; ?>
</table>
<?
  return $c;
}	// end function


/*
 *
 * drawIndexAlbums()
 *
 * Draw a list of albums,
 * thumbnail image on the left, details on the right
 * Used by recent-albums.php (recent albums) and everything.php (all albums)
 *
 */
function drawIndexAlbums($type=null, $site=null)
{
	global $_zp_current_album;

	echo "<table id=\"centeredAlbums\" class=\"indexalbums\">\n";

	if ($type == 'dynamiconly' OR $type == 'frontpage')
	{
		while (next_album(true))
		{
			if ($_zp_current_album->isDynamic())
			{
				drawWongmAlbumRow();
			}
		}
	}
	elseif($type=='nodynamic')
	{
		while (next_non_dynamic_album())
		{
			if (!$_zp_current_album->isDynamic())
			{
				drawWongmAlbumRow();
			}
		}
	}
	elseif($type=='recent')
	{
		while (next_non_dynamic_album(false, 'ID', 'DESC'))
		{
			drawWongmAlbumRow();
		}
	}
	else
	{
		while (next_album())
		{
			drawWongmAlbumRow();
		}
	}
 ?>
</table>
<?
}

/*
 *
 * drawWongmAlbumRow()
 *
 * Draw an album row
 * thumbnail image on the left, details on the right
 * Used by drawIndexAlbums() in this file
 *
 */
function drawWongmAlbumRow()
{
	global $_zp_current_album;
?>
<tr class="album">
	<td class="albumthumb">
		<a href="<?php echo htmlspecialchars(getAlbumLinkURL());?>" title="<?php echo gettext('View album:'); ?> <?php echo strip_tags(getAlbumTitle());?>"><?php printAlbumThumbImage(getAlbumTitle()); ?></a>
	</td><td class="albumdesc">
		<h4><a href="<?php echo htmlspecialchars(getAlbumLinkURL());?>" title="<?php echo gettext('View album:'); ?> <?php echo strip_tags(getAlbumTitle());?>"><?php printAlbumTitle(); ?></a></h4>
		<p><small><?php printAlbumDate(""); ?><?php printHitCounter($_zp_current_album, true); ?></small></p>
		<p><?php printAlbumDesc(); ?></p>
<? 	if (zp_loggedin())
	{
		echo "<p>";
		echo printLink($zf . '/zp-core/admin-edit.php?page=edit&album=' . urlencode(getAlbumLinkURL()), gettext("Edit details"), NULL, NULL, NULL);
		echo '</p>';
	}
?>
	</td>
</tr>
<?

}	// end function
?>