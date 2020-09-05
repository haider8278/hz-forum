<?php
/**
 * Template Forum
 *
 * @package    HZ Forum
 * @subpackage Views
 * @author     TCAW
 * @license    GPL-3.0+
 * @copyright  2018 TCAW
 */
get_header();
$forum_categories = get_terms(array('taxonomy'=>'topic_categories','hide_empty' => false));
$forum_tags = get_terms(array('taxonomy'=>'topic_tags','hide_empty' => false));
if(isset($_GET['fcat'])){
  $fcat = $_GET['fcat'];
}else{
  $fcat = "";
}
if(isset($_GET['ftag'])){
  $ftag = $_GET['ftag'];
}else{
  $ftag = "";
}
if(!empty($ftag)){
  wp_redirect(site_url('topics/?ftag='.$ftag));
  exit();
}
?>
<div class="sub-content single-sub-content">
  <div class="container">
    	<div class="row">
            <div class="col-md-12 wp_content">
                <h1><?=the_title();?></h1> 
                <?php the_content();?>
            </div>
    	</div>
  </div>
  <div class="container">
    <div class="row">
      <div class="search-content">
        <div class="col-md-12 f-right">
          <div class="right-content">
            <div class="forums-wrapp">
              <div class="forums-selecton">
                <form method="GET" >
                  <div class="sorty">
                    <select name="fcat">
                      <option value="">All Categories</option>
                      <?php foreach($forum_categories as $c){?>
                      <option value="<?php echo $c->slug;?>" <?php if($fcat == $c->slug){?>selected<?php }?>><?php echo $c->name;?></option>
                      <?php }?>
                    </select>
                  </div>
                  <div class="sorty">
                    <select name="ftag">
                      <option value="">All Tags</option>
                      <?php foreach($forum_tags as $t){?>
                      <option value="<?php echo $t->slug;?>" <?php if($fcat == $t->slug){?>selected<?php }?>><?php echo $t->name;?></option>
                      <?php }?>
                    </select>
                  </div>
                </form>
              </div>
              <div class="cat-list">
                <ul>
				  <li class="active"><a href="<?php echo site_url('community');?>">Categories</a></li>
                  <li><a href="<?php echo site_url('topics/?st=latest');?>">Latest</a></li>
                  <li><a href="<?php echo site_url('topics/?st=top');?>">Top</a></li>
                  <!--<li><a href="<?php echo site_url('topics/?st=subscribe');?>">Subscribed</a></li>
                  <li><a href="<?php echo site_url('topics/?st=following');?>">Following</a></li>
                  <li><a href="#">FAQ</a></li>-->
                </ul>
              </div>
            </div>
            <div class="forums-wrapp-right" style="position:relative;">
                <?php if(is_user_logged_in()){?>
                <a href="#postnewtopic" class="newtopic"><i class="fa fa-plus"></i> New Topic</a>
                <div id="postnewtopic" class="white-popup mfp-hide">
                    <form id="login" class="post_new_topic" action="login" method="post">
                        <h2 class="text-center">Create a new Topic</h2>
                        <div class="form-group">
                            <input class="form-control" name="title" placeholder="What is this discussion about in one brief sentence" type="text">
                        </div>
                        <div class="form-group">
                            <select class="form-control" id="select_category" name="category">
                                <option value="" selected="">Select a category...</option>
                            <?php foreach($forum_categories as $c){?>
                                <option value="<?php echo $c->term_id;?>"><?php echo $c->name;?></option>
                            <?php }?>
                            </select>
                        </div>
                        <div class="form-group">
                            <textarea name="content" placeholder="Type here. Use Markdown, BBCode, or HTML to format. Drag or paste images url." class="form-control txtarea" rows="5" id="newtopic_editor"></textarea>
                        </div>
                        <div class="form-group">
                            <input name="tags" class="form-control" placeholder="choose optional tags for this topic" type="text" data-role="tagsinput">
                        </div>
                        <div class="form-group">
                            <input value="Submit" class="addset" id="" type="submit">
                            <a href="javascript:;" class="addset addset-two close-popup">Cancel</a>
                        </div>
                        </form>
                </div>
                <?php }else{?>
                <button class="hzf-tooltip hzf-button disabled" disabled="disabled"><i class="fa fa-plus"></i> New Topic</button>
                <div class="tooltip-content right-arrow" id="tooltip-content" style="display: none;top:45px;z-index:1111;">
                	<p>You must be login to post a topic.</p>
              	</div>
                <?php }?>
            </div>
            <div class="clearfix"></div>
            <div class="forums-tbl-wrapp">
              <div class="forums-tbl">
                <div class="forums-tbl-head">
                  <div class="forums-tbl-head-row">
                    <div class="forums-tbl-head-data first"> Category </div>
                    <div class="forums-tbl-head-data second"> Latest </div>
                    <div class="forums-tbl-head-data third"> Topics </div>
                  </div>
                </div>
                <div class="forums-tbl-body">
                <?php
                  $forum_cat = get_terms(array(
                                'taxonomy'=>'topic_categories',
                                'hide_empty' => false,
                                'name'  => $fcat,
                              ));
					//d($forum_cat);		  
                  foreach($forum_cat as $fc){
                    $sub_cat = get_term_children( $fc->term_id, 'topic_categories' );
                    $desc = term_description( $fc->term_id, 'topic_categories' );
                ?>
                  <div class="forums-tbl-body-row">
                    <div class="hide-desk show-mob">
                      <div class="forums-tbl-head-mob">Category</div>
                      <div class="clearfix"></div>
                    </div>
                    <div class="forums-tbl-body-data first">
                      <div>
                        <h3><a href="<?php echo get_term_link( $fc, 'topic_categories' );?>">
                          <span class="cat-name"><?php echo $fc->name;?>
                          </span>
                          </a>
                        </h3>
                        <div class="cat-desc"><?php echo $desc;?></div>
                        <div class="clearfix"></div>
                        <div class="sub-cat">
                          <?php
                            foreach($sub_cat as $sc){
                              $sct = get_term_by( 'id', $sc, 'topic_categories' );
                          ?>
                          <a href="<?php echo get_term_link( $sc, 'topic_categories' );?>"><span class="box"></span><span class="box-desc"><?php echo $sct->name;?></span></a>
                          <?php }?>
                        </div>
                      </div>
                    </div>
                    <div class="hide-desk show-mob">
                      <div class="forums-tbl-head-mob">Latest</div>
                      <div class="forums-tbl-head-mob alternate">Topics</div>
                      <div class="clearfix"></div>
                    </div>
                    <div id="forum_cat_<?=$fc->term_id?>" class="forums-tbl-body-data second" style="position:relative;">
                      <?php
                        $args = array(
                          'post_type'=>'topic',
                          'status'  =>'publish',
                          'posts_per_page' => '-1',
                          'tax_query' => array(array(
                            'taxonomy' => $fc->taxonomy,
                            'field' => 'slug',
                            'terms' => $fc->slug,
                          )),
                          'date_query' => array(
                              array(
                                  'year' => date('Y'),
                                  'week' => date('W'),
                              ),
                          'fields' => 'ids' // only return an array of post IDs
                            ),
                        );
                        $results = get_posts( $args );
                        $weekly = count( $results );
                        $args = array(
                          'post_type'=>'topic',
                          'status'  =>'publish',
                          'posts_per_page' => '-1',
                          'tax_query' => array(array(
                            'taxonomy' => $fc->taxonomy,
                            'field' => 'slug',
                            'terms' => $fc->slug,
                          )),
                          'date_query' => array(
                              array(
                                  'year' => date('Y'),
                                  'month' => date('M'),
                              ),
                          'fields' => 'ids' // only return an array of post IDs
                            ),
                        );
                        $results = get_posts( $args );
					    $monthly = count( $results );
                        $topics = get_posts(array(
                                              'post_type'=>'topic',
                                              'status'  =>'publish',
                                              'posts_per_page' => -1,
                                              'orderby' =>'ID',
                                              'order' =>'desc',
                                              'tax_query' => array(array(
                                                  'taxonomy' => $fc->taxonomy,
                                                  'field' => 'slug',
                                                  'terms' => $fc->slug,
                                              ))
                                          ));
					  $topic_count = 1;
                      foreach($topics as $t){
						  $replies_args = array(
								'post_type'     =>  'reply',
								'post_status'   =>  'publish',
								'posts_per_page'=>  -1,
								'order'         =>  'ASC',
								'meta_query'    =>  array(
									array(
										'key'   =>  'topic_id',
										'value' =>  $t->ID,
									),
								),
						   );
							$replies = get_posts($replies_args);
							$r_dates=array();
							foreach($replies as $rep){
								$r_dates[] = strtotime($rep->post_date);
								$get_post_year = date("Y",strtotime($rep->post_date));
								$get_post_month = date("m",strtotime($rep->post_date));								
								if(date('Y')==$get_post_year && date('m')==$get_post_month){
									$monthly = $monthly+1;
								}
							}
							if(!empty($r_dates)){
								$max_date = max($r_dates);
								$total_days = days_from_now(date("Y-m-d",$max_date));								
							}else{
								$total_days = days_from_now(get_the_date("Y-m-d",$t->ID));
							}
							$replies_count =  count($replies);
							if($topic_count > 5){$blur='blureffect';$hide="hide-desk"; $readmore_class = "show_on_mobile";$auto_hight = '';}else{$blur='';$readmore_class = "hide";$hide=""; $auto_hight = 'height:auto;';}	
                      ?>
                      <div class="latest-rep <?= $hide ?>">                        
                        <a class="latest-title" href="<?php echo get_the_permalink( $t->ID)?>"><?php echo get_the_title($t->ID)?> </a>
                        <span class="lates-postat"><span style="font-size:15px;color: #999;"><?php echo $total_days;?>d</span></span>
                        <span class="lates-postat"><span style="font-size:15px;color: #999;">(<?php echo $replies_count;?> Replies)</span></span>
                      </div>                      
                      <?php $topic_count++;}
					  
					  if($topic_count>5){ ?>
						  <span class="<?=$blur?>" style="height:80px;"></span>
                        <a href="javascript:;" style="position:relative;display:block;" class="readmore <?php echo $readmore_class;?>" onclick="expandPost(<?=$fc->term_id?>, this); return false;"><img src="<?php echo get_template_directory_uri();?>/images/arrow-down-symbol.png" alt="">Show More</a>			
					  <?php }
					  
					  ?>
                    </div>
                    <div class="forums-tbl-body-data third">
                      <div class="cat-stats">
                        <div> <?php /*?><span><?php echo $weekly;?><small> / week</small></span><?php */?> <span style="font-size:19px;"><?php echo $monthly;?><small style="font-size:15px;"> / month</small></span> </div>
                        <div> </div>
                      </div>
                    </div>
                  </div>
                <?php }?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php get_footer();?>
<script>
var expandPost = function(i, a) {
		//var h = jQuery('#forum_cat_' + i).height();		
		if (a.innerHTML == '<img src="<?php echo get_template_directory_uri();?>/images/arrow-down-symbol.png" alt="">Show More') {
			a.innerHTML = '<img src="<?php echo get_template_directory_uri();?>/images/arrow-up-symbol.png" alt="">Show Less';
			//jQuery('#forum_cat_' + i).find('.hide-desk').show();
			//jQuery('#forum_cat_' + i).animate({height:h},1000);
			jQuery('#forum_cat_' + i).find('.blureffect').hide();
		} else {
			a.innerHTML = '<img src="<?php echo get_template_directory_uri();?>/images/arrow-down-symbol.png" alt="">Show More';
			jQuery('#forum_cat_' + i).find('.blureffect').show();
			//jQuery('#forum_cat_' + i).animate({height:"110px"},1000);
		}
};
jQuery(document).ready(function($){
 
  $('.readmore').click(function(){
		$this = $(this);
		$this.parent().find('.hide-desk').slideToggle('slow');
  });
	
  $(".sorty select").on('change',function(){
    $(this).closest('form').submit();
  });
  $(".newtopic").on('click',function(){
        $.magnificPopup.open({
            items: {
                src: '#postnewtopic',
                type: 'inline'
            },
			callbacks: {
				open: function() {tinymce.init({ selector:'.txtarea',plugins: "lists, paste",paste_as_text: true,menubar: false,gecko_spellcheck: true})},
				close: function(){tinymce.remove();}
			}
        });
    });
	$(".close-popup").on('click',function(e){
		e.preventDefault();
		$.magnificPopup.close();	
	});
	$(".hzf-tooltip").hover(
			function(){$(this).next('.tooltip-content').fadeIn();},
			function(){$(this).next('.tooltip-content').fadeOut();}
	);
    $(".post_new_topic").submit(function(e){
        e.preventDefault();
		var title = $('input[name="title"]').val();
		var cat_id = $('#select_category option:selected').val();
		var content = tinyMCE.activeEditor.getContent();
		var tags = $('input[name="tags"]').val();
		if(cat_id == "" || content == "" || title == ""){
			alert("Please fill required fields.");
			return fasle;
		}
		console.log(content);
        $.ajax({
            url:'<?php echo admin_url('admin-ajax.php');?>',
            type:'POST',
            data:{'action':'hz_post_topic','title':title,'content':content,'tags':tags,'cat_id':cat_id},
            success:function(resp){
                console.log(resp);
                if($.trim(resp) == "success"){
                    window.location.reload();
                }else{
                    alert("Oops Something went wrong please try again.");
                }
            }
        });
        return false;
    });
});
</script>