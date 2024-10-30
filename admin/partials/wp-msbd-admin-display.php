<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.webwave.ch
 * @since      1.0.0
 *
 * @package    Wp_Msbd
 * @subpackage Wp_Msbd/admin/partials
 */
 

 global $wpdb;
 
 $upload_dir = wp_upload_dir();
 $downloadlink ="";
 
 function format_size($size) {
      $sizes = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
      if ($size == 0) { return('n/a'); } else {
      return (round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $sizes[$i]); }
}

 if(isset($_POST["submit"])){
	 $files = $_POST["file"];
	 
	 
	 //ZIP File erstellen
	 	
		$zip = new ZipArchive();
		$date = date("d-m-y_h_i_s");
		$filename = dirname (dirname( plugin_dir_path(__FILE__) ))."/archives/".$date.".zip";
		
		if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
		    exit("cannot open <$filename>\n");
		}
		
		$zip->addEmptyDir('test');
		/*foreach($files as $file){
			$filepath = get_attached_file( $file );
			$zip->addFile($filepath, basename($filepath));
		}*/
		
		$zip->close();
		
		$downloadlink =  plugins_url()."/media-select-bulk-downloader/archives/".$date.".zip";
 }
	 ?>
<form method="post" action="upload.php?page=wp-msbd"> 
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">

    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
    
<?if(isset($_POST["submit"])){ ?>
	<div class="notice notice-info inline">
		<h4 style="margin: 10px 0"><?echo "Creating Archive...";?></h4>
		<div id="loading_info" class="spinner is-active" style="height: 30px; float:none;width:auto;padding: 0;background-position:0px 0;padding-left: 30px;padding; top: 2px; margin-left: 0px;">Adding Files to Archive (<font id="files_now">0</font>/<font id="files_total"><?echo count($files); ?></font>)</div>
		<p id="download_btn" style="display: none" ><a href="<? echo $downloadlink;?>" class="button-primary">Download Archive (<font id="zipfilesize"></font>)</a><a style="float: right" target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=6BLU73VRLL34S&lc=US&item_name=webwave%20GmbH&no_note=1&no_shipping=1&rm=1&return=https%3a%2f%2fwww%2ewebwave%2ech&cancel_return=https%3a%2f%2fwww%2ewebwave%2ech&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted" class="button-primary"><i class="fa fa-coffee"></i>Spend a Coffee!</a></p>
	</div>
<?}?>
    
    <?php
		//Anzeige aller Medien:
		$medien = $wpdb->get_results( "SELECT * FROM $wpdb->posts WHERE post_type='attachment'" ); 
		?>
		<?
		echo "<table class='wp-list-table widefat fixed striped media'>";
		echo "<th style='width: 5%; margin: 0'><input id='checkAll' style='margin: 0' type='checkbox'></th>";
		echo '<th style="width: 50%">File</th>';
		echo "<th>Size</th>";
		echo "<th>Filetype</th>";
		foreach ($medien as $media){
			
			$filetype = wp_check_filetype($media->guid);
			?>
			<tr class="msbd_tr" id="<?echo $media->ID;?>">
				<td><input class="msbd_checkbox" id="cb_<?echo $media->ID;?>" name="file[]" value="<?echo $media->ID;?>" type="checkbox"></td>
				<td><div style="width: 60px; display: inline-block"><?
				if(strpos($filetype["type"], "image") !== false){
					echo wp_get_attachment_image( $media->ID, array('60', '60'), "", array( "class" => "img-responsive") );
				}	 
				else{
					?>
					<img src="<?echo get_site_url();?>/wp-includes/images/media/document.png">
					<?
				}
				?>
				</div>
				<div style="width: 80%; display: inline-block; vertical-align: top">
					<p style="margin-bottom: 0; font-weight: bold;vertical-align: top;margin-left: 10px;">
					<?
					echo basename($media->guid); 
					?>
					</p>
					<p style=" font-size: 11px; color: gray;vertical-align: top;margin-left: 10px;">
					<?
					echo $media->guid; 
					?>
					</p>
				</div>
				</td>
				<td><? echo format_size(filesize(get_attached_file($media->ID))); ?></td>
				<td><? echo $filetype["type"]; ?></td>
			</tr>
			<?
		}
		
		echo "</table>"
		?>
		

</div>
<div class="wrap">
	<input id="createArchiveBtn" class="button-primary" disabled="disabled" type="submit" name="submit" value="<?php esc_attr_e( 'Create Archive' ); ?>" />
	<a style="float: right" target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=6BLU73VRLL34S&lc=US&item_name=webwave%20GmbH&no_note=1&no_shipping=1&rm=1&return=https%3a%2f%2fwww%2ewebwave%2ech&cancel_return=https%3a%2f%2fwww%2ewebwave%2ech&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted" class="button-primary"><i class="fa fa-coffee"></i>Spend a Coffee!</a>
	
	
</div>
</form>
<script>
<?if(isset($_POST["submit"])){ ?>

	jQuery(document).ready(function() {
		
		
		var files_now = 0;
		var files_total = <?echo count($files);?>;
		
		/*function checkTotal(){
			if (files_now == files_total){
				jQuery("#loading_info").slideUp();
				jQuery("#download_btn").slideDown();		
			}
		}*/
		
		var files = [<?
			
			foreach($files as $file){
				echo '"';
				echo get_attached_file( $file );
				echo '", ';
			}
		?>];
		
		
		
		function loopfiles(){
			jQuery.ajax({
			  method: "POST",
			  url: "<? echo plugins_url()."/media-select-bulk-downloader/admin"; ?>/addfileToArchive.php",
			  data: { zipfile: "<?echo $filename;?>", file: files[files_now]}
			})
			  .done(function( msg ) {
				files_now++;
				jQuery("#files_now").html(files_now);
				if (files_now == files_total){
					jQuery("#zipfilesize").html(msg);
					jQuery("#loading_info").slideUp();
					jQuery("#download_btn").slideDown();		
				}
				else{
					loopfiles()
					
				}
			});
					

		}
		
		if (files_now != files_total){
			loopfiles();	
		}
		
		
		/*
		function runAni(files){		
		    jQuery.each(files, function(index, value) {
		        setTimeout(function() {
		            jQuery.ajax({
					  method: "POST",
					  url: "<? echo plugins_url()."/wp-msbd/admin"; ?>/addfileToArchive.php",
					  data: { zipfile: "<?echo $filename;?>", file: value}
					})
					  .done(function( msg ) {
						  files_now++;
						  jQuery("#files_now").html(files_now);
						  checkTotal();
					});
		        }, 200 * index);
		    });
		}*/
		

		//runAni(files);
		
		
		
		
		/*for ( var i = 0, l = files.length; i < l; i++ ) {
			
		       
			
		}
		
		jQuery.each(files, function (index, value) {
			
		});*/


		
		//alert(body);
		
	});	
<?}?>
	  jQuery(document).ready(function() {
			jQuery(".msbd_tr").on("click", function(){
				var id = jQuery(this).attr("id");
				
				if(jQuery('#cb_'+id).is(':checked') == true){
					jQuery('#cb_'+id).attr('checked', false);
				}
				else{
					jQuery('#cb_'+id).attr('checked', true);
				}
				if(jQuery( ".msbd_checkbox:checked" ).length > 0){
					jQuery("#createArchiveBtn").prop('disabled', false);
				}
				else{
					jQuery("#createArchiveBtn").prop('disabled', true);
				}
			});
			
			jQuery("#checkAll").on("click", function(){
				if(jQuery('#checkAll').is(':checked') == true){
					jQuery('.msbd_checkbox').attr('checked', true);
				}
				else{
					jQuery('.msbd_checkbox').attr('checked', false);
				}
				if(jQuery( ".msbd_checkbox:checked" ).length > 0){
					jQuery("#createArchiveBtn").prop('disabled', false);
				}
				else{
					jQuery("#createArchiveBtn").prop('disabled', true);
				}
			});
			
			jQuery(".msbd_checkbox").on("click", function(){
				if(jQuery(this).is(':checked') == true){
					jQuery(this).attr('checked', false);
				}
				else{
					jQuery(this).attr('checked', true);
				}
				if(jQuery( ".msbd_checkbox:checked" ).length > 0){
					jQuery("#createArchiveBtn").prop('disabled', false);
				}
				else{
					jQuery("#createArchiveBtn").prop('disabled', true);
				}
			});
	  });
</script>