<?php 
$pageTitle = "Site Management";
include_once("common/header.php");
?>
<table class="centeredTable">
<tr><td>
<?php


if ($_SESSION['authorised'])
{
 ?>
<!--<h4>Sources:</h4>
<a href="enterNewSources.php">Enter New Sources</a><br>
<a href="listSources.php">List All Sources</a><br>
</td>
<td>
-->

<h4>Location Types:</h4>
<a href="enterNewLocationTypes.php">Enter New Location Types</a><br>
<a href="listLocationTypes.php">List All Location Types</a><br>

</td>
</tr>

<tr><td>
<h4>Safeworking Types:</h4>
<a href="enterNewSafeworkingTypes.php">Enter New Safeworking Type</a><br>
<a href="listSafeworkingTypes.php">List All Safeworking Types</a><br>
</td>
</tr>

<tr><td>
<h4>Location images:</h4>
<a href="listLocationImages.php?type=images">Locations with images</a><br>
<a href="listLocationImages.php?type=required">Locations without images</a><br>
<a href="listLocationImages.php">All locations</a>
<?php
}	// end authorised if statement
else
{
	echo '<h3 class="error">Unauthorised</h3>';
}	// end login statement
?>
</td></tr>
</table>

<?php include_once("common/footer.php"); ?>