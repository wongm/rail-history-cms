<?php 
$pageTitle = "Site Management";
include_once("common/header.php");
?>
<table class="centeredTable">
<tr><td>
<?


if ($_SESSION['authorised'])
{
 ?>
<h4>Sources:</h4>
<a href="enterNewSources.php">Enter New Sources</a><br>
<a href="listSources.php">List All Sources</a><br>
</td>

<td>
<h4>Location Types:</h4>
<a href="enterNewLocationTypes.php">Enter New Location Types</a><br>
<a href="listLocationTypes.php">List All Location Types</a><br>

</td>

<td>
<h4>Articles:</h4>
<a href="enterNewArticles.php">Enter New Articles</a><br>
<a href="listArticles.php">List All Articles</a><br>

</td></tr>

<tr><td>
<h4>Safeworking Types:</h4>
<a href="enterNewSafeworkingTypes.php">Enter New Safeworking Type</a><br>
<a href="listSafeworkingTypes.php">List All Safeworking Types</a><br>
</td>

<td>
<h4>Misc:</h4>
<a href="enterNewConfig.php">Enter New Config Varriable</a><br>
<a href="listConfig.php">List All Variables</a><br>
</td>

<td>
<h4>Misc:</h4>
<a href="enterNewRaillineRegion.php">Enter New Lineguide - Region Link</a><br>
<a href="listRaillineRegion.php">List All Lineguide - Region Links</a><br>
</td></tr>

<tr><td>
<h4>Location images:</h4>
<a href="listLocationImages.php?type=images">Locations with images</a><br>
<a href="listLocationImages.php?type=required">Locations without images</a><br>
<a href="listLocationImages.php">All locations</a>
<?
}	// end authorised if statement
else
{
	echo '<h3 class="error">Unauthorised</h3>';
}	// end login statement
?>
</td></tr>
</table>

<? include_once("common/footer.php"); ?>