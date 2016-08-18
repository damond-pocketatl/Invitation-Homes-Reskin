<?php get_header();
global  $wp_query;
 $queried_object = $wp_query->get_queried_object();
 ?>
<main id="main" role="main">
	<?php //the_title('<h1>', '</h1>'); ?>
    <h1><?php echo $queried_object->name;?></h1>
	<div id="twocolumns">
		<div id="content">
			<div class="c1">
                <div class="team-members">
               
               <?php $current_query = $wp_query->query_vars;
			$wp_query = new WP_Query();
			$wp_query->query(array(
				$current_query['taxonomy'] => $current_query['term'],
				'paged' => $paged,
				'posts_per_page' => 8,
			));
			while ($wp_query->have_posts()) : $wp_query->the_post();
			$ytid = get_post_meta(get_the_id(), "_yt_id", true);
			?>
                    <div class="team-member">
                        <div class="top">
                       <?php  if ( has_post_thumbnail() ) { ?>
                            <div class="image"><?php the_post_thumbnail('full'); ?></div>
                            <div class="title"><?php the_title();?></div>
                            <div class="position"><?php the_field('position_job_title'); ?></div>
                            <div class="location"><?php $terms = get_the_terms( $post->ID , 'team-locations' );							
                            foreach ( $terms as $term ) {
                                echo $term->name;
                            }
                            ?></div>
                            <?php } else { ?>							
                            <div class="title left_justified_title"><?php the_title();?></div>
                            <div class="position left_justified_position"><?php the_field('position_job_title'); ?></div>	
                            <div class="location left_justified_location"><?php $terms = get_the_terms( $post->ID , 'team-locations' );							
                            foreach ( $terms as $term ) {
                                echo $term->name;
                            }
                            ?></div>						
							<?php } ?>                            
                            
                        </div>
                        <div class="bio">
                        <?php the_content();?>
                        </div>
                    </div>
                <?php endwhile;
                wp_pagenavi();?> 
                </div>
            </div>
		</div>
        <aside id="sidebar">
        	<div class="search-box">
            	<form method="get" id="search-form" class="search-form" action="<?php bloginfo('url');?>">
				<div class="form-main">
					<div class="holder">
						<input type="search" name="s" id="s" value="Search Keywords" onfocus="if (this.value == 'Search Keywords') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Search Keywords';}">
                        <input type="hidden" name="post_type" value="video" />
						<input type="submit" value="Search Keywords">
					</div>
				</div>
				</form>               
                <div class="team-dropdown">
                <form method="get" action="/">
                	<fieldset>
                    		<?php $args = array( 'show_option_all' => 'Choose a Location', 'taxonomy' => 'team-categories', 'walker' => new my_Walker_CategoryDropdown ); wp_dropdown_categories( $args ); ?> 
							<script type="text/javascript">var dropdown = document.getElementById("cat"); function onCatChange() { if ( dropdown.options[dropdown.selectedIndex].value != '0' ) { location.href = "<?php echo get_option('home'); ?>/?team-categories="+dropdown.options[dropdown.selectedIndex].value; } } dropdown.onchange = onCatChange;</script>
					</fieldset>
				</form>               
                </div>                
                <div class="check-holder">
                	<strong class="title">Team Categories:</strong>
                   <?php $args = array('orderby' => 'name',
					'order' => 'ASC');
				$terms = get_terms( 'team-categories', $args );
				if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
					$count = count( $terms );
					$term_list = '';
					foreach ( $terms as $term ) {
						if( strtolower($term->name) == "featured") continue;
						$active = (get_queried_object_id() == $term->term_id) ? ' current_page_item active' : '';
						$term_list .= '<div class="row"><a class="'.$active.'" href="' . get_term_link( $term ) . '" title="' . sprintf( __( 'View all post filed under %s', 'my_localization_domain' ), $term->name ) . '">' . $term->name . '</a></div>';
					}
					echo '<div class="row"><a href="/our-team/">View all</a></div>';
					echo $term_list;
				}?>					
				</div>
			</div>
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('inner-sidebar') ) : ?>
			<?php endif; ?>
		</aside>
	</div>
	<?php get_template_part('blocks/inner-boxes');?>
</main>
<?php get_footer(); ?>