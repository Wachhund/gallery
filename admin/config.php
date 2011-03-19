<?php
$currentdir = getcwd();
$pos = strrpos($currentdir,"admin");
$dir = substr($currentdir,0,$pos);

// If the server is having problems finding the main config file,
// comment out the line below, uncomment and edit the line after
// to point directly to the file. Sorry.
include($dir."/setup/config.php");
//include($_SERVER['DOCUMENT_ROOT']."<path to setup/config.php>");
?>
