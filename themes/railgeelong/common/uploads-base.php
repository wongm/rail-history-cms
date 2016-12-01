<?php

$description = "images ";
$breadcrumb = 'Recent Uploads';
$pageTitle = " - $breadcrumb";
$totalImages = getNumPhotostreamImages();
require_once('header.php'); 
?>
<div id="headbar">
	<div class="link"><a href="/">Home</a> &raquo; <a href="<?php echo getGalleryIndexURL();?>" title="Gallery Index">Gallery</a> &raquo; <a href="/gallery/recent"><?php echo $breadcrumb; ?></a></div>
	<div class="search"><?php printSearchForm(); ?></div>
</div>
<?php require_once('midbar.php'); ?>
<div class="topbar">
	<h3><?php echo $breadcrumb; ?></h3>
</div>
<p><?php echo getNumberCurrentDisplayedRecords("Displaying $description", " of $totalImages"); ?></p>
<!-- Images -->
<table class="centeredTable">
	<?php	 
	$i = 0;
	while (next_photostream_image()):
	if ($i == 0)
	{
		echo "<tr>\n";
	} 
	global $_zp_current_image;
?>
	<td class="image" valign="top">
			<div class="imagethumb"><a href="<?php echo getImageURL();?>" title="<?php echo getImageTitle();?>">
			 <?php printImageThumb(getImageTitle()); ?></a></div>
			<div class="imagetitle">
				<h4><a href="<?php echo getImageURL();?>" title="<?php echo getImageTitle();?>"><?php printImageTitle(); ?></a></h4>
		<?php echo printImageDescWrapped(); ?>
				<p>In album: <a href="<?php echo getAlbumURL();?>" title="View parent album"><?php echo getAlbumTitleForPhotostreamImage(); ?></a></p>
				<small><?php printImageDate(); ?><br/><?php if(function_exists('printHitCounter')) { printHitCounter($_zp_current_image); } ?></small>
			</div>
		</td>
	<?php 
	// neater for when only 4 items
	if ($i == 2 || ($totalImages == 4 && $i == 1))
	{
		echo "</tr>\n";
		$i = 0;
	}
	else
	{
		$i++; 
	}
	endwhile;
	
	if ($i != 0)
	{
		echo "</tr>\n";
	} ?>
</table>
<?php
printPhotostreamPageListWithNav("« " . gettext("Previous"), gettext("Next") . " »");
require_once('footer.php'); 
?>