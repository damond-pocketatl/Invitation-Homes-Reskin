<!-- footer of the page -->
</div>

<footer id="footer">
  <div class="footer-holder">
    <div class="footer-frame">
      <div class="columns-holder">
        <div class="column"> <strong class="title">Information Resources</strong>
          <?php									
								if(has_nav_menu('footer_menu_1'))
								wp_nav_menu( array('container' => 'nav',
								'container_class' => 'list-holder',	
								'theme_location' => 'footer_menu_1',
								'menu_id' => 'navigation',
								'menu_class' => 'list1',
								'items_wrap' => '<ul class="%2$s">%3$s</ul>',
								'walker' => new Custom_Walker_Nav_Menu) ); ?>
        </div>
        <div class="column column2"> <strong class="title">Locations</strong>
          <?
								wp_nav_menu( array(
								'container' => 'nav',
								'menu' => 110,
								'container_class' => 'list-holder',	
								'menu_id' => 'locations',
								'menu_class' => 'list1',
								'items_wrap' => '<ul class="%2$s">%3$s</ul>',
								'walker' => new Custom_Walker_Nav_Menu) );?>
        </div>
        <div class="column column3"><strong class="title">Connect</strong> <?php 
									if(has_nav_menu('footer_menu_3'))
									wp_nav_menu( array('container' => 'nav',
									'container_class' => 'list-holder',	
									'theme_location' => 'footer_menu_3',
									'menu_id' => 'navigation',
									'menu_class' => 'list5',
									'items_wrap' => '<ul class="%2$s">%3$s</ul>',
									'walker' => new Custom_Walker_Nav_Menu) );?>
									
		
          <?php if(get_field('social_media', 'option')): ?>
          <br /><br />
          <ul class="social-networks">
            <?php while(has_sub_field('social_media', 'option')): ?>
            <li><a href="<?php the_sub_field('social_media_link'); ?>" class="<?php the_sub_field('social_media_name'); ?>">
              <?php the_sub_field('social_media_name'); ?>
              </a></li>
            <?php endwhile; ?>
            <li class="rss_feed"><a href="<?php bloginfo('rss2_url'); ?>"></a></li>
          </ul>
          <?php endif; ?>
        </div>
      </div>
      <div class="copy">
        <p>
          <?php the_field('footer_text', 'option'); ?>
        </p>
        <?php
							$image = get_field('footer_logo', 'option');
							if( !empty($image) ): ?>
        <a href="http://portal.hud.gov/hudportal/HUD?src=/program_offices/fair_housing_equal_opp" target="_blank"><img src="<?php echo $image['url']; ?>" height="43" width="45" alt="<?php echo $image['alt']; ?>" /></a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</footer>
</div>
<?php wp_footer(); ?>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/mobile-nav.js"></script>

<script type="text/javascript">
$('#selectall').click(function () {
	if ($(this).is(":checked"))
		$('.row span input.selectedId').not(":checked").click();
	else
		$('.row span input.selectedId:checked').click();
});
</script>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/jquery.ba-replacetext.min.js"></script>
<script type="text/javascript">
var pathInfo = {
	base: '<?php echo get_template_directory_uri(); ?>/',
	css: 'css/',
	js: 'js/',
	swf: 'swf/',
}
/*$(document).ready(function() {
	$('#main *').replaceText( /\biCal Export\b/gi, 'Add to Calendar' );
});*/
</script>
<script type='text/javascript'>
window.__wtw_lucky_site_id = 29105;
(function() {
	var wa = document.createElement('script'); wa.type = 'text/javascript'; wa.async = true;
	wa.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://w1') + '.livestatserver.com/w.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(wa, s);
})();
</script>
<script src="<?php bloginfo('template_directory'); ?>/js/respond.min.js"></script>
<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<!-- Google Universal Analytics -->
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-35067868-1', 'auto');
ga('require', 'displayfeatures');
ga('send', 'pageview');
</script>
<!-- End Google Universal Analytics -->

<!-- Undertone code -->
<script type='text/javascript'>
    var p=location.protocol=='https:'?'https:':'http:';
    var r=Math.floor(Math.random()*999999);
    document.write('<img src="' + p + '//ads.undertone.com/f?pid=48494&cb=' + r +'" alt="" style="display:none;" border="0" height="1" width="1" />');
</script>
<noscript>
<img src="https://ads.undertone.com/f?pid=48494&cb=[timestamp]" style="display: none;" width="0" height="0" alt="" />
</noscript>
<!-- end Undertone code -->
<?php

global $wp_query;
$term = $wp_query->get_queried_object();
$condition = is_post_type_archive( 'blog') || is_singular('blog') || ( is_archive() && $term->taxonomy == 'category' ) || is_post_type_archive( 'news') || is_singular('news') || $term->taxonomy == 'news-categories' || is_page( 'video-gallery') || is_singular('video') || $term->taxonomy == 'video_category';

if ( $condition )  {
?>
<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
<?php  } ?>

<script src="//platform.twitter.com/oct.js" type="text/javascript"></script>
<script type="text/javascript">twttr.conversion.trackPid('ntnie', { tw_sale_amount: 0, tw_order_quantity: 0 });</script>
<noscript>
<img height="1" width="1" style="display:none;" alt="" src="https://analytics.twitter.com/i/adsct?txn_id=ntnie&p_id=Twitter&tw_sale_amount=0&tw_order_quantity=0" />
<img height="1" width="1" style="display:none;" alt="" src="//t.co/i/adsct?txn_id=ntnie&p_id=Twitter&tw_sale_amount=0&tw_order_quantity=0" />
</noscript>
<script>
jQuery(document).ready(function(){
jQuery("li#menu-item-302900").append('<span id="edit_slide_up_down" class="fa fa-caret-down" style="cursor:pointer; float:right; width:0px;"></span>');
jQuery("li#menu-item-303026").append('<span id="edit_slide_up_down" class="fa fa-caret-down" style="cursor:pointer; float:right; width:0px;"></span>');	
jQuery( 'li#menu-item-302900 span#edit_slide_up_down' ).on( 'click', function () {
jQuery("li#menu-item-302900 ul").css('position','inherit');
if( jQuery(this).attr('class') == 'fa fa-caret-up') 
{
jQuery(this).attr("class","fa fa-caret-down");
jQuery("li#menu-item-302900 ul").css('display','none');
} else if(jQuery(this).attr('class') == 'fa fa-caret-down' )
{
  jQuery(this).attr("class","fa fa-caret-up");
  jQuery("li#menu-item-302900 ul").css('display','block');	
}
});
jQuery( 'li#menu-item-303026 span#edit_slide_up_down' ).on( 'click', function () {
jQuery("li#menu-item-303026 ul").css('position','inherit');
if( jQuery(this).attr('class') == 'fa fa-caret-up') 
{
jQuery(this).attr("class","fa fa-caret-down");
jQuery("li#menu-item-303026 ul").css('display','none');
} else if(jQuery(this).attr('class') == 'fa fa-caret-down' )
{
  jQuery(this).attr("class","fa fa-caret-up");
  jQuery("li#menu-item-303026 ul").css('display','block');	
}
});
//jQuery( 'li#menu-item-303026' ).on( 'click', function () {
//jQuery("li#menu-item-303026 ul").css('position','inherit');
//jQuery("li#menu-item-303026 ul").slideToggle();
//});
});
</script>
<script>
	jQuery( document ).ready(function() {
		jQuery('.marketblocks').find('.wpb_wrapper').addClass('targetBlock');
		jQuery( '.targetBlock' ).each(function() {
			jQuery( this ).find('a[href^="mailto:"]').addClass('target');
			var marketName = jQuery(this).find('a > strong').html();
			var analyt = ga('send', 'event', 'contact-mailto', 'click ', 'test market event');
			jQuery(this).find('.target').attr('onClick',  "ga('send', 'event', 'contact-mailto', 'click ', '" + marketName +" market');" );
		});
		/*
jQuery( '.target' ).click(function() {
			    var marketName = jQuery(this).parent().find('a > strong').html();
			    console.log('target ' + marketName + ' clicked');
			   
		});
*/
		
	});
</script>
</body></html>
