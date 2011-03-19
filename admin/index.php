<?php
include('config.php'); 
requirelogin();
?>
<!DOCTYPE html>
<html><head><meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="<?php echo(STYLESHEETLOCATION); ?>">
<link rel="shortcut icon" type="image/ico" href="<?php echo(FAVICONLOCATION); ?>">
<title>toastwaffle.</title>
</head>
<body>
<div class="content" id="content" name="content">
<?php displaycontent('admin-header'); ?>
<?php displaycontent('admin-lower'); ?>
<hr />
<hr />
<?php displaycontent('admin-navigation-bar'); ?>
</div>
</body>
</html>
