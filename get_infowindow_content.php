<?php  
add_action( 'wp_ajax_get_infowindo_content', 'get_infowindo_content_callback' );
add_action( 'wp_ajax_nopriv_get_infowindo_content', 'get_infowindo_content_callback' );
function get_infowindo_content_callback()
{

$slugs = $_POST["id"];
$i = $_POST["id1"];

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
							if($count == $i+1){
						     $tooltips = "<div class='mapitem' itemscope itemtype='http://schema.org/LocalBusiness' id='map-item$count' style=''>
					<a id='tooltip' class='test' href='{$propdetails['url-to-property-detail-page']}' onclick='ga('send', 'event', 'property-click', 'click ', '{$propdetails['address']}, {$propdetails['city']}, {$propdetails['state']} {$propdetails['zip-code']}');' style='display: block;'>
					
					<img itemprop='photo' itemscope itemtype='http://schema.org/photo' src='{$photo}' width=100 style='float: left; margin: 5px;' />
					
					<div class='tooldetails' >
	                    
	                    {$f}                 
	                    <p itemprop='address' itemscope itemtype='http://schema.org/PostalAddress'>
							<span itemprop='streetAddress'><b>{$propdetails['address']}</span><br><span itemprop='addressLocality'>{$propdetails['city']}</span>, <span itemprop='addressRegion'>{$propdetails['state']}</span> <span itemprop='postalCode'>{$propdetails['zip-code']}</span></b>
						</p>
						<ul class='listing-info'>
							<li class='listing-info-beds'>{$propdetails['bedrooms']} Beds</li>
							<li class='listing-info-baths'>{$propdetails['bathrooms']} Baths</li>
							<li class='listing-info-area'>{$propdetails['square-footage']} ft<sup>2</sup></li>
							<li class='listing-info-rent'><strong>\${$propdetails['rent']}</strong></li>
						</ul>
					</div>
					</a>
					</div>";
							}
							$count++;
				          }
						++$idx;
					}
					?>			
<?php
echo $tooltips;
die;
}
?>