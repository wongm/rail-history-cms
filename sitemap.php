<?php include_once("common/dbConnection.php");
include_once("common/formatting-functions.php");
include_once("common/lineguide-functions.php");

$pageTitle = 'Sitemap';
include_once("common/header.php");?>
<div id="sitemap">
<p>A guide to all the pages on this site.</p>
<ul>
<li><a href="/index.php">Home</a></li>
<li><a href="/aerial.php?section=overview">Aerial Explorer</a></li>
<?
// get regions
$articles = MYSQL_QUERY("SELECT * FROM articles WHERE line_id = '-1'");
$numberOfRows = MYSQL_NUMROWS($articles);
if ($numberOfRows>0) 
{
	//echo "<ul>\n";
	
	for ($i = 0; $i < MYSQL_NUM_ROWS($articles); $i++)
	{
		echo '<li><a href="region/'.stripslashes(MYSQL_RESULT($articles,$i,"link")).'">'.stripslashes(MYSQL_RESULT($articles,$i,"title")).'</a></li>';
	}
	
	//echo "</ul></li>\n";
}

// draw all lines and their subsections
echo "<li><a href=\"/lineguide\">Lineguide Overview</a>\n";
drawAllLineguideDotpoints('sitemap');
?>
<li><a href="/locations/">Location Overview</a></li>
<ul>
<li><a href="/locations/#search">Search</a>
<li><a href="/locations/stations">Stations</a></li>
<li><a href="/locations/industries">Industries</a></li>
<li><a href="/locations/signalboxes">Signal Boxes</a></li>
<li><a href="/locations/yards">Yards</a></li>
<li><a href="/locations/misc">Miscellaneous</a></li>
</ul></li>
<?
// get all articles
$articles = MYSQL_QUERY("SELECT * FROM articles WHERE link != '' AND `line_id` = '0'");
$numberOfRows = MYSQL_NUMROWS($articles);
if ($numberOfRows>0) 
{?>
<li><a href="articles.php">Articles Listing</a>
<ul>
<?	
	for ($i = 0; $i < MYSQL_NUM_ROWS($articles); $i++)
	{
		echo '<li><a href="/articles/'.stripslashes(MYSQL_RESULT($articles,$i,"link")).'">'.stripslashes(MYSQL_RESULT($articles,$i,"title")).'</a></li>';
	}?>
</ul></li>
<?	
}
?>
<li><a href="/gallery/">Gallery</a>
<ul>
	<li><a href="/gallery/recent/">Updates</a></li>
	<li><a href="/gallery/search/">Search</a></li>
</ul></li>
<li><a href="/news">News</a></li>
<li><a href="/contact.php">Contact</a></li>
<li><a href="/credits.php">Credits and Acknowledgements</a></li>
<li><a href="/sources.php">Sources</a></li>
<li><a href="/sitemap.php">Sitemap</a></li>
</ul></div>
<?
include_once("common/footer.php");
?>
