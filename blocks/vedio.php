<div class="boxes-row">
	<div class="intro-alt">
<?php $term = get_term_by( 'id', 40, 'gallery' ); 
echo '<h2>' . $term->name . '</h2><p>' . $term->description . '</p>';?>
<?php
$taxonomy = 'gallery';
$queried_term = get_query_var($taxonomy);
$terms = get_terms($taxonomy, 'slug='.$queried_term);
if ($terms) {
  foreach($terms as $term) {
    echo '<p> This is the Term name ' . $term->name . 'and description '. $term->description . '</p> ';
  }
}
?>
	</div>
	<?php
	$arrayName = array('post_type' => 'video','posts_per_page' => '3', 'taxonomy' => 'gallery', ); 
	query_posts($arrayName); 
	if ( have_posts() ) : ?>
    
    
		<div class="items-box">
			<div class="holder"> <?php
				while ( have_posts() ) : the_post(); $ytid = get_post_meta(get_the_id(), '_yt_id', true); ?>
				<div class="item">
					<div class="img-box">						
                        	<a href="<?php the_permalink();?>" class="btn-play">                         	
								<img src="http://img.youtube.com/vi/<?=$ytid?>/hqdefault.jpg" />
                            </a>						
					</div>
					<p><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
				</div><?php
				endwhile; ?>
			</div>	
		</div>	<?php
	endif;
	wp_reset_query(); ?>
</div>
