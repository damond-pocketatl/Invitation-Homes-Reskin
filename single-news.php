<?php get_header(); ?>
	
		<main id="main" role="main">
			<h1><?php echo "News" ?></h1>
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
								$draught_links = array();
								$category = get_the_terms( $postID, 'news-categories' );
								foreach ( $category as $term ) {
		                            $draught_links[] = '<a href="' . get_term_link( $term ) . '" title="' . sprintf( __( "View all posts in %s" ), $term->name ) . '" ' . '>' . $term->name.'</a>';
	                                 }
									 $on_draught = join( ", ", $draught_links );
								$timedate =  $postdate.'T'.$posttime;?>
								<time datetime="<?php echo $timedate;?>"><?php echo $postdatetime.' EST'; ?> | Filed in <?php echo $on_draught ?></time>
								<ul class="share-list">
									<li><span class="btn-share st_facebook_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>">Facebook</span></li>
									<li><span class="btn-share st_twitter_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>">Twitter</span></li>
									<li><span class="btn-share st_pinterest_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>">Pinterest</span></li>
									<li><span class="btn-share st_email_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>">Email</span></li>
								</ul>
							</div>
							<h2><?php the_title(); ?></h2>
							<p><?php the_content(); ?></p>
                            <?php wp_link_pages(); ?>
						</div>
						<?php endwhile; ?>
						<div class="btn-row">
							<a href="<?php echo get_permalink(news_page_id); ?>" class="btn-back">Back to News</a>
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
							<input type="search" name="s" id="s" value="Search Keywords" onfocus="if (this.value == 'Search Keywords') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Search Keywords';}">
                    <input type="hidden" name="post_type[]" value="blog" />
                        <input type="hidden" name="post_type[]" value="video" /> 
                        <input type="hidden" name="post_type[]" value="news" /> 
                        <input type="hidden" name="post_type[]" value="wpm-testimonial" /> 
					<input type="submit" value="Search Keywords">
						</div>
					</div>
					<div class="check-holder">
				<strong class="title">News Categories:</strong>
				<?php $args = array(
					'orderby' => 'name',
					'order' => 'ASC',
					'exclude' => features_news,
				);
				$categories = get_terms( 'news-categories', $args );
				//$categories = get_categories($args);
				foreach($categories as $category) { 
					$cat_list .='<div class="row"><a href="' . get_term_link( $category ) . '" title="' . sprintf( __( "View all posts in %s" ), $category->name ) . '" ' . '>' . $category->name.'</a></div> ';
				}
				echo '<div class="row"><a href="'.get_permalink(news_page_id).'">View all</a></div>';
				echo $cat_list;
				?>	
			</div>
				</form>
			</div>
			<?php get_sidebar(); ?>
		</aside>
			</div><?php
				get_template_part('blocks/inner-boxes');?>		
		</main>
<?php get_footer(); ?>