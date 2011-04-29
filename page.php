<?php
include("setup/config.php");
displaycontent("html-page-header");
?>
<div class="gallerycontent">
<?php
  if ((!isset($_GET)) || (count($_GET) != 1)) {
  var_dump($_GET);
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
			if ($value != "") { 
				$qry = "SELECT * FROM `".GALLERYDB."`.`".PAGESTBL."` WHERE `slug`='".$value."'";
			} else {
				$qry = "SELECT * FROM `".GALLERYDB."`.`".PAGESTBL."` WHERE `slug`='".$key."'";
			}
			$result = mysql_query($qry,$cxn);
			if (mysql_num_rows($result) != 1) {
				echo("<h3>The page you have requested is an invalid link. Please <a href=\"index.php\">Go Home</a> to find what you're looking for</h3>");
			} else {
				$row = mysql_fetch_assoc($result);
				if ($row['html'] == 0) {
					echo(smartypants(markdown($row['content'])));
				} else {
					echo($row['content']);
				}
			}
		}
	}
?>
</div>
<?php displaycontent("html-page-footer"); ?>
