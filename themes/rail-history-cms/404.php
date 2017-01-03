<?php 

/*
 * index.php in ROOT is where the redirect is set
 *	
 */
if (!defined('WEBPATH')) die(); 

$numberofresults = 0;
$displaySearch = function_exists('searchOn404');

if (function_exists('redirectOn404')) {
	redirectOn404();
}

if ($displaySearch) {
	searchOn404();
	$term = getSearchTermFrom404();
}
 
$startTime = array_sum(explode(" ",microtime())); 
$pageTitle = ' - 404 Page Not Found';
require_once('common/header.php');
?>
<div id="headbar">
	<div class="link"><a href="/gallery/" title="Gallery Index">Gallery</a> &raquo; 404 Page Not Found</div>
	<div class="search"><?php printSearchForm(); ?></div>
</div>
<?php require_once('common/midbar.php'); ?>
<div class="topbar">
  	<h3>404 Page Not Found</h3>
</div>
<h4>The page you are looking for cannot be found.</h4>
<?php
if ($displaySearch) 
{
    if (wasLookingForImage()) 
    {
        $numberofresults = getNumImages();
        // will only show top images
        drawWongmGridImages(3);
    }
    else
    {
        $numberofresults = getNumAlbums();
        drawWongmGridAlbums(3);
    }
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
<p><?=$wording?>can use <a href="<?=SEARCH_URL_PATH?><?=$term?>">Search</a> to find what you are looking for. </p> 
<p>Otherwise please check you typed the address correctly. If you followed a link from elsewhere, please inform them. If the link was from this site, then <a href="<?php echo CONTACT_URL_PATH ?>">Contact Me</a>.</p>
<?php require_once('common/footer.php');
 ?>