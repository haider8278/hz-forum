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
$query_term =  get_query_var( 'term' );
if(isset($_GET['fcat']) || $query_term != ""){
  $fcat = isset($_GET['fcat']) ? $_GET['fcat'] : $query_term;
}else{
  $fcat = "";
}
if(isset($_GET['ftag'])){
  $ftag = $_GET['ftag'];
}else{
  $ftag = "";
}
?>

<div class="sub-content single-sub-content">
	<div class="container">
    	<div class="row">
            <div class="search-content">
            	<div class="col-md-12 f-right">
                    <div class="right-content">
                    	<div class="forums-wrapp">
                        	<div class="forums-selecton">
                            <form method="GET">
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
                                        <option value="<?php echo $t->slug;?>" <?php if($ftag == $t->slug){?>selected<?php }?>><?php echo $t->name;?></option>
                                        <?php }?>
                                    </select>
                                </div>
                            </form>
                            </div>
                            <div class="cat-list">
                            	<ul>
                                    <li <?php if(!isset($_GET['st'])){echo 'class="active"';}?>><a href="<?php echo site_url('forum');?>">Categories</a></li>
                                    <li <?php if(isset($_GET['st']) && $_GET['st'] == 'latest'){echo 'class="active"';}?>><a href="<?php echo site_url('topics/?st=latest');?>">Latest</a></li>
                                    <li <?php if(isset($_GET['st']) && $_GET['st'] == 'top'){echo 'class="active"';}?>><a href="<?php echo site_url('topics/?st=top');?>">Top</a></li>
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
                                    <?php /*?><div class="form-group">
                                        <?php
                                        $content = '';
                                        $editor_id = 'comment';
                                        $settings = array(
                                            'textarea_rows'=> 6,
                                            'editor_class'=>'form-control txtarea'
                                        );
                                        wp_editor( $content, $editor_id );
                                        ?>
                                    </div><?php */?>
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
                            <div class="tooltip-content right-arrow" id="tooltip-content" style="display:none;top:45px;z-index:1111;">
                                <p>You must be login to post a topic.</p>
                            </div>
                            <?php }?>
                        </div>
                        <div class="clearfix"></div>
                        <div class="forums-tbl-wrapp single-forums-tbl-wrapp">
                        	<div class="forums-tbl single-forums-tbl">
                            	<div class="forums-tbl-head">
                                	<div class="forums-tbl-head-row">
                                        <div class="forums-tbl-head-data first">
                                            Topic
                                        </div>
                                        <div class="forums-tbl-head-data second">
                                            Users
                                        </div>
                                        <div class="forums-tbl-head-data third">
                                            Replies
                                        </div>
                                        <div class="forums-tbl-head-data four">
                                            Views
                                        </div>
                                        <div class="forums-tbl-head-data five">
                                            Activiity
                                        </div>
                                	</div>
                                </div>
                                <div class="forums-tbl-body">
                                <?php
                                    $term = get_query_var( 'term' );
                                    $taxonomy = get_query_var( 'taxonomy' );
									if(isset($_GET['fcat']) && $_GET['fcat'] != ""){
										$term = $_GET['fcat'];
										$taxonomy = "topic_categories";
									}
									if(isset($_GET['ftag']) && $_GET['ftag'] != ""){
										$tags = $_GET['ftag'];
										$tag_taxonomy = "topic_tags";
									}else{
										$tags = "";
										$tag_taxonomy = "";
									}
                                    $topics_args = array(
                                        'post_type' =>  'topic',
                                        'post_status'=>'publish',
                                        'posts_per_page'=>10,
                                    );
									if(!empty($term) || !empty($taxonomy) || !empty($tag_taxonomy)){
										$topics_args['tax_query'] = array(
											'relation' => 'OR',
											array(
												'taxonomy' => $taxonomy,
												'field' => 'slug',
												'terms' => $term,
											),
											array(
												'taxonomy' => $tag_taxonomy,
												'field' => 'slug',
												'terms' => $tags,
											)
										);
									}
									
									
									
									//d($topics_args);
                                    $topics = get_posts($topics_args);
                                    //d($topics);
                                    foreach($topics as $t){
                                        $tagsss = wp_get_post_terms($t->ID,'topic_tags');
										$replies_args = array(
											'post_type'     =>  'reply',
											'post_status'   =>  'pulish',
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
										
                                ?>
                                    <div class="forums-tbl-body-row">
                                    	<div class="hide-desk show-mob">
                                            <div class="forums-tbl-head-mob">Topic</div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="forums-tbl-body-data first">
                                        	<div class="latest-rep alternate">
                                            	<span class="latest-status"><i class="fa fa-thumb-tack"></i></span>
                                                <a href="<?php echo get_the_permalink($t->ID);?>"><?php echo get_the_title($t->ID);?></a>
                                                <?php if(!empty($tagsss)){?>
                                                <div class="tag">
                                                <?php
                                                foreach( $tagsss as $tgs ) {
				                                echo '<a href="' . get_term_link( $tgs, 'topic_tags' ) . '">' . esc_html( $tgs->name ) . '</a>, ';
                                                }
                                                ?>
                                                </div>
                                                <?php }?>
                                                <div class="cat-desc"><?php echo $t->post_content;?></div>
                                            </div>
                                        </div>
                                        <div class="hide-desk show-mob"><div class="forums-tbl-head-mob single-forums-tbl-head-mob">Users</div><div class="forums-tbl-head-mob single-forums-tbl-head-mob">Replies</div><div class="forums-tbl-head-mob single-forums-tbl-head-mob">Views</div><div class="forums-tbl-head-mob single-forums-tbl-head-mob">Activity</div><div class="clearfix"></div></div>
                                        <div class="forums-tbl-body-data second">
                                        	<div class="users-ico">
                                            	<?php
													$rn = 1;
													foreach($replies as $rep){
														if($rn>2){break;}
														$auther_id = $rep->post_author;
														$avatar	= get_avatar_url($author_id,array('size'=>45));
														$cover_id = get_user_meta($auther_id, 'profile_pic', true);
														$cover = wp_get_attachment_image_url( $cover_id, 'thumbnail', "", array( "class" => "img-responsive" ) );
														(empty($cover)) ? $avatar=esc_url( get_avatar_url($auther_id,array('size'=>45)) ) : $avatar=$cover;
												?>
                                            	<a href="javascript::"><img alt="" src="<?php echo $avatar;?>"></a>
                                                <?php
												 $rn++;
													}
												?>
                                            </div>
                                        </div>
                                        <div class="forums-tbl-body-data third">
                                        	<?php echo count($replies);?>
                                        </div>
                                        <div class="forums-tbl-body-data four">
                                        	<?php echo get_post_meta($t->ID,'total_views',true)?>
                                        </div>
                                        <div class="forums-tbl-body-data five">
                                        	<a href="#" class="normal"><?php the_time("M Y");?></a>
                                        </div>
                                	</div>
                                <?php
                                    }
                                ?>
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
jQuery(document).ready(function($){
	$("#menu-item-272").removeClass("current-menu-item");
	$("#menu-item-3605").addClass("current-menu-item");
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