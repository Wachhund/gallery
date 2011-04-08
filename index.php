<?php
include("setup/config.php");
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; 
charset=ISO-8859-1">
	<link rel="stylesheet" type="text/css" href="<?php echo(STYLESHEETLOCATION); ?>">
	<link rel="shortcut icon" type="image/ico" href="<?php echo(FAVICONLOCATION); ?>">
	<title><?php echo(GALLERYTITLE); ?></title>
</head>
<body>
<div class="content">
<?php displaycontent("home-page"); ?>
</div>
</body>
</html>
