<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die();

$pageTitle = ' - '.getAlbumTitle();
require_once('common/header.php');
?>
<div id="headbar">
	<div class="link"><a href="/">Home</a> &raquo; <a href="/news/">News</a></div>
	<div class="search"><?php printSearchForm(); ?></div>
</div>
<?php 

require_once('common/midbar.php');

// single news article
if(is_NewsArticle()) { 
?>
<div class="topbar"><h2><?php printNewsTitle(); ?></h2></div>
<div id="news">
	<div class="newsarticle"> 
		<div class="newsarticlecredit"><span class="newsarticlecredit-left"><?php printNewsDate();?> | </span> <?php printNewsCategories(", ",gettext("Categories: "),"newscategories"); ?></div>
		<p><?php printNewsContent(); ?></p>
	</div>
<?php 
// COMMENTS TEST


	drawNewsNextables();
	echo "<p id=\"hitcounter\">Viewed ".getHitcounter()." times.</p>";

} else {
// news article loop
?>
<div id="news">
<?php
  while (next_news()): ;?> 
	<div class="newsarticle"> 
    	<h3><?php echo getNewsTitle(); ?></h3>
        <div class="newsarticlecredit">
        <small><?php printNewsDate();?></small>
		</div>
    	<div class="newsarticlecontent"><?php echo getNewsContent(true); ?></div>
 	</div>	
<?php
  endwhile; 
  
?>
<div class="pagelist">
<?php printNewsPageListWithNav(gettext("Next") . " »", "« " . gettext("Previous")); ?>
</div>
<?php
} 
?>
</div>
<?php
require_once('common/footer.php'); 
?>