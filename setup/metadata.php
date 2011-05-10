<?php
include("config.php");
header("Content-Type: text/plain");
echo("<metadata width=\"300\" height=\"380\" title=\"Image Information\" ok=\"Ok\" cancel=\"Cancel\">\n");
echo("\t<input type=\"text\" name=\"name\" label=\"Image Title\" />\n");
echo("\t<input type=\"textarea\" name=\"description\" label=\"Image Description\" />\n");
echo("\t<input type=\"text\" name=\"date\" label=\"Date (YYYY-MM-DD) - Blank to use EXIF\" />\n");
echo("\t<input type=\"select\" name=\"galleryid\" label=\"Gallery\" massApplyEnabled=\"true\" massApply=\"true\" required=\"true\">\n");
$qry = "SELECT id,name FROM `".GALLERYDB."`.`".GALLERYTBL."`";
$result = mysql_query($qry,$cxn);
while ($row = mysql_fetch_assoc($result)) {
echo("\t\t<option value=\"".$row['id']."\" name=\"".stripslashes($row['name'])."\" />\n");
}
echo("\t</input>\n</metadata>");
?>