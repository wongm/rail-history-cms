<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die();

include_once("common/dbConnection.php");
include_once("common/updates-functions.php");
include_once("common/formatting-functions.php");

$pageTitle = ' - Updates';

$pageNumber = 1;
if($_REQUEST['page'] > 0)
{
	$pageNumber = $_REQUEST['page'];
}
$maxRowsPerPage = 50;
$index = ($pageNumber*$maxRowsPerPage)-$maxRowsPerPage;
$updatedPages = getUpdatedPages($index, $maxRowsPerPage);
$updatedPages['maxRowsPerPage'] = $maxRowsPerPage;
$updatedPages['page'] = $pageNumber;
$updatedPages['index'] = $index;

if ($updatedPages["numberOfRows"] > 0)
{
	include_once("header.php");
?>
<div id="headbar">
	<div class="link"><a href="/">Home</a> &raquo; <a href="/updates">Updates</a></div>
	<div class="search"><?php printSearchForm(); ?></div>
</div>
<?php 
	include_once("midbar.php");
	drawPageOfUpdated($updatedPages);
	include_once("footer.php");
}
else
{
	draw404InvalidSubpage('updates');
}
?>