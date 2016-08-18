<?php get_header(); ?>
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
				'posts_per_page' => 5,
			));
			while ($wp_query->have_posts()) : $wp_query->the_post(); 
			$ytid = get_post_meta(get_the_id(), "_yt_id", true);
			?>
			<li>
			<?php if(has_post_thumbnail()) { ?>
            	<div class="img-holder">
                <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(array(300,210)); ?></a>
                </div>
			<?php } else { ?>
            <div class="img-holder">
            	<a href="<?php the_permalink(); ?>"><img src="http://img.youtube.com/vi/<?=$ytid?>/mqdefault.jpg" /></a>
			</div>
			<? } ?>
            	<div class="description">
                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <p><?php the_excerpt(); ?></p>
                      <meta property="og:title" content="<?php the_title(); ?>"/>
                        <?php global $post;
                        $url = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );?>
                        <meta property="og:url" content="<?php the_permalink(); ?>"/>
                        <meta property="og:image" content="<?php echo $url; ?>"/>
                        <meta property="og:site_name" content="Invitation Homes"/>                           
                        <meta property="og:description" content="<?php the_advanced_excerpt(); ?>"/>
                    <div class="meta">
                        <ul class="share-list">
                            <li><span class="btn-share st_facebook_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>">Facebook</span></li>
                            <li><span class="btn-share st_twitter_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>">Twitter</span></li>
                            <li><span class="btn-share st_pinterest_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>">Pinterest</span></li>
                            <li><span class="btn-share st_email_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>">Email</span></li>
                           <!--  <li class="hud"><span class="hud-img"><a href="http://portal.hud.gov/hudportal/HUD?src=/program_offices/fair_housing_equal_opp"><img width="45" height="43" alt="EQUAL HOUSING OPPORTUNITY" src="/wp-content/themes/InvitationHomes/images/home.png"></a></span></li> -->
                            <li><span><a href="<?php get_bloginfo_rss('rss2_url'); ?>feed"><img src="<?php echo get_template_directory_uri(); ?>/images/rss_blog.png" /></a></span></li>
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
                	<strong class="title">Blog Categories:</strong>
                    <?php $args = array( 'hide_empty' => true,'exclude'=> array(1,3,5,9) );
					$terms = get_terms( 'blog-categories', $args );
					if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
						$count = count( $terms );
						$term_list = '';
						foreach ( $terms as $term ) {
							//$active = (get_queried_object_id() == $category->ID) ? ' current_page_item active' : '';
							$active = (get_queried_object_id() == $term->term_id) ? ' current_page_item active' : '';
							$term_list .= '<div class="row"><a class="'.$active.'" href="' . get_term_link( $term ) . '" title="' . sprintf( __( 'View all post filed under %s', 'my_localization_domain' ), $term->name ) . '">' . $term->name . '</a></div>';
							}
						echo '<div class="row"><a href="/blog/">View all</a></div>';
						echo $term_list;
					}?>								
				</div>
		</div>
		<?php get_sidebar(); ?>
	</aside>
</div>
</main>
<?php get_footer(); ?>