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
<table class="headbar">
	<tr><td><a href="/">Home</a> &raquo; <a href="/updates">Updates</a></td>
	<td id="righthead"><? drawHeadbarSearchBox(); ?></td></tr>
</table>
<?
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