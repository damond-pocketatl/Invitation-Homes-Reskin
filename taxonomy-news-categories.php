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
				'posts_per_page' => 10,
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
                    <h2><a itemprop="name" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <p itemprop="description"><?php the_excerpt(); ?></p>
                    <div class="panel">
						<?php $postdatetime =  get_the_date('l, F d Y | g:i A');
                        $postdate =  get_the_date('Y-m-d');
                        $posttime =  get_the_date('G:i');
                        $timedate =  $postdate.'T'.$posttime;
						$draught_links = array();
					$category = get_the_terms( get_the_ID(), 'news-categories' );
					foreach ( $category as $term ) {
						$draught_links[] = '<a href="' . get_term_link( $term ) . '" title="' . sprintf( __( "View all posts in %s" ), $term->name ) . '" ' . '>' . $term->name.'</a>';
						 }
						 $on_draught = join( ", ", $draught_links );
                        ?>
                        <time datetime="<?php echo $timedate;?>"><?php echo $postdatetime.' EST'; ?> <!--| Filed in--> <?php //echo $on_draught;?></time>
                        <ul class="share-list">
                        	<li><span class="btn-share st_facebook_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>">Facebook</span></li>
                            <li><span class="btn-share st_twitter_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>">Twitter</span></li>
                            <li><span class="btn-share st_pinterest_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>">Pinterest</span></li>
                                <li><span class="btn-share st_email_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>">Email</span></li>
						</ul>
					</div>
                    <?php /*?><div class="meta">
                        <ul class="share-list">
                            <li><span class="btn-share st_facebook_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>">Facebook</span></li>
                            <li><span class="btn-share st_twitter_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>">Twitter</span></li>
                            <li><span class="btn-share st_pinterest_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>">Pinterest</span></li>
                            <li><span class="btn-share st_email_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>">Email</span></li>
						   <li class="hud"><span class="hud-img"><a href="http://portal.hud.gov/hudportal/HUD?src=/program_offices/fair_housing_equal_opp"><img width="45" height="43" alt="EQUAL HOUSING OPPORTUNITY" src="/wp-content/themes/InvitationHomes/images/home.png"></a></span></li>
                        </ul>
                    </div>
					<?php */?>
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
                    <input type="hidden" name="post_type[]" value="blog" />
                        <input type="hidden" name="post_type[]" value="video" /> 
                        <input type="hidden" name="post_type[]" value="news" /> 
                        <input type="hidden" name="post_type[]" value="wpm-testimonial" /> 
					<input type="submit" value="Search Keywords">
                   
				</div>
			</div>
			</form>
            <div class="check-holder">
            	<strong class="title">News Categories:</strong>
                <?php $args = array('orderby' => 'name',
					'order' => 'ASC');
				$terms = get_terms( 'news-categories', $args );
				if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
					$count = count( $terms );
					$term_list = '';
					foreach ( $terms as $term ) {
						if( strtolower($term->name) == "featured") continue;
						$active = (get_queried_object_id() == $term->term_id) ? ' current_page_item active' : '';
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