<?php

$cxn = mysql_connect($host,$user,$pass);
if (!($cxn)) {
  die('Could not connect to MySQL Database'.mysql_error($cxn));
}

function displaycontent($slug) {
  error_reporting(E_ALL);
  global $cxn;
  $qry = 'SELECT * FROM `'.GALLERYDB.'`.`'.CONTENTTBL.'` WHERE `slug` = 
\''.mysql_real_escape_string($slug).'\'';
  $result = mysql_query($qry,$cxn);
  if ((!($result)) || (mysql_num_rows($result) != 1)) {
    die('Error retrieving content'.mysql_error($cxn));
  }
  $row = mysql_fetch_assoc($result);
  $text = $row['content'];
  $matches = array();
  $pregresult = preg_match_all("/\[\[![A-Za-z0-9,-_\s]*\]\]/",$text,$matches);
  if (($pregresult != 0) && ($pregresult != FALSE)) {
		$matches = $matches[0];
		foreach($matches as $match) {
			$args = explode(",",substr($match,3,-2));
			$includepath = TEMPLATESLOCATION.$args[0].".php";
			ob_start();
			include $includepath;
			$contents = ob_get_contents();
			ob_end_clean();
			$text = str_replace($match,$contents,$text);
		}
	}
  if ($row['html'] == 0) {
  	echo(smartypants(markdown(stripslashes($text))));
  } else {
	echo(stripslashes($text));
  }
}

function requirelogin() {
	session_start();
	if (!(isset($_SESSION['username']))) {
		header("Location: login.php?ref=".$_SERVER['REQUEST_URI']);
	}
}

function shoppingform($photoid) {
	echo("<div class=\"shoppingform\">\n<form action=\"cart.php\" method=\"post\">\n");
	echo("<input type=\"hidden\" name=\"referrer\" value=\"".curPageURL()."\" />\n");
	echo("<input type=\"hidden\" name=\"photoid\" value=\"".$photoid."\" />\n");
	echo("<input type=\"select\" name=\"item\" id=\"item\">\n");
	global $cxn;
	$qry = "SELECT * FROM `".GALLERYDB."`.`".PRODUCTSTBL."` WHERE `producttype`='photo'";
	$result = mysql_query($qry,$cxn);
	while ($row = mysql_fetch_assoc($result)) {
		echo("<option value=\"".$row['id']."\">".stripslashes($row['name'])."</option>\n");
	}
	echo("</input>\n");
	echo("<div id=\"iteminfo\"></div>\n");
	echo("<input type=\"submit\" />\n</form>\n</div>\n");	
}

?>
