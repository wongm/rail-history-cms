<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die();

require_once("common/definitions.php");
require_once("common/updates-functions.php");
require_once("common/formatting-functions.php");

$pageTitle = 'Updates';

$pageNumber = 1;
if(isset($_REQUEST['page']) && $_REQUEST['page'] > 0)
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
	require_once("common/header.php");
?>
<div id="headbar">
	<div class="link"><a href="/">Home</a> &raquo; <a href="/updates/">Updates</a></div>
	<div class="search"><?php printSearchForm(); ?></div>
</div>
<?php 
	require_once("common/midbar.php");
	drawPageOfUpdated($updatedPages);
	require_once("common/footer.php");
}
else
{
	draw404InvalidSubpage('updates');
}
?>