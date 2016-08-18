<?php
/*
Template Name: Search Results
*/
get_header(); ?>
		<main id="main" role="main">
			<h1><?php printf( __( 'Search Results: %s', 'InvitationHomes' ), '<span>' . get_search_query() . '</span>'); ?></h1>
			<div id="twocolumns">
            <?php
			$search_refer = $_GET["post_type"];
			if ($search_refer == 'video') { load_template(TEMPLATEPATH . '/search-results-videos.php'); }
			else if( $search_refer == 'wpm-testimonial' ) { load_template(TEMPLATEPATH . '/search-results-testimonial.php'); }
			else if( $search_refer == 'team' ) { load_template(TEMPLATEPATH . '/search-results-team.php'); }
			else { load_template(TEMPLATEPATH . '/search-results.php'); };
			?>
			</div>
		</main><?php
get_footer(); ?>