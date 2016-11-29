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
require_once('header.php');
require_once("search-functions.php");
//$pageBreadCrumb = "<a href=\"".POPULAR_URL_PATH."\" title=\"Popular photos\">Popular photos</a>";
$pageBreadCrumb = 'Popular photos';
?>
<div id="headbar">
	<div class="link"><a href="<?php echo getGalleryIndexURL();?>" title="Gallery Index"><?php echo getGalleryTitle();?></a> &raquo; <?php echo $pageBreadCrumb?></div>
	<div class="search"><?php printSearchForm(); ?></div>
</div>
<?php 

require_once('midbar.php');

foreach (array('this-week', 'ratings', 'this-month', 'all-time') AS $viewType)
{
	echo '<div class="topbar"><h3>'.$popularImageText[$viewType]['text']."</h3>\n";
	echo "<p><a href=\"".$popularImageText[$viewType]['url']."\">View more...</a></p></div>";
	$galleryResults = getGalleryUploadsResults('popular', $viewType, '', 0, 3, 0);
	drawImageGallery($galleryResults['galleryResult'], $viewType);
}

require_once('footer.php'); 
?>