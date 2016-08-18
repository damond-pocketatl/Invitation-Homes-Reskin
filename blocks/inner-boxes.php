<?php
 if (isset($_COOKIE['invitationhomes'])) $category = "current";
 else $category = "future";
 $arrayName = array(
		'post_type' => 'inner_boxes',
		'posts_per_page' => '3',
		'tax_query' => array(array('taxonomy'=>'home-category','field'=>'slug','terms' => array($category)))
	);		
	query_posts($arrayName); 
	if ( have_posts() ) : ?>
<style type="text/css">
	.img-holder img { width: 225px; margin: 0px auto; }
	.intro-posts .item { width: 230px; }
	.intro-posts { border-top: 3px solid #ccc; padding-top: 30px; width: 920px; }
</style>
<div class="intro-posts">
	<div class="item">
		<? $post = get_page(304436); setup_postdata($post); ?>
		<p class="h2style" ><? the_title(); ?></p>
		<p><? the_content(); ?></p>
		<? wp_reset_postdata(); ?>
	</div>
	<?php while ( have_posts() ) : the_post(); ?>
	<div class="item">	
		<div class="img-holder">
			<?php $videopageurl = get_field ('video_page_url');
			$externalurl = get_field('external_url');
			if ($videopageurl == '') { ?>						
			<a href="<?php if ($externalurl == '') {the_permalink(); } else { echo $externalurl; } ?>" class=""><?php the_post_thumbnail('full'); ?></a>						
			<?php } else { ?>
			<a href="<?php echo $videopageurl;?>" class="btn-play">
			<!-- Add class "lightbox iframe" if we want to restore functionality to open videos in lightbox and set href to echo $videolink; -->
            <?php if(has_post_thumbnail()): ?>
			<?php the_post_thumbnail('full'); ?>
            <?php endif; ?>
			</a>                        
			<?php } ?>
		</div>	
	<strong class="title"><a href="<?php if ($externalurl == '') {echo $videopageurl; /*the_permalink();*/ } else { echo $externalurl; } ?>"><?php the_title(); ?></a></strong>
	<?php the_excerpt();?>
    </div>
    <?php                
	endwhile; endif; wp_reset_query();
	?>
</div>