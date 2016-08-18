<?php 
/*add_action('wp_head','facebook_share');

function facebook_share()
{
	$ytid = get_post_meta(get_the_id(), "_yt_id", true);
	echo '<meta property="og:image" content="http://img.youtube.com/vi/'.$ytid.'/mqdefault.jpg">';
	echo '<meta property="og:image:type" content="image/jpeg">';
	echo '<meta property="og:image:width" content="320">';
	echo '<meta property="og:image:height" content="180">';
} */

get_header(); ?>
<main id="main" role="main">
<h1><?php single_cat_title( $prefix = '', $display = true ); ?></h1>
	<div id="twocolumns">
		<div id="content">
			<div class="c1">            
            <ul class="posts-list">
			<?php $current_query = $wp_query->query_vars;
			$wp_query = new WP_Query();
			$wp_query->query(array(
				$current_query['taxonomy'] => $current_query['term'],
				'paged' => $paged,
				'posts_per_page' => 10,
				'meta_key' => '_views',
				'orderby' => 'meta_value_num',
				'order' => 'DESC',
			));
			while ($wp_query->have_posts()) : $wp_query->the_post(); 
			$ytid = get_post_meta(get_the_id(), "_yt_id", true);
			?>
			<li itemprop="video" itemscope itemtype="http://schema.org/VideoObject">
			<meta itemprop="embedUrl" content="http://www.youtube.com/embed/<?=$ytid?>" />
			<?php if(has_post_thumbnail()) { ?>
            	<div class="img-holder">
                <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('thumbnail'); ?></a>
                </div>
			<?php } else { ?>
			<meta itemprop="thumbnailUrl" content="http://img.youtube.com/vi/<?=$ytid?>/mqdefault.jpg" />
            <div class="img-holder">
            	<a href="<?php the_permalink(); ?>"><img src="http://img.youtube.com/vi/<?=$ytid?>/mqdefault.jpg" /></a>
			</div>
			<? } ?>
            	<div class="description">
                    <h2 itemprop="name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <p itemprop="description"><?php echo strip_tags(get_the_excerpt()); ?></p>
                    <div class="meta">
                        <ul class="share-list">
                            <li><span class="btn-share st_facebook_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>" st_image="http://img.youtube.com/vi/<?=$ytid?>/mqdefault.jpg">Facebook</span></li>
							<li><span class="btn-share st_twitter_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>" st_image="http://img.youtube.com/vi/<?=$ytid?>/mqdefault.jpg">Twitter</span></li>
							<li><span class="btn-share st_pinterest_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>" st_image="http://img.youtube.com/vi/<?=$ytid?>/mqdefault.jpg">Pinterest</span></li>
							<li><span class="btn-share st_email_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>" st_image="http://img.youtube.com/vi/<?=$ytid?>/mqdefault.jpg">Email</span></li>
						   <li class="hud"><span class="hud-img"><a href="http://portal.hud.gov/hudportal/HUD?src=/program_offices/fair_housing_equal_opp"><img width="45" height="43" alt="EQUAL HOUSING OPPORTUNITY" src="/wp-content/themes/InvitationHomes/images/home.png"></a></span></li>
                        </ul>
                    </div>
				</div>
			</li>
			<?php endwhile; 
			wp_pagenavi();
			wp_reset_postdata();?>
		</ul>
		</div>
	</div>
	<aside id="sidebar">
        <div class="search-box">
            <form method="get" id="search-form" class="search-form" action="<?php bloginfo('url');?>">
			<div class="form-main">
				<div class="holder">
					<input type="search" name="s" id="s" value="Search Keywords" onfocus="if (this.value == 'Search Keywords') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Search Keywords';}">
                      <input type="hidden" name="post_type[]" value="video" />
                                 <input type="hidden" name="post_type[]" value="blog" />
					<input type="submit" value="Search Keywords">
				</div>
			</div>
			</form>
            <div class="check-holder">
            	<strong class="title">Video Categories:</strong>
                <?php $args = array( 'hide_empty' => 0, 'exclude'=> 108  );
				$terms = get_terms( 'video_category', $args );
				if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
					$count = count( $terms );
					$term_list = '';
					foreach ( $terms as $term ) {
						$term_list .= '<div class="row"><a href="' . get_term_link( $term ) . '" title="' . sprintf( __( 'View all post filed under %s', 'my_localization_domain' ), $term->name ) . '">' . $term->name . '</a></div>';
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
		</div>
		<?php get_sidebar(); ?>
	</aside>
</div>
</main>
<?php get_footer(); ?>