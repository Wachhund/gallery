<?php
include("config.php");
requirelogin();
?>
<?php displaycontent("html-admin-page-header"); ?>
<div class="gallerycontent">
<?php
if (!isset($_POST['title'])) {
?>
<form action="rss.php" method="post">
<p>Title <input type="text" name="title" /></p>
<p>Link <input type="text" name="link" /></p>
<p>Date <input type="text" name="pubDate" /></p>
<p>Description</p>
<textarea name="description" rows="20" cols="50"></textarea>
<input type="submit" value="Submit" />
</form>
<?php
} else {
$old = new SimpleXMLElement(RSSLOCATION,NULL,true);
if (!($old)) {
	die("Error opening rss.xml");
}
$new = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?><rss version=\"2.0\"></rss>");
$channel = $new->addChild('channel');
$channel->addChild('title',RSSTITLE);
$channel->addChild('link',RSSLINK);
$channel->addChild('description',RSSDESCRIPTION);
$item = $channel->addChild('item');
$item->addChild('title',stripslashes($_POST['title']));
$item->addChild('link',stripslashes($_POST['link']));
$item->addChild('description',stripslashes($_POST['description']));
if ($_POST['pubDate'] == "") {
	$date = date("l, jS F Y H:i e");
}
else {
	$date = stripslashes($_POST['pubDate']);
}
$item->addChild('pubDate',$date);
for ($i=0;$i<=(min($old->channel->item->count()-1,24));$i++) {
	$item = $channel->addChild('item');
	foreach ($old->channel->item[$i]->children() as $child) {
		$item->addChild($child->getName(),htmlspecialchars((string)$child));
	}
}
$success = $new->asXML(RSSLOCATION);
/*
$count = 1;
$new = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>\n<rss version=\"2.0\">\n\n<channel>\n  <title>toastwaffle News Feed</title>\n  <link>http://www.toastwaffle.com</link>\n  <description>The home of Samuel Littley and RateMyBreakfast</description>\n";
$new .= "  <item>\n";
$new .= "    <title>" . stripslashes($_POST['title']) . "</title>\n";
$new .= "    <link>" . stripslashes($_POST['link']) . "</link>\n";
$new .= "    <description>" . stripslashes($_POST['description']) . "</description>\n";
if ($_POST['pubDate'] == "") { $date = date("l, jS F Y H:i e"); }
else { $date = stripslashes($_POST['pubDate']); }
$new .= "    <pubDate>" . $date . "</pubDate>\n";
$new .= "  </item>\n";

foreach($old->channel->children() as $child)
  {
  if ($child->getName() == "item" && $count<=24)
		{
		$count++;
		$new .= "  <item>\n";
    $new .= "    <title>" . stripslashes($child->title) . "</title>\n";
    $new .= "    <link>" . stripslashes($child->link) . "</link>\n";
    $new .= "    <description>" . stripslashes($child->description) . "</description>\n";
    $new .= "    <pubDate>" . stripslashes($child->pubDate) . "</pubDate>\n";
    $new .= "  </item>\n";
		}
  }
$new .= "</channel>\n\n</rss>";
$success = file_put_contents($_SERVER['DOCUMENT_ROOT']."rss.xml",$new);
*/
if (!$success) {
  echo("FAIL!!!!");
}
else {
  echo("<p>Feed Generated and saved. <a href=\"rss.php\">Add another item?</a></p>");
}
}
?>
<?php displaycontent('admin-navigation-bar'); ?>
</div>
<?php displaycontent("html-admin-page-footer"); ?>