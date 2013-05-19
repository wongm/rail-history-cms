<?php 
include_once("common/dbConnection.php");
include_once("common/formatting-functions.php");

$pageTitle = 'Credits and Acknowledgements';
include_once("common/header.php");
?>
<div id="headbar">
	<div class="link"><a href="/">Home</a> &raquo; <? echo $pageTitle; ?></div>
	<div class="search"><? drawHeadbarSearchBox(); ?></div>
</div>
<?php include_once("common/midbar.php"); ?>
<h3><? echo $pageTitle; ?></h3>
<div id="credits">
<p>I would like to thank the following people for their assistance provided in creating this site.</p>
<p>Andrew Waugh provided signalling information for the majority of stations from his <a href="http://www.vrhistory.com/">Victorian Railways Resources</a> site. I would like to thank him for giving me permission to reproduce this.</p>
<p>Kathleen and Paul Kenny also provided assistance with details for the Queenscliff line. Authors of the book "Trains, Troops, and Tourists: The South Geelong ~ Drysdale ~ Queenscliff Railway" the majority of information on the Queenscliff line has been sourced from their book with permission.</p>
<p>The concept of the <a href="/lineguide.php">Line Guides</a> was inspired by those by Chris Gordon at <a href="http://vicsig.net">http://vicsig.net</a>.</p>
<p>Finally I would like to thank the staff at the State Library and the Geelong Heritage Centre for their assistance in tracking down various documents.</p></div>
<?
include_once("common/footer.php");
?>