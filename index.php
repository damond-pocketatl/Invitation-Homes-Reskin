<?php get_header(); ?>
<main id="main" role="main">
<p class="h1style"><?php single_cat_title( $prefix = '', $display = true ); ?>Blog</p>
	<div id="twocolumns">
		<div id="content">
			<div class="c1">
            	<?php if( !is_paged() ) { ?>
 				<?php $arrayName = array(
					'post_type' => 'blog',
					'posts_per_page' => 1,
					'tax_query' => array(
						array(
							'taxonomy' => 'blog-categories',
							'field'    => 'slug',
							'terms'    => 'features',
						),
					),
					'order' => 'DESC'				
					
				);
				$posts = query_posts($arrayName);
				?>
				<?php if(have_posts()):?>
				<?php //query_posts($arrayName);?>
				<?php // query_posts('posts_per_page=1');?>
				<?php while(have_posts()):the_post();?>
				<div class="post single-post single-post-alt">
					<div class="post-img"><strong class="note">Featured Article</strong><?php if(has_post_thumbnail()) { ?>
						<a href="<?=the_permalink();?>"><? the_post_thumbnail('full'); ?></a><? } ?>
					</div>
					<h1 class="h2style"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
					<?php the_excerpt(); ?>							
					<div class="meta">
					<?php $postdatetime =  get_the_date('l, F d Y | g:i A');
					$postdate =  get_the_date('Y-m-d');
					$posttime =  get_the_date('G:i');
					$timedate =  $postdate.'T'.$posttime;?>
					<time datetime="<?php echo $timedate;?>"><?php echo $postdatetime.' EST'; ?> | Filed in <?php the_category(', ') ?></time>
					<ul class="share-list">
						<li><span class="btn-share st_facebook_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>">Facebook</span></li>
						<li><span class="btn-share st_twitter_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>">Twitter</span></li>
						<li><span class="btn-share st_pinterest_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>">Pinterest</span></li>
						<li><span class="btn-share st_email_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>">Email</span></li>
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
				'post_type' => 'blog',
				'posts_per_page' => 5,
				'paged' => $paged
			);
			query_posts($args); 
			if ( have_posts() ) : while (have_posts()) : the_post();?>
			<li>
			<?php if(has_post_thumbnail()) { ?>
            	<div class="img-holder">
                <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('thumbnail'); ?></a>
                </div>
			<? } ?>
            	<div class="description">
                    <h1 class="h2style"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
                    <p><?php the_advanced_excerpt(); ?></p>
                    <div class="panel">
						<?php $postdatetime =  get_the_date('l, F d Y | g:i A');
                        $postdate =  get_the_date('Y-m-d');
                        $posttime =  get_the_date('G:i');
                        $timedate =  $postdate.'T'.$posttime;
                        ?>
                        <time datetime="<?php echo $timedate;?>"><?php echo $postdatetime.' EST'; ?> | Filed in <?php the_category(', ') ?></time>
                        <?php
						$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'thumbnail' );
						$url = $thumb['0'];
					?>
                        <ul class="share-list">
                          	<li><span class="btn-share st_facebook_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>" st_image="<?=$url?>">Facebook</span></li>
							<li><span class="btn-share st_twitter_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>" st_image="<?=$url?>">Twitter</span></li>
							<li><span class="btn-share st_pinterest_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>" st_image="<?=$url?>">Pinterest</span></li>
							<li><span class="btn-share st_email_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>" st_image="<?=$url?>">Email</span></li>
						   <li class="hud"><span class="hud-img"><a href="http://portal.hud.gov/hudportal/HUD?src=/program_offices/fair_housing_equal_opp"><img width="45" height="43" alt="EQUAL HOUSING OPPORTUNITY" src="/wp-content/themes/InvitationHomes/images/home.png"></a></span></li>
                        </ul>
					</div>
				</div>
			</li>
			<?php endwhile;
			wp_pagenavi();
			wp_reset_query(); 
			endif;?>
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
				<?php $args = array(
					'taxonomy' => 'blog-categories',
					'orderby' => 'name',
					'order' => 'ASC',
					'exclude' => '5,1',
				);
				$categories = get_categories($args);
				foreach($categories as $category) {
					$active = (get_queried_object_id() == $category->ID) ? ' current_page_item active' : '';
					$cat_list .='<div class="row"><a class="'.$active.'" href="' . get_category_link( $category->term_id, 'blog' ) . '" title="' . sprintf( __( "View all posts in %s" ), $category->name ) . '" ' . '>' . $category->name.'</a></div> ';
				}
				echo '<div class="row"><a href="/?page_id=124/">View all</a></div>';
				echo $cat_list;
				?>	
			</div>			
		</div>
		<?php get_sidebar(); ?>
	</aside>
</div>
</main>
<?php get_footer(); ?>