<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die();

$pageTitle = ' - '.getAlbumTitle();
include_once('header.php'); ?>

<div id="headbar">
	<div class="link"><a href="/">Home</a> &raquo; 
		<a href="<?php echo getGalleryIndexURL();?>" title="Gallery Index"><?php echo getGalleryTitle();?></a> &raquo; 
      	<?php printParentBreadcrumb('', ' » ', ' » '); ?>
      	<?php echo getAlbumTitle();?></div>
	<div class="search"><?php printSearchForm(); ?></div>
</div>
<?php include_once('midbar.php'); ?>
  <div class="topbar">
  	<h3><?php printMWEditableAlbumTitle(true);?></h3>
  	<?php printMWEditableAlbumDesc(true); ?>
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
      <div class="albumthumb"><a href="<?php echo getAlbumURL();?>" title="<?php echo getAlbumTitle();?>">
        <?php printAlbumThumbImage(getAlbumTitle()); ?></a></div>
      <div class="albumtitle">
      	<h4><a href="<?php echo getAlbumURL();?>" title="<?php echo getAlbumTitle();?>"><?php printAlbumTitle(); ?></a></h4>
        <small><?php printAlbumDate(); ?><br/><?php if(function_exists(printHitCounter)) { printHitCounter($_zp_current_album); } ?></small></div>
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
  $i = 0;
  while (next_image()):
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
        <small><?php printImageDate(); ?><br/><?php if(function_exists(printHitCounter)) { printHitCounter($_zp_current_image); } ?></small>
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
<?php endif; 

printPageListWithNav("« " . gettext("Previous"), gettext("Next") . " »");
if(function_exists(formatHitcounter)) { 
	echo "<p>" . formatHitcounter(incrementAndReturnHitCounter('album'), false) . "</p>"; 
}

include_once('footer.php'); ?>