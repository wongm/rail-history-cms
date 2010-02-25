<?php include_once("common/dbConnection.php");
include_once("common/formatting-functions.php");

$pageTitle = 'Credits and Acknowledgements';
include_once("common/header.php");?>
<div id="credits">
<h4><? echo $pageTitle; ?></h4>
<hr/>
<?
echo '<p>'.getConfigVariable('credits').'</p></div>';

include_once("common/footer.php"); ?>