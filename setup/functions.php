<?php

function displaycontent($slug) {
  global $cxn;
  $qry = 'SELECT * FROM `'.PERSONALDB.'`.`'.CONTENTTBL.'` WHERE `slug` = \''.mysql_real_escape_string($slug).'\'';
  $result = mysql_query($qry,$cxn);
  if ((!($result)) || (mysql_num_rows($result) != 1)) {
    die('Error retrieving content'.mysql_error($cxn));
  }
  $row = mysql_fetch_assoc($result);
  echo(smartypants(markdown(stripslashes($row['content']))));
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

function displaybutton($photoname,$photoid) {
$business = BUSINESSID;
$shipping = PAYPALSHIPPING;
$code = <<<text
<form target="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_cart" />
<input type="hidden" name="business" value="$business" />
<input type="hidden" name="lc" value="GB" />
<input type="hidden" name="item_name" value="Buy Photo - $photoname" />
<input type="hidden" name="item_number" value="$photoid" />
<input type="hidden" name="button_subtype" value="products" />
<input type="hidden" name="shipping" value="$shipping" />
<input type="hidden" name="add" value="1" />
<input type="hidden" name="bn" value="PP-ShopCartBF:btn_cart_LG.gif:NonHosted" />
<input type="hidden" name="on0" value="Purchase" />
<p>What would you like to buy?</p>
<select name="os0">\n
text;
for (PAYPALITEMS as $key => $value) {
	$code .= "<option value=\"".$value."\">".$value." £".PAYPALPRICES[$key]."</option>\n";
}
$code .= <<<text
</select>
<input type="hidden" name="currency_code" value="GBP" />\n
text;
for (PAYPALITEMS as $key => $value) {
	$code .= "<input type=\"hidden\" name=\"option_select".$key."\" value=\"".$value."\" />\n";
	$code .= "<input type=\"hidden\" name=\"option_amount".$key."\" value=\"".PAYPALPRICES[$key]."\" />\n";
}
$code .= <<<text
<input type="hidden" name="option_index" value="0" />
<br />
<input type="image" src="https://www.paypal.com/en_GB/i/btn/btn_cart_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online." />
<img alt="" border="0" src="https://www.paypal.com/en_GB/i/scr/pixel.gif" width="1" height="1" />
</form>
text;
echo($code);
}
?>
