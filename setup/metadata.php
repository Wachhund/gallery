<?php
include("config.php");
header("Content-type: text/xml");
$starttext = <<<STARTTEXT
<metadata width="300" height="250" title="Metadata" ok="Ok" cancel="Cancel">
    <input type="text" name="name" label="Title" />
    <input type="textarea" rows="4" name="description" label="Description" />
    <input type="select" name="galleryid" label="Gallery">
STARTTEXT;
echo($starttext);
$qry = "SELECT * FROM `".GALLERYDB."`.`".GALLERYTBL."`";
$result = mysql_query($qry,$cxn);
while ($row = mysql_fetch_assoc($result)) {
	echo("<option value=\"".$row['id']."\" name=\"".$row['id']." - ".stripslashes($row['name'])."\" />");
}
$endtext = <<<ENDTEXT
    </input>
</metadata>
ENDTEXT;
echo($endtext);
