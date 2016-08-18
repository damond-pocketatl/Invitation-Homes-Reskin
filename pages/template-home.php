<?php
/*
Template Name: Home Template
*/
global $post;
get_header(); ?>
<main id="main" role="main"><?php 
	
	if (is_page( 'future-residents' )) {
    	$homepagetype = 'future';
	} elseif ( is_page( 'current-residents' )) {	
    	$homepagetype = 'current';
	} elseif ( is_page( 'home')) {
		$homepagetype = 'main';
	}
	$arrayName = array(
		'post_type' => 'home_box',
		'posts_per_page' => '3',
		'tax_query' => array(
			array(
				'taxonomy' => 'home-category',
				'field' => 'slug',
				'terms' => $homepagetype
			)
   	 	)
	);		
	query_posts($arrayName); 
	?>
	<!-- <?php if ($post) { ?> -->
	<div class="page-content">
		<?php the_title('<h1>', '</h1>'); ?>
		<?php the_content(); ?>
	</div>
	<!-- <?php } ?> -->
	<?php if ( have_posts() ) : ?>
		<div class="intro-posts main-intro-posts"> <?php
			while ( have_posts() ) : the_post(); ?>
			<div class="item"><?php
				if(has_post_thumbnail()): ?>
					<div class="img-holder">
                    	<?php $videopageurl = get_field ('video_page_url');
						$externalurl = get_field('external_url');
						if ($videopageurl == '') { ?>		
						<a href="<?php if ($externalurl == '') {the_permalink(); } else { echo $externalurl; } ?>" class=""><?php the_post_thumbnail('full'); ?></a>				
						<?php } else { ?>
                        
                        <a href="<?php echo $videopageurl;?>" class="btn-play">
                        <!-- Add class "lightbox iframe" if we want to restore functionality to open videos in lightbox and set href to echo $videolink; -->
						<?php the_post_thumbnail('full'); ?>
                        </a>                        
                        <?php } ?>
                        
					</div><?php 
				endif;?>
				<strong class="title"><a href="<?php if ($externalurl == '') {echo $videopageurl; /*the_permalink();*/ } else { echo $externalurl; } ?>"><?php the_title(); ?></a></strong>
				<?php the_excerpt(); ?>
			</div> <?php
			endwhile; ?>
		</div>	<?php
	endif;
	wp_reset_query();
	/*
if ($_COOKIE['invitationhomes'] == "visited")
		$post = get_post(302891);
	else
		$post = get_post(302889);
	
	setup_postdata($post);
*/
	?>
	<!-- <?php if ($post) { ?> -->
	<?php $sTitle = get_field('additional_title', $post->ID); ?>
	<?php $sContent = get_field('additional_content', $post->ID); ?>
	<?php if($sTitle || $sContent) { ?>	
	<div class="page-content">
		<?php if($sTitle){ echo '<h1>' . $sTitle . '</h1>'; } ?>
		<?php if($sContent){ echo $sContent; } ?>
	</div>
	<?php } ?>
	<!-- <?php } ?> -->

</main>
<?php get_footer(); ?>