<?php 
/*
 * Shows a list of different types of high rating photo page
 * - highest this month
 * - highest this week
 * - highest of all time
 * - highest ranking
 *
 */ 
$startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

$pageTitle = ' - Popular photos';
include_once('header.php');
require_once("search-functions.php");
//$pageBreadCrumb = "<a href=\"".POPULAR_URL_PATH."\" title=\"Popular photos\">Popular photos</a>";
$pageBreadCrumb = 'Popular photos';
?>
<table class="headbar">
	<tr><td><a href="<?=getGalleryIndexURL();?>" title="Gallery Index"><?=getGalleryTitle();?></a> &raquo;
	<?=$pageBreadCrumb?>
	</td><td id="righthead"><?printSearchBreadcrumb();?></td></tr>
</table>
<?

foreach (array('this-week', 'ratings', 'this-month', 'all-time') AS $viewType)
{
	echo '<div class="topbar"><h3>'.$popularImageText[$viewType]['text']."</h3>\n";
	echo "<p><a href=\"".$popularImageText[$viewType]['url']."\">View more...</a></p></div>";
	$galleryResults = getGalleryUploadsResults('popular', $viewType, '', 0, 3, 0);
	drawImageGallery($galleryResults['galleryResult'], $viewType);
}

include_once('footer.php'); 
?>