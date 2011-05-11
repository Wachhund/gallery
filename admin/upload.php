<?php
include('config.php'); 
requirelogin();
?>
<?php displaycontent("html-admin-page-header"); ?>
<div class="gallerycontent">
<applet id="jumpLoaderApplet" name="jumpLoaderApplet"
		code="jmaster.jumploader.app.JumpLoaderApplet.class"
		archive="<?php echo(JUMPLOADERLOCATION); ?>jumploader_z.jar"
		width="600"
		height="400" 
		mayscript>
	<param name="uc_uploadUrl" value="<?php echo(SETUPLOCATION); ?>handler.php" />
	<param name="uc_useMetadata" value="true" />
	<param name="uc_metadataDescriptorUrl" value="<?php echo(SETUPLOCATION); ?>metadata.php" />
</applet>
<hr />
<hr />
<?php displaycontent('admin-navigation-bar'); ?>
</div>
<?php displaycontent("html-admin-page-footer"); ?>
