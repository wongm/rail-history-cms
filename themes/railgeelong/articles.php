<?php

require_once("common/definitions.php");
require_once("common/formatting-functions.php");

$pageTitle = $pageTitleArticles = 'Articles Listing';

require_once("common/header.php");
?>
<div id="headbar">
	<div class="link"><a href="/">Home</a> &raquo; Articles</div>
	<div class="search"><?php drawHeadbarSearchBox(); ?></div>
</div>
<?php require_once("common/midbar.php"); ?>
<h3><?php echo $pageTitleArticles?></h3>
<?php 
$articles = query_full_array("SELECT * FROM articles WHERE link != '' AND `line_id` = '0'");

for ($i = 0; $i < sizeof($articles); $i++)
{
	echo '<h4><a href="/article/'.stripslashes($articles[$i]["link"]).'/">'.stripslashes($articles[$i]["title"]).'</a></h4>';
	echo '<p class="details">'.stripslashes($articles[$i]["description"]).'</p>';
}
require_once("common/footer.php");	
?>