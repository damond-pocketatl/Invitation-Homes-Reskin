<?php
/**
 * Single Event Meta (Venue) Template
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe-events/modules/meta/venue.php
 *
 * @package TribeEventsCalendar
 */

if ( ! tribe_get_venue_id() ) {
	return;
}
$phone = tribe_get_phone();
$website = tribe_get_venue_website_link();
?>

<div class="tribe-events-meta-group tribe-events-meta-group-venue">
	<span class="where">Where:</span>
	
		<?php do_action( 'tribe_events_single_meta_venue_section_start' ) ?>

		<?php echo tribe_get_venue() ?>

		<?php
		// Do we have an address?
		$address = tribe_address_exists() ? '' . tribe_get_full_address() . '' : '';

		// Do we have a Google Map link to display?
		$gmap_link = tribe_show_google_map_link() ? tribe_get_map_link_html() : '';
		$gmap_link = apply_filters( 'tribe_event_meta_venue_address_gmap', $gmap_link );

		// Display if appropriate
		if ( ! empty( $address ) ) {
			echo '' . "$address $gmap_link";
		}
		?>

		<?php if ( ! empty( $phone ) ): ?>
			<span class="phone"><?php _e( 'Phone:', 'tribe-events-calendar' ) ?> <?php echo $phone ?></span>
		<?php endif ?>

		<?php if ( ! empty( $website ) ): ?>
			<span class="website"><?php _e( 'Website:', 'tribe-events-calendar' ) ?> <?php echo $website ?></span>
		<?php endif ?>

		<?php do_action( 'tribe_events_single_meta_venue_section_end' ) ?>
	</dl>
</div>