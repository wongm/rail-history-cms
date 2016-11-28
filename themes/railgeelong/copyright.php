<?php include_once("common/dbConnection.php");
include_once("common/formatting-functions.php");
$pageTitle = 'Copyright';
include_once("common/header.php");?>
<div id="headbar">
	<div class="link"><a href="/">Home</a> &raquo; <?php echo $pageTitle; ?></div>
	<div class="search"><?php drawHeadbarSearchBox(); ?></div>
</div>
<?php include_once("common/midbar.php"); ?>
<h3><?php echo $pageTitle; ?></h3>
<div id="copyright">
<p>All content on this site is the copyright of the author Marcus Wong unless otherwise noted.</p>
<p>Sources of information used in the creation of this site are listed on the <a href="/sources.php">Sources</a> page.</p>
<p>Content not produced by myself, but used with permission of the author, is marked as such.</p>
<p><a href="http://www.wordpress.org">News</a> and <a href="http://www.zenphoto.org/">Gallery</a> software is copyright of the respective creators. Themes for these pieces of software have been adapted from existing themes by myself.</p>
<p>All photographs on this site and in the Gallery are by the author unless noted otherwise.</p>
<p>The concept of the <a href="/lineguide.php">Line Guides</a> was inspired by those by Chris Gordon at <a href="http://vicsig.net">http://vicsig.net</a>.</p>

<h3>Linking and Reproduction</h3>
<hr/>
<p>Linking to pages on this site is welcomed. Links to photographs should be to the page that contains them, not to the image itself.</p>
<p>Inquiries about the extension of this research is welcomed.</p>
</div>
<?php
include_once("common/footer.php");
?>
