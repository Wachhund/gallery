<?php
include("config.php");
requirelogin();
displaycontent("html-admin-page-header");
?>
<div class="gallerycontent">
<?php
if (isset($_POST['add'])) {
if (!isset($_PASSWORD) {
	die("<p>You must add a password to make a gallerie private. <a href=\"javascript:history.go(-1)\">Go Back.</a></p>");
}
$slug = strtolower($_POST['name']);
$slug = preg_replace("/[^a-z0-9\s-]/", "", $slug);
$slug = trim(preg_replace("/[\s-]+/", " ", $slug));
$slug = trim(substr($slug, 0, 255));
$slug = preg_replace("/\s/", "-", $slug);
$slug = mysql_real_escape_string($slug);
if (isset($_POST['private'])) {
$qry = "INSERT INTO `".GALLERYDB."`.`".GALLERYTBL."` (name,slug,description,private,password) VALUES ('".mysql_real_escape_string($_POST['name'])."','".$slug."','".mysql_real_escape_string($_POST['description'])."',1,'".hash('SHA256',$_POST['password'])."'";
} else {
$qry = "INSERT INTO `".GALLERYDB."`.`".GALLERYTBL."` (name,slug,description,private) VALUES ('".mysql_real_escape_string($_POST['name'])."','".$slug."','".mysql_real_escape_string($_POST['description'])."',0";
}
$result = mysql_query($qry,$cxn);
if ($result) {
  echo("<p>New Gallery Added - ".$_POST['name']."</p>");
}
else {
  echo("<p>Error, Gallery Not added - ".mysql_error($cxn)."</p>");
}
}
elseif (isset($_POST['edit'])) {
if (isset($_POST['private'])) {
  if (isset($_POST['password'])) {
		$qry = "UPDATE `".GALLERYDB."`.`".GALLERYTBL."` SET `name`='".mysql_real_escape_string($_POST['name'])."' , `slug`='".mysql_real_escape_string($_POST['newslug'])."' , `description`='".mysql_real_escape_string($_POST['description'])."' , `private`='1' , `password`='".hash('SHA256',$_POST['password'])."' WHERE `slug`='".mysql_real_escape_string($_POST['oldslug'])."'";
  } else {
		$qry = "UPDATE `".GALLERYDB."`.`".GALLERYTBL."` SET `name`='".mysql_real_escape_string($_POST['name'])."' , `slug`='".mysql_real_escape_string($_POST['newslug'])."' , `description`='".mysql_real_escape_string($_POST['description'])."' , `private`='0' WHERE `slug`='".mysql_real_escape_string($_POST['oldslug'])."'";
	}
} else {
$qry = "UPDATE `".GALLERYDB."`.`".GALLERYTBL."` SET 
`name`='".mysql_real_escape_string($_POST['name'])."' , 
`slug`='".mysql_real_escape_string($_POST['newslug'])."' , 
`description`='".mysql_real_escape_string($_POST['description'])."' , `private`='0' 
WHERE `slug`='".mysql_real_escape_string($_POST['oldslug'])."'";
}
$result = mysql_query($qry,$cxn);
if ($result) {
  echo("<p>Gallery Updated - ".$_POST['name']."</p>");
}
else {
  echo("<p>Error, Gallery Not Updated - ".mysql_error($cxn)."</p>");
}
}
elseif (isset($_GET['remove'])) {
$qry = "DELETE FROM `".GALLERYDB."`.`".GALLERYTBL."` WHERE `slug`='".mysql_real_escape_string($_GET['remove'])."'";
$result = mysql_query($qry,$cxn);
if ($result) {
  echo("<p>Gallery Removed</p>");
}
else {
  echo("<p>Error, Gallery Not Removed - ".mysql_error($cxn)."</p>");
}
}
elseif (isset($_GET['edit'])) {
$qry = "SELECT * FROM `".GALLERYDB."`.`".GALLERYTBL."` WHERE `slug` = '".mysql_real_escape_string($_GET['edit'])."'";
$result = mysql_query($qry,$cxn);
if ((!($result)) || mysql_num_rows($result) != 1) {
  die("Could not retrieve Galleries. <a href=\"javascript:history.go(-1)\">Go Back</a> - ".mysql_error($cxn));
}
$row = mysql_fetch_assoc($result);
echo("<form action=\"editgalleries.php\" method=\"post\">\n");
echo("<input type=\"hidden\" name=\"edit\" value=\"edit\" />\n");
echo("<input type=\"hidden\" name=\"oldslug\" value=\"".$_GET['edit']."\" />\n");
echo("<p>Name: <input type=\"text\" length=\"255\" width=\"50\" name=\"name\" value=\"".$row['name']."\" /></p>\n");
echo("<p>Slug: <input type=\"text\" length=\"255\" width=\"50\" name=\"newslug\" value=\"".$row['slug']."\" /></p>\n");
echo("<p>Description\n<textarea rows=\"20\" cols=\"50\" name=\"description\">".stripslashes($row['description'])."</textarea></p>\n");
if ($row['private'] == 1) {
echo("<p>Private?: <input type=\"checkbox\" name=\"private\" value=\"private\" checked=\"checked\" /></p>\n");
} else {
echo("<p>Private?: <input type=\"checkbox\" name=\"private\" value=\"private\" /></p>\n");
}
echo("<p>Password (Leave blank to not change): <input type=\"password\" length=\"255\" width=\"50\" name=\"password\" /></p>\n");
echo("<input type=\"submit\" value=\"Submit\" />\n</form>\n<br /><hr /><br />");
}
elseif (isset($_GET['view'])) {
$qry = "SELECT * FROM `".GALLERYDB."`.`".GALLERYTBL."` WHERE `slug` = '".mysql_real_escape_string($_GET['view'])."'";
$result = mysql_query($qry,$cxn);
if ((!($result)) || mysql_num_rows($result) != 1) {
  die("Could not retrieve Galleries. <a href=\"javascript:history.go(-1)\">Go Back</a> - ".mysql_error($cxn));
}
$row = mysql_fetch_assoc($result);
echo("<h3>".$row['name']."</h3>\n");
echo("<p>Slug: ".$row['slug']."</p>\n");
echo("<h4>Description</h4>\n<p>".$row['description']."</p>\n");
echo("<p><a href=\"editgalleries.php?edit=".$row['slug']."\">- Edit -</a>");
echo("<a href=\"editgalleries.php?remove=".$row['slug']."\">- Remove -</a></p>\n");
echo("<br /><hr /><br />");
}
?>
<h3>Add new item</h3>
<form action="editgalleries.php" method="post">
<input type="hidden" name="add" value="add" />
<p>Name: <input type="text" length="255" width="50" name="name" /></p>
<p>Description: <textarea rows="20" cols="50" name="description"></textarea></p>
<p>Private?: <input type="checkbox" name="private" value="private" /></p>
<p>Password: <input type="password" length="255" width="50" name="password" /></p>
<input type="submit" value="Submit" />
</form>
<br /><hr /><br />
<h3>View/Remove/Edit existing items</h3>
<table class="plaintable">
<tr>
<th>Content Name</th>
<th colspan="3">Action</th>
</tr>
<?php
$qry = "SELECT name,slug FROM `".GALLERYDB."`.`".GALLERYTBL."`";
$result = mysql_query($qry,$cxn);
if (!($result)) {
  die("Could not retrieve Galleries. <a href=\"javascript:history.go(-1)\">Go Back</a> - ".mysql_error($cxn));
}
while ($row = mysql_fetch_assoc($result)) {
  echo("<tr>\n");
  echo("<td>".$row['name']."</td>\n");
  echo("<td><a href=\"editgalleries.php?view=".$row['slug']."\">View</a></td>\n");
  echo("<td><a href=\"editgalleries.php?edit=".$row['slug']."\">Edit</a></td>\n");
  echo("<td><a href=\"editgalleries.php?remove=".$row['slug']."\">Remove</a></td>\n");
  echo("</td>");
}
?>
</table>
<hr />
<hr />
<?php displaycontent('admin-navigation-bar'); ?>
</div>
<?php displaycontent('html-admin-page-footer'); ?>
