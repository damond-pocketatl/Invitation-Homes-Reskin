<?php
/**
 * List View Loop
 * This file sets up the structure for the list loop
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/list/loop.php
 *
 * @package TribeEventsCalendar
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
} ?>

<?php
global $more;
$more = false;
$location = explode(',',$_GET['event_loc']);
$team = explode(',',$_GET['event_c']);
$paged = get_query_var('paged') ? get_query_var('paged') : 1;
$args = array('post_type' => 'tribe_events', 'posts_per_page' => 8, 'paged' => $paged);
$args_tax_query = array();
				if( (isset($_GET['event_loc']) && $_GET['event_loc'] != '') || (isset($_GET['event_c']) && $_GET['event_c'] != '') ) {
					
					$is_loc = false;
					$is_cat = false;
					if (isset($_GET['event_loc']) && $_GET['event_loc'] != '')
					{
						$args_tax_query[] = array(
									 'taxonomy' => 'event-locations',
									 'field' => 'term_id',
									 'terms' => $location
								   );
						$is_loc = true; 		   
					}
					if (isset($_GET['event_c']) && $_GET['event_c'] != '') 
					{
						$args_tax_query[] =  array(
									 'taxonomy' => 'tribe_events_cat',
									 'field' => 'term_id',
									 'terms' => $team
								   );
						$is_cat = true;		   
					 }
					 if( $is_loc && $is_cat )
					 {
						 $args_tax_query['relation'] = 'OR';
					 }
					 
				}
				if( count( $args_tax_query  ) )
				{
					$args['tax_query'] = $args_tax_query ;					
				}
				//print_r($args);
$loop = new WP_Query( $args );
?>
<?php 
   global $wp_query;   
   $post_type = get_query_var('post_type'); 
if( $wp_query->is_search && $post_type == 'tribe_events' ){ ?>
<div class="tribe-events-loop vcalendar">
	<?php while ( have_posts() ) : the_post(); ?>
		<?php do_action( 'tribe_events_inside_before_loop' ); ?>

		<!-- Month / Year Headers -->
		<?php tribe_events_list_the_date_headers(); ?>

		<!-- Event  -->
		<div id="post-<?php the_ID() ?>" class="<?php tribe_events_event_classes() ?>">
			<?php tribe_get_template_part( 'list/single', 'event' ) ?>
		</div><!-- .hentry .vevent -->


		<?php do_action( 'tribe_events_inside_after_loop' ); ?>
	<?php endwhile; 
	//global $wp_query;
	//echo "<pre>asasdasd";print_r($wp_query);
	?>

</div>
<?php }else { ?>
<div class="tribe-events-loop vcalendar">
	<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
		<?php do_action( 'tribe_events_inside_before_loop' ); ?>

		<!-- Month / Year Headers -->
		<?php tribe_events_list_the_date_headers(); ?>

		<!-- Event  -->
		<div id="post-<?php the_ID() ?>" class="<?php tribe_events_event_classes() ?>">
			<?php tribe_get_template_part( 'list/single', 'event' ) ?>
		</div><!-- .hentry .vevent -->


		<?php do_action( 'tribe_events_inside_after_loop' ); ?>
	<?php endwhile; 
	//global $wp_query;
	//echo "<pre>asasdasd";print_r($wp_query);
	?>

</div>
<?php } ?>
<!-- .tribe-events-loop -->
