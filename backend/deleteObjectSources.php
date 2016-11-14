<?php
include_once("common/dbConnection.php");
include_once("common/header.php");
?>
<?php
	// Retreiving Form Elements from Form
	$id = addslashes($_REQUEST['id']);
	$type = addslashes($_REQUEST['type']);

?>
<?php
$sql = "DELETE FROM object_sources WHERE linkzor_id = '$id'";
$result = MYSQL_QUERY($sql);

?>
Record has been deleted from database.
<?php
include_once("common/footer.php");
?>