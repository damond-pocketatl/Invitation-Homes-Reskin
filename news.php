<?php
/*
Template Name: News
*/
get_header(); ?>
<main id="main" role="main">
	<?php the_title('<h1>', '</h1>'); ?>
	<div id="twocolumns">
		<div id="content">
			<div class="c1">
            	<?php if( !is_paged() ) { ?>
				<?php $arrayName = array('post_type' => 'news', 'posts_per_page' => 1,
					'tax_query' => array(
						array(
							'taxonomy' => 'news-categories',
							'field' => 'slug',
							'terms' => 'featured'
						)
					)
				);
				$posts = query_posts($arrayName);
				if ( have_posts()) : while ( have_posts() ) : the_post(); 
				$featured = get_the_id(); $ytid = get_post_meta(get_the_id(), "_yt_id", true); ?>
                <div class="post single-post single-post-alt">
                	<meta itemprop="embedUrl" content="http://www.youtube.com/embed/<?=$ytid?>" />
                	<div class="post-img">
                    	<strong class="note">Featured News</strong>
                        <?php 
								if(has_post_thumbnail()): the_post_thumbnail('full'); endif; ?>
					</div>
					<h2><a itemprop="name" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<p itemprop="description"><?php the_excerpt(); //echo strip_tags(get_the_excerpt()); ?></p>					
					<div class="meta">
						<?php $postdatetime =  get_the_date('l, F d Y | g:i A');
                        $postdate =  get_the_date('Y-m-d');
                        $posttime =  get_the_date('G:i');
                        $timedate =  $postdate.'T'.$posttime;?>
                        <time datetime="<?php echo $timedate;?>"><?php echo $postdatetime.' EST'; ?></time>
                        <ul class="share-list">
                            <li><span class="btn-share st_facebook_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>">Facebook</span></li>
                            <li><span class="btn-share st_twitter_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>">Twitter</span></li>
                            <li><span class="btn-share st_pinterest_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>">Pinterest</span></li>
                            <li><span class="btn-share st_email_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>">Email</span></li>
						  <li class="hud"><span class="hud-img"><a href="http://portal.hud.gov/hudportal/HUD?src=/program_offices/fair_housing_equal_opp"><img width="45" height="43" alt="EQUAL HOUSING OPPORTUNITY" src="/wp-content/themes/InvitationHomes/images/home.png"></a></span></li>
                        </ul>
					</div>
				</div>
                <?php endwhile;?>
				<?php wp_reset_query(); ?>
                <?php endif;?>
				<?php } ?>

				
				<ul class="posts-list">
				<?php $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
				$args = array(
					'post_type' => 'news',
					'posts_per_page' => 5,
					'paged' => $paged
				);
				query_posts($args); 
				if ( have_posts() ) : while (have_posts()) : the_post();
				$ytid = get_post_meta(get_the_id(), "_yt_id", true);?>
                	<li><?php if(has_post_thumbnail()) { ?>
                		<meta itemprop="embedUrl" content="http://www.youtube.com/embed/<?=$ytid?>" />
                    	<div class="img-holder">
                        	<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('medium'); ?></a>
						</div>
						<?php } else { ?>
                        <div class="img-holder">
                        	<meta itemprop="thumbnailUrl" content="http://img.youtube.com/vi/<?=$ytid?>/mqdefault.jpg" />
                        	<a href="<?php the_permalink(); ?>"><img src="http://img.youtube.com/vi/<?=$ytid?>/mqdefault.jpg" /></a>
						</div>
						<? } ?>
                        <div class="description">
                        	<h1 class="h2style"><a itemprop="name" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
                            <p itemprop="description"><?php the_excerpt(); ?></p>
                            <div class="meta">                         
								<time datetime="<?php echo $timedate;?>"><?php echo $postdatetime.' EST'; ?> | Filed in <a href=""></a></time>
                            	<ul class="share-list">
                                	<li><span class="btn-share st_facebook_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>">Facebook</span></li>
                                    <li><span class="btn-share st_twitter_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>">Twitter</span></li>
                                    <li><span class="btn-share st_pinterest_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>">Pinterest</span></li>
                                    <li><span class="btn-share st_email_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>">Email</span></li>
								  <li class="hud"><span class="hud-img"><a href="http://portal.hud.gov/hudportal/HUD?src=/program_offices/fair_housing_equal_opp"><img width="45" height="43" alt="EQUAL HOUSING OPPORTUNITY" src="/wp-content/themes/InvitationHomes/images/home.png"></a></span></li>
								</ul>
							</div>
						</div>
					</li>
				<?php endwhile;				
				wp_pagenavi();
				wp_reset_query(); 
				endif;?>
                
                <!--echo '<div id="arrow-pagination">';
				get_pagination();
				echo '</div>';-->
				</ul>
			</div>
		</div>
        <aside id="sidebar">
        	<div class="search-box">
            	<form method="get" id="search-form" class="search-form" action="<?php bloginfo('url');?>">
				<div class="form-main">
					<div class="holder">
						<input type="search" name="s" id="s" value="Search Keywords" onfocus="if (this.value == 'Search Keywords') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Search Keywords';}">
                        <input type="hidden" name='taxonomy' value="news-categories">
                         <input type="hidden" name='post_type' value="news">
						<input type="submit" value="Search Keywords">
					</div>
				</div>
				</form>
                <div class="check-holder">
                	<strong class="title">News Categories:</strong>
                    <?php $args = array( 'orderby' => 'name',
					'order' => 'ASC' );
					$terms = get_terms( 'news-categories', $args );
					if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
						$count = count( $terms );
						$term_list = '';
						foreach ( $terms as $term ) {
							if( strtolower($term->name) == "featured") continue;
							$active = (get_queried_object_id() == $category->ID) ? ' current_page_item active' : '';
							$term_list .= '<div class="row"><a class="'.$active.'" href="' . get_term_link( $term ) . '" title="' . sprintf( __( 'View all post filed under %s', 'my_localization_domain' ), $term->name ) . '">' . $term->name . '</a></div>';
							}
						echo '<div class="row"><a href="/news/">View all</a></div>';
						echo $term_list;
					}?>								
				</div>
			</div>
			<?php get_sidebar(); ?>
		</aside>
	</div>
</main>
<?php get_footer(); ?>