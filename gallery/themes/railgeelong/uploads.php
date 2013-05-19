<?php

$description = "images ";

// admin only magic for photostream
if (isset($_REQUEST['caption'])) {
	$pageTypeModifier = $_REQUEST['caption'];
	$limitSql = "i.title REGEXP '_[0-9]{4}' OR i.title REGEXP 'DSCF[0-9]{4}'";
	
	// images without a caption
	if ($pageTypeModifier == 'images') {
		$description = "uncaptioned images ";
		setCustomPhotostream($limitSql);
	// albums that have at least one image without a caption
	} else if ($pageTypeModifier == 'albums') {
		$description = "albums with uncaptioned images ";
		setCustomPhotostream($limitSql, "albumid");
	}
}

$breadcrumb = 'Recent Uploads';
$pageTitle = " - $breadcrumb";
include_once('header.php'); 
?>
<div id="headbar">
	<div class="link"><a href="/">Home</a> &raquo; <a href="<?=getGalleryIndexURL();?>" title="Gallery Index"><?=getGalleryTitle();?></a> &raquo; <a href="/gallery/recent"><?php echo $breadcrumb; ?></a></div>
	<div class="search"><? printSearchForm(); ?></div>
</div>
<?php include_once('midbar.php'); ?>
<div class="topbar">
	<h3><?php echo $breadcrumb; ?></h3>
</div>
<p><?php echo getNumberCurrentDisplayedRecords("Displaying $description", ""); ?></p>
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
			<div class="imagethumb"><a href="<?=getImageLinkURL();?>" title="<?=getImageTitle();?>">
			 <?php printImageThumb(getImageTitle()); ?></a></div>
			<div class="imagetitle">
				<h4><a href="<?=getImageLinkURL();?>" title="<?=getImageTitle();?>"><?php printImageTitle(); ?></a></h4>
		<?php echo printImageDescWrapped(); ?>
				<small><?php printImageDate(); ?><br/><? if(function_exists(printHitCounter)) { printHitCounter($_zp_current_image); } ?></small>
			</div>
		</td>
	<?php 
	// neater for when only 4 items
	if ($i == 2 || ($num == 4 && $i == 1))
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
include_once('footer.php'); 
?>