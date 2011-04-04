<?php
// Leave in place!! //
error_reporting(0);

// Editable Section Begins Here //

// MySQL Settings provided by your hosting company //
$host = 'localhost';
$user = 'webuser';
$pass = 'qwertyuiop';

// MySQL Options for you to set //
define("GALLERYDB",'gallery',true); // Name of database to use
define("IMGSTBL",'photos',true); // Name of table for images
define("GALLERYTBL",'galleries',true); // Name of table for galleries
define("DOWNLOADTBL",'download',true); // Name of table for image downloads
define("CONTENTTBL",'content',true); // Name of table for site content
define("USERSTBL",'users',true); // Name of table for site admin system users
define("PRODUCTSTBL",'products',true); // Name of table for products to be sold

// Installation Location Settings
define("AMAZONLOCATION", $_SERVER['DOCUMENT_ROOT'].'gallery/setup/amazon/sdk.class.php', true); // Location of Amazon AWS SDK class file
define("JUMPLOADERLOCATION", $_SERVER['DOCUMENT_ROOT'].'gallery/setup/jar/', true); // Location of Jumploader Java Archive folder with trailing slash
define("MARKDOWNLOCATION", $_SERVER['DOCUMENT_ROOT'].'gallery/setup/markdown.php', true); // Location of markdown class file
define("SMARTYPANTSLOCATION", $_SERVER['DOCUMENT_ROOT'].'gallery/setup/smartypants.php', true); // Location of markdown class file
define("FUNCTIONSLOCATION", $_SERVER['DOCUMENT_ROOT'].'gallery/setup/functions.php', true); // Location of functions.php file
define("STYLESHEETLOCATION", '../setup/style/style.css', true); // Location of style.css file
define("FAVICONLOCATION", '../setup/style/favicon.ico', true); // Location of favicon.ico file
define("TEMPLATESLOCATION", $_SERVER['DOCUMENT_ROOT'].'gallery/setup/templates/', true); // Location of templates folder, with trailing slash
define("AJAXLOCATION", $_SERVER['DOCUMENT_ROOT'].'gallery/setup/ajax/', true); // Location of ajax folder, with trailing slash

// Amazon AWS settings
define("AWSPUBLICKEY", 'examplepublickey', true);
define("AWSPRIVATEKEY", 'exampleprivatekey', true);
define("BUCKET", 'mybucket', true);

// Jumploader settings
define("UPLOADDIR", $_SERVER['DOCUMENT_ROOT'].'gallery/uploads/', true); // Location of folder to temporarily upload files to. Must be writable by server
define("UPLOADSTAGEDIR", $_SERVER['DOCUMENT_ROOT'].'gallery/uploads/stage/', true); // Location of folder to temporarily upload file partitions to. Must be writable by server

// Google Checkout settings
define("MERCHANTID", 'examplemerchantid', true); // Google Checkout business id, for creation of button

// Gallery Settings
define("WATERMARKLOCATION", $_SERVER['DOCUMENT_ROOT'].'gallery/setup/watermark.png', true); // Location of transparent watermark to stamp previews with
define("THUMBNAILHEIGHT", 100, true); // Height of thumbnails
define("THUMBNAILWIDTH", 150, true); // Width of thumbnails
define("PREVIEWHEIGHT", 660, true); // Height of image previews
define("PREVIEWWIDTH", 440, true); // Width of image previews
define("GALLERYTITLE", 'gallery', true); // Browser title for site

// RSS Settings
define("RSSLOCATION",$_SERVER['DOCUMENT_ROOT']."gallery/rss.xml",true);
define("RSSTITLE","Gallery News Feed",true);
define("RSSLINK","http://www.example.com",true);
define("RSSDESCRIPTION","A News Feed for the Gallery",true);

include(MARKDOWNLOCATION);
include(SMARTYPANTSLOCATION);
include(FUNCTIONSLOCATION);

