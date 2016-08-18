<?php
/*
Template Name: Page (Events)
*/
?>

<?php get_header(); 
 while (have_posts()) : the_post(); ?>
<main id="main" role="main">
	<?php the_title('<h1>', '</h1>'); ?>
	<div class="post">
		<div class="visual">
			<div class="details">
				<img src="<?php echo get_template_directory_uri(); ?>/images/logo-alt2.svg" width="62" height="61" alt="INVITATION HOMES" onerror="this.onerror=null; this.src='<?php echo get_template_directory_uri(); ?>/images/logo-alt2.png'">
				<div class="text">
					<?php the_field('image_text'); ?>
				</div>
			</div>
			<?php if(has_post_thumbnail()): the_post_thumbnail('full'); endif; ?>
		</div>
		<?php the_content(); ?>
	</div><?php
endwhile;				
get_template_part('blocks/inner-boxes');?>
</main>
<?php get_footer(); ?>