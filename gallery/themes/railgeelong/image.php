<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

$pageTitle = ' - '.getImageTitle();
include_once('header.php'); ?>

  <table class="headbar">
	<tr><td><a href="/">Rail Geelong</a> &raquo; 
		<a href="<?=getGalleryIndexURL();?>" title="Gallery Index"><?=getGalleryTitle();?></a> &raquo; 
		<?php printParentBreadcrumb('', ' &raquo; ', ' &raquo; '); ?>
		<a href="<?=getAlbumLinkURL();?>" title="<?=getAlbumTitle();?> Index"><?=getAlbumTitle();?></a> &raquo; 
      	<?php printTruncatedImageTitle(true); ?>
	</td><td id="righthead"><?php printSearchBreadcrumb(true); ?></td></tr>
  </table>
  
  <div class="topbar">
  	<h3>Image: <?=getImageTitle();?></h3>
  	<?php printImageDesc(true); ?>
  </div>
  
  <?php drawWongmImageNextables(); ?>
  
  <table class="centeredTable">
	  <tr><td class="imageDisplay">
        <a href="<?=getFullImageURL();?>" rel="lightbox" title="<?=getImageTitle();?>">
        <?php printDefaultSizedImage(getImageTitle()); ?></a><br/>
        <a href="<?=getFullImageURL();?>" rel="lightbox" title="<?=getImageTitle();?>"><? getSelectedSizedThingy(); ?></a><br/>
      </td></tr>
  </table>
<?php 

printEXIFData();
drawWongmImageNextables();
printForumLink();
include_once('footer.php'); 
?>