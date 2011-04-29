<?php
include("config.php");
requirelogin();
displaycontent("html-admin-page-header");
?>
<div class="gallerycontent">
<?php
if (isset($_POST['add'])) {
$slug = strtolower($_POST['name']);
$slug = preg_replace("/[^a-z0-9\s-]/", "", $slug);
$slug = trim(preg_replace("/[\s-]+/", " ", $slug));
$slug = trim(substr($slug, 0, 255));
$slug = preg_replace("/\s/", "-", $slug);
$slug = mysql_real_escape_string($slug);
if (isset($_POST['html'])) {
	$qry = "INSERT INTO `".GALLERYDB."`.`".PAGESTBL."` 
(name,slug,content,html) VALUES 
('".mysql_real_escape_string($_POST['name'])."','".$slug."','".mysql_real_escape_string($_POST['content'])."',1)";
} else {
	$qry = "INSERT INTO `".GALLERYDB."`.`".PAGESTBL."` 
(name,slug,content,html) VALUES 
('".mysql_real_escape_string($_POST['name'])."','".$slug."','".mysql_real_escape_string($_POST['content'])."',0)";
}
$result = mysql_query($qry,$cxn);
if ($result) {
  echo("<p>New Page Added - ".$_POST['name']."</p>");
}
else {
  echo("<p>Error, Page Not added - ".mysql_error($cxn)."</p>");
}
}
elseif (isset($_POST['edit'])) {
if (isset($_POST['html'])) {
$qry = "UPDATE `".GALLERYDB."`.`".PAGESTBL."` SET 
`name`='".mysql_real_escape_string($_POST['name'])."' , 
`slug`='".mysql_real_escape_string($_POST['newslug'])."' , 
`content`='".mysql_real_escape_string($_POST['content'])."' , `html`='1' 
WHERE `slug`='".mysql_real_escape_string($_POST['oldslug'])."'";
} else {
$qry = "UPDATE `".GALLERYDB."`.`".PAGESTBL."` SET 
`name`='".mysql_real_escape_string($_POST['name'])."' , 
`slug`='".mysql_real_escape_string($_POST['newslug'])."' , 
`content`='".mysql_real_escape_string($_POST['content'])."' , `html`='0' 
WHERE `slug`='".mysql_real_escape_string($_POST['oldslug'])."'";
}
$result = mysql_query($qry,$cxn);
if ($result) {
  echo("<p>Page Updated - ".$_POST['name']."</p>");
}
else {
  echo("<p>Error, Page Not Updated - ".mysql_error($cxn)."</p>");
}
}
elseif (isset($_GET['remove'])) {
$qry = "DELETE FROM `".GALLERYDB."`.`".PAGESTBL."` WHERE `slug`='".mysql_real_escape_string($_GET['remove'])."'";
$result = mysql_query($qry,$cxn);
if ($result) {
  echo("<p>Page Removed</p>");
}
else {
  echo("<p>Error, Page Not Removed - ".mysql_error($cxn)."</p>");
}
}
elseif (isset($_GET['edit'])) {
$qry = "SELECT * FROM `".GALLERYDB."`.`".PAGESTBL."` WHERE `slug` = '".mysql_real_escape_string($_GET['edit'])."'";
$result = mysql_query($qry,$cxn);
if ((!($result)) || mysql_num_rows($result) != 1) {
  die("Could not retrieve Pages. <a href=\"javascript:history.go(-1)\">Go Back</a> - ".mysql_error($cxn));
}
$row = mysql_fetch_assoc($result);
echo("<form action=\"editpages.php\" method=\"post\">\n");
echo("<input type=\"hidden\" name=\"edit\" value=\"edit\" />\n");
echo("<input type=\"hidden\" name=\"oldslug\" value=\"".$_GET['edit']."\" />\n");
echo("<p>Name: <input type=\"text\" length=\"255\" width=\"50\" name=\"name\" value=\"".$row['name']."\" /></p>\n");
echo("<p>Slug: <input type=\"text\" length=\"255\" width=\"50\" name=\"newslug\" value=\"".$row['slug']."\" /></p>\n");
echo("<p>Content\n<textarea rows=\"20\" cols=\"50\" name=\"content\">".stripslashes($row['content'])."</textarea></p>\n");
if ($row['html'] == 1) {
echo("<p>Raw HTML: <input type=\"checkbox\" name=\"html\" value=\"html\" checked=\"checked\" /></p>\n");
} else {
echo("<p>Raw HTML: <input type=\"checkbox\" name=\"html\" value=\"html\" /></p>\n");
}
echo("<input type=\"submit\" value=\"Submit\" />\n</form>\n<br /><hr 
/><br />");
}
elseif (isset($_GET['view'])) {
$qry = "SELECT * FROM `".GALLERYDB."`.`".PAGESTBL."` WHERE `slug` = '".mysql_real_escape_string($_GET['view'])."'";
$result = mysql_query($qry,$cxn);
if ((!($result)) || mysql_num_rows($result) != 1) {
  die("Could not retrieve Pages. <a href=\"javascript:history.go(-1)\">Go Back</a> - ".mysql_error($cxn));
}
$row = mysql_fetch_assoc($result);
echo("<h3>".$row['name']."</h3>\n");
echo("<p>Slug: ".$row['slug']."</p>\n");
echo("<h4>Base Content</h4>\n<pre>".htmlentities(stripslashes($row['content']))."</pre>\n");
if ($row['html'] == 0) {
	echo("<h4>Markdown and Smartypants Output</h4>\n<pre><code>".htmlentities(smartypants(markdown(stripslashes($row['content']))))."</code></pre>\n");
}
echo("<p><a href=\"editpages.php?edit=".$row['slug']."\">- Edit -</a>");
echo("<a href=\"editpages.php?remove=".$row['slug']."\">- Remove -</a></p>\n");
echo("<br /><hr /><br />");
}
?>
<h3>Add new item</h3>
<form action="editpages.php" method="post">
<input type="hidden" name="add" value="add" />
<p>Name: <input type="text" length="255" width="50" name="name" /></p>
<p>Content: <textarea rows="20" cols="50" name="content"></textarea></p>
<p>Raw HTML: <input type="checkbox" name="html" value="html" /></p>
<input type="submit" value="Submit" />
</form>
<br /><hr /><br />
<h3>View/Remove/Edit existing items</h3>
<table class="plaintable">
<tr>
<th>Page Name</th>
<th colspan="3">Action</th>
</tr>
<?php
$qry = "SELECT name,slug FROM `".GALLERYDB."`.`".PAGESTBL."`";
$result = mysql_query($qry,$cxn);
if (!($result)) {
  die("Could not retrieve Pages. <a href=\"javascript:history.go(-1)\">Go Back</a> - ".mysql_error($cxn));
}
while ($row = mysql_fetch_assoc($result)) {
  echo("<tr>\n");
  echo("<td>".$row['name']."</td>\n");
  echo("<td><a href=\"editpages.php?view=".$row['slug']."\">View</a></td>\n");
  echo("<td><a href=\"editpages.php?edit=".$row['slug']."\">Edit</a></td>\n");
  echo("<td><a href=\"editpages.php?remove=".$row['slug']."\">Remove</a></td>\n");
  echo("</td>");
}
?>
</table>
<hr />
<hr />
<?php displaycontent('admin-navigation-bar'); ?>
</div>
<?php displaycontent('html-admin-page-footer'); ?>
