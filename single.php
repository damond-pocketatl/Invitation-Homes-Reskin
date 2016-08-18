<?php get_header(); ?>
	
		<main id="main" role="main">
			<p class="h1style"><?php // echo "Blog" ?></p>
			<div id="twocolumns">
				
				<div id="content">
					<div class="c1"><?php
						while (have_posts()) : the_post(); ?>
						<div class="post single-post"><?php
							if(has_post_thumbnail()): ?>
								<div class="post-img">
									<?php the_post_thumbnail('full'); ?>
								</div><?php 
							endif; ?>
							<div class="meta"><?php  	
								$postdatetime =  get_the_date('l, F d Y | g:i A');
								$postdate =  get_the_date('Y-m-d');
								$posttime =  get_the_date('G:i');
								$timedate =  $postdate.'T'.$posttime;?>
								<time datetime="<?php echo $timedate;?>"><?php echo $postdatetime.' EST'; ?> <!-- | Filed in --> <?php //the_category(', ') ?></time>
								<ul class="share-list">
									<li><span class="btn-share st_facebook_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>">Facebook</span></li>
									<li><span class="btn-share st_twitter_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>">Twitter</span></li>
									<li><span class="btn-share st_pinterest_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>">Pinterest</span></li>
									<li><span class="btn-share st_email_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>">Email</span></li>
									<!-- <li class="hud"><span class="hud-img"><a href="http://portal.hud.gov/hudportal/HUD?src=/program_offices/fair_housing_equal_opp"><img width="45" height="43" alt="EQUAL HOUSING OPPORTUNITY" src="/wp-content/themes/InvitationHomes/images/home.png"></a></span></li> -->
                            <li><span><a href="<?php get_bloginfo_rss('rss2_url'); ?>feed"><img src="<?php echo get_template_directory_uri(); ?>/images/rss_blog.png" /></a></span></li>
								</ul>
							</div>
							<h1><?php the_title(); ?></h1>
							<p><?php the_content(); ?></p>
                            <?php wp_link_pages(); ?>
						</div>
						<?php endwhile; ?>
						<div class="btn-row">
							<a href="<?php echo get_permalink(blog_page_id); ?>" class="btn-back">Back to Blog</a>
						</div>
                        <!--?php comments_template(); ?-->
					</div>
				</div>
				
				<!-- contain sidebar of the page -->
				<aside id="sidebar">
			<div class="search-box">
				<form method="get" action="<?php echo home_url(); ?>" class="search-form" >
					<div class="form-main">
						<div class="holder">
							<input type="search" name="s" placeholder="Search Keywords">
							<input type="hidden" name='taxonomy' value="category">
                              <input type="hidden" name="post_type[]" value="video" />
                                 <input type="hidden" name="post_type[]" value="blog" />
							<input type="submit" value="Search">
						</div>
					</div>
					<div class="check-holder">
				<strong class="title">Blog Categories:</strong>
                <?php
				global $post;
				if($post->post_type == "blog"):?>
				  <?php $args = array(  'hide_empty' => true,'exclude'=> array(1,3,5,9) );
					$terms = get_terms( 'blog-categories', $args );
					if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
						$count = count( $terms );
						$term_list = '';
						foreach ( $terms as $term ) {
							$active = (get_queried_object_id() == $term->term_id) ? ' current_page_item active' : '';
							$term_list .= '<div class="row"><a class="'.$active.'" href="' . get_term_link( $term ) . '" title="' . sprintf( __( 'View all post filed under %s', 'my_localization_domain' ), $term->name ) . '">'.$term->name . '</a></div>';
							}
						echo '<div class="row"><a href="/blog/" class=" current_page_item active ">View all</a></div>';
						echo $term_list;
					}?>		
                 <?php else:?>   
				<?php $args = array(
					'orderby' => 'name',
					'order' => 'ASC',
					'exclude' => '5',
				);
				$categories = get_categories($args);
				foreach($categories as $category) { 
					$cat_list .='<div class="row"><a href="' . get_category_link( $category->term_id ) . '" title="' . sprintf( __( "View all posts in %s" ), $category->name ) . '" ' . '>' . $category->name.'</a></div> ';
				}
				echo '<div class="row"><a href="/blog/">View all</a></div>';
				echo $cat_list;
				?>
                <?php endif;?>
                	
			</div>
				</form>
			</div>
			<?php get_sidebar(); ?>
		</aside>
			</div><?php
				get_template_part('blocks/inner-boxes');?>		
		</main>
<?php get_footer(); ?>