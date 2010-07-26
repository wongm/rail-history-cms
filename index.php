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
drawHeaderPic('headers', rand(1, RANDOM_MAX), $caption);
?>
<h3 class="intro">Rail Geelong - hopefully everything you ever wanted to know about the history of the railways of Geelong and District, and then some.</h3>
<p class="intro">Currently detailed histories are in place for the Melbourne - Geelong - Warrnambool, Geelong - Ballarat, Maribyrnong River Line, Newport Power Station, Altona, Fyansford,  Cunningham Pier, Queenscliff, Geelong Racecourse, and Mortlake railway lines. Histories of the locations on the lines themselves have also been completed in various levels of detail.<br/><br/>
Any comments or feedback is welcomed via the <a href="/contact.php">contact form</a>.</p>
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
<h4 style="clear:both">Coming Soon...</h4>
<hr/>
<p>Melbourne to Geelong and on to Warrnambool has now been covered, along with as well the various branches around Melbourne and Geelong. Geelong to Ballarat is currently in the works, with the Gheringhap to Maroona and Moriac to Wensleydale lines also partly researched.</p>
<?php include_once("common/footer.php"); ?>