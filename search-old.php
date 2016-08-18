<?php get_header();?>
<main id="main" role="main">
	<?php if (have_posts()) : ?>

	<div <?php post_class(); ?>>
		<div class="title">
			<h1><?php printf( __( 'Search Results: %s', 'InvitationHomes' ), '<span>' . get_search_query() . '</span>'); ?></h1>
		</div>
	</div>

	<?php while (have_posts()) : the_post(); ?>
		<?php get_template_part('blocks/content', get_post_type()); ?>
	<?php endwhile; ?>
	
	<?php get_template_part('blocks/pager'); ?>
	
	<?php else : ?>
		<?php get_template_part('blocks/not_found'); ?>
	<?php endif; ?>
	
</main>

<?php get_footer(); ?>