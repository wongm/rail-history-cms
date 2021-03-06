<?php 
$pageTitle = "Site Management";
include_once("common/dbConnection.php");
include_once("common/header.php");
?>
<table class="centeredTable">
<tr><td>
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
<a href="listLocationImages.php?type=with">Locations with images</a><br>
<a href="listLocationImages.php?type=without">Locations without images</a><br>
<a href="listLocationImages.php">All locations</a>

<tr><td>
<h4>Location permalinks:</h4>
<a href="listLocationPermanlinks.php?type=empty">Locations without permalinks</a><br>
<a href="listLocationPermanlinks.php?type=duplicates">Duplicated permalinks</a><br>
<a href="listLocationPermanlinks.php">All locations</a>

</td></tr>
</table>

<?php include_once("common/footer.php"); ?>
