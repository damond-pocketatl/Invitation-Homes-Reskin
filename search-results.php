<div id="content">
  <div class="c1">    
    <ul class="posts-list">    
    <?php if(have_posts()):?>
    <?php while(have_posts()):the_post();?>
      <li>
        <?php if(has_post_thumbnail()){ ?>
        <div class="img-holder"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(array(320,180));  ?></a></div>
        <?php } else if (get_post_type() == "video"){ 
		$ytid = get_post_meta(get_the_id(), "_yt_id", true);
		?>
        <div class="img-holder"> <a href="<?php the_permalink(); ?>"><img src="http://img.youtube.com/vi/<?=$ytid?>/mqdefault.jpg" /></a> </div>
        <? } ?>
        <div class="description">
          <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
          <?php the_excerpt(); ?>
          <div class="panel">
            <?php 
			$postdatetime =  get_the_date('l, F d Y | g:i A');
			$postdate =  get_the_date('Y-m-d');
			$posttime =  get_the_date('G:i');
			$timedate =  $postdate.'T'.$posttime;?>
            <time datetime="<?php echo $timedate;?>"><?php echo $postdatetime.' EST'; ?></time>
            <!-- social plugin container -->
            <ul class="share-list">
              <li><span class="btn-share st_facebook_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>">Facebook</span></li>
              <li><span class="btn-share st_twitter_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>">Twitter</span></li>
              <li><span class="btn-share st_pinterest_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>">Pinterest</span></li>
              <li><span class="btn-share st_email_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>">Email</span></li>
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
						<input type="submit" value="Search Keywords">
                          <input type="hidden" name="post_type[]" value="video" />
                                 <input type="hidden" name="post_type[]" value="blog" />
					</div>
				</div>
			</form>
			<!--<div class="check-holder">
				<strong class="title">Blog Categories:</strong>
				<?php $args = array(
					'orderby' => 'name',
					'order' => 'ASC',
					'exclude' => '5',
				);
				$categories = get_categories($args);
				foreach($categories as $category) {
					$active = (get_queried_object_id() == $category->ID) ? ' current_page_item active' : '';
					$cat_list .='<div class="row"><a class="'.$active.'" href="' . get_category_link( $category->term_id ) . '" title="' . sprintf( __( "View all posts in %s" ), $category->name ) . '" ' . '>' . $category->name.'</a></div> ';
				}
				echo '<div class="row"><a href="/blog/">View all</a></div>';
				echo $cat_list;
				?>	
			</div>-->			
		</div>
		<?php get_sidebar(); ?>
	</aside>
