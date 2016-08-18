<?php get_header(); 
$args = array(
	'post_type' => 'blog',
	'cat' => get_queried_object_id()
);

query_posts($args);	
	
?>
<main id="main" role="main">
<p class="h1style"><?php single_cat_title( $prefix = '', $display = true ); ?></p>
	<div id="twocolumns">
		<div id="content">
			<div class="c1">            
            <ul class="posts-list">
			<?php if(have_posts()):?>
			<?php while(have_posts()):the_post();?>
			<li>
			<?php if(has_post_thumbnail()) { ?>
            	<div class="img-holder">
                <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('full'); ?></a>
                </div>
			<?php } else { ?>
            <div class="img-holder">
            	<a href="<?php the_permalink(); ?>"><img src="http://img.youtube.com/vi/<?=$ytid?>/mqdefault.jpg" /></a>
			</div>
			<? } ?>
            	<div class="description">
                    <h1 class="h2style"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
                    <p><?php the_excerpt(); ?></p>
                    <div class="panel">
						<?php $postdatetime =  get_the_date('l, F d Y | g:i A');
                        $postdate =  get_the_date('Y-m-d');
                        $posttime =  get_the_date('G:i');
                        $timedate =  $postdate.'T'.$posttime;
                        ?>
                        <time datetime="<?php echo $timedate;?>"><?php echo $postdatetime.' EST'; ?> | Filed in <?php the_category(', ') ?></time>
                        <ul class="share-list">
                        	<li><span class="btn-share st_facebook_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>">Facebook</span></li>
                            <li><span class="btn-share st_twitter_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>">Twitter</span></li>
                            <li><span class="btn-share st_pinterest_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>">Pinterest</span></li>
                                <li><span class="btn-share st_email_custom" st_title="<?php the_title(); ?>" st_url="<?php the_permalink(); ?>">Email</span></li>
						</ul>
					</div>
				</div>
			</li>
			<?php endwhile;
			wp_pagenavi();
			endif;?>
		</ul>
		</div>
	</div>
	<aside id="sidebar">
    	<div class="search-box">
			<form method="get" id="search-form" class="search-form" action="<?php bloginfo('url');?>">
				<div class="form-main">
					<div class="holder">
						<input type="search" name="s" id="s" value="Search" onfocus="if (this.value == 'Search') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Search';}">
                          <input type="hidden" name="post_type[]" value="video" />
                                 <input type="hidden" name="post_type[]" value="blog" />
						<input type="submit" value="Search">
					</div>
				</div>
			</form>
			<div class="check-holder">
				<strong class="title">Blog Categories:</strong>
				<?php $args = array(
					'taxonomy' => 'category',
					'orderby' => 'name',
					'order' => 'ASC',
					'exclude' => array(1,5),
				);
				$categories = get_categories($args);
				foreach($categories as $category) { 
					$active = (get_queried_object()->term_id == $category->term_id) ? ' current_page_item active' : '';
					$cat_list .='<div class="row"><a class="'.$active.'" href="' . get_category_link( $category->term_id ) . '" title="' . sprintf( __( "View all posts in %s" ), $category->name ) . '" ' . '>' . $category->name.'</a></div> ';
				}
				echo '<div class="row"><a href="/blog/">View all</a></div>';
				echo $cat_list;
				?>	
			</div>			
		</div>
		<?php get_sidebar(); ?>
	</aside>
</div>
</main>
<?php get_footer(); ?>