<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die();

$pageTitle = ' - '.getAlbumTitle();
include_once('header.php'); ?>

<table class="headbar">
	<tr><td><a href="/">Home</a> &raquo; 
		<a href="<?=getGalleryIndexURL();?>" title="Gallery Index"><?=getGalleryTitle();?></a> &raquo; 
      	<?php printParentBreadcrumb('', ' &raquo; ', ' &raquo; '); ?>
      	<?php printAlbumTitle(true);?>
	</td>
    <td id="righthead"><?php printSearchBreadcrumb(true); ?></td></tr>
</table>

  <div class="topbar">
  	<h3>Album: <?=getAlbumTitle();?></h3>
  	<?php printAlbumDescAndLink(true); ?>
  </div>
  
  <?php
  $num = getNumAlbums(); 
  if ($num > 0) // Only print if we have images
  {
?>
  <!-- Sub-Albums -->
  <table class="centeredTable">
  <?php 
  // neater for when only 4 items
  if ($num == 4)
  {
	  $i = 1;
  }
  else
  {
	  $i = 0;
  }
  while (next_album()):
  if ($i == 0)
  {
	  echo "<tr>\n";
  }
  global $_zp_current_album;
?>
    <td class="album" valign="top">
      <div class="albumthumb"><a href="<?=getAlbumLinkURL();?>" title="<?=getAlbumTitle();?>">
        <?php printAlbumThumbImage(getAlbumTitle()); ?></a></div>
      <div class="albumtitle"><h4><a href="<?=getAlbumLinkURL();?>" title="<?=getAlbumTitle();?>">
        <?php printAlbumTitle(); ?></a></h4><small><?php printAlbumDate(); ?><br/><? printHitCounter($_zp_current_album) ?></small></div>
      <div class="albumdesc"><?php printAlbumDesc(); ?></div>
    </td>
  <?php if ($i == 2)
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
  }
  ?>
  </table>
  <?php 
  } // end no album if
  
  $num = getNumImages(); 
  if ($num > 0): /* Only print if we have images. */ ?>
  <!-- Images -->
  <table class="centeredTable">
  <?php 
  // neater for when only 4 items
  if ($num == 4)
  {
	  $i = 1;
  }
  else
  {
	  $i = 0;
  }
  while (next_image()):
  if ($i == 0)
  {
	  echo "<tr>\n";
  } 
  global $_zp_current_image;
?>
  <td class="image" valign="top">
      <div class="imagethumb"><a href="<?=getImageLinkURL();?>" title="<?=getImageTitle();?>">
       <?php printImageThumb(getImageTitle()); ?></a></div>
      <div class="imagetitle"><h4><a href="<?=getImageLinkURL();?>" title="<?=getImageTitle();?>">
        <?php printImageTitle(); ?></a></h4><small><?php printImageDate(); ?><br/><? printHitCounter($_zp_current_image) ?></small></div>
    </td>  
  <?php if ($i == 2)
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
<?php endif; 
  
  	if (hasPrevPage() || hasNextPage())
  	{
?>
<table class="nextables"><tr id="pagelinked"><td>
	<?php if (hasPrevPage()) { ?> <a class="prev" href="<?=getMyPageURL(getPrevPageURL());?>" title="Previous Page"><span>&laquo;</span> Previous</a> <?php } ?>
	</td><td><?php printPageList(); ?></td><td>
	<?php if (hasNextPage()) { ?> <a class="next" href="<?=getMyPageURL(getNextPageURL());?>" title="Next Page">Next <span>&raquo;</span></a><?php } ?>
</td></tr></table>
<?php
	} 
  
  echo "<p>".formatHitcounter(incrementAndReturnHitCounter('album'), false)."</p>";
  
include_once('footer.php'); ?>