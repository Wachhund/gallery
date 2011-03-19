<?php
session_start();
if (isset($_SESSION['username'])) {
	if (isset($_GET['ref'])) {
		header("Location: ".$_GET['ref']);
	}
	else {
		header("Location: index.php");
	}
}
include("config.php");
?>
<!DOCTYPE html>
<html><head><meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="<?php echo(STYLESHEETLOCATION); ?>">
<link rel="shortcut icon" type="image/ico" href="<?php echo(FAVICONLOCATION); ?>">
<title><?php echo(GALLERYTITLE) ?></title>
</head>
<body>
<div class="content" id="content" name="content">
<?php
if (isset($_POST['username'])) {
	$qry = "SELECT * FROM `".GALLERYDB."`.`".USERSTBL."` WHERE `username`='".mysql_real_escape_string($_POST['username'])."'";
	$result = mysql_query($qry,$cxn);
	if (!($result)) {
		die("Error connecting to MySQL - ".mysql_error($cxn));
	}
	if (mysql_num_rows($result) != 1) {
		die("Error #".mysql_num_rows($result).", could not log in. <a href=\"javascript:history.go(-1)\">Go Back</a>");
	}
	$row = mysql_fetch_assoc($result);
	$hash = hash("sha256",$_POST['password']);
	if ($hash != $row['password']) {
		die("Error #123, Could not log in. <a href=\"javascript:history.go(-1)\">Go Back</a>");
	}
	else {
		$_SESSION['username'] = $row['username'];
		if (isset($_POST['ref'])) {
			echo("<p>Logged In. <a href=\"".$_POST['ref']."\">Continue</a></p>");
		} else {
			echo("<p>Logged In. <a href=\"index.php#loggedin\">Continue</a></p>");
		}
	}
}
else {
?>
<form action="login.php" method="post">
<form method="post" action="login.php">
<table width="300" border="0" align="center" cellpadding="3" cellspacing="1">
<tr>
<td colspan="3"><strong>Member Login </strong></td>
</tr>
<tr>
<td width="78">Username</td>
<td width="6">:</td>
<td width="294"><input name="username" type="text" id="username"></td>
</tr>
<tr>
<td>Password</td>
<td>:</td>
<td><input name="password" type="password" id="password"></td>
</tr>
<tr>
<td>
<?php if (isset($_GET['ref'])) {
$uri = $_GET['ref'];
echo "<input type=\"hidden\" name=\"ref\" value=\"$uri\"";
} ?>
</td>
<td>&nbsp;</td>
<td><input type="submit" name="Submit" value="Login"></td>
</tr>
</table>
</form>
<?php } ?>
<hr />
<hr />
<?php displaycontent('admin-navigation-bar'); ?>
</div>
</body>
</html>
