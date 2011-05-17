<?php
include("config.php");
include(AMAZONLOCATION);
//----------------------------------------------
//    partitioned upload file handler script
//----------------------------------------------

//
//    specify upload directory - storage 
//    for reconstructed uploaded files
$upload_dir = UPLOADDIR;

//
//    specify stage directory - temporary storage 
//    for uploaded partitions
$stage_dir = UPLOADSTAGEDIR;

//
//    retrieve request parameters
$file_param_name = 'file';
$file_name = $_FILES[ $file_param_name ][ 'name' ];
$file_id = $_POST[ 'fileId' ];
$partition_index = $_POST[ 'partitionIndex' ];
$partition_count = $_POST[ 'partitionCount' ];
$file_length = $_POST[ 'fileLength' ];

//
//    the $client_id is an essential variable, 
//    this is used to generate uploaded partitions file prefix, 
//    because we can not rely on 'fileId' uniqueness in a 
//    concurrent environment - 2 different clients (applets) 
//    may submit duplicate fileId. thus, this is responsibility 
//    of a server to distribute unique clientId values
//    (or other variable, for example this could be session id) 
//    for instantiated applets.
$client_id = $_GET[ 'clientId' ];

//
//    move uploaded partition to the staging folder 
//    using following name pattern:
//    ${clientId}.${fileId}.${partitionIndex}
$source_file_path = $_FILES[ $file_param_name ][ 'tmp_name' ];
$target_file_path = $stage_dir . $client_id . "." . $file_id . 
    "." . $partition_index;
if( !move_uploaded_file( $source_file_path, $target_file_path ) ) {
    echo "Error:Can't move uploaded file";
    return;
}

//
//    check if we have collected all partitions properly
$all_in_place = true;
$partitions_length = 0;
for( $i = 0; $all_in_place && $i < $partition_count; $i++ ) {
    $partition_file = $stage_dir . $client_id . "." . $file_id . "." . $i;
    if( file_exists( $partition_file ) ) {
        $partitions_length += filesize( $partition_file );
    } else {
        $all_in_place = false;
    }
}

//
//    issue error if last partition uploaded, but partitions validation failed
if( $partition_index == $partition_count - 1 &&
        ( !$all_in_place || $partitions_length != intval( $file_length ) ) ) {
    echo "Error:Upload validation error";
    return;
}

//
//    reconstruct original file if all ok
if( $all_in_place ) {
    $file = $upload_dir . $client_id . "." . $file_id;
    $file_handle = fopen( $file, 'w' );
    for( $i = 0; $all_in_place && $i < $partition_count; $i++ ) {
        //
        //    read partition file
        $partition_file = $stage_dir . $client_id . "." . $file_id . "." . $i;
        $partition_file_handle = fopen( $partition_file, "rb" );
        $contents = fread( $partition_file_handle, filesize( $partition_file ) );
        fclose( $partition_file_handle );
        //
        //    write to reconstruct file
        fwrite( $file_handle, $contents );
        //
        //    remove partition file
        unlink( $partition_file );
    }
    fclose( $file_handle );
    //
    // rename to original file
    // NB! This may overwrite existing file
    $fileext = substr($file_name,-4);
    $timestamp = time();
    $filename = $upload_dir . $timestamp . $fileext;
    $filenameshort = $timestamp . $fileext;
    rename($file,$filename);
    list($width,$height) = getimagesize($filename);
    $image = imagecreatefromstring(file_get_contents($filename);
    
		$watermark = imagecreatefrompng(WATERMARKLOCATION);
		$image_p = imagecreatetruecolor(PREVIEWWIDTH, PREVIEWHEIGHT);
		imagecopyresampled($image_p, $image, 0, 0, 0, 0, PREVIEWWIDTH, PREVIEWHEIGHT, $width, $height);
		imagecopymerge($image_p, $watermark, 0, 0, 0, 0, PREVIEWWIDTH, PREVIEWHEIGHT, 15);
		imagejpeg($image_p,UPLOADDIR."preview.jpg");
		
		$image_t = imagecreatetruecolor(THUMBNAILWIDTH, THUMBNAILHEIGHT);
		imagecopyresampled($image_t, $image, 0, 0, 0, 0, THUMBNAILWIDTH, THUMBNAILHEIGHT, $width, $height);
		imagejpeg($image_t,UPLOADDIR."thumb.jpg");
		
		imagedestroy($image);
		imagedestroy($image_p);
		imagedestroy($image_t);
		imagedestroy($watermark);
    
    $exif = exif_read_data($filename,"FILE");
    if (isset($_POST['date']) && ($_POST['date'] != '')) {
	$posttime = strtotime($_POST['date']);
	echo("posttime = $posttime");
    } 
    if ($exif) {
    	$exiftime = $exif['FILE']['FileDateTime'];
	echo("exiftime = $exiftime");
    }
    if ($posttime > 0) {
	$time = $posttime;
    } elseif ($exiftime > 0) {
	$time = $exiftime;
    } else {
	$time = time();
    }
    $date = date("Y-m-d H:i:s",$time);
    $selqry = "SELECT position FROM `".GALLERYDB."`.`".IMGSTBL."` WHERE `galleryid` = '".mysql_real_escape_string($_POST['galleryid'])."' ORDER BY position DESC LIMIT 0 , 1";
    $selresult = mysql_query($selqry,$cxn)
    if (!($selresult)) {
        echo("Error, could not add image to MySQL database - ".mysql_error());
	    return;
  	}
    if (mysql_num_rows($selresult) != 1) {
        $position = 1;
    } else {
        $row = mysql_fetch_assoc($selresult);
        $position = $row['position'] + 1;
    }
    $qry = "INSERT INTO `".GALLERYDB."`.`".IMGSTBL."` (galleryid,position,name,date,description,filename) VALUES ('".mysql_real_escape_string($_POST['galleryid'])."','".$postition."','".mysql_real_escape_string($_POST['name'])."','".mysql_real_escape_string($date)."','".mysql_real_escape_string($_POST['description'])."','".mysql_real_escape_string($filenameshort)."')";
    $result = mysql_query($qry,$cxn);
    if (!($result)) {
    	echo("Error, could not add image to MySQL database - ".mysql_error());
	    return;
  	}
  	
  	$s3 = new AmazonS3(AWSPUBLICKEY,AWSPRIVATEKEY);
    $input = $s3->create_object(BUCKET,$filenameshort, array( 'fileUpload' => $filename , 'acl' => AmazonS3::ACL_PRIVATE , 'length' => filesize($filename)));
    if (!($input)) {
    	echo("Error adding file to S3");
			return;
    }
    $input2 = $s3->create_object(BUCKET,"thumbs/".$filenameshort, array( 'fileUpload' => UPLOADDIR."thumb.jpg" , 'acl' => AmazonS3::ACL_PRIVATE , 'length' => filesize($filename)));
    if (!($input2)) {
    	echo("Error adding file to S3");
			return;
    }
    $input3 = $s3->create_object(BUCKET,"previews/".$filenameshort, array( 'fileUpload' => UPLOADDIR."preview.jpg" , 'acl' => AmazonS3::ACL_PRIVATE , 'length' => filesize($filename)));
    if (!($input3)) {
    	echo("Error adding file to S3");
			return;
    }
  	
  	unlink($filename);
  	unlink(UPLOADDIR."thumb.jpg");
  	unlink(UPLOADDIR."preview.jpg");
} 
return;
?>
