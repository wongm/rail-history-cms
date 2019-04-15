<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

// trick zenpage into displaying the HOME page
//zenpage_setup_page('home');

require_once("common/definitions.php");
require_once("common/formatting-functions.php");
require_once("common/updates-functions.php");

$pageHeading = "Welcome";
require_once('common/header.php'); 
?>
<div id="headbar">
	<div class="link">Welcome</div>
	<div class="search"><?php drawHeadbarSearchBox(); ?></div>
</div>
<?php require_once('common/midbar.php'); ?>
<div id="frontpage">
<script type="text/javascript" src="<?php echo $_zp_themeroot ?>/js/frontpage.js"></script>
<div id="headerpane" class="photo-right">
<img id="randomimage" height="267" width="400" alt="Random image" title="Random image" src="/images/frontpage/E105_0086.jpg"/>
<span id="randomcaption">Random image</span>
</div>
<h3 class="intro">Rail Geelong - hopefully everything you ever wanted to know about the history of the railways of Geelong and District, and then some.</h3>
<p class="intro"><?php echo printPageContent('home-top'); ?><br/><br/>
Any comments or feedback is welcomed via the <a href="<?php echo CONTACT_URL_PATH ?>">contact form</a>.</p>
<h4 style="clear:both">Site news</h4>
<hr/>
<table id="news" class="linedTable">	
<?php

add_context(ZP_ZENPAGE_NEWS_DATE);

$newsCount = 0;
if (function_exists('next_news')) {
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
}
?>
</table>
<p><a href="/news/">Complete List...</a></p>
<h4 style="clear:both">Updated content</h4>
<hr/>
<?php
$updates = getUpdatedPages(0, 10);
drawUpdatedPagesTable($updates['result'], true);
 ?>
<p><a href="/updates/">Complete List...</a></p>
<h4 style="clear:both">Updated galleries</h4>
<hr/>
<table class="centeredTable">
<tbody>
<?php 

setCustomPhotostream("", "i.albumid, DATE(i.date)", "i.date DESC");

for ($albumCount = 1; $albumCount < 7; $albumCount++) {
	next_photostream_image();
	if (($albumCount % 3) == 1)
	{
		echo "<tr>";
	}
		
	echo '<td class="image">';
	echo "	<a href=\"" . getAlbumURL()."\" title=\"" . getAlbumTitleForPhotostreamImage() . "\">\n";
	printImageThumb(getAlbumTitleForPhotostreamImage());
	echo "	</a>\n";
	echo "	<h4><a href=\"" . getAlbumURL() . "\" title=\"" . getAlbumTitleForPhotostreamImage() . "\">" . getAlbumTitleForPhotostreamImage() . "</a></h4>\n";
	echo "	<small>" . printImageDate() . "</small>\n";
	echo "</td>\n";
		
	if (($albumCount % 3) == 0)
	{
		echo "</tr>";
	}
}
?>
</tbody></table>
<p><a href="/gallery/recent/">Complete List...</a></p>
<h4 style="clear:both">Coming Soon...</h4>
<hr/>
<p><?php printPageContent('home-bottom'); ?></p>
</div>
<?php
include("common/footer.php"); 
?>