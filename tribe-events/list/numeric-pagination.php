<!-- Numeric Pagination -->
<?php 
$current_page = (get_query_var('paged') == "") ? 1 : get_query_var('paged');
if( is_tax() ) {	
	wp_pagenavi();
	
}
else {
wp_pagenavi();
/*
	$term = $wp_query->get_queried_object();
	$taxslug = $term->slug;	
	$args = array(
	   'posts_per_page' => 999,
	   'post_type' => 'tribe_events',
	   'eventDisplay' => 'upcoming',
	   'tax_query' => array(
			array(
				'taxonomy' => 'tribe_events_cat',
				'field'    => 'slug',
				'terms'    => $taxslug,
			),
		),
	);
	if (isset($_REQUEST['tribe_venues'])) {
		$args['meta_key'] = '_EventVenueID';
		$args['meta_value'] = $_REQUEST['tribe_venues'];
	}
	$numberofevents = new WP_Query($args);
	print_r($numberofevents);
	*/
/*$pages = ceil($wp_query->found_posts / 4);
for ($i = 1; $i <= $pages; $i++) {
	?><li id="page-<?=$i?>" class="<?php if ($current_page == $i) { echo "page number current";} else { echo "page number";}?>"><?=$i?></li><?
}*/


/*
if ($wp_query->found_posts <= "4") {echo "";}
elseif ($wp_query->found_posts <= "8") {?>
    <li id="page-1" class="<?php if ($current_page == '') { echo "page number current";} elseif ($current_page == '1') { echo "page number current";} else { echo "page number";}?>">1</li>
    <li id="page-2" class="<?php if ($current_page == '2') { echo "page number current";} else { echo "page number";}?>">2</li>
<?php }
elseif ($wp_query->found_posts <= "12") {?>
	<li id="page-1" class="<?php if ($current_page == '1') { echo "page number current";} else { echo "page number";}?>">1</li>
	<li id="page-2" class="<?php if ($current_page == '2') { echo "page number current";} else { echo "page number";}?>">2</li>
	<li id="page-3" class="<?php if ($current_page == '3') { echo "page number current";} else { echo "page number";}?>">3</li>
<?php } elseif ($wp_query->found_posts <= "16") {?>
	<li id="page-1" class="<?php if ($current_page == '1') { echo "page number current";} else { echo "page number";}?>">1</li>
	<li id="page-2" class="<?php if ($current_page == '2') { echo "page number current";} else { echo "page number";}?>">2</li>
	<li id="page-3" class="<?php if ($current_page == '3') { echo "page number current";} else { echo "page number";}?>">3</li>
	<li id="page-4" class="<?php if ($current_page == '4') { echo "page number  current";} else { echo "page number";}?>">4</li>
<?php } elseif ($wp_query->found_posts <= "20") {?>
	<li id="page-1" class="<?php if ($current_page == '1') { echo "page number current";} else { echo "page number";}?>">1</li>
	<li id="page-2" class="<?php if ($current_page == '2') { echo "page number current";} else { echo "page number";}?>">2</li>
	<li id="page-3" class="<?php if ($current_page == '3') { echo "page number current";} else { echo "page number";}?>">3</li>
	<li id="page-4" class="<?php if ($current_page == '4') { echo "page number current";} else { echo "page number";}?>">4</li>
	<li id="page-5" class="<?php if ($current_page == '5') { echo "page number current";} else { echo "page number";}?>">5</li>
<?php } elseif ($wp_query->found_posts <= "24") {?>
	<li id="page-1" class="<?php if ($current_page == '1') { echo "page number current";} else { echo "page number";}?>">1</li>
	<li id="page-2" class="<?php if ($current_page == '2') { echo "page number current";} else { echo "page number";}?>">2</li>
	<li id="page-3" class="<?php if ($current_page == '3') { echo "page number current";} else { echo "page number";}?>">3</li>
	<li id="page-4" class="<?php if ($current_page == '4') { echo "page number current";} else { echo "page number";}?>">4</li>
	<li id="page-5" class="<?php if ($current_page == '5') { echo "page number current";} else { echo "page number";}?>">5</li>
    <li id="page-6" class="<?php if ($current_page == '6') { echo "page number current";} else { echo "page number";}?>">6</li>
<?php }
*/
}?>