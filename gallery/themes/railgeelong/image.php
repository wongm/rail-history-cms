<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

$pageTitle = ' - '.getImageTitle();
include_once('header.php'); ?>

  <div id="headbar">
	<div class="link"><a href="/">Home</a> &raquo; 
		<a href="<?=getGalleryIndexURL();?>" title="Gallery Index"><?=getGalleryTitle();?></a> &raquo; 
		<?php printParentBreadcrumb('', ' » ', ' » '); ?>
		<a href="<?=getAlbumURL();?>" title="<?=getAlbumTitle();?> Index"><?=getAlbumTitle();?></a>
	</div>
	<div class="search"><?php printSearchForm(); ?></div>
  </div>

<?php include_once('midbar.php'); ?>
  
  <div class="topbar">
  	<h3><?php printMWEditableImageTitle(true);?></h3>
  	<?php printMWEditableImageDesc(true); ?>
  </div>
  
  <table class="centeredTable">
	  <tr><td class="imageDisplay">
        <a href="<?=getFullImageURL();?>" rel="lightbox" title="<?=getImageTitle();?>">
        <?php printDefaultSizedImage(getImageTitle()); ?></a><br/>
        <a href="<?=getFullImageURL();?>" rel="lightbox" title="<?=getImageTitle();?>">View full size photo (<?=getFullWidth()?>px by <?=getFullHeight()?>px)</a><br/>
      </td></tr>
  </table>
<?php 
if(function_exists("printEXIFData")) { printEXIFData(); } ?>
<?php if (hasPrevImage() or hasNextImage()) { ?>    
  <div class="pagelist"><table><tr id="thumbnav"><td>
    <?php if (hasPrevImage()) { ?>
    <a class="prev" href="<?=getPrevImageURL();?>" title="Previous Image"><span>&laquo;</span> Previous</a>
    </td><td>
    <a class="next" href="<?=getPrevImageURL();?>" title="<?=getPrevImageTitle();?>"><img src="<?=getPrevImageThumb();?>" alt="<?=getPrevImageTitle();?>" /></a>
    <?php } else { echo "</td><td>"; } ?>
    </td><td>
    <?php if (hasNextImage()) { ?>
    <a class="prev" href="<?=getNextImageURL();?>" title="<?=getNextImageTitle();?>"><img src="<?=getNextImageThumb();?>" alt="<?=getNextImageTitle();?>"/></a>
    </td><td>
    <a class="next" href="<?=getNextImageURL();?>" title="Next Image">Next <span>&raquo;</span></a>
    <?php } else { echo "</td><td>"; } ?>
  </td></tr></table></div>
<?php } 

if(function_exists("printImageMarkupFields")) {
	printImageMarkupFields();
}

include_once('footer.php'); 
?>