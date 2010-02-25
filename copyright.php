<?php include_once("common/dbConnection.php");
include_once("common/formatting-functions.php");
$pageTitle = 'Copyright';
include_once("common/header.php");?>
<div id="copyright">
<h3><? echo $pageTitle; ?></h3>
<hr/>
<?
// last bit from DB
echo getConfigVariable('copyright');
?>
</div>
<?
include_once("common/footer.php");
?>
