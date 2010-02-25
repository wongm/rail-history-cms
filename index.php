<?php $pageTitle = "Welcome";
$pageHeading = "Welcome";
//$pageTitle = array(array("Welcome", ''));

include_once("common/dbConnection.php");
include_once("common/formatting-functions.php"); 
include_once("common/news-functions.php");
include_once("common/updates-functions.php");
include_once("common/gallery-functions.php");
include_once("common/header.php");

$caption = "Random image - Collect them all!";
drawHeaderPic('headers', rand(1,getConfigVariable('index-maximages')), $caption);
echo getConfigVariable('index-top'); ?>
<h4 style="clear:both">Recent Updates</h4>
<hr/>
<?php 
printNews();
?>
<p><a href="/news">Complete List...</a></p>
<h4 style="clear:both">Updated content</h4>
<hr/>
<?php
$updates = getUpdatedPages(0, 10);
drawUpdatedPagesTable($updates['result'], true);
 ?>
<p><a href="/updates">Complete List...</a></p>
<h4 style="clear:both">Updated galleries</h4>
<hr/>
<?php
printFrontpageRecent();
?>
<p><a href="/gallery/recent">Complete List...</a></p>
<? echo getConfigVariable('index-bottom'); ?>
<?php include_once("common/footer.php"); ?>