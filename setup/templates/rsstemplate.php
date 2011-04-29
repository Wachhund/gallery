<?php
/*
Name: RSS Output Template
Developer: Samuel Littley
Arguments: 2
Argument 1: String for Title
Argument 2: Integer for number of items to output
*/
$returnstr = "<div class=\"news\">\n";
$returnstr .= "<h2>".$args[1]."</h2>\n";
$returnstr .= "<h6><a href=\"".RSSLOCATION."\">RSS Link</a></h6>\n";
if (file_exists(RSSLOCATION))
  {
  $xml = simplexml_load_file(RSSLOCATION);
  }
$count = 1;
foreach($xml->channel->children() as $child)
  {
  if ($child->getName() == "item" && $count<=$args[2])
		{
		$count++;
		$returnstr .= "<div class=\"newsitem\">\n";
		$returnstr .= "<hr />\n<h3><a href=\"" . $child->link . "\">" . stripslashes($child->title) . "</a></h3>\n";
		$returnstr .= "<p class=\"date\">" . stripslashes($child->pubDate) . "</p>\n";
		$returnstr .= markdown(preg_replace("/(http|https|ftp)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(:[a-zA-Z0-9]*)?\/?([a-zA-Z0-9\-\._\?\,\'\/\\\+&amp;%\$#\=~])*/","<a href=\"$0\">$0</a>",str_replace("http://http://","http://",preg_replace("/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(:[a-zA-Z0-9]*)?\/?([a-zA-Z0-9\-\._\?\,\'\/\\\+&amp;%\$#\=~])*/","http://$0",stripslashes($child->description)))));
		$returnstr .= "</div>\n";
		}
  }
$returnstr .= "</div>";

echo $returnstr;
?>
