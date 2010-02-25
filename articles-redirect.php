<?php

$name = str_replace(' ', '-', $_REQUEST['name']);
$name = str_replace('%20', '-', $name);

$url = "/articles/$name";
$url = "http://".$_SERVER['HTTP_HOST'].$url;

header("Location: ".$url,TRUE,301);

?>