<?php
/*
Template Name: Video Template
*/

get_header(); ?>
<main id="main" role="main">
	<?php the_title('<h1>', '</h1>'); ?>
	<div id="twocolumns">
		<div id="content">
			<div class="c1">
            	<?php if( !is_paged() ) { ?>
				<?php $arrayName = array('post_type' => 'video', 'posts_per_page' => 1,
					'tax_query' => array(
						array(
							'taxonomy' => 'video_category',
							'field' => 'slug',
							'terms' => 'featured'
						)
					)
				);
				$posts = query_posts($arrayName);
				if ( have_posts()) : while ( have_posts() ) : the_post(); 
				$featured = get_the_id(); $ytid = get_post_meta(get_the_id(), "_yt_id", true); 
				$durat= get_post_meta(get_the_id(), "_durat", true);?> 
                <div class="post single-post single-post-alt" itemprop="video" itemscope itemtype="http://schema.org/VideoObject">
                	<meta property="og:image" content="http://img.youtube.com/vi/<?=$ytid?>/mqdefault.jpg">
                     <?php if($durat && $durat != '') {  ?>                            
                     <meta itemprop="duration" content="<?=$durat?>" />
                     <?php } ?>
                	<meta property="og:image:type" content="image/jpeg">
                	<meta property="og:image:width" content="320">
                	<meta property="og:image:height" content="180">
                	<meta itemprop="embedUrl" content="http://www.youtube.com/embed/<?=$ytid?>" />
                    <meta itemprop="uploadDate" content="<?php echo get_the_date();?>" /> 
                	<div class="post-img">
                    	<strong class="note">Featured Video</strong>
                        <?php if(has_post_thumbnail()) { ?>
                        	<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('full'); ?></a>
						<?php } else { ?>
							<meta itemprop="thumbnailUrl" content="http://img.youtube.com/vi/<?=$ytid?>/mqdefault.jpg" />
                        	<a href="<?php the_permalink(); ?>"><img src="http://img.youtube.com/vi/<?=$ytid?>/mqdefault.jpg" /></a>
						<? } ?>
					</div>
					<h2><a itemprop="name" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<p itemprop="description"><?php echo strip_tags(get_the_excerpt()); ?></p>					
					<div class="meta">
						<?php $postdatetime =  get_the_date('l, F d Y | g:i A');
                        $postdate =  get_the_date('Y-m-d');
                        $posttime =  get_the_date('G:i');
                        $timedate =  $postdate.'T'.$posttime;?>
                        <time datetime="<?php echo $timedate;?>"><?php echo $postdatetime.' EST'; ?></time>
                          <ul class="share-list">
                          	<li><span class="btn-share st_facebook_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>" st_image="http://img.youtube.com/vi/<?=$ytid?>/mqdefault.jpg">Facebook</span></li>
							<li><span class="btn-share st_twitter_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>" st_image="http://img.youtube.com/vi/<?=$ytid?>/mqdefault.jpg">Twitter</span></li>
							<li><span class="btn-share st_pinterest_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>" st_image="http://img.youtube.com/vi/<?=$ytid?>/mqdefault.jpg">Pinterest</span></li>
							<li><span class="btn-share st_email_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>" st_image="http://img.youtube.com/vi/<?=$ytid?>/mqdefault.jpg">Email</span></li>
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
					'post_type' => 'video',
					'posts_per_page' => 5,
					'paged' => $paged
				);
				query_posts($args); 
				if ( have_posts() ) : while (have_posts()) : the_post();
				$ytid = get_post_meta(get_the_id(), "_yt_id", true);
                $durat = get_post_meta(get_the_id(), "_durat", true);?>
                	<li><?php if(has_post_thumbnail()) { ?>
                	
                    	<div class="img-holder">
                         	<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(array(320,180)); ?></a>
						</div>
						<?php } else { ?>
                        <div class="img-holder">
                        	<meta itemprop="thumbnailUrl" content="http://img.youtube.com/vi/<?=$ytid?>/mqdefault.jpg" />
                        	<a href="<?php the_permalink(); ?>"><img src="http://img.youtube.com/vi/<?=$ytid?>/mqdefault.jpg" /></a>
						</div>
						<? } ?>
                        <div class="description" itemprop="video" itemscope itemtype="http://schema.org/VideoObject">
                            <meta property="og:image" content="http://img.youtube.com/vi/<?=$ytid?>/mqdefault.jpg">
                            <?php if($durat && $durat != '') {  ?>                            
                            <meta itemprop="duration" content="<?=$durat?>" />
                            <?php } ?>
                            <meta property="og:image:type" content="image/jpeg">
                            <meta property="og:image:width" content="320">
                            <meta property="og:image:height" content="180">
                            <meta itemprop="thumbnailUrl" content="http://img.youtube.com/vi/<?=$ytid?>/mqdefault.jpg" />
                            <meta itemprop="embedUrl" content="http://www.youtube.com/embed/<?=$ytid?>" />
                            <meta itemprop="uploadDate" content="<?php echo get_the_date();?>" /> 
                        	<h2><a itemprop="name" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
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
                        <input type="hidden" name="post_type[]" value="blog" />
                        <input type="hidden" name="post_type[]" value="video" /> 
                        <input type="hidden" name="post_type[]" value="news" /> 
                        <input type="hidden" name="post_type[]" value="wpm-testimonial" /> 
						<input type="submit" value="Search Keywords">
					</div>
				</div>
				</form>
                <div class="check-holder">
                	<strong class="title">Video Categories:</strong>
                    <?php $args = array( 'hide_empty' => 0, 'exclude'=> '127,95' );
					$terms = get_terms( 'video_category', $args );
					if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
						$count = count( $terms );
						$term_list = '';
						foreach ( $terms as $term ) {
							if( strtolower($term->name) == "featured") continue;
							$active = (get_queried_object_id() == $term->ID) ? ' current_page_item active' : '';
							$term_list .= '<div class="row"><a class="'.$active.'" href="' . get_term_link( $term ) . '" title="' . sprintf( __( 'View all post filed under %s', 'my_localization_domain' ), $term->name ) . '">' . $term->name . '</a></div>';
							}
						echo '<div class="row"><a href="/video-gallery/">View all</a></div>';
						echo $term_list;
					}?>								
<!--
				</div>
                <div class="property-holder">
	                <strong class="title"><h2 class="widgettitle">SEE OUR PROPERTIES:</h2></strong>
                	<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('custom-sidebar') ) : endif; ?>
                    <?php 
					$menu = wp_get_nav_menu_items(104);
					$terms = get_terms( 'market',$args );
					if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
						$count = count( $terms );
						$term_list = '';
						foreach ( $menu as $market ) {
						//print_r(get_term_link( $term ));die;
						   //$active = (get_queried_object_id() == $category->ID) ? ' current_page_item active' : '';
						    $murl = str_replace( "%market_slug%", "market" , $market->url);
							$term_list .= '<div class="row"><a target="_blank" class="" href="' .  $murl . '" title="' . sprintf( __( "View all posts in %s" ), $term->name ) . '" ' . '>' . $market->title.'</a></div>';
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