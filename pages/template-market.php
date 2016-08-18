<?php
/*
Template Name: Market Template
*/
$featured = query_posts(array( 
    'post_type' => 'property',
    'posts_per_page' => 10,
    'meta_key' => 'featured-property',
    'meta_value' => 'on',
));
wp_reset_query();
							
get_header(); ?>
	<main id="main" role="main">
		<?php the_title('<h1>', '</h1>'); ?>
		<div class="main-holder">
			<div class="map-area"><?php 
				if(has_post_thumbnail()): ?>
				<div class="map">
					<div class="holder">
						<?php the_post_thumbnail('full'); ?>
					</div>
				</div><?php 
				endif; ?>
				<!-- Static Block -->
				<div class="aside">
					<form action="#">
						<fieldset>
							<h2>Request Information</h2>
							<div class="row">
								<input type="text" id="full-name">
								<label for="full-name">Full Name</label>
							</div>
							<div class="row">
								<input type="tel" id="phone">
								<label for="phone">Phone Number <span class="note">ex: 123-456-7890</span></label>
							</div>
							<div class="row">
								<input type="email" id="email-address">
								<label for="email-address">Email Address</label>
							</div>
							<div class="row">
								<textarea id="request-comments" cols="30" rows="10"></textarea>
								<label for="request-comments">Comments <span class="note">Start typing...</span></label>
							</div>
							<div class="row">
								<input type="submit" value="Submit">
							</div>
						</fieldset>
					</form>
					<address>
						<a href="tel:8003397368" class="tel-link"><?php the_field('market_telephone_number'); ?></a>
						<dl>
							<dt>Our Headquarters</dt>
							<dd>901 Main Street, Suite #4700<br>Dallas, TX 75202</dd>
						</dl>
						<dl>
							<dt>Email us At:</dt>
							<dd><a href="mailto:<?php the_field('market_email_address');?>"><?php the_field('market_email_address');?></a></dd>
						</dl>
					</address>
				</div>
			</div>			
			<div class="featured">
				<h2>Featured Homes for Rent</h2>
				<!-- cycle gallery -->
				<div class="cycle-gallery">
					<div class="mask-wrapper">
						<div class="mask">
							<div class="slideset">
								<?
							    foreach ($featured as $post) { setup_postdata($post); $meta = get_post_meta($post->ID);
								?>
								<div class="slide">
									<div class="img-holder">
										<a href="#">
											<strong class="price"><?=$meta['Price'][0]?></strong>
											<img src="<?=$meta['photos'][0]?>" alt="image description">
										</a>
									</div>
									<h3><a href="#"><?=$meta['City'][0]?>, <?=$meta['State'][0]?></a></h3>
									<span><?=$meta['Beds'][0]?> beds, <?=number_format($meta['Baths'][0])?> baths, <?=number_format($meta['Square Footage'][0])?> sq. ft.</span>
									<address><?=$post->post_title?><br><?=$meta['City'][0]?>, <?=$meta['State'][0]?> <?=$meta['Zip'][0]?></address>
								</div>
								<? } ?>
							</div>
						</div>
					</div>
					<a class="btn-prev" href="#">Previous</a>
					<a class="btn-next" href="#">Next</a>
				</div>
			</div>
			<div class="page-content">
				<?php the_content(); ?>
			</div><?php
		get_template_part('blocks/vedio'); ?>
		</div>
	</main><?php 	
get_footer(); ?>