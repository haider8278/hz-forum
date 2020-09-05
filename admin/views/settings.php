<?php
/**
 * Plugin settings page
 *
 * @package    Hz_Timeline
 * @subpackage Hz_Timeline_Admin
 * @author     HZA
 */
 if(!empty($_POST)){
	 foreach($_POST as $key=>$val){
		 update_option($key,$val);
	 }
 }
?>
<style>
.wp-color-result{
	margin:0 !important;
}
label {
    display: block;
}
</style>
<div class="wrap timeline-settings-page">
	<h1><?php echo esc_html__( 'Settings', 'hz-timeline' ); ?></h1>
	<form id="timeline-shortcode-form" action="<?php //print admin_url( 'edit.php?post_type=hza_slide' ); ?>" method="post">
		<table class="timeline-options">
			<tbody>
			<tr>
				<td style="width:30%">
					<div class="field-group">
                    <?php
						$width = get_option('hz_timeline_width');
						if(empty($width)){
							update_option('hz_timeline_width','100%');
						}
						$height = get_option('hz_timeline_height');
						if(empty($height)){
							update_option('hz_timeline_height','670px');
						}
					?>
						<label for="timeline-width"><?php echo esc_html__( 'Width:', 'hz-timeline' ); ?></label>
						<input id="timeline-width" type="text" name="hz_timeline_width" value="<?=get_option('hz_timeline_width');?>">
						<p class="info-text"><?php echo __( 'Controls the overall site width<br> in px or %, ex: 100% or 1170px.', 'hz-timeline' ); ?></p>
					</div>
				</td>
                <td style="width:30%">
					<div class="field-group">
						<label for="timeline-height"><?php echo esc_html__( 'Height:', 'hz-timeline' ); ?></label>
						<input id="timeline-height" type="text" name="hz_timeline_height" value="<?=get_option('hz_timeline_height');?>">
						<p class="info-text"><?php echo __( 'Controls the overall site height<br> in px, ex: 670px.', 'hz-timeline' ); ?></p>
					</div>
				</td>
                <td style="width:40%">
					<div class="field-group">
						<label for="timeline-background"><?php echo esc_html__( 'Background:', 'hz-timeline' ); ?></label>
						<input class="background" id="timeline-background" type="text" name="hz_timeline_background" value="<?=get_option('hz_timeline_background');?>">
						<p class="info-text"><?php //echo esc_html__( 'Timeline Background', 'hz-timeline' ); ?></p>
					</div>
				</td>
                </tr>
                <tr>
                <td style="width:30%">
                	<div class="field-group">
					<?php $selected_bg_img = get_option('hz_timeline_background_image');?>
                        <label>Background Image</label>
                        <input id="background_image" type="text" name="hz_timeline_background_image" value="<?=$selected_bg_img?>" />
                        <input id="upload_image_button" type="button" value="Upload Image" />
                        <img id="bg_thumb" src="<?=$selected_bg_img?>" style="width:100px;display:block;" />
                    </div>
                </td>
                <td></td><td></td>
                </tr>
                <tr>
                <td>
                    <div class="field-group">
                        <input class="button button-primary" id="submit" type="submit" value="Submit">
                    </div>
            	</td>
			</tr>
			</tbody>
		</table>
	</form>
</div>
<script>
jQuery(document).ready(function($) {
 
	$('#upload_image_button').click(function() {
	 formfield = $('#background_image').attr('name');
	 tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
	 return false;
	});
	 
	window.send_to_editor = function(html) {
		console.log(html);
	 imgurl = $(html).attr('src');
	 $('#background_image').val(imgurl);
	 $("#bg_thumb").attr('src',imgurl);
	 tb_remove();
	}
 
});
</script>
