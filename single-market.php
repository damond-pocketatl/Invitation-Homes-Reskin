<?php
/*
Template Name: Market Template
*/
?>
<?php
if($post->post_parent !== 0){include('market-child.php');}
else { ?>
<?php
wp_reset_query();
$market = get_queried_object();
$markets = get_the_terms($market, "market");
$slugs = array();
$mlocation = false;
foreach ($markets as $m) {
	$slugs[] = $m->slug;
	if( !$mlocation )
		$mlocation = $m->name;
}
$market->coords = get_post_meta($market->ID, 'martygeocoderlatlng', true);
$featured = query_posts(array( 
    'post_type' => 'property',
    'posts_per_page' => 10,
    'meta_key' => 'featured-property',
    'meta_value' => 'on',
    'tax_query' => array(
    	array(
    		'taxonomy' => 'market',
    		'field' => 'slug',
    		'terms' => $slugs
    	)
    )
));
$properties = query_posts(array( 
    'post_type' => 'property',
    'posts_per_page' => -1,
    'tax_query' => array(
    	array(
    		'taxonomy' => 'market',
    		'field' => 'slug',
    		'terms' => $slugs
    	)
    )
));
wp_reset_query();


global $post;
$market_title = get_post( $post )->post_name;
				
				
get_header(); 
$check = json_encode($markets);
 $chec = json_decode($check);
//print_r($chec);
foreach($chec as $test) {
$slugg =  $test->slug;
break;
}

?>
<script type="text/javascript">
var x = '<?php echo $slugg; ?>';
</script>
	<style type="text/css">
		ul.listing-info li { padding: 0px; padding-left: 10px; margin: 0px; }
		#tooltip p { margin: 0px; }
		#tooltip > img {max-width: 200px; width: 100%; height: auto; float: left; margin:5px 5px 5px -8px;}
		.tooldetails {width: 160px; float: right; padding-left: 5px;}
		html body.single.single-market.postid-36842 div#wrapper div.w1 main#main div.main-holder div.map-area div.map div#map-holder.holder.same-height-left div.gm-style div div div div div img {display:none;}
		.map { height: 250px; }
		#map-holder { height: 250px; }
		.map-area img {
			max-width: none;
			width: 100%;
		}
		
	</style>
<div class="slideDown">
        <div id="adv_search" class="adv_search">
			<form id="form_adv_search" action="http://www.invitationhomesforrent.com/apartmentsforrent/searchlisting.aspx">
<!-- 			<form id="form_adv_search" action="http://ihtesting7s.reslisting.com/searchlisting.aspx">    -->
        <fieldset> 
        
     <!--    <label id="BedsBaths" class="beds_label">Beds/Baths</label>     -->
        <input type="hidden" name="txtCity" value="<?php echo $mlocation; ?>">
        <input type="hidden" name="txtDistance" value="50">    
        
      <!--
  <select id="Beds" name="cmbBeds">
        <option value="-1">Any</option> 
        <option value="0" class="">studio</option>
        <option value="1" class="">1+</option>
        <option value="2" class="">2+</option>
        <option value="3" class="">3+</option>
        <option value="4" class="">4+</option>
        </select>
        
        <select id="Baths" name="cmbBaths">
        <option value="-1">Any</option> 
        <option value="1" class="">1+</option>
        <option value="2" class="">2+</option>
        <option value="3" class="">3+</option>
        <option value="4" class="">4+</option>
        </select>  
-->
    <!--

          <span id="Rent" class="adv_rent">Rent</span>
          <input type="text"  name="txtMinRent" id="min_rent" class="min_rent" placeholder="Min">
          <input type="text"  name="txtMaxRent" id="max_rent" class="max_rent" placeholder="Max">
-->
          <div class="adv_search_submit_holder"><input type="submit" id="gform_submit_button_1" class="gform_button button" value="Search For Homes" tabindex="6" ></div>  
    
    </fieldset>
    </form></div></div>
<!-- <span class="grabPromo showit"><p class="h2style">Refine Search</p></span> -->
	<?php the_title('<h1 class="adv_header">Rent homes in ', '</h1>'); ?>
		<div class="main-holder">
			<div class="map-area"> 
				<div class="map">
					<div id="map-holder" class="holder">
						
					</div>
				</div>
				<!-- Static Block -->
				<div class="aside">
					<?=do_shortcode('[gravityform id="1" name="Request Information" description="false"]'); ?>
					<address>
						<a href="tel:<?php the_field('market_telephone_number'); ?>" class="tel-link"><strong><?php the_field('market_telephone_number'); ?></strong></a>
						<dl>
							<dt></dt>
							<dd><?php the_field('market_address'); ?></dd>
						</dl>
						<dl>
							<dt>Email us At:</dt>
							<dd><a href="mailto:<?php the_field('market_email_address');?>" onclick="ga('send', 'event', 'contact-mailto', 'click ', '<?php the_title();?> market page');"><?php the_field('market_email_address');?></a></dd>
						</dl>
					</address>
				</div>
			</div>			
			<div class="featured">
				<script type="text/javascript">
					jQuery('document').ready(function($) {
						$('body').delegate('a.gallery-link', 'click', function() {
							ga('send', 'event', 'property-click', 'click ', '<?=$propdetails['address']?> <?=$propdetails['city']?>, <?=$propdetails['state']?> <?=$propdetails['zip-code']?>');
						});
					});
					
				</script>
				<h2><?php 
		if(get_field('title_area_1', $post->ID)){
			echo get_field('title_area_1');
		} else { echo "Featured Homes for Rent in " . get_the_title();}
		?></h2>
				<!-- cycle gallery -->
                
				<div class="cycle-gallery">
					<div class="mask-wrapper">
						<div class="mask">
							<div class="slideset">
							
								<?
							    foreach ($featured as $post) { setup_postdata($post); $meta = get_post_meta($post->ID);
							    if (is_string($meta['propdetails'][0])) $meta['propdetails'] = unserialize($meta['propdetails'][0]);
							    $meta['propdetails'] = $meta['propdetails'][0];
							    $meta['photos'][0] = str_replace("http://invitationhomes.com/", site_url()."/", $meta['photos'][0]);
								$srcImg = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), array( 300,200 ), false, '' );
								?>
								<div class="slide" itemscope itemtype="http://schema.org/LocalBusiness">
									<div class="img-holder">
										<a class="gallery-link" href="<?=$meta['propdetails']['url-to-property-detail-page']?>" onclick="ga('send', 'event', 'property-click', 'click ', '<?php echo $market_title ?>');">
											<strong class="price">$<? $theprice =$meta['propdetails']['rent']; $formatedprice = number_format($theprice); echo $formatedprice;
//=$meta['propdetails']['rent']
?></strong>
<?php if($srcImg[0] != '') { ?>
											<img itemprop="photo" itemscope itemtype="http://schema.org/photo" src="<?=$srcImg[0]?>" alt="image description">
                                            <?php }  ?>
										</a>
									</div>
									<h3 ><a href="<?=$meta['propdetails']['url-to-property-detail-page']?>" onclick="ga('send', 'event', 'property-click', 'click ', '<?php echo $market_title ?>');" class="gallery-link"><?=$meta['City'][0]?>, <?=$meta['State'][0]?></a></h3>
									<span><?=$meta['Beds'][0]?> beds, <?=number_format($meta['Baths'][0])?> baths, <?=number_format($meta['Square Footage'][0])?> sq. ft.</span>
									<address itemprop="address" itemscope itemtype="http://schema.org/PostalAddress"><span itemprop="streetAddress"><?=$post->post_title?></span><br><span itemprop="addressLocality"><?=$meta['City'][0]?></span>, <span itemprop="addressRegion"><?=$meta['State'][0]?></span> <span itemprop="postalCode"><?=$meta['Zip'][0]?></span></address>
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
				<?php wp_reset_postdata(); the_content(); ?>
			</div><?php
		get_template_part('blocks/inner-boxes'); ?>
		</div>
	</main>
	<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
	<!--
								<div class="slide" itemscope itemtype="http://schema.org/LocalBusiness">
									<div class="img-holder">
										<a class="gallery-link" href="<?=$meta['propdetails']['url-to-property-detail-page']?>">
											<strong class="price">$<? $theprice =$meta['propdetails']['rent']; $formatedprice = number_format($theprice); echo $formatedprice;
//=$meta['propdetails']['rent']
?></strong>
											<img src="<?=$meta['photos'][0]?>" alt="image description">
										</a>
									</div>
									<h3 ><a href="<?=$meta['propdetails']['url-to-property-detail-page']?>" class="gallery-link"><?=$meta['City'][0]?>, <?=$meta['State'][0]?></a></h3>
									<span><?=$meta['Beds'][0]?> beds, <?=number_format($meta['Baths'][0])?> baths, <?=number_format($meta['Square Footage'][0])?> sq. ft.</span>
									<address itemprop="address" itemscope itemtype="http://schema.org/PostalAddress"><span itemprop="streetAddress"><?=$post->post_title?></span><br><span itemprop="addressLocality"><?=$meta['City'][0]?></span>, <span itemprop="addressRegion"><?=$meta['State'][0]?></span> <span itemprop="postalCode"><?=$meta['Zip'][0]?></span></address>
								</div>
	-->
	<script type="text/javascript">
		jQuery(document).ready(function($) { 		
			// build an array of locations
			var markers = [
					<?php
					// walk through each property and write JS to mark the location
					$end = count($properties);
					$tooltips = "";
					$count = 1;
					$pars = array("(", ")");
					foreach($properties as $k => $p) {
						$meta = get_post_meta($p->ID, "martygeocoderlatlng", true);
						$meta = str_replace("(", "", $meta);
						$meta = str_replace(")", "", $meta);
						$meta = str_replace(" ", "", $meta);
						list($lat, $lng) = explode(",", $meta);
						$featured = get_post_meta($p->ID, "featured-property", true);
						$propdetails = get_post_meta($p->ID, "propdetails", true);
						$propdetails = $propdetails[0];
						//$photo = get_post_meta($p->ID, "photos", true);
						$photo = wp_get_attachment_image_src( get_post_thumbnail_id($p->ID), array( 200,200 ), false, '' );
						if (!$photo) $photo = get_template_directory_uri()."/images/default_image.jpg";
						else
						$photo = $photo[0];
						
						if (isset($propdetails['rent'])) $propdetails['rent'] = number_format($propdetails['rent']);
					    $idx == 1;
						$p = (array)$p;
						if(isset($lat) && isset($lng)) {
						    $newlatlng = str_replace($pars, "", $p['latlng']);
						    list($part1, $part2) = explode(',', $newlatlng);
						    $f = ($featured == "on") ? "<p class='featmaplist'>Featured Listing</p>" : '';
						    /*$tooltips .= <<<TOOLTIP
					<div class='mapitem' itemscope itemtype="http://schema.org/LocalBusiness" id='map-item$count' style="display: none;">
					<a id="tooltip" class="test" href='{$propdetails['url-to-property-detail-page']}' onclick="ga('send', 'event', 'property-click', 'click ', '{$propdetails['address']}, {$propdetails['city']}, {$propdetails['state']} {$propdetails['zip-code']}');" style="display: block;">
					
					<img itemprop="photo" itemscope itemtype="http://schema.org/photo" src="{$photo}" width=100 style="float: left; margin: 5px;" />
					
					<div class="tooldetails" >
	                    
	                    {$f}                 
	                    <p itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
							<span itemprop="streetAddress"><b>{$propdetails['address']}</span><br><span itemprop="addressLocality">{$propdetails['city']}</span>, <span itemprop="addressRegion">{$propdetails['state']}</span> <span itemprop="postalCode">{$propdetails['zip-code']}</span></b>
						</p>
						<ul class='listing-info'>
							<li class='listing-info-beds'>{$propdetails['bedrooms']} Beds</li>
							<li class='listing-info-baths'>{$propdetails['bathrooms']} Baths</li>
							<li class='listing-info-area'>{$propdetails['square-footage']} ft<sup>2</sup></li>
							<li class='listing-info-rent'><strong>\${$propdetails['rent']}</strong></li>
						</ul>
					</div>
					</a>
					</div>		    
TOOLTIP;*/
						    
						    
							echo "\n";
							echo "{"."\n";
							  echo "    'lat': '".$lat."', "."\n";
							  echo "    'lng': '".$lng."', "."\n";
							  if($featured == 'on') {
								echo "    'icon': '".get_template_directory_uri()."/images/featured_property_icon.png',"."\n";
							  } else {
							 	echo "    'icon': '".get_template_directory_uri()."/images/property_icon.png',"."\n";
							  }
							//echo "    'info': jQuery(\"#map-item".$count++."\").html()"."\n";
	                        echo "}, ";
						}
						++$idx;
					}
					?>
					
			];
			
		  // create the map itself
		  var mapOptions = {
				zoom: <?php echo $p['map-zoom-level'] ? $p['map-zoom-level'] : 9; ?>, 
				center: new google.maps.LatLng<?php echo $market->coords ? $market->coords : '(20.8165975, -156.92731930000002)' ?>, 
				mapTypeId: google.maps.MapTypeId.ROADMAP 
		  };
			  
		  var map = new google.maps.Map(document.getElementById("map-holder"), mapOptions);
		  var infowindow = new google.maps.InfoWindow({ width: 300 });
          var i = 0;
			  
		  var interval = setInterval(function () {
		    var data = markers[i];
			var myLatlng = new google.maps.LatLng(data.lat, data.lng);
			var ico = data.icon;
            var marker = new google.maps.Marker({
                  position: myLatlng,
                  map: map,
				  icon: ico,
            });
			
			(function (marker, data) {
			 google.maps.event.addListener(marker, 'click', (function(marker, i) {			 
			    return function() {	
				 infowindow.open(map, marker); 				
				 $.ajax({  
				 type: "POST",
                url: '<?php echo admin_url( 'admin-ajax.php' )?>',  
				data:{action : 'get_infowindo_content', id : x,id1 : i},
                success: function(data) {  				
				
                  infowindow.setContent(data);
				  
                }  
            });
				}
			  })(marker, i));
			  google.maps.event.addListener(map, 'click', (function(marker, i) {
			    return function() {
			    
				  infowindow.close();
				}
			  })(marker, i));
            })(marker, data);
            i++;
            if (i == markers.length) {
              clearInterval(interval);
            }
          }, 20);
	});
	</script>
	<?php /*?><?=$tooltips?><?php */?>
<?php 	
get_footer(); 
?>
<?php } ?>