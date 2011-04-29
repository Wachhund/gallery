<?php
include('config.php'); 
requirelogin();
?>
<?php displaycontent("html-admin-page-header"); ?>
<div class="gallerycontent">
<?php displaycontent('admin-header'); ?>
<?php displaycontent('admin-lower'); ?>
<hr />
<hr />
<?php displaycontent('admin-navigation-bar'); ?>
</div>
<?php displaycontent("html-admin-page-footer"); ?>
