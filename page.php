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
<?php
  if ((!isset($_GET)) || (count($_GET) != 1)) {
  ?>
  <h1>Pages</h1>
  <hr />
  <ul>
  <?php
  	$qry = "SELECT * FROM `".GALLERYDB."`.`".PAGESTBL."`";
  	$result = mysql_query($qry,$cxn);
  	while ($row = mysql_fetch_assoc($result)) {
  		echo("\t<li><a href=\"".$_SERVER['SCRIPT_NAME']."?".$row['slug']."\">".stripslashes($row['name'])."</a></li>\n");
  	}
  ?>
  </ul>
  <?php
  } else {
		foreach ($_GET as $key => $value) {
			if ($vale != "") { 
				$qry = "SELECT * FROM `".GALLERYDB."`.`".PAGESTBL."` WHERE `slug`='".$value."'";
			} else {
				$qry = "SELECT * FROM `".GALLERYDB."`.`".PAGESTBL."` WHERE `slug`='".$key."'";
			}
			$result = mysql_query($qry,$cxn);
			if (mysql_num_rows($result) != 1) {
				echo("<h3>The page you have requested is an invalid link. Please <a href=\"index.php\">Go Home</a> to find what you're looking for</h3>");
			} else {
				$row = mysql_fetch_assoc($result);
				echo(smartypants(markdown($row['content'])));
			}
		}
	}
?>
</div>
</body>
</html>
