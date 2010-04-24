<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

$pageTitle = ' - Search';
include_once('header.php'); 

$totalAlbums = getNumAlbums();
$totalImages = getNumImages();
$total = $totalAlbums + $totalImages;

if (strlen($searchwords) == 0)
{
	$leadingIntroText = "<h3>Search</h3>";
}
else
{
	$leadingIntroText = "<h3>Search results</h3>";
}
?>
<table class="headbar">
    <tr><td><a href="/">Rail Geelong</a> &raquo; <a href="/gallery/">Gallery</a> &raquo; <a href="<?=SEARCH_URL_PATH?>">Search</a></td>
    <td id="righthead"><? printSearchBreadcrumb(); ?></td></tr>
</table>
<div class="topbar">
	<?php echo $leadingIntroText; ?>
</div>
<div id="searchpage">
<?php
if ($totalAlbums > 0)
{
	$albumsText = " - $totalAlbums albums and $totalImages images.";
}
if ($total > 0) 
{
	if (isset($_REQUEST['date']))
	{
		$searchwords = getFullSearchDate();
	} 
	else 
	{ 
		$searchwords = getSearchWords(); 
	}
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
<?php drawWongmGridImages(); ?>
</div>
<?php
if (function_exists('printSlideShowLink')) {
	echo "<p align=\"center\">";
	printSlideShowLink(gettext('View Slideshow'));
	echo "</p>";
}
if ($totalImages == 0 AND $totalAlbums == 0) 
{
	if (!empty($searchwords))
	{
		echo "<p>".gettext("Sorry, no image matches. Try refining your search.")."</p>";
	}
	printSearchForm();
}

if (hasNextPage() OR hasPrevPage())
{
?>
<table class="nextables"><tr id="pagelinked"><td>
	<?php if (hasPrevPage()) { ?> <a class="prev" href="<?=getMyPageURL(getPrevPageURL());?>" title="Previous Page"><span>&laquo;</span> Previous</a> <?php } ?>
	</td><td><?php printPageList(); ?></td><td>
	<?php if (hasNextPage()) { ?> <a class="next" href="<?=getMyPageURL(getNextPageURL());?>" title="Next Page">Next <span>&raquo;</span></a><?php } ?>
</td></tr></table>
<?
}
?>
</div>
<?php include_once('footer.php'); ?>