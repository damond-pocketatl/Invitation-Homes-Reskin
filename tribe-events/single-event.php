<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$event_id = get_the_ID();
?>
<main id="main" role="main">
  <h1>Upcoming Events</h1>
  <div id="twocolumns">
    <div id="content">
      <div class="c1">          
      <div id="tribe-events-content" class="tribe-events-single vevent hentry">
	<!-- Event meta -->
	<?php do_action( 'tribe_events_single_event_before_the_meta' ) ?>
	<?php if ( ! apply_filters( 'tribe_events_single_event_meta_legacy_mode', false ) ) {
		tribe_get_template_part( 'modules/meta' );
	} else {
		echo tribe_events_single_event_meta();
	}
	do_action( 'tribe_events_single_event_after_the_meta' )?>
	<!-- Notices -->
	<?php tribe_events_the_notices() ?>
	<!-- Event header -->
	<div id="tribe-events-header" <?php tribe_events_the_header_attributes() ?>> 
        <!-- Navigation -->
        <h3 class="tribe-events-visuallyhidden">
          <?php _e( 'Event Navigation', 'tribe-events-calendar' ) ?>
        </h3>
		<ul class="tribe-events-sub-nav">
			<li class="tribe-events-nav-previous">
				<?php tribe_the_prev_event_link( '<span>&laquo;</span> %title%' ) ?>
			</li>
			<li class="tribe-events-nav-next">
				<?php tribe_the_next_event_link( '%title% <span>&raquo;</span>' ) ?>
			</li>
		</ul>
		<!-- .tribe-events-sub-nav --> 
	</div>
	<!-- #tribe-events-header -->
	<?php while ( have_posts() ) :  the_post(); ?>
	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>> 
        <!-- Event featured image, but exclude link --> 
        <div class="single-calendar-left">
			<?php echo tribe_event_featured_image( $event_id, 'full', false ); ?>
        	<div class="date-box"><?php echo tribe_events_event_schedule_details_2() ?></div>
        </div>
        <div class="single-calendar-right">
        <?php the_title( '<h2 class="tribe-events-single-event-title summary entry-title">', '</h2>' ); ?>
        <?php tribe_get_template_part( 'modules/meta/venue' );?>    
        <div class="tribe-events-schedule updated published tribe-clearfix">
            <?php echo tribe_events_event_schedule_details( $event_id, '<h3>', '</h3>' ); ?>
            <?php if ( tribe_get_cost() ) : ?>
                <span class="tribe-events-divider">|</span> <span class="tribe-events-cost"><?php echo tribe_get_cost( null, true ) ?></span>
            <?php endif; ?>
        </div>
        
        <!-- Event content -->
        <?php do_action( 'tribe_events_single_event_before_the_content' ) ?>
        <div class="tribe-events-single-event-description tribe-events-content entry-content description">
            <?php the_content(); ?>           
        </div>
        <p class="tribe-events-back">
            <a href="<?php echo tribe_get_events_link() ?>"><?php _e( 'Back to Events', 'tribe-events-calendar' ) ?>
            </a>
            </p>
        <!-- .tribe-events-single-event-description -->
        <?php do_action( 'tribe_events_single_event_after_the_content' ) ?>
        </div>
    </div>
    <!-- #post-x -->
    <?php if ( get_post_type() == TribeEvents::POSTTYPE && tribe_get_option( 'showComments', false ) ) comments_template() ?>
    <?php endwhile; ?>  
    <!-- Event footer -->
    <div id="tribe-events-footer"> 
        <!-- Navigation --> 
        <h3 class="tribe-events-visuallyhidden">
            <?php _e( 'Event Navigation', 'tribe-events-calendar' ) ?>
        </h3>
        <!-- .tribe-events-sub-nav --> 
    </div>
	<!-- #tribe-events-footer -->  
</div>
<!-- #tribe-events-content --> 
      
      
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