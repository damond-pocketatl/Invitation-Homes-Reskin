<?php
/**
 * List View Nav Template
 * This file loads the list view navigation.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/list/nav.php
 *
 * @package TribeEventsCalendar
 *
 */
global $wp_query;

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
} ?>

<h3 class="tribe-events-visuallyhidden"><?php _e( 'Events List Navigation', 'tribe-events-calendar' ) ?></h3>
<ul class="tribe-events-sub-nav">
    <!-- Left Navigation -->
	<?php if ( tribe_has_previous_event() ) : ?>
        <!-- First -->
        <?php if( is_tax() ) { } else {?>
        <li class="first-nav <?php echo tribe_left_navigation_classes(); ?>"><span class="event-first"></span></li>
		<li class="<?php echo tribe_left_navigation_classes(); ?>">
			<!--<a href="<?php echo tribe_get_listview_link() ?>" rel="prev"><span class="event-prev"></span></a>-->
		</li><!-- .tribe-events-nav-left -->
	<?php } endif; ?>
	<!-- Numeric Pagination -->
    <?php tribe_get_template_part( 'list/numeric-pagination' ); ?>
	<!-- Right Navigation -->
	<?php if ( tribe_has_next_event() ) : ?>
    	<?php if( is_tax() ) { } else {?>
		<li class="<?php echo tribe_right_navigation_classes(); ?>">
			<!--<a href="<?php echo tribe_get_listview_link() ?>" rel="next"><span class="event-next"></span></a>-->
		</li><!-- .tribe-events-nav-right -->
         <!-- Last -->
    	<!--<li class="last-nav <?php echo tribe_right_navigation_classes(); ?>"><span class="event-last"></span></li>-->
	<?php } endif; ?>
</ul>