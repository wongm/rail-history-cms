<?php 

/*
 * index.php in ROOT is where the redirect is set
 *	
 */
if (!defined('WEBPATH')) die(); 
 
// do redirect to correct URL if a single image found by exact filename match
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
		header("Location: $location");
		return;
	}
}

// otherwise show the user possible results
header("HTTP/1.0 404 Not Found");
header("Status: 404 Not Found");
 
$startTime = array_sum(explode(" ",microtime())); 
$pageTitle = ' - 404 Page Not Found Error';
include_once('header.php');
include_once('search-functions.php');
?>
<table class="headbar">
	<tr><td><a href="<?=getGalleryIndexURL();?>" title="Gallery Index"><?=getGalleryTitle();?></a> &raquo; 404 Page Not Found Error
	</td><td id="righthead"><?printSearchBreadcrumb();?></td></tr>
</table>
<?php

echo gettext("<h2>404 Page Not Found Error</h2>");
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
<p><?=$wording?>can use <a href="<?=SEARCH_URL_PATH?>">Search</a> to find what you are looking for. </p> 
<p>Otherwise please check you typed the address correctly. If you followed a link from elsewhere, please inform them. If the link was from this site, then <a href="/contact.php">Contact Me</a>.</p>
<?php include_once('footer.php'); ?>