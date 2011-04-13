<?php 

/*
 * index.php in ROOT is where the redirect is set
 *	
 */
if (!defined('WEBPATH')) die(); 

// trying to access cached image but it doesn't exist
// eg: /cache/buses/E100_4823_500.jpg
// suffering from an empty cache file?
// so redirect to i.php to regenerate it
if (substr($album, 0, 5) == 'cache')
{
	$imageparams = explode("_", $image);
	
	// check for URL format: E104_3247_180_thumb.jpg
	if ((strtolower($imageparams[3]) == "thumb.jpg") OR (strtolower($imageparams[3]) == "thumb.gif"))
	{
		$imagesize = $imageparams[2];
		$isThumb = true;
	}
	// or E104_3247_500.jpg
	else 
	{
		$imageparams = explode(".", $imageparams[2]);
			
		if ((strtolower($imageparams[1]) == "jpg") OR (strtolower($imageparams[1]) == "gif"))
		{
			$imagesize = $imageparams[0];
		}
	}
	
	// we have an image size
	if ($imagesize > 0)
	{
		// cleasn up album URL
		$album = str_replace("cache/", "", $album);
		
		// cleanup image URL
		$image = str_replace("_" . $imagesize . "_thumb", "", $image);
		$image = str_replace("_" . $imagesize, "", $image);
		
		if ($isThumb)
			$imagesize = 'thumb';
		
		if (strlen($album) > 0 && strlen($image) > 0)
		{
			$location = sprintf(FULLWEBPATH . "/zp-core/i.php?a=%s&i=%s&s=%s", $album, $image, $imagesize);
			status_header(301);
			header("Location: $location");
			return;
		}
	}
}

// otherwise broken HTML page
// do redirect to correct URL if a single image found by exact filename match
// required a DB hit
if ($image != '')
{
	$searchSql = "SELECT * FROM zen_images 
		INNER JOIN zen_albums ON zen_images.albumid = zen_albums.id 
		WHERE zen_images.filename = '".mysql_real_escape_string($image)."'";
	$searchResult = query_full_array($searchSql);
	
	// if single result is returned
	if (sizeof($searchResult) == 1)
	{
		//fix for some "query_full_array()" differences
		if (sizeof($searchResult[0]) > 1)
		{
			$searchResult = $searchResult[0];
		}
		$location = FULLWEBPATH . "/" . $searchResult["folder"] . "/" . $searchResult["filename"] . ".html";
		status_header(301);
		header("Location: $location");
		return;
	}
}

// otherwise show the user possible results
header("HTTP/1.0 404 Not Found");
header("Status: 404 Not Found");
 
$startTime = array_sum(explode(" ",microtime())); 
$pageTitle = ' - 404 Page Not Found';
include_once('header.php');
include_once('functions-search.php');
?>
<table class="headbar">
	<tr><td><a href="<?=getGalleryIndexURL();?>" title="Gallery Index"><?=getGalleryTitle();?></a> &raquo; 404 Page Not Found
	</td><td id="righthead"><?printSearchBreadcrumb();?></td></tr>
</table>
<div class="topbar">
  	<h2>404 Page Not Found</h2>
</div>
<?php
echo gettext("<h4>The gallery object you are requesting cannot be found.</h4>");

if (isset($image) AND $image != '') 
{
	$term = $image;
	$image = true;
}
else if (isset($album)) 
{
	$term = $album;
	$image = false;
}

// check for images
$term  = str_replace('.jpg', '', $term);
$term  = str_replace('.JPG', '', $term);

if ($image)
{
	$numberofresults = imageOrAlbumSearch($term, 'Image', 'error');
}
else
{
	$numberofresults = 0;
}	

// no images results, so check for albums
$term = str_replace('-', ' ', $term);

if ($numberofresults == 0)
{
	$numberofresults = imageOrAlbumSearch($term, 'Album', 'error');
}

// fix for wording below
if ($numberofresults == 1)
{
	$wording = "Is this it? If it isn't, then you ";
}
else if ($numberofresults > 1)
{
	$wording = "Are these it? If it isn't, then you ";
}
else
{
	$wording = "You ";
}
?>
<p><?=$wording?>can use <a href="<?=SEARCH_URL_PATH?>/<?=$term?>">Search</a> to find what you are looking for. </p> 
<p>Otherwise please check you typed the address correctly. If you followed a link from elsewhere, please inform them. If the link was from this site, then <a href="<?=CONTACT_URL_PATH?>">Contact Me</a>.</p>
<?php include_once('footer.php');

function status_header( $header ) {
	if ( 200 == $header )
		$text = 'OK';
	elseif ( 301 == $header )
		$text = 'Moved Permanently';
	elseif ( 302 == $header )
		$text = 'Moved Temporarily';
	elseif ( 304 == $header )
		$text = 'Not Modified';
	elseif ( 404 == $header )
		$text = 'Not Found';
	elseif ( 410 == $header )
		$text = 'Gone';

	@header("HTTP/1.1 $header $text");
	@header("Status: $header $text");
} ?>