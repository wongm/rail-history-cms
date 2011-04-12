 <?php $pageTitle = "Contact Me";
include_once("common/dbConnection.php");
include_once("common/formatting-functions.php");
include_once("common/header.php"); 
?>
<table class="headbar">
	<tr><td><a href="/">Home</a> &raquo; <? echo $pageTitle; ?></td>
	<td id="righthead"><? drawHeadbarSearchBox(); ?></td></tr>
</table>
<h3></h3>
<?php include("common/gbcf_form.php"); ?> 
<?php include_once("common/footer.php"); ?>


