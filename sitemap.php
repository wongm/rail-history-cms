<?php include_once("common/dbConnection.php");
include_once("common/formatting-functions.php");
include_once("common/lineguide-functions.php");

$pageTitle = 'Sitemap';
include_once("common/header.php");?>
<div id="sitemap">
<p>A guide to all the pages on this site.</p>
<ul>
<li><a href="/index.php">Home</a></li>
<?	
// basic stuff to start off
echo getConfigVariable('sitemap-top');

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

// middle bit from DB
echo getConfigVariable('sitemap-middle');

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

// last bit from DB
echo getConfigVariable('sitemap-bottom');
echo '</ul></div>';
include_once("common/footer.php");
?>
