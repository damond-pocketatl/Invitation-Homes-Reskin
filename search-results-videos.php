<div id="content">
  <div class="c1">
    <ul class="posts-list videos-list">
    <?php if(have_posts()):?>

    <?php while(have_posts()):the_post();
	$ytid = get_post_meta(get_the_id(), "_yt_id", true);
	?>
      <li>
        <?php if(has_post_thumbnail()) { ?>
        <div class="img-holder"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(array(320,180)); ?></a></div>
        <?php } else { ?>
        <div class="img-holder"> <a href="<?php the_permalink(); ?>"><img src="http://img.youtube.com/vi/<?=$ytid?>/mqdefault.jpg" /></a> </div>
        <? } ?>
        <div class="description">
          <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
          <p><?php the_excerpt(); ?></p>
          <div class="meta"> 
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
      </li>
      <?php endwhile;?>
      <?php endif;?>
    </ul>
    <?php get_template_part('blocks/pager'); ?>
  </div>
</div>
<!-- contain sidebar of the page -->
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
                <!--<div class="check-holder">
                	<strong class="title">Video Categories:</strong>
                    <?php $args = array( 'hide_empty' => 0 );
					$terms = get_terms( 'video_category', $args );
					if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
						$count = count( $terms );
						$term_list = '';
						foreach ( $terms as $term ) {
							$active = (get_queried_object_id() == $category->ID) ? ' current_page_item active' : '';
							$term_list .= '<div class="row"><a class="'.$active.'" href="' . get_term_link( $term ) . '" title="' . sprintf( __( 'View all post filed under %s', 'my_localization_domain' ), $term->name ) . '">' . $term->name . '</a></div>';
							}
						echo '<div class="row"><a href="/video-gallery/">View all</a></div>';
						echo $term_list;
					}?>								
				</div>-->
			</div>
			<?php get_sidebar(); ?>
		</aside>
