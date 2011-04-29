<?php
session_start();
$_SESSION = array();
session_destroy();
include('config.php');
?>
<?php displaycontent("html-admin-page-header"); ?>
<div class="gallerycontent">
<?php echo("<p>All Done. <a href=\"javascript:history.go(-1)\">Go Back</a></p>"); ?>
<hr />
<hr />
<?php displaycontent('admin-navigation-bar'); ?>
</div>
<?php displaycontent("html-admin-page-footer"); ?>
