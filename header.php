<!DOCTYPE HTML>
<html <?php language_attributes(); ?>>
<head>

<?php //echo "<pre>"; print_r($_COOKIE)?>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="language" content="english">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta property="fb:app_id" content="198425630496954"/>
<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/images/favicon.png" type="image/x-icon">
<?php /*
<script type="application/ld+json">
    {
      "@context": "http://schema.org",
      "@type": "Organization",
	  "name" : "Invitation Homes",
	  "telephone" : "855-684-7368",
	  "email" : "info@IHRent.com",
	  "address" : {
		"@type" : "PostalAddress",
		"streetAddress" : "901 Main Street, Suite #4700",
		"addressLocality" : "Dallas, TX",
		"postalCode" : "75202"
	  },
      "url": "http://invitationhomes.com",
      "logo": "http://invitationhomes.com/wp-content/uploads/2015/04/logo.png"
    }
    </script>

*/?>
<!--<link rel="icon" href="/favicon.ico" type="image/x-icon">-->
<title><?php
global $post;
$post = get_post($post->ID);
$slug = $post->post_name;
//echo "asdasd".$slug;
//if ( $slug == "blog") { echo "blog";} else { echo"not blog"; }
?>
<?php
if(is_page('blog')): ?>
Information on the Home Rental Lifestyle | Invitation Homes Blog
<?php else: ?>
<?php bloginfo('name'); ?> - <?php wp_title(); ?>
<?php endif; ?>
</title>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_template_directory_uri(); ?>/theme.css"  />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_template_directory_uri(); ?>/style.css"  />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="<?=site_url()?>/wp-content/plugins/LayerSlider/static/js/layerslider.kreaturamedia.jquery.js?ver=5.1.1"></script>
<?php if ( is_singular() ) wp_enqueue_script( 'theme-comment-reply', get_template_directory_uri()."/js/comment-reply.js" ); ?>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/jquery.main.js"></script>
<!-- Bing Webmaster tools validation -->
<meta name="msvalidate.01" content="A0DE2998B118A1E7A63363010F8C8CC8" />
<!-- Facebook Pixel Code -->
<?php global $post; if( $post->ID == 302871 ) { ?>
<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','https://connect.facebook.net/en_US/fbevents.js';);

fbq('init', '1598154653771869');
fbq('track', "PageView");</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=1598154653771869&ev=PageView&noscript=1";
/></noscript>
<?php } ?>
<!-- End Facebook Pixel Code -->

<!-- pixel for miami -->
<?php global $post; if( $post->ID == 302876 ) { ?>
<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','https://connect.facebook.net/en_US/fbevents.js');

fbq('init', '1598154653771869');
fbq('track', "PageView");</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=1598154653771869&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->
<?php } ?>
<!-- end miami pixel -->
<script type="text/javascript">var switchTo5x=true;</script>
<!-- <script type="text/javascript">stLight.options({publisher: "78cd4a1d-f23e-4f11-8728-7665de7acf89", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script> -->
<?php
if ( is_singular('video') ) {


} elseif ( is_singular('blog')) {
global $post;
$url = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
$content_post = get_post($post->ID);
/*$content = $content_post->post_content;
$content = apply_filters('the_content', $content);
$content = strip_tags(str_replace(']]>', ']]&gt;', $content));
$content = wp_trim_words( $content, 55 );
$excerpt = apply_filters('get_the_excerpt', $single->post_excerpt);*/
 setup_postdata( $content_post );
 wp_reset_postdata();
 $content = strip_tags(get_the_excerpt());
//print_r( $excerpt );
?>
    <meta property="og:title" content="<?php the_title(); ?>"/>
    <?php /*?><meta property="og:type" content="news"/><?php */?>
    <meta property="og:url" content="<?php the_permalink(); ?>"/>
    <meta property="og:image" content="<?php echo $url; ?>"/>
    <meta property="og:site_name" content="Invitation Homes"/>
    <meta property="og:description" content="<?php echo $content; ?>"/>
<?php
} elseif (is_archive()) {


} elseif ( has_term('', 'video_category', $post )) {


} else {

echo '<meta property="og:image" content="http://invitationhomes.com/wp-content/uploads/2015/04/IHogimage.jpg"/>';
echo '<meta property="og:image:type" content="image/jpeg">';
echo '<meta property="og:image:width" content="600">';
echo '<meta property="og:image:height" content="600">';
echo '<meta property="og:image" content="http://invitationhomes.com/wp-content/uploads/2015/04/IHogimage2.jpg"/>';
echo '<meta property="og:image:type" content="image/jpeg">';
echo '<meta property="og:image:width" content="600">';
echo '<meta property="og:image:height" content="600">';
echo '<meta property="og:image" content="http://invitationhomes.com/wp-content/uploads/2015/04/IHogimage3.jpg"/>';
echo '<meta property="og:image:type" content="image/jpeg">';
echo '<meta property="og:image:width" content="600">';
echo '<meta property="og:image:height" content="600">';
}
?>

<!-- End Bing Webmaster tools validation -->
<!-- Lucky Orange real time analytics -->
<!-- End Lucky Orange real time analytics -->
<meta name="google-site-verification" content="hnCWd_WxgFLBvCzIF9wFJ8phBkcR0kmD-Ym9Bt6UMJY" />
<!--[if lt IE 9]><link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/ie8.css"><![endif]-->
<?php if(is_page('current-residents')) {
//echo '<link rel="canonical" href="'.site_url().'" />';
   }  ?>

<?php

global $post;
global $wp_query;
$term = $wp_query->get_queried_object();
if ( is_post_type_archive( 'blog') || $post->post_type=='market' || is_singular('blog') || (is_archive() && $term->taxonomy == 'category' ) )  {
 ?>
 <link rel="alternate" type="application/rss+xmakalasdflkasdflkasdfjkl;asdfjkll" title="Invitation Homes » Feed" href="http://invitationhomes.com/feed/" />

<link rel="alternate" type="application/rss+xml" title="Invitation Homes » Comments

Feed" href="http://invitationhomes.com/comments/feed/" />

<link rel="alternate" type="text/calendar" title="Invitation Homes » iCal Feed"

href="http://invitationhomes.com/events/?ical=1" />

<link rel="alternate" type="application/rss+xml" title="Invitation Homes » Welcome to Invitation Homes Comments Feed" href="http://invitationhomes.com/currentresidents/feed/" />
 <?php
}
wp_head();
 ?>
</head>
<body <?php if(is_front_page()): body_class(array('page-style2' , 'page-style3')); else: body_class(); endif; ?>>

<!-- Notification bar -->


<!--
<div id="wnb-bar">
	<div id="notification">
	We are currently experiencing technical difficulties with the online application on the Invitation Homes website. We apologize for the inconvenience and are working diligently to resolve this issue. If you encounter an error message or are unable to complete your application, please contact your Invitation Homes leasing agent directly or call the <a href="http://invitationhomes.com/contact/">local office</a>. We appreciate your interest in an Invitation Home and will do our very best to expedite your application. Thank you!
	</div>
</div>
-->



<!-- End Notification Bar -->

		<div id="wrapper">
			<div class="w1">
				<header id="header">
					<!-- page logo -->
					<?php if( get_field('logo','option') ): ?>
					<div class="logo-holder">
						<div itemscope itemtype="http://schema.org/Organization" class="logo"><a itemprop="url" href="<?php echo home_url(); ?>"><img alt="Homes for Rent from Invitation Homes" itemprop="logo" src="<?php the_field('logo', 'option'); ?>"  /></a></div>
						<a href="#" class="nav-opener"><span>Open/close</span></a>
					</div>
					<?php endif; ?>
					<div class="nav-drop">
						<div class="nav-drop-holder">
							<div class="tabset-holder">
							<?php if(has_nav_menu('primary'))
							wp_nav_menu( array('container' => false,
							'theme_location' => 'primary',
							'menu_id' => 'navigation',
							'menu_class' => 'tabset',
							'items_wrap' => '<ul class="%2$s">%3$s</ul>',
							'depth' => '2',
							'walker' => new Custom_Header_Nav_Menu_1 ) ); ?>

							</div>
							<style type='text/css'>
								#tab1, #tab2 { display: none; }
							</style>
							<script type="text/javascript">
								jQuery(document).ready(function($) {
									<? if (!isset($_COOKIE['invitationhomes']) && !is_page(302891)) { ?>
										$("#menu-item-302900").addClass('active');
										$("#tab1").show();
									<? } else { ?>
										$("#menu-item-303026").addClass('active');
										$("#tab2").show();
									<? } ?>
									$("#menu-item-302900 > a").click(function(){
										$.get('/?deletecookie', function() {
											document.cookie = "invitationhomes=visited; expires=Thu, 18 Dec 2013 12:00:00 UTC; path=/";
											window.location.href = "/";
										});
										return false;
									});
									$("#location-form").submit(function() {
										//console.log($("#market-select").val());
										window.location = $("#market-select").val();
										return false;
									});
									$("#market-select").change(function() {
										$("#location-form").submit();
									});
								});
							</script>
							<?
								$args = array(
									'post_type' => 'market',
									'posts_per_page' => -1,
								);
								$markets = get_posts($args);
								$menu = wp_get_nav_menu_items(104);

							?>
							<div class="location-holder">
								<!-- search form -->
								<form id="location-form" class="location-form" action="<?php echo home_url(); ?>" >
									<fieldset>
										<select id="market-select" name="s">
											<option value="/choose-a-location/" class="hidden">Choose A Location</option>
											<? foreach ($menu as $market) { $selected = ($market->ID == get_queried_object_id()) ? 'selected="selected"' : ''; ?>
												<option value="<?=str_replace("%market_slug%","market",$market->url)?>" <?=$selected?>><?=$market->title?></option>
											<? } ?>
										</select>
									</fieldset>
								</form>
								<!-- tabs content holder -->
								<div class="tab-content">
									<?php if(has_nav_menu('primary'))
									wp_nav_menu( array('container' => false,
									'theme_location' => 'primary',
									'menu_id' => 'navigation',
									'menu_class' => 'tabset',
									'items_wrap' => '%3$s',
									'depth' => '2',
									'walker' => new Custom_Header_Nav_Menu_2) ); ?>
								</div>
							</div>
						</div>
					</div>
					<!-- slideshow -->
					<?php if(is_front_page() || is_page(array(302891,302889))):  ?>
						<div class="slideshow">
						<?php /*
						if (!isset($_COOKIE['invitationhomes']) && !is_page(302891))
						 	layerslider(2);
						 else
						 	layerslider(1);
						 */ ?>


					<?php

						if (!isset($_COOKIE['invitationhomes']) && !is_page(302891)) { // future
						 	if ( !wp_is_mobile() ) {layerslider(2);} else { layerslider(4);}
						 } else { //current
						 	if ( !wp_is_mobile()) { layerslider(1); } else { layerslider(5);}
						 }
						?>
						</div>
					<?php endif; ?>
				</header>
<div id="bread"><?php  ih_breadcrumb ();  ?></div>
