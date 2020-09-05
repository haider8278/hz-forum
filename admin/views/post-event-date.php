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
      return date('g:ia', $time);
    } else {
      return date('g:ia', $time);
    }
  };
  $halfHourSteps = range(0, 47*1800, 1800);
  return array_map($formatter, $halfHourSteps);
} 
$spost = $this->post;
$slide_id = $spost->ID;
echo $slide_id;
?><input type="hidden" name="<?php print esc_attr( sprintf( '%s-nonce', $this->id ) ); ?>" value="<?php print esc_attr( $this->nonce ); ?>">
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
</style>
<ul class="timline_ul">
	<li>
    	<label>Year</label>
        <input type="text" name="year" value="<?=get_post_meta($slide_id,'year',true);?>" />
        <?php /*?><select name="year">
        	<?php
			  $selected_year = get_post_meta($slide_id,'year',true);
			 for($y=date("Y");$y>=1970;$y--){?>
            <option value="<?=$y?>" <?php if($y==$selected_year){?> selected="selected"<?php }?>><?=$y?></option>
            <?php }?>
        </select><?php */?>
    </li>
    <li>
    	<label>Month</label>
        <input type="text" name="month" value="<?=get_post_meta($slide_id,'month',true);?>" />
        <?php /*?><select name="month">
        	<?php
			$selected_month = get_post_meta($slide_id,'month',true);
			 for($m=1;$m<=12;$m++){?>
            <option value="<?=$m?>" <?php if($m==$selected_month){?> selected="selected"<?php }?>><?=$m?></option>
            <?php }?>
        </select><?php */?>
    </li>
    <li>
    	<label>Day</label>
        <input type="text" name="day" value="<?=get_post_meta($slide_id,'day',true);?>" />
       <?php /*?> <select name="day">
        	<?php
			$selected_day = get_post_meta($slide_id,'day',true);
			 for($d=1;$d<=31;$d++){?>
            <option value="<?=$d?>" <?php if($d==$selected_day){?> selected="selected"<?php }?>><?=$d?></option>
            <?php }?>
        </select><?php */?>
    </li>
    <li>
    	<label>Time</label>
        <input type="text" name="time" value="<?=get_post_meta($slide_id,'time',true);?>" />
      <?php /*?><select name="time">
        	<?php
				$times = halfHourTimes();
				$selected_time = get_post_meta($slide_id,'time',true);
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
      <?php /*?><select name="end_year">
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
      <?php /*?><select name="end_time">
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
        </select>
  </li>
    <li>
		<?php $selected_group = get_post_meta($slide_id,'type',true); ?>
        <label>Group</label>
        <input type="text" name="group" value="<?=$selected_group ?>" />
    </li>
    <li style="width:45%">
		<?php $selected_bg = get_post_meta($slide_id,'background',true); ?>
        <label>Background</label>
        <input class="background" type="text" name="background" value="<?=$selected_bg ?>" />
    </li>
</ul>
<input type="text" id="<?php print esc_attr( sprintf( '%s-value', $this->id ) ); ?>" name="<?php print esc_attr( $this->id ); ?>" value="<?php print esc_attr( $this->value ); ?>" placeholder="<?php echo esc_html__( 'Choose date', 'tm-timeline' ); ?>">

<script type="text/javascript">
	(function ($) {
		'use strict';
		$(document).ready(function () {

			var $field = $('#<?php print esc_attr( sprintf( '%s-value', $this->id ) ); ?>');

			$field.datepicker({
				changeMonth: true,
				changeYear : true,
				dateFormat : '<?php echo esc_html( $this->date_format ); ?>',
				buttonText : '<?php  echo esc_html__( 'Choose', 'tm-timeline' ); ?>',
				showOn     : 'both',
				beforeShow : function (input, $input) {
					$('#ui-datepicker-div').addClass('<?php print esc_attr( sprintf( '%s-select-value', $this->id ) ); ?>');
				}
			});
			
		});
	}(jQuery || $));
</script>
