<?php 

/*add_action('wp_head','facebook_share');

function facebook_share()
{
	$ytid = get_post_meta(get_the_id(), "_yt_id", true);
	echo '<meta property="og:image" content="http://img.youtube.com/vi/'.$ytid.'/mqdefault.jpg">';
	echo '<meta property="og:image:type" content="image/jpeg">';
	echo '<meta property="og:image:width" content="320">';
	echo '<meta property="og:image:height" content="180">';
}*/

get_header(); ?>
		<main id="main" role="main">
			<?php the_title('<h1>', '</h1>'); ?>
			<div id="twocolumns">
				<div id="content">
					<div class="c1"><?php 
					while ( have_posts() ) : the_post(); $ytid = get_post_meta(get_the_id(), "_yt_id", true); ?>
						<div class="video-box">
							<meta itemprop="embedUrl" content="http://www.youtube.com/embed/<?=$ytid?>" />
							<meta itemprop="thumbnailUrl" content="http://img.youtube.com/vi/<?=$ytid?>/mqdefault.jpg" />
								<div class="class="player"">
                                <?php $videourl = get_field('video_link'); $videourl = "http://www.youtube.com/embed/{$ytid}?rel=0";
								if ($videourl == '') { } else { echo "<iframe width='720' height='405' src='" . $videourl . "' frameborder='0' allowfullscreen></iframe>";}?>
                                </div>
							<div class="description">
								<h2 itemprop="name"><?php the_title(); ?></h2>
								<p itemprop="description"><?php echo strip_tags(get_the_content(), '<a>'); ?></p>
                                <?php wp_link_pages(); ?>
								<div class="btn-row">
                                    <?php $transcript = get_field('video_transcript');
								if ($transcript == '') { } else {  echo "<h3>Transcript</h3>".$transcript; }?>
									<!-- social plugin container -->
									<ul class="share-list">
							<li><span class="btn-share st_facebook_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>" st_image="http://img.youtube.com/vi/<?=$ytid?>/mqdefault.jpg">Facebook</span></li>
							<li><span class="btn-share st_twitter_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>" st_image="http://img.youtube.com/vi/<?=$ytid?>/mqdefault.jpg">Twitter</span></li>
							<li><span class="btn-share st_pinterest_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>" st_image="http://img.youtube.com/vi/<?=$ytid?>/mqdefault.jpg">Pinterest</span></li>
							<li><span class="btn-share st_email_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>" st_image="http://img.youtube.com/vi/<?=$ytid?>/mqdefault.jpg">Email</span></li>
						   <li class="hud"><span class="hud-img"><a href="http://portal.hud.gov/hudportal/HUD?src=/program_offices/fair_housing_equal_opp"><img width="45" height="43" alt="EQUAL HOUSING OPPORTUNITY" src="/wp-content/themes/InvitationHomes/images/home.png"></a></span></li>
									</ul>
								</div>
							</div>
						</div><?php
						endwhile; ?> 
						<div class="btn-row">
							<a href="<?php echo get_permalink(video_page_id); ?>" class="btn-back">Back to Video Gallery</a>
						</div>
					</div>
				</div>
				<aside id="sidebar">
					<div class="search-box">
            			<form method="get" id="search-form" class="search-form" action="<?php bloginfo('url');?>">
						<div class="form-main">
							<div class="holder">
								<input type="search" name="s" id="s" value="Search Keywords" onfocus="if (this.value == 'Search Keywords') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Search Keywords';}">
								<input type="submit" value="Search Keywords">
                                 <input type="hidden" name="post_type[]" value="video" />
                                 <input type="hidden" name="post_type[]" value="blog" />
							</div>
						</div>
						</form>
           				<div class="check-holder">
            				<strong class="title">Video Categories:</strong>
               					 <?php $args = array( 'hide_empty' => 0, 'exclude'=> 108 );
								$terms = get_terms( 'video_category', $args );
								$post_terms = wp_get_post_terms(get_queried_object_id(), 'video_category');
								if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
									$count = count( $terms );
									$term_list = '';
									foreach ( $terms as $term ) {
										$active = ($post_terms[0]->term_id == $term->term_id) ? ' current_page_item active' : '';
										$term_list .= '<div class="row"><a class="'.$active.'" href="' . get_term_link( $term ) . '" title="' . sprintf( __( 'View all post filed under %s', 'my_localization_domain' ), $term->name ) . '">' . $term->name . '</a></div>';
									}
								echo '<div class="row"><a href="/video-gallery/">View all</a></div>';
								echo $term_list;
								}?>								
						</div>
<!--
						 <div class="property-holder" >
						 <strong class="title"><h2 class="widgettitle">SEE OUR PROPERTIES:</h2></strong>
             <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('custom-sidebar') ) :
      endif; ?>
                    <?php 
					$menu = wp_get_nav_menu_items(104);
					$terms = get_terms( 'market',$args );
					if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
						$count = count( $terms );
						$term_list = '';
						foreach ( $menu as $market ) {
						//print_r(get_term_link( $term ));die;
						   //$active = (get_queried_object_id() == $category->ID) ? ' current_page_item active' : '';
							$term_list .= '<div class="row"><a class="" href="' . $market->url . '" title="' . sprintf( __( "View all posts in %s" ), $term->name ) . '" ' . '>' . $market->title.'</a></div>';
							}
						//echo '<div class="row"><a href="/our-team/">View all</a></div>';
						echo $term_list;
					}?>							
			</div>		
-->
									 <?php 
if ( has_term('maintenance-how-to', 'video_category', $post )) {
echo "<div style='width:100%;height:auto;margin-top:20px;padding:5px;font-size:10px;background:#ffffff;color:#000000;font-style: italic;'>*The information on this web page is intended solely to provide some helpful tips to our tenants. Nothing contained herein is intended to modify any of the obligations set forth in your lease and in the event of a conflict, the terms of your lease controls. If you should have any questions, please contact us.</div>";
} ?>
					</div>
			<?php get_sidebar(); ?>
		</aside>
	</div>
</main>
<script type="text/javascript">
$('.transcript-btn').click(function () {
	$(this).text(function(i, text){
          return text === "Show Transcript" ? "Hide Transcript" : "Show Transcript";
    })
	$('.transcript').slideToggle("fast");
});
</script>
<?php get_footer(); ?>