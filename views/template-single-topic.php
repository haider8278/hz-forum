<?php
/**
 * Template Single Topic
 *
 * @package    HZ Forum
 * @subpackage Views
 * @author     TCAW
 * @license    GPL-3.0+
 * @copyright  2018 TCAW
 */
get_header();
$forum_categories = wp_get_post_terms(array('taxonomy'=>'topic_categories','hide_empty' => false));
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
$user = wp_get_current_user();
?>

<div class="sub-content single-sub-content">
	<div class="container">
    	<div class="row">
            <div class="search-content">
            	<div class="col-md-8">
                    <div class="right-content">
                    	<div class="forums-wrapp">
                        	<div class="forums-selecton">
                            <?php /*?><form method="GET" >
                            	<div class="sorty">
                                    <select name="fcat">
                                    <option value="">all categories</option>
                                    <?php foreach($forum_categories as $c){?>
                                    <option value="<?php echo $c->slug;?>" <?php if($fcat == $c->slug){?>selected<?php }?>><?php echo $c->name;?></option>
                                    <?php }?>
                                    </select>
                                </div>
                                <div class="sorty">
                                    <select name="ftag">
                                        <option>all tags</option>
                                        <?php foreach($forum_tags as $t){?>
                                        <option value="<?php echo $t->slug;?>" <?php if($ftag == $t->slug){?>selected<?php }?>><?php echo $t->name;?></option>
                                        <?php }?>
                                    </select>
                                </div>
                            </form><?php */?>
                            </div>
                            <div class="cat-list">
                            	<?php /*?><ul>
                                    <li><a href="<?php echo site_url('forum');?>">Categories</a></li>
                                    <li <?php if(isset($_GET['st']) && $_GET['st'] == 'latest'){echo 'class="active"';}?>><a href="<?php echo site_url('topics/?st=latest');?>">Latest</a></li>
                                    <li <?php if(isset($_GET['st']) && $_GET['st'] == 'top'){echo 'class="active"';}?>><a href="<?php echo site_url('topics/?st=top');?>">Top</a></li>
                                    <!--<li><a href="<?php echo site_url('topics/?st=subscribe');?>">Subscribed</a></li>
                                    <li><a href="<?php echo site_url('topics/?st=following');?>">Following</a></li>
                                    <li><a href="#">FAQ</a></li>-->
								</ul><?php */?>
                            </div>
                        </div>
                        <?php
                            while(have_posts()) : the_post();
                            $term = get_query_var( 'term' );
                            $taxonomy = get_query_var( 'taxonomy' );
                            $pid = get_the_ID();
							$prev_views = get_post_meta($pid,'total_views',true);
							if(empty($prev_views)){$prev_views = 0;}
							update_post_meta($pid,'total_views',($prev_views+1));

                            $topic_categories = wp_get_post_terms($pid,'topic_categories');
                            $topic_tags = wp_get_post_terms($pid,'topic_tags');
                        ?>
                        <div class="forums-wrapp-right">
                         	<div id="postnewreply" class="white-popup mfp-hide">
                                <form id="login" class="post_reply" action="login" method="post">
                                    <h2 class="text-center">Topic Reply</h2>
                                    <p id="replyfor"><?php the_title(); ?></p>
                                    <div class="form-group">
                                        <textarea placeholder="Type here. Use Markdown, BBCode, or HTML to format. Drag or paste images url." class="form-control txtarea" rows="5" id="reply_editor"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" name="topic_id" value="<?php echo $pid;?>">
                                        <input type="hidden" name="parent_reply_id" value="">
                                        <input value="Submit" class="addset" id="" type="submit">
                                        <a href="javascript:;" class="addset addset-two close_popup">Cancel</a>
                                    </div>
                                    </form>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="forums-post-wrapp">
                        	<div class="forums-post-title">
                            	<h1><?php the_title();?></h1>
                                <div class="sub-cat forums-sub-cat">
                                    <?php
                                    foreach( $topic_categories as $tcat ) {
                                    echo '<a href="' . get_term_link( $tcat, 'topic_categories' ) . '"><span class="box"></span><span class="box-desc">' . esc_html( $tcat->name ) . '</span></a>';
                                    }
                                    if(!empty($topic_tags)){
									?>
                                	<div class="forums-list-tags">
                                        <?php
                                        foreach( $topic_tags as $ttag ) {
                                        echo '<a class="tag-corolla discourse-tag simple" href="' . get_term_link( $ttag, 'topic_tags' ) . '">' . esc_html( $ttag->name ) . '</a>';
                                        }
                                        ?>
                                    </div>
                                    <?php }?>
                                </div>
                            </div>
                            <div class="forums-post-desc">
                            	<?php
								$main_author_id=get_the_author_meta('ID');
								$main_author_avatar = get_avatar_url($main_author_id,array('size'=>45));
								$main_author = get_userdata($main_author_id);
								$main_author_name = $main_author->display_name;
								$main_author_fname = $main_author->first_name;
								$main_authorlname = $main_author->last_name;
								$mcity = get_user_meta($main_author_id, 'user_city', true);
								
								$cover_id = get_user_meta($main_author_id, 'profile_pic', true);
								$cover = wp_get_attachment_image_url( $cover_id, 'thumbnail', "", array( "class" => "img-responsive" ) );
								(empty($cover)) ? $main_author_avatar=esc_url( get_avatar_url($main_author_id,array('size'=>45)) ) : $main_author_avatar=$cover;
								
								$numberofdaysfortopic = days_from_now(get_the_date("Y-m-d",$pid));
								if($numberofdaysfortopic > 30){
									$numberofdaysfortopic = round($numberofdaysfortopic/30).' month ago';
								}else{
									$numberofdaysfortopic = $numberofdaysfortopic.' days ago';
								}
								
								?>
                                <div class="forums-post-desc-rep">
                                    <aside class="quote main" data-post="282" data-topic="281" data-full="true">
                                        <img alt="" src="<?php echo $main_author_avatar;?>" title="<?php echo $main_author_name;?>" class="avatar" style="float:left;margin: 8px 10px 0 10px;">
                                        <div class="title">
                                         <span class="first username Registered_Users" style="color: #0582b6;"><?php echo $main_author_name;?></span>
                                         <div class="desc">
                                            <?php if(!empty($mcity)){ ?>
                                            <i class="fa fa-map-marker"></i> <span class="posts-city" style="padding-right: 10px;"><?php echo $mcity;?></span><?php } ?><span class="user-since">Posted: <?php echo $numberofdaysfortopic ?></span>
                                         </div>
                                        </div>
                                        <blockquote>
                                        	<?php echo the_content();?>
                                            <div class="regular contents">
                                                    <section class="post-menu-area clearfix">
                                                        <div class="post-controls clearfix">
                                                            <div class="actions">
                                                                <?php if(is_user_logged_in()){?>
                                                                <a href="#postnewreply" class="postnewreply newtopic" style="color:#fff;"><i class="fa fa-reply" aria-hidden="true"></i> Post Answer</a>
                                                                <?php }else{?>
                                                                <button class="hzf-tooltip widget-button reply create fade-out postnewreply disabled" disabled="disabled"><i class="fa fa-reply" aria-hidden="true"></i> Post Answer</button>
                                                                <div class="tooltip-content right-arrow" id="tooltip-content" style="display: none;top:45px;z-index:1111;left: auto;right: 0;">
                                                                    <p>You must be login to post a reply.</p>
                                                                </div>
                                                                <?php }?>
                                                            </div>
                                                        </div>
                                                    </section>
                                                </div>
                                        </blockquote>
                                    </aside>
                                </div>
                                
                                <?php
                                $n = 1;
                                $replies_args = array(
                                    'post_type'     =>  'reply',
                                    'post_status'   =>  'publish',
                                    'posts_per_page'=>  -1,
                                    'order'         =>  'DESC',
                                    'meta_query'    =>  array(
                                        array(
                                            'key'   =>  'topic_id',
                                            'value' =>  $pid,
                                        ),
                                    ),
                                );

                                $replies = get_posts($replies_args);
                                foreach($replies as $reply){
                                    $author_id=$reply->post_author;
                                    $avatar = get_avatar_url($author_id,array('size'=>45));
                                    $author = get_userdata($author_id);
                                    $author_name = $author->display_name;
                                    $fname = $author->first_name;
                                    $lname = $author->last_name;
									$city = get_user_meta($author_id, 'user_city', true);
                                    $author_email = $author->user_email;
                                    $registered =  $author->user_registered;
                                    $registered = date( "M Y", strtotime( $registered));
                                    $author_post_count = count_user_posts( $author_id , 'reply' );
									$parent_reply_id_t = get_post_meta($reply->ID,'parent_reply_id',true);
									if(!empty($parent_reply_id_t)){
										$parent_post = get_post($parent_reply_id_t);
									}else{
										$parent_post = NULL;
									}
									$cover_id2 = get_user_meta($author_id, 'profile_pic', true);
									
									$cover2 = wp_get_attachment_image_url( $cover_id2, 'thumbnail', "", array( "class" => "img-responsive" ) );
									
									(empty($cover2)) ? $avatar=esc_url( get_avatar_url($author_id,array('size'=>45)) ) : $avatar=$cover2;
									$numberofdaysforreply = days_from_now(get_the_date("Y-m-d",$reply->ID));
									if($numberofdaysforreply > 30){
										$numberofdaysforreply = round($numberofdaysforreply/30).' month ago';
									}else{
										$numberofdaysforreply = $numberofdaysforreply.' days ago';
									}
									
				                 ?>
                                <div class="forums-post-desc-rep">
                                    <div class="post-desc-body clearfix" id="reply_id<?=$reply->ID;?>">
                                    	<img alt="" src="<?php echo $avatar;?>" title="<?php echo $author_name;?>" class="avatar" style="float: left;margin-right: 10px;">
                                    	<div class="topic-meta-data">
                                        	<div class="names trigger-user-card">
                                            	<span class="first username Registered_Users"><a href="#" data-auto-route="" data-user-card=""><?php echo $author_name;?></a></span>
                                                <!--<span class="second full-name"><a href="#" data-auto-route="#" data-user-card="#"><?php echo $fname." ".$lname;?></a></span>-->
                                                <!-- <span class="user-title"><a href="#" class="user-group">Senior PakWheeler</a></span> -->
                                                <div class="desc">
                                                <?php if(!empty($city)){ ?>
                                                	<i class="fa fa-map-marker"></i> <span class="posts-city"><?php echo $city;?></span><?php } ?><span class="user-since">Posted: <?php echo $numberofdaysforreply ?></span>
                                                </div></div>
                                                <div class="post-info"><a href="#" data-share-url="" data-post-number="<?=$n;?>" class="post-date"><span title="" data-format="" class="relative-date"><?php get_the_date("M Y",$reply);?></span></a>
                                                </div>
                                    	</div>
                                        <div class="regular contents">
                                        	<div class="cooked">
                                            	<?php /*?><?php
												if($parent_post !== NULL){
													$author_id2=$parent_post->post_author;
													$avatar2 = get_avatar_url($author_id2,array('size'=>45));
													$author2 = get_userdata($author_id2);
													$author_name2 = $author2->user_nicename;
													$fname2 = $author2->first_name;
													$lname2 = $author2->last_name;
												?>
                                                <aside class="quote" data-post="<?php echo $parent_reply_id_t?>" data-topic="<?php echo $pid?>" data-full="true">
                                                	<div class="title">
														<div class="quote-controls"><a href="#reply_id<?php $parent_post->ID;?>" title="" class="back"></a></div>
														<img alt="" src="<?php echo $avatar2;?>" class="avatar" width="20" height="20"><?php echo $author_name2;?>:
                                                    </div>
													<blockquote>
														<?php echo wpautop($parent_post->post_content);?>
													</blockquote>
                                            	</aside>
                                                <?php }?><?php */?>
                                                <?php echo wpautop($reply->post_content);?>
                                            </div>
                                            <!--<section class="post-menu-area clearfix">
                                            	<div class="post-controls clearfix">
                                                    <?php
                                                    if(!empty($have_child_replies)){
                                                        $total_child_replies = count($have_child_replies);
                                                    ?>
                                                    <button class="widget-button show-replies" aria-label="<?=$total_child_replies;?> Reply" title="<?=$total_child_replies;?> Reply"><?=$total_child_replies;?> Reply<i class="fa fa-chevron-down" aria-hidden="true"></i></button>
                                                    <?php }?>
                                                    <div class="actions">
                                                    	<button class="widget-button toggle-like like no-text" aria-label="like this post" title="like this post"><i class="fa fa-heart" aria-hidden="true"></i></button>
                                                        <button class="widget-button share no-text" aria-label="share a link to this post" title="share a link to this post" data-share-url="/forums/t/toyota-corolla-11th-gen-facelift-in-pakistan/463389/2?u=pwuser152040975337" data-post-number="2"><i class="fa fa-link" aria-hidden="true"></i></button>
                                                        <button class="widget-button bookmark" aria-label="you've read this post; click to bookmark it" title="you've read this post; click to bookmark it"><div class="read-icon"></div></button>
                                                        <button class="widget-button reply create fade-out postnewreply" aria-label="begin composing a reply to this post" title="begin composing a reply to this post" data-replyid="<?php echo $reply->ID;?>"><i class="fa fa-reply" aria-hidden="true"></i>Reply</button>
                                                    </div>
                                                </div>
                                    		</section>-->
                                    	</div>
                                    </div>
                                </div>
                                <?php $n++;}?>
                            </div>
                        <?php endwhile;?>
                        </div>

                    </div>
                </div>
                <div class="col-md-4"></div>
    		</div>
        </div>
    </div>
</div>
<?php get_footer();?>
<script>
jQuery(document).ready(function($){
	$("#menu-item-272").removeClass("current-menu-item");
	$("#menu-item-3605").addClass("current-menu-item");
	/*tinymce.init({
	  selector: 'textarea',  // change this value according to your HTML
	  images_upload_handler: function (blobInfo, success, failure) {
		var xhr, formData;
		xhr = new XMLHttpRequest();
		xhr.withCredentials = false;
		xhr.open('POST', 'postAcceptor.php');
		xhr.onload = function() {
		  var json;
	
		  if (xhr.status != 200) {
			failure('HTTP Error: ' + xhr.status);
			return;
		  }
		  json = JSON.parse(xhr.responseText);
	
		  if (!json || typeof json.location != 'string') {
			failure('Invalid JSON: ' + xhr.responseText);
			return;
		  }
		  success(json.location);
		};
		formData = new FormData();
		formData.append('file', blobInfo.blob(), fileName(blobInfo));
		xhr.send(formData);
	  }
	});*/

    $(".postnewreply").on('click',function(){
        var topic_id = '<?php echo $pid;?>';
        var parent_reply_id = $(this).data('replyid');
        //$("input#topic_id").val(topic_id);
        $('input[name="parent_reply_id"]').val(parent_reply_id);
        $.magnificPopup.open({
            items: {
                src: '#postnewreply',
                type: 'inline'
            },
			callbacks: {
				open: function() {tinymce.init({ selector:'.txtarea',plugins: "lists, paste",paste_as_text: true,menubar: false,gecko_spellcheck: true})},
				close: function(){tinymce.remove();}
			}
        });
    });
	$(".close_popup").on('click',function(e){
		e.preventDefault();
		$.magnificPopup.close();	
	});
	$(".hzf-tooltip").hover(
			function(){$(this).next('.tooltip-content').fadeIn();},
			function(){$(this).next('.tooltip-content').fadeOut();}
	);
    $(".post_reply").submit(function(e){
        e.preventDefault();
        var formData = $(this).serialize();
		var topic_id = $('input[name="topic_id"]').val();
		var parent_reply_id = $('input[name="parent_reply_id"]').val();
		var reply = tinyMCE.activeEditor.getContent();
		if(reply == ""){
			alert("Reply can not be empty.");
			return fasle;
		}
		console.log(reply);
        $.ajax({
            url:'<?php echo admin_url('admin-ajax.php');?>',
            type:'POST',
            data:{'action':'hz_post_reply',topic_id:topic_id,parent_reply_id:parent_reply_id,reply:reply},
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