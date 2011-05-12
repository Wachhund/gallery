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
    
    $exif = exif_read_data($filename,"FILE");
    if (isset($_POST['date'])) {
	$time = strtotime($_POST['date']);
    }
    elseif ($exif) {
    	$time = $exif['FILE']['FileDateTime'];
    } else {
    	$time = time();
  	}
    $date = date("Y-m-d H:i:s",$time);
    $qry = "INSERT INTO `".GALLERYDB."`.`".IMGSTBL."` (galleryid,name,date,description,filename,height,width) VALUES ('".mysql_real_escape_string($_POST['galleryid'])."','".mysql_real_escape_string($_POST['name'])."','".mysql_real_escape_string($date)."','".mysql_real_escape_string($_POST['description'])."','".mysql_real_escape_string($filenameshort)."','".mysql_real_escape_string($height)."','".mysql_real_escape_string($width)."')";
    $result = mysql_query($qry,$cxn);
    if (!($result)) {
    	echo("Error, could not add image to MySQL database - ".mysql_error());
	return;
  	}
  	
  	$s3 = new AmazonS3(AWSPUBLICKEY,AWSPRIVATEKEY);
    $input = $s3->create_object(BUCKET,$filename, array( 'fileUpload' => $filename , 'acl' => AmazonS3::ACL_PRIVATE , 'length' => filesize($filename)));
    if (!($input)) {
    	echo("Error adding file to S3");
	return;
    }
  	
  	unlink($filename);
} 
?>
