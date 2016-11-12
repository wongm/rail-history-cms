<?php

$limitSql = "i.title REGEXP '_[0-9]{4}' OR i.title REGEXP 'DSCF[0-9]{4}'";
$description = "uncaptioned images ";
setCustomPhotostream($limitSql);

$breadcrumb = "Recent $description";
$pageTitle = " - $breadcrumb";
include_once('uploads-base.php'); 

?>