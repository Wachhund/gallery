<?php
session_start();
$_SESSION = array();
session_destroy();
include('config.php');
?>
<!DOCTYPE html>
<html><head><meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="<?php echo(STYLESHEETLOCATION); ?>">
<link rel="shortcut icon" type="image/ico" href="<?php echo(FAVICONLOCATION); ?>">
<title><?php echo(GALLERYTITLE) ?></title>
</head>
<body>
<div class="content" id="content" name="content">
<?php echo("<p>All Done. <a href=\"javascript:history.go(-1)\">Go Back</a></p>"); ?>
<hr />
<hr />
<?php displaycontent('admin-navigation-bar'); ?>
</div>
</body>
</html>
