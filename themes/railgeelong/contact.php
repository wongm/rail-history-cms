 <?php $pageTitle = "Contact Me";
include_once("common/dbConnection.php");
include_once("common/formatting-functions.php");
include_once("common/header.php"); 
?>
<div id="headbar">
	<div class="link"><a href="/">Home</a> &raquo; <?php echo $pageTitle; ?></div>
	<div class="search"><?php drawHeadbarSearchBox(); ?></div>
</div>
<?php include_once("common/midbar.php"); ?>
<h3>Contact Me</h3>
<?php include("common/gbcf_form.php"); ?> 
<?php include_once("common/footer.php"); ?>


