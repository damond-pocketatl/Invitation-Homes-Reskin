<?php
/**
 * List View Template
 * The wrapper template for a list of events. This includes the Past Events and Upcoming Events views
 * as well as those same views filtered to a specific category.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/list.php
 *
 * @package TribeEventsCalendar
 *
 */
global $wp_query;
//print_r($wp_query);
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
} 
?>
<main id="main" role="main">
  <h1>Upcoming Events</h1>
  <div id="twocolumns">
    <div id="content">
      <div class="c1">
        <?php do_action( 'tribe_events_before_template' ); ?>
        <div class="calendar-content">
          <?php tribe_get_template_part( 'list/content' ); ?>
          <div class="tribe-clear"></div>
        </div>
        <?php do_action( 'tribe_events_after_template' ) ?>
      </div>
    </div>
    <aside id="sidebar">
      <div class="search-box">
        <form method="get" id="search-form" class="search-form" action="<?php bloginfo('url');?>">
          <div class="form-main">
            <div class="holder">
              <input type="search" name="s" id="s" value="Search Keywords" onfocus="if (this.value == 'Search Keywords') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Search Keywords';}">
              <input type="hidden" name="post_type" value="tribe_events" />
              <input type="submit" value="Search Keywords">
            </div>
          </div>
        </form>
        <div class="tribe-modules"><?php tribe_get_template_part( 'modules/bar' ); ?></div>
        <div class="check-holder"> 
          <?php $args = array( 'hide_empty' => 1 );
		  			$query_string = ( (isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING']!="") ?'?'.$_SERVER['QUERY_STRING']:"");
					global $wp_query , $wpdb;
					$link = get_current_filter_url(); 
					$arg = 'event_loc';
				    $current_filter = get_current_filter_key($arg );
		 			$showTitle = true;
					$terms = get_terms( 'event-locations', $args );
					if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
						$count = count( $terms );
						$term_list = '';
						$start_date  = $wp_query->get( 'start_date' );
						foreach ( $terms as $term ) {
						$new_current_filter = $current_filter;
							if (!in_array($term->term_id, $new_current_filter)) 
								$new_current_filter[] = $term->term_id;
							$link = add_query_arg( $arg, implode(',', $new_current_filter),$link );
							$team = explode(',',$_GET['event_loc']);
	                       if(in_array($term->term_id,$team)) {
							$event_check = 'checked';
							} else {
							$event_check = '';
							}
							
							$isCountShow = true;
							$noofpostInCatArray =  array();
							if(isset($_GET['tribe_venuesq']) && $_GET['tribe_venuesq']!=""&& $_GET['tribe_venuesq']>0)
							{
								$noofpostArray =  array();
								$noofpostArray   =  isnotemptyeventvenue($_GET['tribe_venues'])	;								
								if(empty($noofpostArray))
								{
									$isCountShow = false;
								}
							}
								
							$noofpostInCatArray  = isnotemptyeventcategory($term->term_taxonomy_id);
							//print_r($noofpostArray);
							if(empty($noofpostInCatArray))
							{
								$isCountShow = false;
							}
							else if(is_array($noofpostArray) && is_array( $noofpostInCatArray) )
							{
								$inter = array_intersect ($noofpostArray, $noofpostInCatArray );
								if( !(is_array($inter) && count($inter) ))
								{
									//$isCountShow = false;
								}
							}					
							if(!$isCountShow) {
								continue;
							}
							if($showTitle)
							{
								?>
                                <strong class="title">Event Location:</strong>
                                <?php
								$showTitle = false;
							}
							if(is_tax() ) {
								$active = (get_queried_object_id() == $term->term_id) ? ' current_page_item active' : '';
							} else {
								$active =  '';
							}
							
							$term_list .= '<div class="row"><input type="checkbox" '.$event_check.' name="loc'.$term->term_id.'" class="loc_filter" value="'.$link.'" id="'.$term->term_id.'"><label class="label_location" for="category">' . $term->name . '</label></div>';
							}
						echo '<div class="row"><a href="/events/'.'">View all</a></div>';
						echo $term_list;
					}?>
                   
        </div>
        
        <div class="check-holder"> 
          <?php $args = array( 'hide_empty' => 1 );
		  			$query_string = ( (isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING']!="") ?'?'.$_SERVER['QUERY_STRING']:"");
					global $wp_query , $wpdb;
					$link = get_current_filter_url(); 
					$arg = 'event_c';
				    $current_filter = get_current_filter_key($arg );
		 			$showTitle = true;
					$terms = get_terms( 'tribe_events_cat', $args );
					if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
						$count = count( $terms );
						$term_list = '';
						$start_date  = $wp_query->get( 'start_date' );
						foreach ( $terms as $term ) {
						$new_current_filter = $current_filter;
							if (!in_array($term->term_id, $new_current_filter)) 
								$new_current_filter[] = $term->term_id;
							$link = add_query_arg( $arg, implode(',', $new_current_filter),$link );
							$team = explode(',',$_GET['event_c']);
	                       if(in_array($term->term_id,$team)) {
							$event_check = 'checked';
							} else {
							$event_check = '';
							}
							/*$sqlET = $wpdb->prepare("SELECT SQL_CALC_FOUND_ROWS DISTINCT ".$wpdb->prefix."posts.*, MIN(".$wpdb->prefix."postmeta.meta_value) as EventStartDate, tribe_event_end_date.meta_value as EventEndDate FROM ".$wpdb->prefix."posts  INNER JOIN ".$wpdb->prefix."term_relationships ON (wp_posts.ID = ".$wpdb->prefix."term_relationships.object_id)  INNER JOIN ".$wpdb->prefix."term_relationships AS tt1 ON (wp_posts.ID = tt1.object_id) INNER JOIN ".$wpdb->prefix."postmeta ON ( ".$wpdb->prefix."posts.ID = ".$wpdb->prefix."postmeta.post_id ) LEFT JOIN ".$wpdb->prefix."postmeta as tribe_event_end_date ON ( ".$wpdb->prefix."posts.ID = tribe_event_end_date.post_id AND tribe_event_end_date.meta_key = '_EventEndDate' )  WHERE 1=1  AND ( 
  ".$wpdb->prefix."term_relationships.term_taxonomy_id IN (%d) 
  AND 
  tt1.term_taxonomy_id IN (%d)
) AND ( 
  ".$wpdb->prefix."postmeta.meta_key = '_EventStartDate'
) AND ".$wpdb->prefix."posts.post_type = 'tribe_events' AND ( ".$wpdb->prefix."posts.post_status = 'publish' OR ".$wpdb->prefix."posts.post_status = 'private') AND ( ".$wpdb->prefix."postmeta.meta_value >= %s OR ( ".$wpdb->prefix."postmeta.meta_value <= %s AND tribe_event_end_date.meta_value >= %s )) GROUP BY  IF( ".$wpdb->prefix."posts.post_parent = 0, ".$wpdb->prefix."posts.ID, ".$wpdb->prefix."posts.post_parent ) ORDER BY ".$wpdb->prefix."posts.menu_order, DATE(MIN( ".$wpdb->prefix."postmeta.meta_value)) ASC, TIME( ".$wpdb->prefix."postmeta.meta_value) ASC LIMIT 0, 1",$term->term_taxonomy_id , $term->term_taxonomy_id ,$start_date, $start_date ,$start_date );
							$resultsET = $wpdb->get_row( $sqlET, OBJECT );
							if ( is_object( $resultsET ) ) {
								//echo "<pre>";
								//print_r($resultsET);
							}
							else
							{
								continue;
							}*/
							$isCountShow = true;
							$noofpostInCatArray =  array();
							if(isset($_GET['tribe_venuesq']) && $_GET['tribe_venuesq']!=""&& $_GET['tribe_venuesq']>0)
							{
								$noofpostArray =  array();
								$noofpostArray   =  isnotemptyeventvenue($_GET['tribe_venues'])	;								
								if(empty($noofpostArray))
								{
									$isCountShow = false;
								}
							}
								
							$noofpostInCatArray  = isnotemptyeventcategory($term->term_taxonomy_id);
							//print_r($noofpostArray);
							if(empty($noofpostInCatArray))
							{
								$isCountShow = false;
							}
							else if(is_array($noofpostArray) && is_array( $noofpostInCatArray) )
							{
								$inter = array_intersect ($noofpostArray, $noofpostInCatArray );
								if( !(is_array($inter) && count($inter) ))
								{
									//$isCountShow = false;
								}
							}					
							if(!$isCountShow) {
								continue;
							}
							if($showTitle)
							{
								?>
                                <strong class="title">Event Categories:</strong>
                                <?php
								$showTitle = false;
							}
							if(is_tax() ) {
								$active = (get_queried_object_id() == $term->term_id) ? ' current_page_item active' : '';
							} else {
								$active =  '';
							}
							
							$term_list .= '<div class="row"><label class="label_location" for="category">' . $term->name . '<input type="checkbox" '.$event_check.' name="team'.$term->term_id.'" class="event_filter" value="'.$link.'" id="'.$term->term_id.'"></label></div>';
							}
						echo '<div class="row"><a href="/events/'.'">View all</a></div>';
						echo $term_list;
					}?>
                    <br />	
                     <input type="button" name="button" class="filter_button" value="Filter" />
        </div>
      </div>
      <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('inner-sidebar') ) : ?>
	  <?php endif; ?>
    </aside>
  </div>
<?php get_template_part('blocks/inner-boxes');?>
</main>
<script>
            jQuery(document).ready(function(){
			var id_check = '<?php echo $location; ?>';
			var x = 1;
			choices_val = [] ;
			choices_val_event = [];
				jQuery(".filter_button").click(function(){
				jQuery(".loc_filter").each(function () {
				if(jQuery(this).is(":checked"))
				{				
				choices_val.push(jQuery(this).attr('id'));										
				} 
				});
				jQuery(".event_filter").each(function () {
				if(jQuery(this).is(":checked"))
				{				
				choices_val_event.push(jQuery(this).attr('id'));										
				} 
				});
				
				
				//choices_val.join(',');
				if(choices_val != '' && choices_val_event != '') 
				{
					location.href = "<?php echo esc_url( home_url( '/' ) ); ?>events/?event_loc="+choices_val.join(',')+"&event_c="+choices_val_event.join(',');
				} else if(choices_val !='' && choices_val_event == '') {
					location.href = "<?php echo esc_url( home_url( '/' ) ); ?>events/?event_loc="+choices_val.join(',');
				} else if(choices_val_event !='' && choices_val == '') {
					location.href = "<?php echo esc_url( home_url( '/' ) ); ?>events/?event_c="+choices_val_event.join(',');
				}
				else
				{
					location.href = "<?php echo esc_url( home_url( '/' ) ); ?>events";
				}

			  });
			});
            
            </script>

