<?php

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
	$hitCounterText = $ratingsText = "";
	
	if (function_exists('getRollingHitcounter'))
	{
		$hitCounterText = getRollingHitcounter($_zp_current_image);
	}
	else if (function_exists('formatHitCounter'))
	{
		$hitCounterText = formatHitCounter(incrementAndReturnHitCounter('image'));
	}
	
	if (function_exists('getDeathmatchRatingsText'))
	{
		$ratingsText = getDeathmatchRatingsText();
		
		if (strlen($hitCounterText) > 0 && strlen($ratingsText) > 0)
		{
			$hitCounterText .= "<br/>";
		}
	}

	if ( zp_loggedin() )
	{
		if (strlen($hitCounterText) > 0)
		{
			$hitCounterText .= "<br/>";
		}
		
		$hitCounterText .= "Week reset = ".$_zp_current_image->get('hitcounter_week_reset').", Month reset = ".$_zp_current_image->get('hitcounter_month_reset');
	}

	if (sizeof($result) > 1 AND $result['EXIFDateTimeOriginal'] != '')
	{
		$date = explode(':', $result['EXIFDateTimeOriginal']);
		$splitdate = explode(' ', $date[2]);
		$udate = mktime($splitdate[1], $date[3],$date[4],$date[1],$splitdate[0],$date[0]);
		$fdate = strftime('%B %d, %Y', $udate);
		$ftime = strftime('%H:%M %p', $udate);

		//check if seach by date exists, should be in set up in plugins\archive_days.php
		if (function_exists('printSingleMonthArchive'))
		{
    		$dateString = $date[0] . '-' . $date[1] . '-' . $splitdate[0];
    		$dateLink = "<a href=\"".html_encode(getSearchURL(null, $dateString, null, 0, null))."\" title=\"See other photos from this date\">$fdate</a> $ftime";
		}
		else
		{
			$dateLink = $fdate.'&nbsp;'.$ftime;
		}
	?>
<p class="exif">
Taken with a <?php echo $result['EXIFModel'] ?><br/>
Date: <?php echo $dateLink;?><br/>
Exposure Time: <?php echo $result['EXIFExposureTime'] ?><br/>
Aperture Value: <?php echo $result['EXIFFNumber'] ?><br/>
Focal Length: <?php echo $result['EXIFFocalLength'] ?><br/>
<?php echo $hitCounterText.$ratingsText?>
</p>
<?php
	}
	else
	{
?>
<p class="exif">
<?php echo $hitCounterText.$ratingsText; ?>
</p>
<?php
	}	// end if
}		// end function

function drawNewsNextables()
{
	$next = getNextNewsURL();
	$prev = getPrevNewsURL();

	if($next OR $prev) {
	?>
<div class="pagelist"><ul class="pagelist"><li class="prev">
  <?php if($prev) { ?><a href="<?php echo $prev['link'];?>" title="<?php echo $prev['title']?>"><span>&laquo;</span> <?php echo $prev['title']?></a> <?php } ?>
  </li><li class="next">
  <?php if($next) { ?><a href="<?php echo $next['link'];?>" title="<?php echo $next['title']?>"><?php echo $next['title']?> <span>&raquo;</span></a> <?php } ?>
</li></ul></div>
  <?php }
}


/*
 *
 * drawWongmGridSubalbums()
 *
 * Draw a grid of sub-albums for an album
 *
 */
function drawWongmGridAlbums($numberOfItems)
{
?>
<!-- Sub-Albums -->
<table class="centeredTable">
<?php
	// neater for when only 4 items
	if ($numberOfItems == 4)
	{
		$i = 1;
	}
	else
	{
		$i = 0;
	}
	while (next_album()):
    	$count++;
		if ($i == 0)
		{
			echo '<tr>';
		}
		global $_zp_current_album;
?>
<td class="album" valign="top">
	<div class="albumthumb"><a href="<?php echo getAlbumURL();?>" title="<?php echo getAlbumTitle();?>">
	<?php printAlbumThumbImage(getAlbumTitle()); ?></a></div>
	<div class="albumtitle"><h4><a href="<?php echo getAlbumURL();?>" title="<?php echo getAlbumTitle();?>">
	<?php printAlbumTitle(); ?></a></h4><small><?php printAlbumDate(); ?><?php if(function_exists('printHitCounter')) { printHitCounter($_zp_current_album, true); } ?></small></div>
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
    	 
        // enforce limit on items displayed
        if ($count >= $numberOfItems)
        {
            break;
        }
    
	endwhile;
?>
</table>
<?php
}	/// end function



/**
 * Returns the raw title of the current image.
 *
 * @return string
 */
function getImageAlbumTitle() {
	if(!in_context(ZP_IMAGE)) return false;
	global $_zp_current_image;
	
	if (function_exists('getAlbumTitleForPhotostreamImage') && strlen(getAlbumTitleForPhotostreamImage()) > 0)
	{
		$title = getAlbumTitleForPhotostreamImage();
	}
	else
	{
		$title = $_zp_current_image->getAlbum()->getTitle();
	}
	return $title;
}

/**
 * Returns the raw title of the current image.
 *
 * @return string
 */
function getImageAlbumLink() {
	if(!in_context(ZP_IMAGE)) return false;
	global $_zp_current_image;
	$title = getImageAlbumTitle();
	$folder = getAlbumURL($_zp_current_image->getAlbum());
	return "<p>In album: <a href=\"$folder\">$title</a>";
}

function printImageDescWrapped()
{
	if (strlen(getImageDesc()) > 0)
	{
		echo "<p>" . getImageDesc() . "</p>\n";
	}	
}


/*
 *
 * drawWongmGridImages()
 *
 * Draw a grid of images for an album
 * Used by album.php and search.php
 *
 */
function drawWongmGridImages($numberOfItems)
{
    $albumLinkHtml = $style = "";
    $column = 0;
    $count = 0;
	$row = 0;
    
	?>
<!-- Images -->
<table class="centeredTable">
<?php
	// neater for when only 4 items
	if ($numberOfItems != 4)
	{
	    $row = 0;
	    $style = ' class="trio"';
	}
	
	while (next_image())
	{
	    $column++;
	    $count++;
	
		if ($row == 0)
		{
			echo "<tr$style>\n";
		}
		
		$albumTitle = getImageTitle();
		if (in_context(ZP_SEARCH))
		{
			$albumLinkHtml = getImageAlbumLink();
			$albumTitle = getImageAlbumTitle() . ": " . $albumTitle;
		}
  
		global $_zp_current_image;
?>
<td class="image">
	<div class="imagethumb"><a href="<?php echo getImageURL();?>" title="<?php echo $albumTitle;?>">
		<img src="<?php echo getImageThumb() ?>" title="<?php echo $albumTitle;?>" alt="<?php echo $albumTitle;?>" />
	</a></div>
	<div class="imagetitle">
		<h4><a href="<?php echo getImageURL();?>" title="<?php echo $albumTitle;?>"><?php echo $albumTitle; ?></a></h4>
		<?php echo printImageDescWrapped(); ?>
		<small><?php printImageDate(); ?><?php if(function_exists('printHitCounter')) { printHitCounter($_zp_current_image, true); } ?></small>
	</div>
</td>
<?php
		if ($row == 2 || ($numberOfItems == 4 && $row == 1))
		{
			echo "</tr>\n";
			$row = 0;
		}
		else
		{
			$row++;
		}
		
		// enforce limit on items displayed
		if ($count >= $numberOfItems)
		{
		    break;
		}
	} ?>
</table>
<?php
}	// end function

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
		<a href="<?php echo htmlspecialchars(getAlbumURL());?>" title="<?php echo gettext('View album:'); ?> <?php echo strip_tags(getAlbumTitle());?>"><?php printAlbumThumbImage(getAlbumTitle()); ?></a>
	</td><td class="albumdesc">
		<h4><a href="<?php echo htmlspecialchars(getAlbumURL());?>" title="<?php echo gettext('View album:'); ?> <?php echo strip_tags(getAlbumTitle());?>"><?php printAlbumTitle(); ?></a></h4>
		<p><small><?php printAlbumDate(""); ?><?php if(function_exists('printHitCounter')) { printHitCounter($_zp_current_album, true); } ?></small></p>
		<p><?php printAlbumDesc(); ?></p>
<?php 	if (zp_loggedin())
	{
		echo "<p>";
		echo printLinkHTML('/zp-core/admin-edit.php?page=edit&album=' . urlencode(getAlbumURL()), gettext("Edit details"), NULL, NULL, NULL);
		echo '</p>';
	}
?>
	</td>
</tr>
<?php

}	// end function

function printMWEditableImageTitle($editable=false, $editclass='editable imageTitleEditable', $messageIfEmpty = true ) {
	if ( $messageIfEmpty === true ) {
		$messageIfEmpty = gettext('(No title...)');
	}
	printMWEditable('image', 'title', $editable, $editclass, $messageIfEmpty);
}

function printMWEditableImageDesc($editable=false, $editclass='', $messageIfEmpty = true) {
	if ( $messageIfEmpty === true ) {
		$messageIfEmpty = gettext('(No description...)');
	}
	printMWEditable('image', 'desc', $editable, $editclass, $messageIfEmpty, !getOption('tinyMCEPresent'));
}

function printMWEditableAlbumTitle($editable=false, $editclass='', $messageIfEmpty = true) {
	if ( $messageIfEmpty === true ) {
		$messageIfEmpty = gettext('(No title...)');
	}
	printMWEditable('album', 'title', $editable, $editclass, $messageIfEmpty);
}

function printMWEditableAlbumDesc($editable=false, $editclass='', $messageIfEmpty = true ) {
	if ( $messageIfEmpty === true ) {
		$messageIfEmpty = gettext('(No description...)');
	}
	printMWEditable('album', 'desc', $editable, $editclass, $messageIfEmpty, !getOption('tinyMCEPresent'));
}

/**
 * Print any album or image data and make it editable in place
 *
 * @param string $context	either 'image' or 'album'
 * @param string $field		the data field to echo & edit if applicable: 'date', 'title', 'place', 'description', ...
 * @param bool   $editable 	when true, enables AJAX editing in place
 * @param string $editclass CSS class applied to element if editable
 * @param mixed  $messageIfEmpty message echoed if no value to print
 * @param bool   $convertBR	when true, converts new line characters into HTML line breaks
 * @param string $override	if not empty, print this string instead of fetching field value from database
 * @param string $label "label" text to print if the field is not empty
 * @since 1.3
 * @author Ozh
 */
function printMWEditable($context, $field, $editable = false, $editclass = 'editable', $messageIfEmpty = true, $convertBR = false, $override = false, $label='') {
	switch($context) {
		case 'image':
			global $_zp_current_image;
			$object = $_zp_current_image;
			break;
		case 'album':
			global $_zp_current_album;
			$object = $_zp_current_album;
			break;
		case 'pages':
			global $_zp_current_zenpage_page;
			$object = $_zp_current_zenpage_page;
			break;
		case 'news':
			global $_zp_current_zenpage_news;
			$object = $_zp_current_zenpage_news;
			break;
		default:
			trigger_error(gettext('printMWEditable() incomplete function call.'), E_USER_NOTICE);
			return false;
	}
	if (!$field || !is_object($object)) {
		trigger_error(gettext('printMWEditable() invalid function call.'), E_USER_NOTICE);
		return false;
	}
	$text = trim( $override !== false ? $override : get_language_string($object->get($field)) );
	$text = zp_apply_filter('front-end_edit', $text, $object, $context, $field);
	if ($convertBR) {
		$text = str_replace("\r\n", "\n", $text);
		$text = str_replace("\n", "<br />", $text);
	}

	if (empty($text)) {
		if ( $editable && zp_loggedin() ) {
			if ( $messageIfEmpty === true ) {
				$text = gettext('(...)');
			} elseif ( is_string($messageIfEmpty) ) {
				$text = $messageIfEmpty;
			}
		}
	}
	if (!empty($text)) echo $label;
	if ($editable && getOption('edit_in_place') && zp_loggedin()) {
		// Increment a variable to make sure all elements will have a distinct HTML id
		static $id = 1;
		$id++;
		$class= 'class="' . trim("$editclass zp_editable zp_editable_{$context}_{$field}") . '"';
		echo "<span id=\"editable_{$context}_$id\" $class>" . $text . "</span>\n";
		echo "<script type=\"text/javascript\">editInPlace('editable_{$context}_$id', '$context', '$field');</script>";
	} else {
		$class= 'class="' . "zp_uneditable zp_uneditable_{$context}_{$field}" . '"';
		echo "<span $class>" . $text . "</span>\n";
	}
}

/**
 * Returns the date of the search
 *
 * @param string $format formatting of the date, default 'F Y'
 * @return string
 * @since 1.1
 */
function getFullSearchDate($format='F Y') {
	if (in_context(ZP_SEARCH)) {
		global $_zp_current_search;
		$date = $_zp_current_search->getSearchDate();
		$date = str_replace("/", "", $date);
		if (empty($date)) { return ""; }
		if ($date == '0000-00') { return gettext("no date"); };

		if (sizeof(explode('-', $date)) == 3) {
			$format='F d, Y';
		}

		$dt = strtotime($date."-01");
		return date($format, $dt);
	}
	return false;
}


function printMetadata($pageTitle)
{
	$description = getGalleryDesc();
	$title = "";
	
	// if date based search with images - we can get summary data for the current date
	if (in_context(ZP_SEARCH) && function_exists('getDailySummaryDesc'))
	{
		// check for images, and that we are not on a month based archive page
		if (isset($_REQUEST['date']) && strlen($_REQUEST['date']) > 8 && getNumImages() && strlen($_REQUEST['date']) > 7)
		{
			global $_zp_current_DailySummaryItem;
			$_zp_current_DailySummaryItem = new DailySummaryItem($_REQUEST['date']);
			$description = getDailySummaryDesc();
			$title = getDailySummaryTitleAndDesc();
			$imagePath = $_zp_current_DailySummaryItem->getDailySummaryThumbImage()->getFullImageURL();
		}
	}
	// image page
	else if (in_context(ZP_IMAGE))
	{
		$imagePath = getDefaultSizedImage();
		if (strlen(getImageDesc()) > 0) {
			$description = strip_tags(getImageDesc());
		}
		$shortTitle = $title = getImageTitle();
	} 
	// album page
	else if (in_context(ZP_ALBUM))
	{
		global $_zp_current_album, $_zp_current_image;
		
		// makeImageCurrent can change $_zp_current_album and $_zp_current_image variable to child album
		// save a local copy, so we can get THIS album back later
		$currentAlbum = $_zp_current_album;
		$currentImage = $_zp_current_image;
		$currentContext = get_context();
		makeImageCurrent($_zp_current_album->getAlbumThumbImage());
		$imagePath = getDefaultSizedImage();
		
		// now reset image to ensure that rest of Zenphoto does not get confused
		$_zp_current_album = $currentAlbum;
		$_zp_current_image = $currentImage;
		set_context($currentContext);
		
		if (strlen(getAlbumDesc()) > 0) {
			$description = strip_tags(getAlbumDesc());
		}
		$title = htmlentities(getBareAlbumTitle());
	}
	
	echo "<meta property=\"og:description\" content=\"$description\" />\n";
	echo "<meta property=\"og:type\" content=\"article\" />\n";
	echo "<meta property=\"og:site_name\" content=\"" . getGalleryTitle() . "\" />\n";
	
	if (strlen($title) > 0)
	{
		$protocol = SERVER_PROTOCOL;
		if ($protocol == 'https_admin') {
			$protocol = 'https';
		}
		$imagePath = $protocol . '://' . $_SERVER['HTTP_HOST'] . WEBPATH . $imagePath;
		
		echo "<meta property=\"og:image\" content=\"$imagePath\" />\n";
		echo "<meta property=\"og:title\" content=\"$title\" />\n";	
		echo "<meta name=\"twitter:card\" content=\"photo\">\n";
		echo "<meta name=\"twitter:title\" content=\"$title\">\n";
		echo "<meta name=\"twitter:image:src\" content=\"$imagePath\">\n";
	}
	else{
		echo "<meta name=\"twitter:card\" content=\"summary\" />\n";
		echo "<meta name=\"twitter:title\" content=\"" . htmlentities($pageTitle) . "\">\n";
	}
	
	echo "<meta name=\"twitter:site\" content=\"@railgeelong\">\n";
	echo "<meta name=\"twitter:creator\" content=\"@railgeelong\">\n";
	echo "<meta name=\"twitter:domain\" content=\"" . getGalleryTitle() . "\">\n";
	echo "<meta name=\"twitter:description\" content=\"$description\">\n";	
	echo "<meta name=\"description\" content=\"$description\" />\n";
}

/**
 * Prints the album description of the current album.
 *
 * @param bool $editable
 */
function printAlbumDescAndLink($editable=false) 
{
	global $_zp_current_album;

	$linkContent = "";	
	$railHistoryCMSLink = getRailHistoryCMSLinkForAlbum($_zp_current_album->getFileName());
	if ($railHistoryCMSLink != null)
	{
		$linkContent = "For more details see <a href=\"" . $railHistoryCMSLink['url'] . "\">" . $railHistoryCMSLink['name'] . "</a>.";
	}
	
	if ($editable AND zp_loggedin())
	{
		printMWEditableAlbumDesc(true);
		echo '<div class="albumdesc">'.$linkContent.'</div>';
	}
	else
	{
		$desc = htmlspecialchars(getAlbumDesc());
		$len = strlen($desc);
		if ($len > 1 AND substr($desc, $len-1, 1) != '.') {
			$desc .= ".";
		}
		
		echo "<div class=\"albumdesc\">$desc $linkContent</div>";
	}
}

function getRailHistoryCMSLinkForAlbum($albumFolderName)
{
	global $_zp_db;

	//prefer lineguide first, even if it doesn't exist
	$lineResultSQL = "SELECT name, link FROM raillines WHERE photos = " . $_zp_db->quote($albumFolderName);
	$lineResult = $_zp_db->queryFullArray($lineResultSQL);
	
	if (sizeof($lineResult) > 0)
	{
		$url = "/lineguide/" . $lineResult[0]['link'] . "/";
		return array('url' => $url, 'name' => $lineResult[0]['name']);
		
	}
	
	$locationResultSQL = "SELECT name, link FROM locations WHERE photos = " . $_zp_db->quote($albumFolderName) . " AND link != ''";
	$locationResult = $_zp_db->queryFullArray($locationResultSQL);
	
	if (sizeof($locationResult) > 0)
	{
		$url = "/location/" . $locationResult[0]['link'] . "/";
		return array('url' => $url, 'name' => $locationResult[0]['name']);
	}
	
	return null;
}

?>