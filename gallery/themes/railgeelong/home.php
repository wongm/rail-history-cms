<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

// trick zenpage into displaying the HOME page
//zenpage_setup_page('home');

include_once("../common/dbConnection.php");
include_once("../common/formatting-functions.php");
include_once("../common/updates-functions.php");

$pageHeading = "Welcome";
include_once('header.php'); 
?>
<div id="headbar">
	<div class="link">Welcome</div>
	<div class="search"><?php drawHeadbarSearchBox(); ?></div>
</div>
<?php include_once('midbar.php'); ?>
<div id="frontpage">
<script type="text/javascript" src="/common/js/frontpage.js"></script>
<div id="headerpane" class="photo-right">
<img id="randomimage" height="267" width="400" alt="Random image" title="Random image" src="/images/frontpage/E105_0086.jpg"/>
<span id="randomcaption">Random image</span>
</div>
<h3 class="intro">Rail Geelong - hopefully everything you ever wanted to know about the history of the railways of Geelong and District, and then some.</h3>
<p class="intro">Currently detailed histories are in place for the Melbourne - Geelong - Warrnambool, Geelong - Ballarat, Maribyrnong River Line, Newport Power Station, Altona, Fyansford,  Cunningham Pier, Queenscliff, Geelong Racecourse, and Mortlake railway lines. Histories of the locations on the lines themselves have also been completed in various levels of detail.<br/><br/>
Any comments or feedback is welcomed via the <a href="/contact.php">contact form</a>.</p>
<h4 style="clear:both">Site news</h4>
<hr/>
<table id="news" class="linedTable">	
<?php

add_context(ZP_ZENPAGE_NEWS_DATE);

$newsCount = 0;
while (next_news() && $newsCount < 5): ;
	if ($newsCount%2 == '0')
	{
		$style = 'odd';
	}
	else
	{
		$style = 'even';
	}
	?>
<tr class="<?php echo $style; ?>">
	<td class="d" valign="top"><?php printNewsDate();?></td>
	<td><h4><?php echo getNewsTitle(); ?></h4>
		<?php echo getNewsContent(true); ?>
	</td>
</tr>
<?php
	$newsCount++;
  endwhile;
?>
</table>
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
<table class="centeredTable">
<tbody>
<?php 
$latestalbums = query_full_array("SELECT i.filename, i.date, a.folder, a.title FROM " . prefix('images'). " i INNER JOIN " . prefix('albums'). " a ON i.albumid = a.id GROUP BY i.albumid, DATE(i.date) ORDER BY i.date DESC LIMIT 6");

$albumCount = 1;
foreach ($latestalbums as $latestalbum) {
	
	if (($albumCount % 3) == 1)
	{
		echo "<tr>";
	}
	
	$folderpath = "/gallery/" . $latestalbum['folder'];
	$foldername = get_language_string($latestalbum['title'], null);
	$thumbUrl = replace_filename_with_cache_thumbnail_version($latestalbum['filename']);
	$thumbnailURL = "/gallery/cache/" . $latestalbum['folder'] . "/$thumbUrl";
	
	echo '<td class="image">';
	echo "	<a href=\"" . htmlspecialchars($folderpath)."\" title=\"" . html_encode($foldername) . "\">\n";
	echo "	<img src=\"" . $thumbnailURL . "\" alt=\"" . html_encode($foldername) . "\" /></a>\n";
	echo "	<h4><a href=\"".htmlspecialchars($folderpath)."\" title=\"" . html_encode($foldername) . "\">$foldername</a></h4>\n";
	echo "	<small>". zpFormattedDate(getOption('date_format'),strtotime($latestalbum['date']))."</small>\n";
	echo "</td>\n";
		
	if (($albumCount % 3) == 0)
	{
		echo "</tr>";
	}
	
	$albumCount++;
}
?>
</tbody></table>
<p><a href="/gallery/recent">Complete List...</a></p>
<h4 style="clear:both">Coming Soon...</h4>
<hr/>
<?php 

printPageContent();
?>
Melbourne to Geelong and on to Warrnambool has now been covered, along with as well the various branches around Melbourne and Geelong. Geelong to Ballarat is currently in the works, with the Gheringhap to Maroona and Moriac to Wensleydale lines also partly researched.
</div>
<?php
include("footer.php"); 
?>