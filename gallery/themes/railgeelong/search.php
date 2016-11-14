<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

$pageTitle = ' - Search results';
include_once('header.php'); 

$totalAlbums = getNumAlbums();
$totalImages = getNumImages();
$total = $totalAlbums + $totalImages;

if (isset($_REQUEST['date']))
{
	$searchwords = getFullSearchDate();
} 
else 
{ 
	$searchwords = getSearchWords(); 
}

if (strlen($searchwords) == 0)
{
	$leadingIntroText = "<h3>Search</h3>";
}
else
{
	$leadingIntroText = "<h3>Search results</h3>";
}
?>
<div id="headbar">
	<div class="link"><a href="/">Home</a> &raquo; <a href="/gallery/">Gallery</a> &raquo; <a href="<?php echo SEARCH_URL_PATH?>">Search</a></div>
	<div class="search"><?php printSearchForm(); ?></div>
</div>
<?php include_once('midbar.php'); ?>
<?php echo $leadingIntroText; ?>
<div id="searchpage">
<?php
if ($totalAlbums > 0)
{
	$albumsText = " - $totalAlbums albums and $totalImages images.";
}

echo "<p>You are currently searching the Gallery: you can also <a href=\"/search?q=$searchwords\">search this site via Google</a>.</p>";

if ($total > 0) 
{
 	echo '<p>'.sprintf(gettext('%2$u total matches for <em>%1$s</em>'), $searchwords, $total)." $albumsText</p>";
}

if ($totalAlbums > 0)
{
	echo "<table class=\"indexalbums\">\n";
	while (next_album())
	{
		if (is_null($firstAlbum)) 
		{
			$lastAlbum = albumNumber();
			$firstAlbum = $lastAlbum;
		} 
		else 
		{
			$lastAlbum++;
		}
		drawWongmAlbumRow();
	}
	echo "</table>";
}
?>
<div id="images">
<?php drawWongmGridImages($totalImages); ?>
</div>
<?php
if (function_exists('printSlideShowLink')) {
	echo "<p align=\"center\">";
	printSlideShowLink(gettext('View Slideshow'));
	echo "</p>";
}
if ($totalImages == 0 AND $totalAlbums == 0) 
{
	if (strlen($searchwords) != 0)
	{
		echo "<p>".gettext("Sorry, no image matches. Try refining your search.")."</p>";
	}
}

printPageListWithNav("« " . gettext("Previous"), gettext("Next") . " »");
?>
</div>
<?php include_once('footer.php'); ?>