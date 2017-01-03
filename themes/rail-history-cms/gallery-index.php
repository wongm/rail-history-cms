<?php 

require_once('common/header.php'); ?>

	<div id="headbar">
		<div class="link"><a href="/">Home</a> &raquo; Gallery</div>
		<div class="search"><?php printSearchForm(); ?></div>
	</div>
	
<?php require_once('common/midbar.php'); ?>

  <div class="topbar">
  	<h3>Welcome to the Gallery</h3>
  	<p>All photographs copyright Marcus Wong unless otherwise noted.</p>
  </div>
  
  <?php if(hasNextPage() || hasPrevPage())
  {	?>
  <table class="nextables"><tr><td>
    <?php if (hasPrevPage()) { ?> <a class="prev" href="<?php echo getPrevPageURL();?>" title="Previous Page"><span>&laquo;</span> Previous</a> <?php } ?>
    <?php if (hasNextPage()) { ?> <a class="next" href="<?php echo getNextPageURL();?>" title="Next Page">Next <span>&raquo;</span></a><?php } ?>
  </td></tr></table>
  <?php } ?>
  
  <table class="centeredTable">
  
  <?php 
  $i = 0;
  
  while (next_album()):
  if ($i == 0)
  {
	  echo "<tr>\n";
  } ?>
    <td class="album" valign="top">
      <div class="albumthumb"><a href="<?php echo getAlbumURL();?>" title="<?php echo getAlbumTitle();?>">
        <?php printAlbumThumbImage(getAlbumTitle()); ?></a></div>
      <div class="albumtitle"><h4><a href="<?php echo getAlbumURL();?>" title="<?php echo getAlbumTitle();?>">
        <?php printAlbumTitle(); ?></a></h4><small><?php printAlbumDate(); ?></small></div>
      <div class="desc"><?php printAlbumDesc(); ?></div>
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
<?php require_once('common/footer.php'); ?>