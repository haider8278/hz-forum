<?php
/**
 * Custom meta-box view
 *
 * @package    Hz_Timeline
 * @subpackage Hz_Timeline_Admin
 * @author     HZA
 */
 
function halfHourTimes() {
  $formatter = function ($time) {
    if ($time % 3600 == 0) {
      return date('H:i', $time);
    } else {
      return date('H:i', $time);
    }
  };
  $halfHourSteps = range(0, 47*1800, 1800);
  return array_map($formatter, $halfHourSteps);
} 
$spost = $this->post;
$slide_id = $spost->ID;

?><input type="hidden" name="<?php print esc_attr( sprintf( '%s-nonce', $slide_id ) ); ?>" value="<?php print esc_attr( $this->nonce ); ?>">
<style>
.wp-picker-container, .wp-picker-container:active{
	width:100%;
}
.wp-color-result::after{
	line-height:26px !important;
}
.wp-color-result{
	height:25px !important;
	margin:0 !important;
}
.wp-core-ui .button-group.button-small .button, .wp-core-ui .button.button-small{
	width:auto;
}
label {
    display: block;
}
</style>
<ul class="timline_ul">
	<li>
    	<label>Year</label>
        <input type="text" name="start_year" value="<?=get_post_meta($slide_id,'start_year',true);?>" />
        <?php /*?><select name="start_year">
        	<?php
			  $selected_year = get_post_meta($slide_id,'start_year',true);
			 for($y=date("Y");$y>=1970;$y--){?>
            <option value="<?=$y?>" <?php if($y==$selected_year){?> selected="selected"<?php }?>><?=$y?></option>
            <?php }?>
        </select><?php */?>
    </li>
    <li>
    	<label>Month</label>
        <input type="text" name="start_day" value="<?=get_post_meta($slide_id,'start_day',true);?>" />
        <?php /*?><select name="start_day">
        	<?php
			$selected_month = get_post_meta($slide_id,'start_month',true);
			 for($m=1;$m<=12;$m++){?>
            <option value="<?=$m?>" <?php if($m==$selected_month){?> selected="selected"<?php }?>><?=$m?></option>
            <?php }?>
        </select><?php */?>
    </li>
    <li>
    	<label>Day</label>
        <input type="text" name="start_day" value="<?=get_post_meta($slide_id,'start_day',true);?>" />
        <?php /*?><select name="start_day">
        	<?php
			$selected_day = get_post_meta($slide_id,'start_day',true);
			 for($d=1;$d<=31;$d++){?>
            <option value="<?=$d?>" <?php if($d==$selected_day){?> selected="selected"<?php }?>><?=$d?></option>
            <?php }?>
        </select><?php */?>
    </li>
    <li>
    	<label>Time</label>
        <input type="text" name="start_time" value="<?=get_post_meta($slide_id,'start_time',true);?>" />
      <?php /*?><select name="start_time">
        	<?php
				$times = halfHourTimes();
				$selected_time = get_post_meta($slide_id,'start_time',true);
			 foreach($times as $t){?>
            <option value="<?=$t?>" <?php if($t==$selected_time){?> selected="selected"<?php }?>><?=$t?></option>
            <?php }?>
        </select><?php */?>
    </li>
</ul>
<ul class="timline_ul">
	<li>
    	<label>End Year</label>
        <input type="text" name="end_year" value="<?=get_post_meta($slide_id,'end_year',true);?>" />
     <?php /*?> <select name="end_year">
        	<?php
			$selected_eyear = get_post_meta($slide_id,'end_year',true);
			 for($ey=date("Y");$ey>=1970;$ey--){?>
            <option value="<?=$ey?>" <?php if($ey==$selected_eyear){?> selected="selected"<?php }?>><?=$ey?></option>
            <?php }?>
        </select><?php */?>
    </li>
    <li>
    	<label>End Month</label>
        <input type="text" name="end_month" value="<?=get_post_meta($slide_id,'end_month',true);?>" />
      <?php /*?><select name="end_month">
        	<?php
			$selected_emonth = get_post_meta($slide_id,'end_month',true);
			 for($em=1;$em<=12;$em++){?>
            <option value="<?=$em?>" <?php if($em==$selected_emonth){?> selected="selected"<?php }?>><?=$em?></option>
            <?php }?>
        </select><?php */?>
    </li>
    <li>
    	<label>End Day</label>
        <input type="text" name="end_day" value="<?=get_post_meta($slide_id,'end_day',true);?>" />
      <?php /*?><select name="end_day">
        	<?php
			$selected_eday = get_post_meta($slide_id,'end_day',true);
			 for($ed=1;$ed<=31;$ed++){?>
            <option value="<?=$ed?>" <?php if($ed==$selected_eday){?> selected="selected"<?php }?>><?=$ed?></option>
            <?php }?>
        </select><?php */?>
    </li>
    <li>
    	<label>End Time</label>
        <input type="text" name="end_time" value="<?=get_post_meta($slide_id,'end_time',true);?>" />
     <?php /*?> <select name="end_time">
        	<?php
				$times = halfHourTimes();
				$selected_etime = get_post_meta($slide_id,'end_time',true);
			 foreach($times as $et){?>
            <option value="<?=$et?>" <?php if($et==$selected_etime){?> selected="selected"<?php }?>><?=$et?></option>
            <?php }?>
        </select><?php */?>
    </li>
</ul>
<ul class="timline_ul">
	<li>
      <label>Type</label>
      <select name="type">
      <?php $selected_type = get_post_meta($slide_id,'type',true); ?>
        	<option value="events" <?php if('events'==$selected_type){?> selected="selected"<?php }?>>Event</option>
            <option value="title" <?php if('title'==$selected_type){?> selected="selected"<?php }?>>Title</option>
            <option value="era" <?php if('era'==$selected_type){?> selected="selected"<?php }?>>Era</option>
            <option value="scale" <?php if('scale'==$selected_type){?> selected="selected"<?php }?>>Scale</option>
        </select>
  </li>
    <li>
		<?php $selected_group = get_post_meta($slide_id,'group',true); ?>
        <label>Group</label>
        <input type="text" name="group" value="<?=$selected_group ?>" />
    </li>
    <li style="width:45%">
		<?php
		$selected_bg = get_post_meta($slide_id,'background',true);
		if($selected_bg == ''){$selected_bg = get_option('hz_timeline_background');}
		 ?>
        <label>Background</label>
        <input class="background" type="text" name="background" value="<?=$selected_bg ?>" />
    </li>
</ul>
<ul class="timeline_ul">
	<li>
    <?php
		$selected_bg_img = get_post_meta($slide_id,'background_image',true);
		if($selected_bg_img == ''){$selected_bg_img = get_option('hz_timeline_background_image');}
	?>
    	<label>Background Image</label>
    	<input id="background_image" type="text" size="36" name="background_image" value="<?=$selected_bg_img?>" />
		<input id="upload_image_button" type="button" value="Upload Image" />
        <img id="bg_thumb" src="<?=$selected_bg_img?>" style="width:100px;display:block;" />
    </li>
</ul>
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