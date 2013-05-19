<?php
include_once("common/dbConnection.php");
include_once("common/updates-functions.php");
include_once("common/formatting-functions.php");

$updatePageIndex = $_REQUEST['updated'];
$pageTitle = 'Updates';

if ($updatePageIndex == '' OR is_numeric($updatePageIndex))
{
	$maxRowsPerPage = 50;
	$index = ($updatePageIndex*$maxRowsPerPage)-$maxRowsPerPage;
	$updatedPages = getUpdatedPages($index, $maxRowsPerPage);
	$updatedPages['maxRowsPerPage'] = $maxRowsPerPage;
	$updatedPages['page'] = $updatePageIndex;
	$updatedPages['index'] = $index;
	
	if ($updatedPages["numberOfRows"] > 0)
	{
		include_once("common/header.php");
?>
<div id="headbar">
	<div class="link"><a href="/">Home</a> &raquo; <a href="/updates">Updates</a></div>
	<div class="search"><? drawHeadbarSearchBox(); ?></div>
</div>
<?php 
		include_once("common/midbar.php");
		drawPageOfUpdated($updatedPages);
		include_once("common/footer.php");
	}
	else
	{
		draw404InvalidSubpage('updates');
	}	
}
else
{
	draw404InvalidSubpage('updates');
}
?>