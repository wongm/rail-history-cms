<?php require_once("common/definitions.php");
require_once("common/formatting-functions.php");
require_once("common/lineguide-functions.php");

$pageTitle = 'Sitemap';
require_once("common/header.php");
?>
<div id="headbar">
	<div class="link"><a href="/">Home</a> &raquo; Sitemap</div>
	<div class="search"><?php drawHeadbarSearchBox(); ?></div>
</div>
<?php require_once("common/midbar.php"); ?>
<h3>Sitemap</h3>
<div id="sitemap">
<p>A guide to all the pages on this site.</p>
<ul>
<li><a href="/index.php">Home</a></li>
<li><a href="/news">News</a></li>
<li><a href="/updates">Updates</a></li>
<li><a href="/aerial.php?section=overview">Aerial Explorer</a></li>
<?php
// get regions
$regions = query_full_array("SELECT * FROM articles WHERE line_id = '-1'");
$numberOfRows = sizeof($regions);
if ($numberOfRows>0) 
{
	//echo "<ul>\n";
	
	for ($i = 0; $i < $numberOfRows; $i++)
	{
		echo '<li><a href="region/'.stripslashes($regions[$i]["link"]).'/">'.stripslashes($regions[$i]["title"]).'</a></li>';
	}
	
	//echo "</ul></li>\n";
}

// draw all lines and their subsections
echo "<li><a href=\"/lineguides/\">Lineguide Overview</a>\n";
drawAllLineguideDotpoints('sitemap');
?>
<li><a href="/locations/">Location Overview</a></li>
<ul>
<li><a href="/locations/#search">Search</a>
<li><a href="/locations/stations/">Stations</a></li>
<li><a href="/locations/industries/">Industries</a></li>
<li><a href="/locations/signalboxes/">Signal Boxes</a></li>
<li><a href="/locations/yards/">Yards</a></li>
<li><a href="/locations/misc/">Miscellaneous</a></li>
</ul></li>
<?php
// get all articles
$articles = query_full_array("SELECT * FROM articles WHERE link != '' AND `line_id` = '0'");
$numberOfRows = sizeof($articles);
if ($numberOfRows>0) 
{?>
<li><a href="/articles/">Articles Listing</a>
<ul>
<?php 
	for ($i = 0; $i < $numberOfRows; $i++)
	{
		echo '<li><a href="/article/'.stripslashes($articles[$i]["link"]).'/">'.stripslashes($articles[$i]["title"]).'</a></li>';
	}?>
</ul></li>
<?php 
}
?>
<li><a href="/gallery/">Gallery</a>
<ul>
	<li><a href="/gallery/recent/">Recent uploads</a></li>
</ul></li>
<li><a href="<?php echo CONTACT_URL_PATH ?>">Contact</a></li>
<li><a href="<?php echo CREDITS_URL_PATH ?>">Credits and Acknowledgements</a></li>
<li><a href="<?php echo SOURCES_URL_PATH ?>">Sources</a></li>
<li><a href="<?php echo SITEMAP_URL_PATH ?>">Sitemap</a></li>
</ul></div>
<?php
require_once("common/footer.php");
?>
