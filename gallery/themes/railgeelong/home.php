<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

// trick zenpage into displaying the HOME page
zenpage_setup_page('home');

include_once("../common/dbConnection.php");
include_once("../common/formatting-functions.php");
include_once("../common/updates-functions.php");

$pageHeading = "Welcome";
include_once('header.php'); 
?>
<div id="frontpage">
<script type="text/javascript" src="/common/jquery-1.2.2.pack.js"></script>
<script type="text/javascript" src="/common/frontpage.js"></script>
<div id="headerpane" class="photo-right">
<img id="randomimage" height="267" width="400" alt="Random image" title="Random image" />
<span id="randomcaption">Random image</span>
</div>
<h3 class="intro">Rail Geelong - hopefully everything you ever wanted to know about the history of the railways of Geelong and District, and then some.</h3>
<p class="intro">Currently detailed histories are in place for the Melbourne - Geelong - Warrnambool, Geelong - Ballarat, Maribyrnong River Line, Newport Power Station, Altona, Fyansford,  Cunningham Pier, Queenscliff, Geelong Racecourse, and Mortlake railway lines. Histories of the locations on the lines themselves have also been completed in various levels of detail.<br/><br/>
Any comments or feedback is welcomed via the <a href="/contact.php">contact form</a>.</p>
<? drawHeadbarSearchBox("Looking for something?"); ?>
<h4 style="clear:both">Site news</h4>
<hr/>
<table id="news" class="linedTable">	
<?php
while (next_news() && $i < 5): ;
	if ($i%2 == '0')
	{
		$style = 'class="x"';
	}
	else
	{
		$style = 'class="y"';
	}
	?>
<tr <? echo $style; ?>>
	<td class="d" valign="top"><?php printNewsDate();?></td>
	<td><h4><?php echo getNewsTitle(); ?></h4>
		<p><?php echo getNewsContent(true); ?></p>
	</td>
</tr>
<?php
	$i++;
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
$i = 1;

foreach ($latestalbums as $latestalbum) {
	
	if (($i % 3) == 1)
	{
		echo "<tr>";
	}
	
	$folderpath = "/gallery/" . $latestalbum['folder'];
	$foldername = $latestalbum['title'];
	$thumbUrl = replace_filename_with_cache_thumbnail_version($latestalbum['filename']);
	$thumbnailURL = "/gallery/cache/" . $latestalbum['folder'] . "/$thumbUrl";
	
	echo '<td class="image">';
	echo "<a href=\"" . htmlspecialchars($folderpath)."\" title=\"" . html_encode($foldername) . "\">\n";
	echo "<img src=\"" . $thumbnailURL . "\" alt=\"" . html_encode($foldername) . "\" /></a>\n";
	echo "<h4><a href=\"".htmlspecialchars($folderpath)."\" title=\"" . html_encode($foldername) . "\">$foldername</a></h4>\n";
	echo "<small>". zpFormattedDate(getOption('date_format'),strtotime($latestalbum['date']))."</small>";
		
	if (($i % 3) == 0)
	{
		echo "</tr>";
	}
	
	$i++;
}
?>
</tbody></table>
<p><a href="/gallery/recent">Complete List...</a></p>
<h4 style="clear:both">Coming Soon...</h4>
<hr/>
<p><?php printPageContent(); ?></p>
</div>
<?php
include("footer.php"); 
?>