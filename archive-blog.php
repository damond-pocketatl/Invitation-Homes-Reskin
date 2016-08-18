<?php
/*
Template Name: BLog Template
*/
/*

*/
  get_header(); ?>
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
					'order' => 'DESC',
				);
				?>
				<?php if(have_posts()):?>
				<?php query_posts($arrayName);?>
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
					<time datetime="<?php echo $timedate;?>"><?php echo $postdatetime.' EST'; ?> | Filed in <?php $terms = get_the_terms( $post->ID , 'blog-categories' ); 
                    foreach ( $terms as $term ) {
                        $term_link = get_term_link( $term, 'blog-categories' );
                        if( is_wp_error( $term_link ) )
                        continue;
                    echo '<a href="' . $term_link . '">' . $term->name . '</a>, ';
                    } 
                ?></time>
                        <meta property="og:title" content="<?php the_title(); ?>"/>
                        <?php global $post;
                        $url = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );?>
                        <meta property="og:url" content="<?php the_permalink(); ?>"/>
                        <meta property="og:image" content="<?php echo $url; ?>"/>
                        <meta property="og:site_name" content="Invitation Homes"/>                           
                        <meta property="og:description" content="<?php the_advanced_excerpt(); ?>"/>
					<ul class="share-list">
						<li><span class="btn-share st_facebook_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>">Facebook</span></li>
						<li><span class="btn-share st_twitter_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>">Twitter</span></li>
						<li><span class="btn-share st_pinterest_custom" st_title="<?php the_title(); ?>"st_url="<?php the_permalink(); ?>">Pinterest</span></li>
						<li><span class="btn-share st_email_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>">Email</span></li>
						<!-- <li class="hud"><span class="hud-img"><a href="http://portal.hud.gov/hudportal/HUD?src=/program_offices/fair_housing_equal_opp"><img width="45" height="43" alt="EQUAL HOUSING OPPORTUNITY" src="/wp-content/themes/InvitationHomes/images/home.png"></a></span></li> -->
                            <li><span><a href="<?php get_bloginfo_rss('rss2_url'); ?>feed"><img src="<?php echo get_template_directory_uri(); ?>/images/rss_blog.png" /></a></span></li>
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
                <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(array(320,180)); ?></a>
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
                        <time datetime="<?php echo $timedate;?>"><?php echo $postdatetime.' EST'; ?> | Filed in <?php $terms = get_the_terms( $post->ID , 'blog-categories' ); 
                    foreach ( $terms as $term ) {
                        $term_link = get_term_link( $term, 'blog-categories' );
                        if( is_wp_error( $term_link ) )
                        continue;
                    echo '<a href="' . $term_link . '">' . $term->name . '</a>, ';
                    } 
                ?></time>
                        <meta property="og:title" content="<?php the_title(); ?>"/>
                        <?php global $post;
                        $url = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );?>
                        <meta property="og:url" content="<?php the_permalink(); ?>"/>
                        <meta property="og:image" content="<?php echo $url; ?>"/>
                        <meta property="og:site_name" content="Invitation Homes"/>                           
                        <meta property="og:description" content="<?php the_advanced_excerpt(); ?>"/>
                        <ul class="share-list">
                        	<li><span class="btn-share st_facebook_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>">Facebook</span></li>
                            <li><span class="btn-share st_twitter_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>">Twitter</span></li>
                            <li><span class="btn-share st_pinterest_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>">Pinterest</span></li>
                                <li><span class="btn-share st_email_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>">Email</span></li>
                                <!-- <li class="hud"><span class="hud-img"><a href="http://portal.hud.gov/hudportal/HUD?src=/program_offices/fair_housing_equal_opp"><img width="45" height="43" alt="EQUAL HOUSING OPPORTUNITY" src="/wp-content/themes/InvitationHomes/images/home.png"></a></span></li> -->
                            <li><span><a href="<?php get_bloginfo_rss('rss2_url'); ?>feed"><img src="<?php echo get_template_directory_uri(); ?>/images/rss_blog.png" /></a></span></li>
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
				</div>			
		</div>
		<?php get_sidebar(); ?>
	</aside>
</div>
</main>
<?php get_footer(); ?>