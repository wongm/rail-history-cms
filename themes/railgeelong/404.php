<?php 

/*
 * index.php in ROOT is where the redirect is set
 *	
 */
if (!defined('WEBPATH')) die(); 

if (function_exists('redirectOn404')) {
	redirectOn404();
}

// otherwise show the user possible results
header("HTTP/1.0 404 Not Found");
header("Status: 404 Not Found");
 
$startTime = array_sum(explode(" ",microtime())); 
$pageTitle = ' - 404 Page Not Found';
require_once('common/header.php');
require_once('common/functions-search.php');
?>
<div id="headbar">
	<div class="link"><a href="<?php echo getGalleryIndexURL();?>" title="Gallery Index">Gallery</a> &raquo; 404 Page Not Found</div>
	<div class="search"><?php printSearchForm(); ?></div>
</div>
<?php require_once('common/midbar.php'); ?>
<div class="topbar">
  	<h3>404 Page Not Found</h3>
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
	// setCustomPhotostream("(i.title like " . db_quote('%' . $term . '%') . " OR i.desc like " . db_quote('%' . $term . '%') . " OR i.filename like " . db_quote('%' . $term . '%') . ")");
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
	// setCustomPhotostream("(a.title like " . db_quote('%' . $term . '%') . " OR a.desc like " . db_quote('%' . $term . '%') . " OR a.folder like " . db_quote('%' . $term . '%') . ")");
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
<p><?php echo $wording?>can use <a href="<?php echo SEARCH_URL_PATH?>/<?php echo $term?>">Search</a> to find what you are looking for. </p> 
<p>Otherwise please check you typed the address correctly. If you followed a link from elsewhere, please inform them. If the link was from this site, then <a href="<?php echo CONTACT_URL_PATH ?>">Contact Me</a>.</p>
<?php require_once('common/footer.php');
 ?>