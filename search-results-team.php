<div id="content">
  <div class="c1">
    <ul class="posts-list">
    <?php if(have_posts()):?>

    <?php while(have_posts()):the_post();	
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
      <?php endwhile;?>
      <?php endif;?>
    </ul>
    <?php get_template_part('blocks/pager'); ?>
  </div>
</div>
<!-- contain sidebar of the page -->
<aside id="sidebar">
        	<div class="search-box">
            <form method="get" id="search-form" class="search-form" action="<?php bloginfo('url');?>">
				<div class="form-main">
					<div class="holder">
						<input type="search" name="s" id="s" value="Search Keywords" onfocus="if (this.value == 'Search Keywords') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Search Keywords';}">
                        <input type="hidden" name="post_type" value="team" />
						<input type="submit" value="Search Keywords">
					</div>
				</div>
				</form>  
            	<form method="get" action="/">
                	<div class="check-holder">
                    <strong class="title">Team Location:</strong>
                   <?php 
				$link = get_current_filter_url();   
   				$arg = 'loc';
				$current_filter = get_current_filter_key($arg ); 
				
				$args = array(
					'orderby' => 'name',
					'order' => 'ASC',			
				);
					//$args = array( 'hide_empty' => 0 );
					$terms = get_terms( 'team-locations',$args );
					if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
						$count = count( $terms );
						$term_list = '';
						foreach ( $terms as $term ) {
							
							$new_current_filter = $current_filter;
							if (!in_array($term->term_id, $new_current_filter)) 
								$new_current_filter[] = $term->term_id;
							$link = add_query_arg( $arg, implode(',', $new_current_filter),$link );
							 if(in_array($term->term_id,$location)) 
							 {
								$loc_check = 'checked';
								} else {
								$loc_check = '';
								}
							
							$active = (get_queried_object_id() == $category->ID) ? ' current_page_item active' : '';
							$term_list .= '<div class="row"><input type="checkbox" '.$loc_check.' name="loc'.$term->term_id.'" class="loc_filter" value="'.$link.'" id="'.$term->term_id.'"><label class="label_location" for="location">' . $term->name . '</label></div>';
							}
						//echo '<div class="row"><a href="/our-team/">View all</a></div>';
						echo $term_list;
					}?>	                   
					</div>
				<!--</form>               
                </div> -->               
                <div class="check-holder">
                	<strong class="title">Team Categories:</strong>
                    <?php 
					$link = get_current_filter_url(); 
					$arg = 'team_c';
				    $current_filter = get_current_filter_key($arg ); 
				
					$args = array(
					'orderby' => 'name',
					'order' => 'ASC',			
				);
					$args = array( 'hide_empty' => 1 );
					$terms = get_terms( 'team-categories',$args );
					if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
						$count = count( $terms );
						$term_list = '';
						foreach ( $terms as $term ) {
						//print_r($term);
						    $new_current_filter = $current_filter;
							if (!in_array($term->term_id, $new_current_filter)) 
								$new_current_filter[] = $term->term_id;
							$link = add_query_arg( $arg, implode(',', $new_current_filter),$link );
	                       if(in_array($term->term_id,$team)) {
							$team_check = 'checked';
							} else {
							$team_check = '';
							}
							$active = (get_queried_object_id() == $category->ID) ? ' current_page_item active' : '';
							$term_list .= '<div class="row"><label class="label_location" for="category">' . $term->name . '<input type="checkbox" '.$team_check.' name="team'.$term->term_id.'" class="team_filter" value="'.$link.'" id="'.$term->term_id.'"></label></div>';
							}
						//echo '<div class="row"><a href="/our-team/">View all</a></div>';
						echo $term_list;
					}?>		
                    <br />	
                     <input type="button" name="button" class="filter_button" value="Filter" />				
				</div>
                
                </form>
			</div>
            <script>
            jQuery(document).ready(function(){
			var id_check = '<?php echo $location; ?>';
			var x = 1;
			choices_val = [] ;
			choices_val_team = [];
				jQuery(".filter_button").click(function(){
				jQuery(".loc_filter").each(function () {
				if(jQuery(this).is(":checked"))
				{				
				choices_val.push(jQuery(this).attr('id'));										
				} 
				});
				jQuery(".team_filter").each(function () {
				if(jQuery(this).is(":checked"))
				{				
				choices_val_team.push(jQuery(this).attr('id'));										
				} 
				});
				//choices_val.join(',');
				if(choices_val != '' && choices_val_team != '') 
				{
					location.href = "<?php echo esc_url( home_url( '/' ) ); ?>our-team/?loc="+choices_val.join(',')+"&team_c="+choices_val_team.join(',');
				} else if(choices_val !='' && choices_val_team == '') {
					location.href = "<?php echo esc_url( home_url( '/' ) ); ?>our-team/?loc="+choices_val.join(',');
				} else if(choices_val_team !='' && choices_val == '') {
					location.href = "<?php echo esc_url( home_url( '/' ) ); ?>our-team/?team_c="+choices_val_team.join(',');
				}
				else
				{
					location.href = "<?php echo esc_url( home_url( '/' ) ); ?>our-team/";
				}
			  });
			});
            
            </script>
			<?php get_sidebar(); ?>
		</aside>
