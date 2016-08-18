<?php 
if ( function_exists('wp_pagenavi')):
	wp_pagenavi(); 
else: ?>
    <div class="navigation">
        <div class="next"><?php next_posts_link(__('Older Entries &raquo;', 'InvitationHomes')) ?></div>
        <div class="prev"><?php previous_posts_link(__('&laquo; Newer Entries', 'InvitationHomes')) ?></div>
    </div><?php
endif;

?>
