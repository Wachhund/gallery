<?php
// Leave in place!! //
error_reporting(0);

// Editable Section Begins Here //

// MySQL Settings provided by your hosting company //
$host = 'toastwaffle.db';
$user = 'webuser';
$pass = 'xs37re!g64';

// MySQL Options for you to set //
define("GALLERYDB",'gallery',true); // Name of database to use
define("IMGSTBL",'photos',true); // Name of table for images
define("GALLERYTBL",'galleries',true); // Name of table for galleries
define("DOWNLOADTBL",'download',true); // Name of table for image downloads
// amazon, jar, markdown, smartypants locations, aws keys

// Installation Location Settings
define("AMAZONLOCATION", $_SERVER['DOCUMENT_ROOT'].'gallery/setup/amazon/sdk.class.php', true); // Location of Amazon AWS SDK class file
define("JUMPLOADERLOCATION", $_SERVER['DOCUMENT_ROOT'].'gallery/setup/jar/', true); // Location of Jumploader Java Archive folder with trailing slash
define("MARKDOWNLOCATION", $_SERVER['DOCUMENT_ROOT'].'gallery/setup/markdown.php', true); // Location of markdown class file
define("SMARTYPANTSLOCATION", $_SERVER['DOCUMENT_ROOT'].'gallery/setup/smartypants.php', true); // Location of markdown class file
define("FUNCTIONSLOCATION", $_SERVER['DOCUMENT_ROOT'].'gallery/setup/functions.php', true); // Location of functions.php file

// Amazon AWS settings
define("AWSPUBLICKEY", 'AKIAI33M2SK4VALRH7LA', true);
define("AWSPRIVATEKEY", 'O9Hc8W3FSz09E1sx9rboSJjbEx6DQKF5Bm5xPI4M', true);
define("BUCKET", 'toastwafflegallery', true);

// Jumploader settings
define("UPLOADDIR", $_SERVER['DOCUMENT_ROOT'].'gallery/uploads/', true); // Location of folder to temporarily upload files to. Must be writable by server
define("UPLOADSTAGEDIR", $_SERVER['DOCUMENT_ROOT'].'gallery/uploads/stage/', true); // Location of folder to temporarily upload file partitions to. Must be writable by server

// Paypal settings
define("BUSINESSID", 'FDF3CBD6VMYCC', true); // Paypal business id, for creation of button
define("PAYPALITEMS", array( 'Print &amp; Mount' , 'Print' , 'Photo CD' , 'Photo Download' ), true); // Array of items for sale through Paypal
define("PAYPALPRICES", array( '5.00' , '3.00' , '4.50' , '4.00' ), true); // Array of prices of items for sale through Paypal
define("PAYPALSHIPPING", '0.50', true); // Shipping per item sold through Paypal

// Gallery Settings
define("WATERMARKLOCATION", $_SERVER['DOCUMENT_ROOT'].'gallery/setup/watermark.png', true); // Location of transparent watermark to stamp previews with
define("THUMBNAILHEIGHT", 100, true); // Height of thumbnails
define("THUMBNAILWIDTH", 150, true); // Width of thumbnails
define("PREVIEWHEIGHT", 660, true); // Height of image previews
define("PREVIEWWIDTH", 440, true); // Width of image previews

include(MARKDOWNLOCATION);
include(SMARTYPANTSLOCATION);
include(FUNCTIONSLOCATION);

