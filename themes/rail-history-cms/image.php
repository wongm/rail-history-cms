<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

$pageTitle = ' - '.getImageTitle();
require_once('common/header.php'); ?>

  <div id="headbar">
	<div class="link"><a href="/">Home</a> &raquo; 
		<a href="<?php echo getGalleryIndexURL();?>" title="Gallery Index">Gallery</a> &raquo; 
		<?php printParentBreadcrumb('', ' » ', ' » '); ?>
		<a href="<?php echo getAlbumURL();?>" title="<?php echo getAlbumTitle();?> Index"><?php echo getAlbumTitle();?></a>
	</div>
	<div class="search"><?php printSearchForm(); ?></div>
  </div>

<?php require_once('common/midbar.php'); ?>
  
  <div class="topbar">
  	<h3><?php printMWEditableImageTitle(true);?></h3>
  	<?php printMWEditableImageDesc(true); ?>
  </div>
  
  <table class="centeredTable">
	  <tr><td class="imageDisplay">
        <a href="<?php echo getFullImageURL();?>" rel="lightbox" title="<?php echo getImageTitle();?>">
        <?php printDefaultSizedImage(getImageTitle()); ?></a><br/>
        <a href="<?php echo getFullImageURL();?>" rel="lightbox" title="<?php echo getImageTitle();?>">View full size photo (<?php echo getFullWidth()?>px by <?php echo getFullHeight()?>px)</a><br/>
      </td></tr>
  </table>
<?php 
if(function_exists("printEXIFData")) { printEXIFData(); } ?>
<?php if (hasPrevImage() or hasNextImage()) { ?>    
  <div class="pagelist"><table><tr id="thumbnav"><td>
    <?php if (hasPrevImage()) { ?>
    <a class="prev" href="<?php echo getPrevImageURL();?>" title="Previous Image"><span>&laquo;</span> Previous</a>
    </td><td>
    <a class="next" href="<?php echo getPrevImageURL();?>" title="<?php echo getPrevImageTitle();?>"><img src="<?php echo getPrevImageThumb();?>" alt="<?php echo getPrevImageTitle();?>" /></a>
    <?php } else { echo "</td><td>"; } ?>
    </td><td>
    <?php if (hasNextImage()) { ?>
    <a class="prev" href="<?php echo getNextImageURL();?>" title="<?php echo getNextImageTitle();?>"><img src="<?php echo getNextImageThumb();?>" alt="<?php echo getNextImageTitle();?>"/></a>
    </td><td>
    <a class="next" href="<?php echo getNextImageURL();?>" title="Next Image">Next <span>&raquo;</span></a>
    <?php } else { echo "</td><td>"; } ?>
  </td></tr></table></div>
<?php } 

if(function_exists("printImageMarkupFields")) {
	printImageMarkupFields();
}

require_once('common/footer.php'); 
?>