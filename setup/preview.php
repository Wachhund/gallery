<?php
include("config.php");
include(AMAZONLOCATION);
//error_reporting(E_ALL);
if (isset($_GET['imageid'])) {
	$qry = "SELECT * FROM `".GALLERYDB."`.`".IMGSTBL."` WHERE `id`=".$_GET['imageid'];
	$result = mysql_query($qry,$cxn);
	if (!($result)) {
		die("Error retrieving image details from MySQL - ".mysql_error($cxn));
	}
	if (mysql_num_rows($result) != 1) {
		die("Error, wrong number of rows returned - ".mysql_num_rows($result));
	}
	while (	$row = mysql_fetch_assoc($result) ) {
		if (file_exists(UPLOADDIR.$row['filename'])) {
			unlink(UPLOADDIR.$row['filename']);
		}
		$s3 = new AmazonS3(AWSPUBLICKEY,AWSPRIVATEKEY);
		$picture = $s3->getObject(BUCKET,$row['filename'],array( 'fileDownload' => UPLOADDIR.$row['filename'] ));
		//var_dump($picture);
		$watermark = imagecreatefrompng(WATERMARKLOCATION);

		// Get new dimensions
		$width = $row['width'];
		$height = $row['height'];

		// Resample
		$image_p = imagecreatetruecolor(PREVIEWWIDTH, PREVIEWHEIGHT);
		$image = imagecreatefromstring(file_get_contents(UPLOADDIR.$row['filename']));
		imagecopyresampled($image_p, $image, 0, 0, 0, 0, PREVIEWWIDTH, PREVIEWHEIGHT, $width, $height);
		imagecopymerge($image_p, $watermark, 0, 0, 0, 0, PREVIEWWIDTH, PREVIEWHEIGHT, 15);

		// Output
		header("Content-type: image/jpeg");
		imagejpeg($image_p, NULL, 100);
		imagedestroy($image);
		imagedestroy($image_p);
		unlink(UPLOADDIR.$row['filename']);
	}
} else {
echo("Add a GET string for imageid!!!");
}
?>
