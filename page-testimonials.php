<?php
/*
Template Name: Page (Testimonials)
*/
get_header(); ?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/redmond/jquery-ui.css" rel="stylesheet" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/jquery.youtubepopup.min.js"></script>
<script type="text/javascript">
$(function () {
	$("a.youtube").YouTubePopup({ autoplay: 0, clickOutsideClose: true });
});
</script>
<main id="main" role="main">
	<?php the_title('<h1>', '</h1>'); ?>
	<div id="twocolumns">
		<div id="content">
			<div class="c1">
				<ul class="posts-list">
				<?php $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
				$args = array('post_type' => 'wpm-testimonial', 'posts_per_page' => 5, 'paged' => $paged, 'orderby' => 'date');
				query_posts($args); 
				if ( have_posts() ) : while (have_posts()) : the_post();
				$ytid = get_post_meta(get_the_id(), "_yt_id", true);
				if( $ytid == '' ) {
				$ytid = get_field('video_link');
				$videourl = $ytid;
				} else {
				$videourl = "http://www.youtube.com/embed/{$ytid}?rel=0";
				}
                $durat = get_post_meta(get_the_id(), "_durat", true);?>
                	<li><?php if(has_post_thumbnail()) { ?>
                	
                    	<div class="img-holder">
                         <?php if($ytid && $ytid!='') {  ?>  
                         	<a onclick="return false;" class="youtube" href="<?=$videourl?>"><?php the_post_thumbnail(array(320,180)); ?></a><?php } else { ?>
                            <?php echo get_the_post_thumbnail( $post->ID, /* array(320,180) */ 'video-thumb' ); } ?>
						</div>
						<?php } else { ?>
                        <div class="img-holder">   
                        <?php if($ytid && $ytid!='') {  ?>                     	
                        	<a onclick="return false;" class="youtube" href="<?=$videourl?>"><img src="http://img.youtube.com/vi/<?=$ytid?>/mqdefault.jpg" /></a>
                            <?php } ?>
                            
						</div>
						<? } ?>
                        <div class="description" itemprop="video" itemscope itemtype="http://schema.org/VideoObject">  
                         <?php if($ytid && $ytid!='') {  ?>                         
                        	<h2><a itemprop="name" onclick="return false;" class="youtube" href="<?=$videourl?>"><?php the_title(); ?></a></h2><?php } ?>
                            <p itemprop="description"><?php the_content(); ?></p>
                            <div class="client <?php if ( !has_post_thumbnail( $post->ID ) ) {?>not_thumbnail1<?php } ?>">
                               <?php $client_name = get_post_meta( get_the_ID(), 'client_name', true ); if( ! empty( $client_name ) &&  $client_name ) { ?>
                                	<div>- </div>
									<?php  } ?>
                                    <div class="name"><?php $client_name = get_post_meta( get_the_ID(), 'client_name', true ); if( ! empty( $client_name )  && $client_name) { echo $client_name; } ?></div>
                                    <?php $client_name = get_post_meta( get_the_ID(), 'client_name', true ); if( ! empty( $client_name )  && $client_name ) { ?>
                                    <!-- <div>, </div> -->
                                    <?php  } ?>
                                    <div class="company"><?php $company_name = get_post_meta( get_the_ID(), 'company_name', true ); if( ! empty( $company_name ) ) { ?><div>, </div><?php echo $company_name; } ?></div>
								</div>                            
						</div>
					</li>
				<?php endwhile;				
				wp_pagenavi();
				wp_reset_query(); 
				endif;?>
                
                <!--echo '<div id="arrow-pagination">';
				get_pagination();
				echo '</div>';-->
				</ul>
			</div>
		</div>
        <aside id="sidebar">
        	<div class="search-box">
            	<form method="get" id="search-form" class="search-form" action="<?php bloginfo('url');?>">
				<div class="form-main">
					<div class="holder">
						<input type="search" name="s" id="s" value="Search Keywords" onfocus="if (this.value == 'Search Keywords') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Search Keywords';}">
                        
                        <input type="hidden" name="post_type[]" value="blog" />
                        <input type="hidden" name="post_type[]" value="video" /> 
                        <input type="hidden" name="post_type[]" value="wpm-testimonial" />  
						<input type="submit" value="Search Keywords">
					</div>
				</div>
			</form>
               <div class="check-holder">
                	<strong class="title">Reviews Categories:</strong>
                    <?php $args = array( 'hide_empty' => 1,'exclude' =>133,'orderby' => 'name','order' => 'ASC' );
					$terms = get_terms( 'wpm-testimonial-category', $args );
					if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
						$count = count( $terms );
						$term_list = '';
						foreach ( $terms as $term ) {
							$active = (get_queried_object_id() == $term->term_id) ? ' current_page_item active' : '';
							$term_list .= '<div class="row"><a class="'.$active.'" href="' . get_term_link( $term ) . '" title="' . sprintf( __( 'View all post filed under %s', 'my_localization_domain' ), $term->name ) . '">' . $term->name . '</a></div>';
							}
						echo '<div class="row"><a href="/reviews/">View all</a></div>';
						echo $term_list;
					}?>		
                    <?php /*?><br />
                    <strong class="title">Reviews Videos:</strong>	
                    <?php $args = array( 'hide_empty' => 0,'slug'=>'reviews','orderby' => 'name','order' => 'ASC' );
					$terms = get_terms( 'wpm-testimonial-category', $args );
					if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
						$count = count( $terms );
						$term_list = '';
						foreach ( $terms as $term ) {
							$active = (get_queried_object_id() == $term->term_id) ? ' current_page_item active' : '';
							$term_list .= '<div class="row"><a class="'.$active.'" href="' . get_term_link( $term ) . '" title="' . sprintf( __( 'View all post filed under %s', 'my_localization_domain' ), $term->name ) . '">' . $term->name . '</a></div>';
							}
						//echo '<div class="row"><a href="/testimonials/">View all</a></div>';
						echo $term_list;
					}?>	<?php */?>					
				</div>               
			</div>
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('inner-sidebar') ) : ?>
			<?php endif; ?>
		</aside>
	</div>
<?php //get_template_part('blocks/inner-boxes');?>
</main>
<?php get_footer(); ?>