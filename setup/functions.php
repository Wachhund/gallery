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
			$_GET = array();
			$args = explode(",",substr($match,3,-2));
			$includepath = TEMPLATESLOCATION.$args[0].".php";
			ob_start();
			include $includepath;
			$contents = ob_get_contents();
			ob_end_clean();
			$text = str_replace($match,"<div>".$contents."</div>",$text);
		}
	}
  echo(smartypants(markdown(stripslashes($text))));
}

function requirelogin() {
	session_start();
	if (!(isset($_SESSION['username']))) {
		header("Location: login.php?ref=".$_SERVER['REQUEST_URI']);
	}
}

function using_ie() 
{ 
    $u_agent = $_SERVER['HTTP_USER_AGENT']; 
    $ub = False; 
    if(preg_match('/MSIE/i',$u_agent)) 
    { 
        $ub = True; 
    } 
    
    return $ub; 
} 

function ie_box() {
    if (using_ie()) {
        ?>
<div class="iedoom">
<h2>Hi. You're using Internet Explorer, a non standards-compliant, inept browser.</h2>
<p>As a developer, I do not in any way support the use of Internet Explorer, due to it's incorrect behaviours in rendering webpages. The extra work I would have to put in to make this website behave correctly in Internet Explorer does not justify the benefits I would gain. You are still free to use this website, and this message will only be displayed on the homepage, however no promises are made as to whether certain features will work. I <em>strongly</em> recommend you switch to using one of the free browsers below. Feel free to <a href="mailto:supersam.littley@gmail.com">contact me</a> if you need help with this</p>
<table border="0" text-align="center">
<tr>
<a href="http://www.mozilla.com/en-US/firefox/">
<td>
<h3>MOZILLA FIREFOX</h3>
<img src="img/firefox.png" style="margin: auto;" />
</td>
</a>
<a href="http://www.google.com/chrome">
<td>
<h3>GOOGLE CHROME</h3>
<img src="img/chrome.png" style="margin: auto;" />
</td>
</a>
<a href="http://www.apple.com/safari/">
<td>
<h3>APPLE SAFARI</h3>
<img src="img/safari.png" style="margin: auto;" />
</td>
</a>
</tr>
</table>
</div>
        <?php
    return;
    }
}

function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

function shoppingform($photoid) {
	echo("<div class=\"shoppingform\">\n<form action=\"cart.php\" method=\"post\">\n");
	echo("<input type=\"hidden\" name=\"referrer\" value=\"".curPageURL()."\" />\n");
	echo("<input type=\"hidden\" name=\"photoid\" value=\"".$photoid."\" />\n");
	echo("<input type=\"select\" name=\"item\" id=\"item\">\n");
	global $cxn;
	$qry = "SELECT * FROM `".GALLERYDB."`.`".PRODUCTSTBL."` WHERE `producttype`='photo'";
	$result = mysql_query($qry,$cxn);
	while ($row = mysql_fetch_assoc($result)) 
		echo("<option value=\"".$row['id']."\">".stripslashes($row['name'])."</option>\n");
	}
	echo("</input>\n");
	echo("<div id=\"iteminfo\"></div>\n");
	echo("<input type=\"submit\" />\n</form>\n</div>\n");	
}

?>
