<?php
include('config.php');
requirelogin();
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
if (isset($_POST['add'])) {
$user = strtolower($_POST['name']);
$user = preg_replace("/[^a-z0-9\s-]/", "", $user);
$user = trim(preg_replace("/[\s-]+/", " ", $user));
$user = trim(substr($user, 0, 255));
$user = preg_replace("/\s/", "-", $user);
$user = mysql_real_escape_string($user);
$qry = "INSERT INTO `".ADMINDB."`.`".USERTBL."` (username,password,email) VALUES ('".$user."','".hash("sha256",$_POST['password'])."','".mysql_real_escape_string($_POST['email'])."')";
$result = mysql_query($qry,$cxn);
if ($result) {
  echo("<p>New User Added - ".$user."</p>");
}
else {
  echo("<p>Error, User Not added - ".mysql_error($cxn)."</p>");
}
}
elseif (isset($_POST['edit'])) {
if ($_POST['password'] != "") {
$qry = "UPDATE `".ADMINDB."`.`".USERTBL."` SET `username`='".mysql_real_escape_string($_POST['name'])."' , `password`='".hash("sha256",$_POST['password'])."' , `email`='".mysql_real_escape_string($_POST['email'])."' WHERE `username`='".mysql_real_escape_string($_POST['oldname'])."'";
}
else {
$qry = "UPDATE `".ADMINDB."`.`".USERTBL."` SET `username`='".mysql_real_escape_string($_POST['name'])."' , `email`='".mysql_real_escape_string($_POST['email'])."' WHERE `username`='".mysql_real_escape_string($_POST['oldname'])."'";
}
$result = mysql_query($qry,$cxn);
if ($result) {
  echo("<p>User Updated - ".$_POST['name']."</p>");
}
else {
  echo("<p>Error, User Not Updated - ".mysql_error($cxn)."</p>");
}
}
elseif (isset($_GET['remove'])) {
$qry = "DELETE FROM `".ADMINDB."`.`".USERTBL."` WHERE `username`='".mysql_real_escape_string($_GET['remove'])."'";
$result = mysql_query($qry,$cxn);
if ($result) {
  echo("<p>User Removed</p>");
}
else {
  echo("<p>Error, User Not Removed - ".mysql_error($cxn)."</p>");
}
}
elseif (isset($_GET['edit'])) {
$qry = "SELECT * FROM `".ADMINDB."`.`".USERTBL."` WHERE `username` = '".mysql_real_escape_string($_GET['edit'])."'";
$result = mysql_query($qry,$cxn);
if ((!($result)) || mysql_num_rows($result) != 1) {
  die("Could not retrieve User Details. <a href=\"javascript:history.go(-1)\">Go Back</a> - ".mysql_error($cxn));
}
$row = mysql_fetch_assoc($result);
echo("<form action=\"editusers.php\" method=\"post\">\n");
echo("<input type=\"hidden\" name=\"edit\" value=\"edit\" />\n");
echo("<input type=\"hidden\" name=\"oldname\" value=\"".$_GET['edit']."\" />\n");
echo("<p>Name: <input type=\"text\" length=\"255\" width=\"50\" name=\"name\" value=\"".$row['username']."\" /></p>\n");
echo("<p>New Password: <input type=\"password\" length=\"255\" width=\"50\" name=\"password\" /> (Leave blank to not change)</p>\n");
echo("<p>Email: <input type=\"text\" length=\"255\" width=\"50\" name=\"email\" value=\"".$row['email']."\" /></p>\n");
echo("<input type=\"submit\" value=\"Submit\" />\n</form>\n<br /><hr /><br />");
}
elseif (isset($_GET['view'])) {
$qry = "SELECT * FROM `".ADMINDB."`.`".USERTBL."` WHERE `username` = '".mysql_real_escape_string($_GET['view'])."'";
$result = mysql_query($qry,$cxn);
if ((!($result)) || mysql_num_rows($result) != 1) {
  die("Could not retrieve content items. <a href=\"javascript:history.go(-1)\">Go Back</a> - ".mysql_error($cxn));
}
$row = mysql_fetch_assoc($result);
echo("<h3>".$row['username']."</h3>\n");
echo("<p>User ID: ".$row['id']."</p>\n");
echo("<p>Email: ".$row['email']."</p>\n");
echo("<p><a href=\"editusers.php?edit=".$row['username']."\">- Edit -</a>");
echo("<a href=\"editusers.php?remove=".$row['username']."\">- Remove -</a></p>\n");
echo("<br /><hr /><br />");
}
?>
<h3>Add new user</h3>
<form action="editusers.php" method="post">
<input type="hidden" name="add" value="add" />
<p>Name: <input type="text" length="255" width="50" name="name" /></p>
<p>Password: <input type="password" length="255" width="50" name="password" /></p>
<p>Email: <input type="text" width="50" name="email" /></p>
<input type="submit" value="Submit" />
</form>
<br /><hr /><br />
<h3>View/Remove/Edit existing users</h3>
<table class="plaintable">
<tr>
<th>Username</th>
<th colspan="3">Action</th>
</tr>
<?php
$qry = "SELECT username FROM `".ADMINDB."`.`".USERTBL."`";
$result = mysql_query($qry,$cxn);
if (!($result)) {
  die("Could not retrieve user details. <a href=\"javascript:history.go(-1)\">Go Back</a> - ".mysql_error($cxn));
}
while ($row = mysql_fetch_assoc($result)) {
  echo("<tr>\n");
  echo("<td>".$row['username']."</td>\n");
  echo("<td><a href=\"editusers.php?view=".$row['username']."\">View</a></td>\n");
  echo("<td><a href=\"editusers.php?edit=".$row['username']."\">Edit</a></td>\n");
  echo("<td><a href=\"editusers.php?remove=".$row['username']."\">Remove</a></td>\n");
  echo("</td>");
}
?>
</table>
<hr />
<hr />
<?php displaycontent('admin-navigation-bar'); ?>
</div>
</body>
</html>
