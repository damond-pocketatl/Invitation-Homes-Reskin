<?php
/**
 * Single Event Meta (Details) Template
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe-events/modules/meta/details.php
 *
 * @package TribeEventsCalendar
 */
?>

<div class="tribe-events-meta-group tribe-events-meta-group-details">
	<?php do_action( 'tribe_events_single_meta_details_section_start' );
	$time_format = get_option( 'time_format', TribeDateUtils::TIMEFORMAT );
	$time_range_separator = tribe_get_option( 'timeRangeSeparator', ' - ' );
	$start_datetime = tribe_get_start_date();
	$start_date = tribe_get_start_date( null, false );
	$start_time = tribe_get_start_date( null, false, $time_format );
	$start_ts = tribe_get_start_date( null, false, TribeDateUtils::DBDATEFORMAT );
	$end_datetime = tribe_get_end_date();
	$end_date = tribe_get_end_date( null, false );
	$end_time = tribe_get_end_date( null, false, $time_format );
	$end_ts = tribe_get_end_date( null, false, TribeDateUtils::DBDATEFORMAT );
	// All day (multiday) events
	if ( tribe_event_is_all_day() && tribe_event_is_multiday() ) :
	?>
    <span class="when"><?php _e( 'When:', 'tribe-events-calendar' ) ?></span>
	<?php esc_html_e( $start_date ) ?> - <?php esc_html_e( $end_date ) ?>
    <?php elseif ( tribe_event_is_all_day() ):?>
	<span class="when"><?php _e( 'When:', 'tribe-events-calendar' ) ?></span>
    <?php esc_html_e( $start_date ) ?>
    <?php // Multiday events
	elseif ( tribe_event_is_multiday() ) :?>
    <span class="when"><?php _e( 'When:', 'tribe-events-calendar' ) ?></span>
    <?php esc_html_e( $start_datetime ) ?> - <?php esc_html_e( $end_datetime ) ?>
    <?php // Single day events
	else : ?>
    <span class="when"><?php _e( 'When:', 'tribe-events-calendar' ) ?></span>
    <?php esc_html_e( $start_date ) ?> @ <?php if ( $start_time == $end_time ) {
		esc_html_e( $start_time );
	} else {
		esc_html_e( $start_time . $time_range_separator . $end_time );
	} ?>
    <?php endif ?>
    <?php do_action( 'tribe_events_single_meta_details_section_end' ) ?>
</div>