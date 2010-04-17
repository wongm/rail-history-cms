<?php 
define('WP_USE_THEMES', false);
require('./wp-blog-header.php'); 
$pageTitle = "News";
$pageHeading = "News";
include_once("../common/header.php"); ?>
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />

<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php wp_get_archives('type=monthly&format=link'); ?>
<?php //comments_popup_script(); // off by default ?>
<?php wp_head();
$i = 0; ?>
<table class="nextables">
<tr><td><?php previous_posts_link('&laquo; Previous Page'); ?></td><td align="right"><?php next_posts_link('Next Page &raquo;'); ?></td></tr>
</table>
<br><table id="news" class="linedTable">
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<?php 

$i++;
if ($i % 2)
	$style = "x";
else
	$style = "y";
?>
<tr class="post <?=$style?>" id="post-<?php the_ID(); ?>">
	<td style="width:11em;" valign="top"><?php the_date(); ?><div class="meta"><?php edit_post_link(__('Edit This')); ?></td>
	<td class="storycontent">
		<h4 class="storytitle"><?php the_title(); ?></h4>
		<?php the_content(__('(more...)')); ?>
	</td>
</tr>
<?php endwhile; else: ?>
<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
<?php endif; ?>
</table>
<table class="nextables">
<tr><td><?php previous_posts_link('&laquo; Previous Page'); ?></td><td align="right"><?php next_posts_link('Next Page &raquo;'); ?></td></tr>
</table>
<?php include_once("../common/dbConnection.php");
///include_once("../common/functions.php");
include_once("../common/footer.php"); ?>